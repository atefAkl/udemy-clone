<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessAssetsUpload implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 minutes
    public $tries = 3;

    protected $lessonId;
    protected $filesData;
    protected $uploadKey;

    /**
     * Create a new job instance.
     */
    public function __construct($lessonId, $filesData, $uploadKey)
    {
        $this->lessonId = $lessonId;
        $this->filesData = $filesData;
        $this->uploadKey = $uploadKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $lesson = Lesson::findOrFail($this->lessonId);
            $uploadedFiles = [];
            $totalFiles = count($this->filesData);
            
            // Update progress: Starting
            $this->updateProgress(0, $totalFiles, 'starting');

            foreach ($this->filesData as $index => $fileData) {
                try {
                    // Move file from temp to permanent storage
                    $tempPath = $fileData['temp_path'];
                    $permanentPath = 'lessons/assets/' . basename($tempPath);
                    
                    // Move file
                    Storage::disk('public')->move($tempPath, $permanentPath);
                    
                    $uploadedFiles[] = [
                        'original_name' => $fileData['original_name'],
                        'path' => $permanentPath,
                        'size' => $fileData['size'],
                        'mime_type' => $fileData['mime_type'],
                    ];
                    
                    // Update progress
                    $this->updateProgress($index + 1, $totalFiles, 'processing');
                    
                    // Small delay to avoid overwhelming the system
                    usleep(100000); // 0.1 second
                    
                } catch (\Exception $e) {
                    Log::error('Failed to process file: ' . $fileData['original_name'], [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            // Update lesson with uploaded files
            $lesson->update([
                'lecture_file' => json_encode($uploadedFiles)
            ]);

            // Update progress: Completed
            $this->updateProgress($totalFiles, $totalFiles, 'completed', [
                'lesson_id' => $lesson->id,
                'files_count' => count($uploadedFiles)
            ]);

            Log::info('Assets upload completed successfully', [
                'lesson_id' => $lesson->id,
                'files_count' => count($uploadedFiles)
            ]);

        } catch (\Exception $e) {
            // Update progress: Failed
            $this->updateProgress(0, 0, 'failed', [
                'error' => $e->getMessage()
            ]);
            
            Log::error('Assets upload job failed', [
                'lesson_id' => $this->lessonId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Update upload progress in cache
     */
    protected function updateProgress($current, $total, $status, $data = [])
    {
        Cache::put($this->uploadKey, [
            'current' => $current,
            'total' => $total,
            'percentage' => $total > 0 ? round(($current / $total) * 100) : 0,
            'status' => $status,
            'data' => $data,
            'updated_at' => now()->toIso8601String(),
        ], 3600); // Cache for 1 hour
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessAssetsUpload job failed permanently', [
            'lesson_id' => $this->lessonId,
            'error' => $exception->getMessage()
        ]);
        
        $this->updateProgress(0, 0, 'failed', [
            'error' => $exception->getMessage()
        ]);
    }
}
