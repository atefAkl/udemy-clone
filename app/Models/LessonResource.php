<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LessonResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'file_name',
        'original_name',
        'file_path',
        'file_size',
        'file_type',
        'mime_type',
        'resource_type',
        'is_downloadable',
        'download_count',
        'sort_order',
    ];

    protected $casts = [
        'is_downloadable' => 'boolean',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
    ];

    // Resource Type Constants
    const TYPE_DOCUMENT = 'document';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_IMAGE = 'image';
    const TYPE_ARCHIVE = 'archive';
    const TYPE_CODE = 'code';
    const TYPE_OTHER = 'other';

    /**
     * Lesson relationship
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Download records
     */
    public function downloads()
    {
        return $this->hasMany(ResourceDownload::class);
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('private')->url($this->file_path);
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('lessons.resources.download', [
            'lesson' => $this->lesson_id,
            'resource' => $this->id
        ]);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get resource type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->resource_type) {
            self::TYPE_DOCUMENT => 'fas fa-file-alt',
            self::TYPE_VIDEO => 'fas fa-file-video',
            self::TYPE_AUDIO => 'fas fa-file-audio',
            self::TYPE_IMAGE => 'fas fa-file-image',
            self::TYPE_ARCHIVE => 'fas fa-file-archive',
            self::TYPE_CODE => 'fas fa-file-code',
            default => 'fas fa-file'
        };
    }

    /**
     * Get resource type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->resource_type) {
            self::TYPE_DOCUMENT => __('app.document'),
            self::TYPE_VIDEO => __('app.video'),
            self::TYPE_AUDIO => __('app.audio'),
            self::TYPE_IMAGE => __('app.image'),
            self::TYPE_ARCHIVE => __('app.archive'),
            self::TYPE_CODE => __('app.code'),
            default => __('app.file')
        };
    }

    /**
     * Determine resource type from file extension
     */
    public static function getResourceTypeFromExtension(string $extension): string
    {
        $extension = strtolower($extension);

        $types = [
            self::TYPE_DOCUMENT => ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'],
            self::TYPE_VIDEO => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            self::TYPE_AUDIO => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
            self::TYPE_IMAGE => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'],
            self::TYPE_ARCHIVE => ['zip', 'rar', '7z', 'tar', 'gz'],
            self::TYPE_CODE => ['html', 'css', 'js', 'php', 'py', 'java', 'cpp', 'c', 'json', 'xml'],
        ];

        foreach ($types as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }

        return self::TYPE_OTHER;
    }

    /**
     * Record download
     */
    public function recordDownload(User $user): void
    {
        ResourceDownload::create([
            'lesson_resource_id' => $this->id,
            'user_id' => $user->id,
            'downloaded_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->increment('download_count');
    }

    /**
     * Check if user can download
     */
    public function canDownload(User $user): bool
    {
        if (!$this->is_downloadable) {
            return false;
        }

        return $this->lesson->canAccess($user);
    }

    /**
     * Scope for downloadable resources
     */
    public function scopeDownloadable($query)
    {
        return $query->where('is_downloadable', true);
    }

    /**
     * Scope by resource type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('resource_type', $type);
    }
}
