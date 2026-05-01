<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create one if none exist
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // Create 15 dummy posts
        Post::factory(15)->create([
            'user_id' => $user->id,
        ]);
    }
}
