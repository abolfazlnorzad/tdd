<?php

namespace Tests\Feature\Middlewares;

use App\Http\Middleware\UserActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserActivityMiddlewareTest extends TestCase
{

    public function testUserActivityWhenUserIsLoggedIn()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $req = Request::create("/", "GET");
        $middleware = new UserActivity();
        $res = $middleware->handle($req, function () {
        });
        $this->assertNull($res);
        $this->assertEquals("online", cache()->get("user-{$user->id}-status"));
        $this->travel(11)->seconds();
        $this->assertNull(cache()->get("user-{$user->id}-status"));
    }


    public function testUserActivityWhenUserIsNotLoggedIn()
    {

        $req = Request::create("/", "GET");
        $middleware = new UserActivity();
        $res = $middleware->handle($req, function () {
        });
        $this->assertNull($res);
    }

    public function testUserActivityMiddlewareSetInWebGroup()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route("home"))->assertOk();

        $this->assertEquals("online", cache()->get("user-{$user->id}-status"));

        $this->assertEquals(\request()->route()->middleware(), ['web']);
    }

}
