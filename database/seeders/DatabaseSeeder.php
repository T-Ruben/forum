<?php

namespace Database\Seeders;

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(9)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        ForumCategory::insert([
            ['title' => 'Game Updates'],
            ['title' => 'Games'],
            ['title' => 'Community Originals'],
            ['title' => 'General'],
        ]);

        Forum::factory(10)->create();

        Thread::factory(30)->create();

        Post::factory(50)->create();
    }
}
