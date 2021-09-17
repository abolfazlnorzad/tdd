<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

class PostTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function Model(): Model
    {
       return new Post();
    }

    public function testPostRelationWithUser()
    {
        $post = Post::factory()
            ->for(User::factory())
            ->create();

        $this->assertTrue(isset($post->user_id));
        $this->assertTrue($post->user instanceof User);
    }


    public function testPostRelationWithTag()
    {
        $count = rand(1,10);
        $post = Post::factory()
            ->hasTags($count)
            ->create();
        assertCount($count,$post->tags);
        assertTrue($post->tags->first() instanceof Tag);
    }

    public function testPostRelationWithComment()
    {
        $count= rand(1,10);
        $post = Post::factory()
            ->has(Comment::factory()->count($count))
            ->create();
        $this->assertCount($count,$post->comments);
    }


}
