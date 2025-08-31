<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test instructor
        User::create([
            'name' => 'Test Instructor',
            'email' => 'instructor@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'is_active' => true,
            'preferred_language' => 'ar',
        ]);

        // Create test admin
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'preferred_language' => 'ar',
        ]);

        // Create test student
        User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'preferred_language' => 'ar',
        ]);

        // Create test categories
        $categories = [
            [
                'name' => 'تطوير الويب',
                'slug' => 'web-development-2',
                'description' => 'تعلم تطوير مواقع الويب',
                'icon' => 'fas fa-code',
                'color' => '#007bff',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'البرمجة',
                'slug' => 'programming',
                'description' => 'أساسيات البرمجة',
                'icon' => 'fas fa-laptop-code',
                'color' => '#28a745',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'التصميم',
                'slug' => 'design',
                'description' => 'تصميم الواجهات والجرافيك',
                'icon' => 'fas fa-paint-brush',
                'color' => '#dc3545',
                'status' => 'active',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
