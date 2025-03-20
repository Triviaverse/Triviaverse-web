<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Tanar User',
            'email' => 'tanar@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher'
        ]);

        User::factory()->create([
            'name' => 'Diak User',
            'email' => 'diak@example.com',
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);

        Quiz::factory(10)->create();
    }
}

