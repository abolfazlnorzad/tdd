<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function Model(): Model
    {
        return new User();
    }
    public function testUserRelationWithPost()
    {
        $count = rand(1, 10);
        $user = User::factory()
            ->has(Post::factory()->count($count))
            ->create();
        $this->assertCount($count, $user->posts);
        $this->assertTrue($user->posts->first() instanceof Post);
    }


    public function testUserRelationWithComment()
    {
        $count = rand(1, 10);
        $user = User::factory()
            ->has(Comment::factory()->count($count))
            ->create();
        $this->assertCount($count, $user->comments);
        $this->assertTrue($user->comments->first() instanceof Comment);
    }

}
