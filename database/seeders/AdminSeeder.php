<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@udemy.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@udemy.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
                'preferred_language' => 'ar',
            ]
        );

        // Create test instructor
        User::updateOrCreate(
            ['email' => 'instructor@udemy.com'],
            [
                'name' => 'Test Instructor',
                'email' => 'instructor@udemy.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_INSTRUCTOR,
                'is_active' => true,
                'email_verified_at' => now(),
                'preferred_language' => 'ar',
                'bio' => 'مدرب متخصص في التكنولوجيا والبرمجة',
            ]
        );

        // Create test student
        User::updateOrCreate(
            ['email' => 'student@udemy.com'],
            [
                'name' => 'Test Student',
                'email' => 'student@udemy.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'is_active' => true,
                'email_verified_at' => now(),
                'preferred_language' => 'ar',
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@udemy.com / password');
        $this->command->info('Instructor: instructor@udemy.com / password');
        $this->command->info('Student: student@udemy.com / password');
    }
}
