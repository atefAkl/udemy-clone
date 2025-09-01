<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    /**
     * Upload resource for lesson
     */
    public function upload(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_downloadable' => 'boolean',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;

        // Store file in private disk
        $filePath = $file->storeAs(
            'lesson-resources/' . $lesson->id,
            $fileName,
            'private'
        );

        // Determine resource type
        $resourceType = LessonResource::getResourceTypeFromExtension($extension);

        // Create resource record
        $resource = LessonResource::create([
            'lesson_id' => $lesson->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $extension,
            'mime_type' => $file->getMimeType(),
            'resource_type' => $resourceType,
            'is_downloadable' => $request->boolean('is_downloadable', true),
            'sort_order' => $lesson->resources()->count(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('app.resource_uploaded_successfully'),
            'resource' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'original_name' => $resource->original_name,
                'formatted_size' => $resource->formatted_size,
                'type_icon' => $resource->type_icon,
                'type_label' => $resource->type_label,
                'download_url' => $resource->download_url,
                'is_downloadable' => $resource->is_downloadable,
            ]
        ]);
    }

    /**
     * Download resource
     */
    public function download(Lesson $lesson, LessonResource $resource)
    {
        // Check if user can access this lesson
        if (!$lesson->canAccess(auth()->user())) {
            abort(403, __('app.access_denied'));
        }

        // Check if resource belongs to lesson
        if ($resource->lesson_id !== $lesson->id) {
            abort(404);
        }

        // Check if resource is downloadable
        if (!$resource->canDownload(auth()->user())) {
            abort(403, __('app.download_not_allowed'));
        }

        // Record download
        $resource->recordDownload(auth()->user());

        // Stream file download
        return $this->streamFile($resource);
    }

    /**
     * Update resource
     */
    public function update(Request $request, Lesson $lesson, LessonResource $resource)
    {
        $this->authorize('update', $lesson->course);

        // Check if resource belongs to lesson
        if ($resource->lesson_id !== $lesson->id) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_downloadable' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_downloadable' => $request->boolean('is_downloadable'),
            'sort_order' => $request->integer('sort_order', $resource->sort_order),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('app.resource_updated_successfully'),
            'resource' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'is_downloadable' => $resource->is_downloadable,
                'sort_order' => $resource->sort_order,
            ]
        ]);
    }

    /**
     * Delete resource
     */
    public function destroy(Lesson $lesson, LessonResource $resource)
    {
        $this->authorize('update', $lesson->course);

        // Check if resource belongs to lesson
        if ($resource->lesson_id !== $lesson->id) {
            abort(404);
        }

        // Delete file from storage
        if (Storage::disk('private')->exists($resource->file_path)) {
            Storage::disk('private')->delete($resource->file_path);
        }

        // Delete resource record
        $resource->delete();

        return response()->json([
            'success' => true,
            'message' => __('app.resource_deleted_successfully')
        ]);
    }

    /**
     * Reorder resources
     */
    public function reorder(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $request->validate([
            'resources' => 'required|array',
            'resources.*.id' => 'required|exists:lesson_resources,id',
            'resources.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->resources as $resourceData) {
            LessonResource::where('id', $resourceData['id'])
                ->where('lesson_id', $lesson->id)
                ->update(['sort_order' => $resourceData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => __('app.resources_reordered_successfully')
        ]);
    }

    /**
     * Get lesson resources
     */
    public function index(Lesson $lesson)
    {
        // Check if user can access this lesson
        if (!$lesson->canAccess(auth()->user())) {
            abort(403, __('app.access_denied'));
        }

        $resources = $lesson->downloadableResources()
            ->select(['id', 'title', 'description', 'original_name', 'file_size', 'resource_type', 'download_count'])
            ->get()
            ->map(function ($resource) {
                return [
                    'id' => $resource->id,
                    'title' => $resource->title,
                    'description' => $resource->description,
                    'original_name' => $resource->original_name,
                    'formatted_size' => $resource->formatted_size,
                    'type_icon' => $resource->type_icon,
                    'type_label' => $resource->type_label,
                    'download_url' => $resource->download_url,
                    'download_count' => $resource->download_count,
                ];
            });

        return response()->json([
            'success' => true,
            'resources' => $resources
        ]);
    }

    /**
     * Stream file for download
     */
    private function streamFile(LessonResource $resource): StreamedResponse
    {
        $filePath = $resource->file_path;
        $fileName = $resource->original_name;

        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, __('app.file_not_found'));
        }

        return Storage::disk('private')->download($filePath, $fileName);
    }
}
