<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersAndPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        // Create posts for John Doe
        Post::create([
            'title' => 'First Post by John',
            'content' => 'This is the content of the first post by John Doe.',
            'author_id' => $user1->id,
        ]);

        Post::create([
            'title' => 'Second Post by John',
            'content' => 'This is the content of the second post by John Doe.',
            'author_id' => $user1->id,
        ]);

        // Create posts for Jane Smith
        Post::create([
            'title' => 'First Post by Jane',
            'content' => 'This is the content of the first post by Jane Smith.',
            'author_id' => $user2->id,
        ]);
    }
}
