<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $middlewares = ['web', 'admin'];

    public function testIndexMethod()
    {
//        $this->withoutExceptionHandling();
        $tags = Tag::factory()->count(20)->create();
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->get(route("tag.index"))
            ->assertViewIs("admin.tag.index")
            ->assertViewHas("tags", Tag::query()->latest()->paginate(15))
            ->assertOk();
        $this->assertEquals($this->middlewares, request()->route()->middleware());

    }

    public function testCreateMethod()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->get(route("tag.create"))
            ->assertViewIs("admin.tag.create")
            ->assertOk();
        $this->assertEquals($this->middlewares, request()->route()->middleware());
    }

    public function testEditMethod()
    {
        $tag = Tag::factory()->create();
        $user = User::factory()->admin()->create();
        $this->actingAs($user)
            ->get(route("tag.edit", $tag->id))
            ->assertViewIs("admin.tag.edit")
            ->assertViewHas("tag", $tag)
            ->assertOk();
        $this->assertEquals($this->middlewares, request()->route()->middleware());
    }

    public function testStoreMethod()
    {
        $user = User::factory()->admin()->create();
        $data = Tag::factory()->make()->toArray();
        $this->actingAs($user)
            ->post(route("tag.store", $data))
            ->assertSessionHas("message", "new tag has been created.")
            ->assertRedirect(route("tag.index"));
        $this->assertDatabaseHas("tags", $data);
        $this->assertEquals($this->middlewares, request()->route()->middleware());

    }

    public function testUpdateMethod()
    {
        $tag = Tag::factory()->create();
        $user = User::factory()->admin()->create();
        $data = Tag::factory()->make()->toArray();
        unset($data['user_id']);
        $this->actingAs($user)
            ->patch(route("tag.update", $tag->id), $data)
            ->assertRedirect(route("tag.index"))
            ->assertSessionHas("message", "the tag has been updated.");
        $this->assertDatabaseHas("tags", array_merge(['id' => $tag->id], $data));
        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );

    }


    public function testRequiredValidation()
    {
        $user = User::factory()->admin()->create();
        $data = [];
        $errors = ['name' => 'The name field is required.'];
        $this->actingAs($user)
            ->post(route("tag.store", $data))
            ->assertSessionHasErrors($errors);

        $this
            ->actingAs($user)
            ->patch(route('tag.update', Tag::factory()->create()->id), $data)
            ->assertSessionHasErrors($errors);
    }


    public function testMinCharValidation()
    {
        $user = User::factory()->admin()->create();
        $tag = Tag::factory()->create();
        $errors = ["name" => "The name must be at least 5 characters."];
        $data = ["name" => "abol"];
        $this->actingAs($user)
            ->post(route("tag.store"), $data)
            ->assertSessionHasErrors($errors);

        $this->actingAs($user)
            ->patch(route("tag.update", $tag->id), $data)
            ->assertSessionHasErrors($errors);
    }


    public function testDestroyMethod()
    {
        $tag = Tag::factory()->hasPosts(15)->create();
        $user = User::factory()->admin()->create();

        $this->actingAs($user)->delete(route("tag.destroy", $tag->id))
            ->assertSessionHasAll(['message' => 'the tag has been deleted'])
            ->assertRedirect(route('tag.index'));

        $this->assertDeleted($tag)
            ->assertEmpty($tag->posts);

        $this->assertEquals($this->middlewares, request()->route()->middleware());
    }


}
