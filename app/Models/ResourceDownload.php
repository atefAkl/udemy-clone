<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_resource_id',
        'user_id',
        'downloaded_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    /**
     * Resource relationship
     */
    public function resource()
    {
        return $this->belongsTo(LessonResource::class, 'lesson_resource_id');
    }

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for recent downloads
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('downloaded_at', '>=', now()->subDays($days));
    }

    /**
     * Scope by user
     */
    public function scopeByUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
