<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Post;

class PostModelTest extends TestCase
{
    /**
     * Test for creation without parameters
     *
     * 
     * @return void
     */
    public function testPostCreationEmpty()
    {
        try
        {
            $post = Post::create([]);
            $this->fail('No exception when creating with empty array');
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
        }
        
        try
        {
            $post = Post::create([
                'title' => 'Title'
            ]);
            $this->fail('No exception when creating with partial array');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
        }

        try
        {
            $post = Post::create([
                'title' => 'Title',
                'body' => 'Body',
                'user_id' => 1
            ]);

            $this->fail('No exception when creating with full array. user_id shall be filtered.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
        }

        $user = \App\User::first();
        $this->assertFalse(is_null($user));

        $folder = $user->folders()->first();
        $this->assertFalse(is_null($folder));

        $post = $user->posts()->create([
            'title' => 'Title',
            'body' => 'Body',
            'user_id' => 5,
            'folder_id' => $folder->id
        ]);

        $this->assertFalse(is_null($post));
        $this->assertTrue($post->user_id == $user->id);
        $this->assertTrue($post->folder_id == $folder->id);
    }
}
