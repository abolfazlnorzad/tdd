<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function Model(): Model
    {
        return new Comment();
    }

    public function testCommentRelationWithPost()
    {
        $comment = Comment::factory()
            ->has(Post::factory(),"commentable")
            ->create();
        $this->assertTrue(isset($comment->commentable->id));
        $this->assertTrue($comment->commentable instanceof Post);
    }

    public function testCommentRelationWithUser()
    {
        $comment = Comment::factory()
            ->for(User::factory())
            ->create();
        $this->assertTrue($comment->user instanceof User);
        $this->assertTrue(isset($comment->user->id));
    }

}
