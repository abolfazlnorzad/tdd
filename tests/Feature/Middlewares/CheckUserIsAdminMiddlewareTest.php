<?php

namespace Tests\Feature\Middlewares;


use App\Http\Middleware\UserIsAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class CheckUserIsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function testUserIsNotAdmin()
    {
        $user = User::factory()->user()->create();
        $this->actingAs($user);
        $req = Request::create("/", 'GET');
        $middleware = new UserIsAdmin();
        $res = $middleware->handle($req, function () {
        });
        $this->assertEquals($res->getStatusCode(), 302);
    }

    public function testUserIsAdmin()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);
        $req = Request::create("/", 'GET');
        $middleware = new UserIsAdmin();
        $res = $middleware->handle($req, function () {
        });
        $this->assertEquals($res, null);
    }


    public function testUserWhenIsNotLoggedIn()
    {
        $req = Request::create("/", 'GET');
        $middleware = new UserIsAdmin();
        $res = $middleware->handle($req, function () {
        });
        $this->assertEquals($res->getStatusCode(), 302);
    }
}
