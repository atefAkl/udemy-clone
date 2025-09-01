<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define an array of categories with their subcategories
        $categories = [
            [
                'name' => 'البرمجة',
                'description' => 'دورات في تطوير المواقع، تطبيقات الجوال، والذكاء الاصطناعي.',
                'icon' => 'fas fa-code',
                'color' => '#3498db',
                'is_featured' => true,
                'children' => [
                    ['name' => 'تطوير الويب', 'description' => 'تعلم اللغات والأطر البرمجية مثل Laravel, React, Node.js.', 'icon' => 'fas fa-globe', 'color' => '#2ecc71'],
                    ['name' => 'تطبيقات الجوال', 'description' => 'بناء تطبيقات لأنظمة Android و iOS باستخدام Flutter و React Native.', 'icon' => 'fas fa-mobile-alt', 'color' => '#e74c3c'],
                    ['name' => 'الذكاء الاصطناعي', 'description' => 'مقدمة في تعلم الآلة والتعلم العميق باستخدام Python.', 'icon' => 'fas fa-robot', 'color' => '#f1c40f'],
                ],
            ],
            [
                'name' => 'التصميم',
                'description' => 'دورات في التصميم الجرافيكي، تصميم واجهات المستخدم، وتجربة المستخدم.',
                'icon' => 'fas fa-paint-brush',
                'color' => '#9b59b6',
                'is_featured' => true,
                'children' => [
                    ['name' => 'تصميم الجرافيك', 'description' => 'تعلم استخدام أدوات مثل Photoshop و Illustrator.', 'icon' => 'fas fa-camera-retro', 'color' => '#c0392b'],
                    ['name' => 'تجربة المستخدم (UX)', 'description' => 'أساسيات تصميم تجربة المستخدم وتطويرها.', 'icon' => 'fas fa-user-friends', 'color' => '#34495e'],
                ],
            ],
            [
                'name' => 'التسويق الرقمي',
                'description' => 'دورات في التسويق عبر وسائل التواصل الاجتماعي وال SEO.',
                'icon' => 'fas fa-bullhorn',
                'color' => '#1abc9c',
                'is_featured' => false,
                'children' => [
                    ['name' => 'السيو (SEO)', 'description' => 'تحسين محركات البحث لمواقع الويب.', 'icon' => 'fas fa-search', 'color' => '#f39c12'],
                    ['name' => 'التسويق بالمحتوى', 'description' => 'إنشاء محتوى جذاب وفعال للتسويق.', 'icon' => 'fas fa-edit', 'color' => '#2980b9'],
                ],
            ],
        ];

        $sortOrder = 1;

        foreach ($categories as $categoryData) {
            // Create the parent category
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'color' => $categoryData['color'],
                'status' => 'published',
                'is_featured' => $categoryData['is_featured'],
                'sort_order' => $sortOrder++,
                'meta_title' => $categoryData['name'],
                'meta_description' => $categoryData['description'],
            ]);

            // Create child categories for the parent
            foreach ($categoryData['children'] as $childData) {
                Category::create([
                    'name' => $childData['name'],
                    'slug' => Str::slug($childData['name']),
                    'description' => $childData['description'],
                    'parent_id' => $parent->id,
                    'icon' => $childData['icon'],
                    'color' => $childData['color'],
                    'status' => 'published',
                    'is_featured' => false,
                    'sort_order' => $sortOrder++,
                    'meta_title' => $childData['name'],
                    'meta_description' => $childData['description'],
                ]);
            }
        }
    }
}
