<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Section;
use App\Policies\CategoryPolicy;
use App\Policies\CoursePolicy;
use App\Policies\SectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Category::class => CategoryPolicy::class,
        Section::class => SectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
