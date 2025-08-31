<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'color',
        'status',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Parent category relationship
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Child categories relationship
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Courses in this category
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Check if category is parent (no parent_id)
     */
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Get all parent categories
     */
    public static function parents()
    {
        return static::whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * Get category breadcrumb
     */
    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumb, $category);
            $category = $category->parent;
        }

        return $breadcrumb;
    }

    /**
     * Get category URL
     */
    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }
}
