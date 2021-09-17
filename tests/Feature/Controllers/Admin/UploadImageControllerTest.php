<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadImageControllerTest extends TestCase
{
    protected $middlewares = ['web', 'admin'];
    public function testAdminCanUploadImageForPost()
    {
        $user = User::factory()->admin()->create();
        $image = UploadedFile::fake()->image("image.png");

        $this->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(route("upload"), compact("image"))
            ->assertJson(["url"=>"/upload/{$image->hashName()}"])
            ->assertOk();

        $this->assertFileExists(public_path("/upload/{$image->hashName()}"));
        $this->assertEquals($this->middlewares, request()->route()->middleware());

    }


    public function testImageRule()
    {
        $user = User::factory()->admin()->create();
        $image = UploadedFile::fake()->create("image.txt");
        $errors =[
            'image' => 'The image must be an image.',
        ];
        $this->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(route("upload"),compact('image'))
            ->assertJsonValidationErrors($errors);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));
    }

    public function testMaxSizeRule()
    {
        $user = User::factory()->admin()->create();
        $image = UploadedFile::fake()->create("image.png",251);
        $errors =[
            'image' => 'The image must not be greater than 250 kilobytes.'
        ];
        $this->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(route("upload"),compact('image'))
            ->assertJsonValidationErrors($errors);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));
    }

    public function testSizeImageRule()
    {
        $user = User::factory()->admin()->create();
        $image = UploadedFile::fake()->image("image.png",101,201)->size(50);
        $errors =[
            'image' => 'The image has invalid image dimensions.',
        ];
        $this->actingAs($user)
            ->withHeaders([
                'HTTP_X-Requested-with' => 'XMLHttpRequest'
            ])
            ->postJson(route("upload"),compact('image'))
            ->assertJsonValidationErrors($errors);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));
    }




}
