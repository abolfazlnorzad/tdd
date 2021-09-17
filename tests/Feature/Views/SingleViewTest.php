<?php

namespace Tests\Feature\Views;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleViewTest extends TestCase
{

    use RefreshDatabase;

    public function testViewRenderCanSeeStoreCommentFormWhenUserIsLoggedIn()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comments = [];
        $view = (string)$this->actingAs($user)
            ->view("single", compact("post", "comments"));
        $dom = new \DOMDocument();
        $dom->loadHTML($view);
        $dom = new \DOMXPath($dom);
        $action = route('single.comment', $post->id);
        $this->assertCount(1,
            $dom->query("//form[@method='post'][@action='$action']/textarea[@name='text']")
        );
    }

    public function testViewRenderCanSeeStoreCommentFormWhenUserIsNotLoggedIn()
    {
        $post = Post::factory()->create();
        $comments = [];
        $view = (string)$this->view("single", compact("post", "comments"));
        $action = route("single.comment", $post->id);
        $dom = new \DOMDocument();
        $dom->loadHTML($view);
        $dom = new \DOMXPath($dom);
        $this->assertCount(0, $dom->query("//form[@action='$action'][@method='post']/textarea[@name='text']"));
    }

}
