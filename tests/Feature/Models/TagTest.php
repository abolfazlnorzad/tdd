<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function Model(): Model
    {
        return new Tag();
    }


    public function testTagRelationWithPost()
    {
        $count = rand(1,10);
        $tag = Tag::factory()
            ->has(Post::factory()->count($count))
            ->create();
        $this->assertCount($count,$tag->posts);
        $this->assertTrue($tag->posts->first() instanceof Post);
    }
}
