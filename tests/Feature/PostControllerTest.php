<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class PostControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostIndex()
    {
        factory(\App\Post::class, 10)->create();

        $post = \App\Post::first();
        $this->assertFalse(is_null($post));

        $response = $this->get('/posts/');
        $response->assertStatus(200);

        $response = $this->get('/posts/123456');
        $response->assertStatus(404);

        $response = $this->get('/posts/'.$post->id);
        $response->assertStatus(200);


    }

    public function testPostCreate()
    {
        /*
        $user = factory(User::class)->create();
        
        $response = $this->get('/posts/create');
        $response->assertStatus(302);

        $response = $this->actingAs($user)->get('/posts/create');
        $response->assertStatus(200);
*/
    }
}
