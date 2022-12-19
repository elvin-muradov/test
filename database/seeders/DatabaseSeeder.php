<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(15)->create();
        User::factory(20)->create();
        Post::factory(40)->create();
        Tag::factory(60)->create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('posts_tags')->insert([
                'post_id' => Post::all()->random()->id,
                'tag_id' => Tag::all()->unique()->random()->id,
            ]);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
