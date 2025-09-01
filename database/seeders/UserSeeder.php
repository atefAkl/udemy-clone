<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User - عاطف عقل
        User::create([
            'name' => 'عاطف عقل',
            'email' => 'atef@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'preferred_language' => 'ar',
            'phone' => '+201234567890',
            'bio' => 'مدير التطبيق ومطور النظام',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Instructor User - على عاطف عقل
        User::create([
            'name' => 'على عاطف عقل',
            'email' => 'ali@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'preferred_language' => 'ar',
            'phone' => '+201234567891',
            'bio' => 'مدرس خبير في البرمجة والتطوير',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Student User - ريناد عاطف عقل
        User::create([
            'name' => 'ريناد عاطف عقل',
            'email' => 'rinad@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'student',
            'preferred_language' => 'ar',
            'phone' => '+201234567892',
            'bio' => 'طالبة متحمسة للتعلم والتطوير',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create additional test users for demonstration
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'ahmed@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'preferred_language' => 'ar',
            'phone' => '+201234567893',
            'bio' => 'مدرس متخصص في التصميم والجرافيك',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'فاطمة علي',
            'email' => 'fatima@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'student',
            'preferred_language' => 'ar',
            'phone' => '+201234567894',
            'bio' => 'طالبة في مجال التسويق الرقمي',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'محمد حسن',
            'email' => 'mohamed@udemy.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'student',
            'preferred_language' => 'ar',
            'phone' => '+201234567895',
            'bio' => 'مطور مبتدئ يسعى لتطوير مهاراته',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
