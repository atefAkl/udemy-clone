<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class SampleCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get a random instructor and category
        $instructor = User::where('role', 'instructor')->inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        // Ensure instructor and category exist before creating the course
        if (!$instructor || !$category) {
            $this->command->error('Could not find an instructor or a category to create the sample course.');
            return;
        }

        Course::create([
            'title' => 'Complete Web Development Bootcamp',
            'subtitle' => 'Learn everything from HTML to React, Node.js, and more!',
            'slug' => Str::slug('Complete Web Development Bootcamp'),
            'description' => 'This is the only course you need to learn web development. It covers everything from the basics of HTML, CSS, and JavaScript to advanced topics like React, Node.js, and databases.',
            'short_description' => 'A comprehensive course covering all aspects of web development.',
            'price' => 199.99,
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'level' => 'beginner',
            'language' => 'en',
            'status' => 'published',
            'is_featured' => true,
            'requirements' => [
                'A computer with internet access',
                'No prior coding experience needed',
                'A willingness to learn',
            ],
            'what_you_learn' => [
                'Build beautiful and responsive websites',
                'Master front-end development with React',
                'Build scalable back-end applications with Node.js and Express',
                'Work with databases like MongoDB and PostgreSQL',
            ],
            'meta_title' => 'Complete Web Development Bootcamp 2025',
            'meta_description' => 'The ultimate web development course for beginners and aspiring developers.',
            'has_certificate' => true,
            'access_duration_type' => 'lifetime',
        ]);

        $this->command->info('Sample course created successfully.');
    }
}
