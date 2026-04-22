<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            'Game Updates' => [
                'is_admin_only' => true,
                'forums' => ['Announcements', 'Patch Notes']
            ],
            'Games' => [
                'is_admin_only' => false,
                'forums' => ['General Discussion', 'Guides & Tutorials', 'Feedback & Suggestions', 'Technical Support']
            ],
            'Community Originals' => [
                'is_admin_only' => false,
                'forums' => ['Fan Art', 'Lore & Fan Fiction', 'Modding & Assets', 'Media & Clips']
            ],
            'General' => [
                'is_admin_only' => false,
                'forums' => ['Casual Discussion', 'Off-Topic', 'Forum Games', 'Forum Feedback']
            ],
        ];

        foreach($data as $title => $details) {
            $category = ForumCategory::create([
                'title' => $title,
                'is_admin_only' => $details['is_admin_only'],
            ]);

            foreach($details['forums'] as $forumTitle) {
                $category->forums()->create([
                    'title' => $forumTitle,
                ]);
            }
        }

    }
}
