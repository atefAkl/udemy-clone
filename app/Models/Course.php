<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'instructor_id',
        'category_id',
        'thumbnail',
        'preview_video',
        'level',
        'language',
        'duration',
        'status',
        'is_featured',
        'requirements',
        'what_you_learn',
        'meta_title',
        'meta_description',
        // New fields for enhanced course structure
        'launch_date',
        'launch_time',
        'objectives',
        'table_of_contents',
        'has_certificate',
        'access_duration_type',
        'access_duration_value',
        'target_level',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        // No discount, there's coupons to manage discounts
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'requirements' => 'array',
        'what_you_learn' => 'array',
    ];

    // Course Status Constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';

    // Course Level Constants
    const LEVEL_BEGINNER = 'beginner';
    const LEVEL_INTERMEDIATE = 'intermediate';
    const LEVEL_ADVANCED = 'advanced';

    /**
     * Instructor relationship
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Category relationship
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Lessons relationship
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    /**
     * Enrollments relationship
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Students enrolled in course
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('progress', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Reviews relationship
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if course is published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if course is draft
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Get course thumbnail URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/courses/' . $this->thumbnail);
        }

        return asset('images/default-course.jpg');
    }

    /**
     * Get course URL
     */
    public function getUrlAttribute(): string
    {
        return route('courses.show', $this->slug);
    }

    /**
     * Get final price (considering discount)
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if course has discount
     */
    public function hasDiscount(): bool
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /**
     * Get total students count
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->enrollments()->count();
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Get total lessons count
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Get total duration in minutes
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->lessons()->sum('duration');
    }

    /**
     * Scope for published courses
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope for featured courses
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
