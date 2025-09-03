<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UdemyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Main categories based on Udemy's main categories
        $categories = [
            [
                'name' => 'Development',
                'description' => 'Web Development, Programming Languages, Game Development, and more',
                'icon' => 'fas fa-code',
                'color' => '#2d95e3',
                'is_featured' => true,
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Web Development', 'description' => 'HTML, CSS, JavaScript, React, Angular, Vue.js', 'icon' => 'fas fa-laptop-code', 'color' => '#2d95e3', 'sort_order' => 1],
                    ['name' => 'Mobile Apps', 'description' => 'iOS, Android, React Native, Flutter, Swift', 'icon' => 'fas fa-mobile-alt', 'color' => '#2d95e3', 'sort_order' => 2],
                    ['name' => 'Game Development', 'description' => 'Unity, Unreal Engine, Game Design', 'icon' => 'fas fa-gamepad', 'color' => '#2d95e3', 'sort_order' => 3],
                    ['name' => 'Programming Languages', 'description' => 'Python, Java, C++, C#, JavaScript, PHP', 'icon' => 'fas fa-code-branch', 'color' => '#2d95e3', 'sort_order' => 4],
                    ['name' => 'Data Science', 'description' => 'Machine Learning, Deep Learning, Data Analysis', 'icon' => 'fas fa-database', 'color' => '#2d95e3', 'sort_order' => 5],
                ]
            ],
            [
                'name' => 'Business',
                'description' => 'Entrepreneurship, Communications, Management, Sales, Strategy',
                'icon' => 'fas fa-briefcase',
                'color' => '#f56040',
                'is_featured' => true,
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Entrepreneurship', 'description' => 'Startup, Business Fundamentals, Freelancing', 'icon' => 'fas fa-lightbulb', 'color' => '#f56040', 'sort_order' => 1],
                    ['name' => 'Communication', 'description' => 'Public Speaking, Writing, Negotiation', 'icon' => 'fas fa-comments', 'color' => '#f56040', 'sort_order' => 2],
                    ['name' => 'Management', 'description' => 'Leadership, Project Management, Team Leadership', 'icon' => 'fas fa-users', 'color' => '#f56040', 'sort_order' => 3],
                ]
            ],
            [
                'name' => 'IT & Software',
                'description' => 'IT Certification, Network & Security, Operating Systems',
                'icon' => 'fas fa-laptop',
                'color' => '#a435f0',
                'is_featured' => true,
                'sort_order' => 3,
                'children' => [
                    ['name' => 'IT Certification', 'description' => 'AWS, Cisco, CompTIA, Microsoft', 'icon' => 'fas fa-certificate', 'color' => '#a435f0', 'sort_order' => 1],
                    ['name' => 'Network & Security', 'description' => 'Ethical Hacking, Cyber Security, Network Administration', 'icon' => 'fas fa-shield-alt', 'color' => '#a435f0', 'sort_order' => 2],
                    ['name' => 'Operating Systems', 'description' => 'Linux, Windows, Mac OS', 'icon' => 'fas fa-desktop', 'color' => '#a435f0', 'sort_order' => 3],
                ]
            ],
            [
                'name' => 'Design',
                'description' => 'Web Design, Graphic Design, Design Tools, User Experience',
                'icon' => 'fas fa-palette',
                'color' => '#ff6b6b',
                'is_featured' => true,
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Web Design', 'description' => 'UI/UX, WordPress, Adobe XD, Figma', 'icon' => 'fas fa-paint-brush', 'color' => '#ff6b6b', 'sort_order' => 1],
                    ['name' => 'Graphic Design', 'description' => 'Photoshop, Illustrator, InDesign', 'icon' => 'fas fa-images', 'color' => '#ff6b6b', 'sort_order' => 2],
                    ['name' => '3D & Animation', 'description' => 'Blender, Maya, 3D Modeling', 'icon' => 'fas fa-cube', 'color' => '#ff6b6b', 'sort_order' => 3],
                ]
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital Marketing, Social Media Marketing, Branding, Content Marketing',
                'icon' => 'fas fa-bullhorn',
                'color' => '#00b4d8',
                'is_featured' => true,
                'sort_order' => 5,
                'children' => [
                    ['name' => 'Digital Marketing', 'description' => 'SEO, Email Marketing, Content Marketing', 'icon' => 'fas fa-hashtag', 'color' => '#00b4d8', 'sort_order' => 1],
                    ['name' => 'Social Media Marketing', 'description' => 'Facebook, Instagram, YouTube, TikTok', 'icon' => 'fas fa-share-alt', 'color' => '#00b4d8', 'sort_order' => 2],
                    ['name' => 'Branding', 'description' => 'Personal Branding, Business Branding', 'icon' => 'fas fa-trademark', 'color' => '#00b4d8', 'sort_order' => 3],
                ]
            ],
            [
                'name' => 'Personal Development',
                'description' => 'Personal Transformation, Productivity, Leadership, Career Development',
                'icon' => 'fas fa-user-tie',
                'color' => '#ff9f1c',
                'is_featured' => true,
                'sort_order' => 6,
                'children' => [
                    ['name' => 'Personal Transformation', 'description' => 'Happiness, Personal Productivity, Memory & Study Skills', 'icon' => 'fas fa-user-edit', 'color' => '#ff9f1c', 'sort_order' => 1],
                    ['name' => 'Productivity', 'description' => 'Time Management, Focus, Goal Setting', 'icon' => 'fas fa-tasks', 'color' => '#ff9f1c', 'sort_order' => 2],
                    ['name' => 'Leadership', 'description' => 'Management Skills, Communication Skills', 'icon' => 'fas fa-chess-king', 'color' => '#ff9f1c', 'sort_order' => 3],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
            
            $categoryData['slug'] = Str::slug($categoryData['name']);
            $parent = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['slug'] = Str::slug($childData['name']);
                $childData['parent_id'] = $parent->id;
                Category::create($childData);
            }
        }
    }
}
