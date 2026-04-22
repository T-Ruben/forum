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
        $categories = ['Game Updates', 'Games', 'Community Originals', 'General'];

        foreach($categories as $title) {
            ForumCategory::create(['title' => $title]);
        }




    }
}
