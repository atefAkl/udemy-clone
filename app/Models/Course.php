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
        'subtitle',
        'slug',
        'description',
        'short_description',
        'price',
        'instructor_id',
        'category_id',
        'thumbnail',
        'preview_video',
        'level',
        'language',
        'duration',
        'discount_price',
        'status',
        'is_featured',
        'requirements',
        'what_you_learn',
        'meta_title',
        'meta_description',
        'launch_date',
        'launch_time',
        'has_certificate',
        'access_duration_type',
        'access_duration_value',
        'access_duration_unit'
    ];

    protected $casts = [
        'price'                 => 'decimal:2',
        'duration'              => 'decimal:1',
        'is_featured'           => 'boolean',
        'has_certificate'       => 'boolean',
        'requirements'          => 'array',
        'what_you_learn'        => 'array',
        'launch_date'           => 'date',
        'access_duration_value' => 'integer',
    ];

    // Course Status Constants
    const STATUS_DRAFT          = 'draft';
    const STATUS_PENDING        = 'pending';
    const STATUS_PUBLISHED      = 'published';
    const STATUS_REJECTED       = 'rejected';

    // Course Level Constants
    const LEVEL_BEGINNER        = 'beginner';
    const LEVEL_INTERMEDIATE    = 'intermediate';
    const LEVEL_ADVANCED        = 'advanced';

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
    /**
     * Get the sections for the course.
     */
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    /**
     * Get the lessons for the course.
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

    /**
     * Wishlists relationship
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Users who wishlisted this course
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withPivot('added_at')
            ->withTimestamps();
    }

    /**
     * Certificates relationship
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * User lists that contain this course
     */
    public function userLists()
    {
        return $this->belongsToMany(UserList::class, 'user_list_courses')
            ->withPivot('added_at', 'sort_order')
            ->withTimestamps();
    }

    /**
     * Check if course is in user's wishlist
     */
    public function isInWishlist($userId)
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    /**
     * Get wishlist count
     */
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlists()->count();
    }
}
