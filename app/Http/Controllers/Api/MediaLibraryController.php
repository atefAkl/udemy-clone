<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MediaLibraryController extends Controller
{
    /**
     * Get user's media library files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'image'); // image or video
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Define the user's media directory
        $userMediaPath = "media/users/{$user->id}";
        
        // Get all files from user's media directory
        $files = [];
        
        if (Storage::disk('public')->exists($userMediaPath)) {
            $allFiles = Storage::disk('public')->files($userMediaPath);
            
            foreach ($allFiles as $file) {
                $mimeType = Storage::disk('public')->mimeType($file);
                
                // Filter by type
                if ($type === 'image' && str_starts_with($mimeType, 'image/')) {
                    $files[] = [
                        'name' => basename($file),
                        'url' => Storage::disk('public')->url($file),
                        'path' => $file,
                        'size' => Storage::disk('public')->size($file),
                        'mime_type' => $mimeType,
                        'created_at' => Storage::disk('public')->lastModified($file)
                    ];
                } elseif ($type === 'video' && str_starts_with($mimeType, 'video/')) {
                    $files[] = [
                        'name' => basename($file),
                        'url' => Storage::disk('public')->url($file),
                        'path' => $file,
                        'size' => Storage::disk('public')->size($file),
                        'mime_type' => $mimeType,
                        'created_at' => Storage::disk('public')->lastModified($file)
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'files' => $files,
            'count' => count($files)
        ]);
    }

    /**
     * Upload file to user's media library
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'type' => 'required|in:image,video'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $file = $request->file('file');
        $type = $request->input('type');

        // Validate file type
        if ($type === 'image' && !str_starts_with($file->getMimeType(), 'image/')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image file'
            ], 422);
        }

        if ($type === 'video' && !str_starts_with($file->getMimeType(), 'video/')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid video file'
            ], 422);
        }

        // Store file
        $userMediaPath = "media/users/{$user->id}";
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($userMediaPath, $filename, 'public');

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'file' => [
                'name' => $filename,
                'url' => Storage::disk('public')->url($path),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]
        ]);
    }

    /**
     * Delete file from user's media library
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $path = $request->input('path');

        // Ensure the file belongs to the user
        if (!str_starts_with($path, "media/users/{$user->id}/")) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this file'
            ], 403);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found'
        ], 404);
    }
}
