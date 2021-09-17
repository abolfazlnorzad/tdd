<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        Post::factory()->count(50)->create();
        $response = $this->get(route("home"));
        $response->assertStatus(200);
        $response->assertViewIs("home");
        $response->assertViewHas("posts",Post::query()->latest()->paginate("15"));
    }
}
