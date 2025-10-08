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
    // Create users
    User::factory(9)->create();

    $testUser = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'testtest'
    ]);

    // Create categories
    $categories = collect([
        ['title' => 'Game Updates'],
        ['title' => 'Games'],
        ['title' => 'Community Originals'],
        ['title' => 'General'],
    ])->map(fn($cat) => ForumCategory::create($cat));

    // Create forums (attach each to a random category)
    $forums = Forum::factory(10)->make()->each(function ($forum) use ($categories) {
        $forum->forum_category_id = $categories->random()->id;
        $forum->save();
    });

    // Create threads (each belongs to a random forum + user)
    $threads = Thread::factory(30)->make()->each(function ($thread) use ($forums) {
        $thread->forum_id = $forums->random()->id;
        $thread->user_id = User::inRandomOrder()->first()->id;
        $thread->save();
    });

    // Create posts (each belongs to a random thread + user)
    Post::factory(50)->make()->each(function ($post) use ($threads) {
        $post->thread_id = $threads->random()->id;
        $post->user_id = User::inRandomOrder()->first()->id;
        $post->save();
    });
}


}
