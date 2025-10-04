<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MediaLibraryController extends Controller
{
    /**
     * Get media library for a specific type
     */
    public function index($type)
    {
        try {
            // Check if media_library table exists
            if (!Schema::hasTable('media_library')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Media library table does not exist',
                    'setup_required' => true
                ], 200);
            }

            $validTypes = ['images', 'videos'];
            
            if (!in_array($type, $validTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid media type'
                ], 200);
            }

            $mediaType = $type === 'images' ? 'image' : 'video';
            
            $media = MediaLibrary::forUser(Auth::id())
                ->ofType($mediaType)
                ->ready()
                ->validated()
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'original_name' => $item->original_name,
                        'url' => $item->full_url,
                        'thumbnail' => $item->thumbnail_url,
                        'mime_type' => $item->mime_type,
                        'size' => $item->size_formatted,
                        'duration' => $item->duration_formatted,
                        'width' => $item->width,
                        'height' => $item->height,
                        'aspect_ratio' => $item->aspect_ratio,
                        'created_at' => $item->created_at->format('Y-m-d H:i')
                    ];
                });

            return response()->json([
                'success' => true,
                'media' => $media,
                'count' => $media->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading media library: ' . $e->getMessage(),
                'setup_required' => true
            ], 500);
        }
    }

    /**
     * Setup media library system
     */
    public function setup()
    {
        try {
            DB::beginTransaction();

            // Check if table exists
            if (!Schema::hasTable('media_library')) {
                // Run the migration
                \Artisan::call('migrate', [
                    '--path' => 'database/migrations',
                    '--force' => true
                ]);
            }

            // Create storage directories if they don't exist
            $directories = [
                'public/media-library',
                'public/media-library/images',
                'public/media-library/videos',
                'public/media-library/thumbnails'
            ];

            foreach ($directories as $directory) {
                if (!\Storage::disk('local')->exists($directory)) {
                    \Storage::disk('local')->makeDirectory($directory);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Media library system setup successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to setup media library: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload media to library
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:png,jpg,jpeg,svg,mp4,avi,mkv,webm|max:20480', // 20MB max
            'type' => 'required|in:image,video'
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $type = $request->input('type');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = "media-library/{$type}s";
            
            // Store file
            $path = $file->storeAs($directory, $filename, 'public');
            $url = asset('storage/' . $path);

            // Get file info
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Create media library entry
            $media = MediaLibrary::create([
                'user_id' => Auth::id(),
                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => $url,
                'mime_type' => $mimeType,
                'extension' => $file->getClientOriginalExtension(),
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'type' => $type,
                'status' => 'processing'
            ]);

            // Process media asynchronously (you might want to use queues here)
            $this->processMedia($media);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'media' => $media
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process media file
     */
    private function processMedia(MediaLibrary $media)
    {
        try {
            $filePath = storage_path('app/public/' . $media->path);
            
            if ($media->isImage()) {
                $this->processImage($media, $filePath);
            } else {
                $this->processVideo($media, $filePath);
            }

            // Validate media
            $validation = $media->getValidationStatus();
            $media->update([
                'is_validated' => $validation['is_valid'],
                'validation_errors' => $validation['errors'],
                'status' => $validation['is_valid'] ? 'ready' : 'failed'
            ]);

        } catch (\Exception $e) {
            $media->update([
                'status' => 'failed',
                'validation_errors' => ['Processing failed: ' . $e->getMessage()]
            ]);
        }
    }

    /**
     * Process image file
     */
    private function processImage(MediaLibrary $media, $filePath)
    {
        $imageInfo = getimagesize($filePath);
        
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $aspectRatio = $width / $height;
            
            $media->update([
                'width' => $width,
                'height' => $height,
                'aspect_ratio' => round($aspectRatio, 2)
            ]);
        }
    }

    /**
     * Process video file
     */
    private function processVideo(MediaLibrary $media, $filePath)
    {
        // Basic video processing (you might want to use FFmpeg for more advanced processing)
        // For now, we'll just set placeholder values
        $media->update([
            'width' => 1920,
            'height' => 1080,
            'aspect_ratio' => 1.78,
            'duration' => 300, // 5 minutes placeholder
            'duration_formatted' => '5:00'
        ]);
    }

    /**
     * Format file size
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Delete media
     */
    public function destroy(MediaLibrary $media)
    {
        try {
            // Check if user owns this media
            if ($media->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Delete files
            \Storage::disk('public')->delete($media->path);
            if ($media->thumbnail_path) {
                \Storage::disk('public')->delete($media->thumbnail_path);
            }

            // Delete database record
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
