<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Model
{
    use HasFactory;

    protected $table = 'media_library';

    protected $fillable = [
        'user_id',
        'name',
        'original_name',
        'path',
        'url',
        'thumbnail_path',
        'thumbnail_url',
        'mime_type',
        'extension',
        'size',
        'size_formatted',
        'type',
        'width',
        'height',
        'aspect_ratio',
        'duration',
        'duration_formatted',
        'metadata',
        'is_validated',
        'validation_errors',
        'status'
    ];

    protected $casts = [
        'metadata' => 'array',
        'validation_errors' => 'array',
        'is_validated' => 'boolean',
        'aspect_ratio' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the media.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        return $this->size_formatted;
    }

    /**
     * Get formatted duration for videos
     */
    public function getFormattedDurationAttribute()
    {
        return $this->duration_formatted;
    }

    /**
     * Check if media is an image
     */
    public function isImage()
    {
        return $this->type === 'image';
    }

    /**
     * Check if media is a video
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Get full URL
     */
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        
        if ($this->isImage()) {
            return $this->getFullUrlAttribute();
        }
        
        return null;
    }

    /**
     * Validate aspect ratio (16:9)
     */
    public function hasValidAspectRatio()
    {
        if (!$this->width || !$this->height) {
            return false;
        }

        $targetRatio = 16 / 9;
        $actualRatio = $this->width / $this->height;
        $tolerance = 0.2; // 20% tolerance

        return abs($actualRatio - $targetRatio) <= $tolerance;
    }

    /**
     * Validate file size
     */
    public function hasValidSize()
    {
        if ($this->isImage()) {
            return $this->size <= (2 * 1024 * 1024); // 2MB for images
        }
        
        if ($this->isVideo()) {
            return $this->size <= (20 * 1024 * 1024); // 20MB for videos
        }
        
        return false;
    }

    /**
     * Validate video duration
     */
    public function hasValidDuration()
    {
        if (!$this->isVideo()) {
            return true;
        }
        
        return $this->duration <= (15 * 60); // 15 minutes
    }

    /**
     * Get validation status
     */
    public function getValidationStatus()
    {
        $errors = [];

        if (!$this->hasValidAspectRatio()) {
            $errors[] = 'Invalid aspect ratio. Required: 16:9';
        }

        if (!$this->hasValidSize()) {
            $maxSize = $this->isImage() ? '2MB' : '20MB';
            $errors[] = "File too large. Maximum: {$maxSize}";
        }

        if (!$this->hasValidDuration()) {
            $errors[] = 'Video too long. Maximum: 15 minutes';
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Scope for user's media
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for media type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for validated media
     */
    public function scopeValidated($query)
    {
        return $query->where('is_validated', true);
    }

    /**
     * Scope for ready media
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }
}
