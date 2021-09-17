<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        $this->withoutExceptionHandling();
        $post = Post::factory()->has(Comment::factory()->count(rand(0, 3)), "comments")->create();
        $response = $this->get(route("single", $post->id));
        $response->assertOk();
        $response->assertViewIs("single");
        $response->assertViewHasAll([
            "post" => $post,
            "comments" => $post->comments()->latest()->paginate(15)
        ]);
    }

    //store new comment with ajax
    public function testCommentMethodWhenUserLoggedIn()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $data = Comment::factory()->state([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
        ])->make()->toArray();

        $response = $this
            ->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(
                route('single.comment', $post->id),
                ['text' => $data['text']]
            );

        $response->assertOk()->assertJson([
            "created" => true
        ]);
        $this->assertDatabaseHas('comments', $data);
    }

    public function testCommentMethodWhenUserNotLoggedIn()
    {

        $post = Post::factory()->create();
        $data = Comment::factory()->state([
            'commentable_id' => $post->id,
        ])->make()->toArray();

        unset($data['user_id']);
        $response = $this
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(
                route('single.comment', $post->id),
                ['text' => $data['text']]
            );
        $response->assertUnauthorized();
        $this->assertDatabaseMissing('comments', $data);
    }

    /*
     * store new comment without ajax
    public function testCommentMethodWhenUserLoggedIn()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $data = Comment::factory()->state([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
        ])->make()->toArray();

        $response = $this
            ->actingAs($user)
            ->post(
                route('single.comment', $post->id),
                ['text' => $data['text']]
            );

        $response->assertRedirect(route('single', $post->id));
        $this->assertDatabaseHas('comments', $data);
    }

    public function testCommentMethodWhenUserNotLoggedIn()
    {

        $post = Post::factory()->create();
        $data = Comment::factory()->state([
            'commentable_id' => $post->id,
        ])->make()->toArray();

        unset($data['user_id']);
        $response = $this
            ->post(
                route('single.comment', $post->id),
                ['text' => $data['text']]
            );
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', $data);
    }

    */


    public function testCommentMethodValidate()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create();
        $data = Comment::factory()->state([
            "user_id" => $user->id,
            "commentable_id" => $post->id
        ])->make()->toArray();

        $response = $this->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(
                route("single.comment", $post->id),
                ['text' => '']
            );
        $response->assertJsonValidationErrors([
            'text' => 'The text field is required.',
        ]);
    }

}
