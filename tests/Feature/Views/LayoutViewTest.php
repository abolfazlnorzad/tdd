<?php

namespace Tests\Feature\Views;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LayoutViewTest extends TestCase
{
    use RefreshDatabase;

    public function testLayoutViewRenderWhenUserIsAdmin()
    {
        $user = User::factory()->state(["is_admin"=>true])->create();
        $this->actingAs($user);
        $view = $this->view("Layouts.layout");
        $view->assertSee('<a href="/admin/dashboard">admin panel</a>',false);
    }


    public function testLayoutViewRenderWhenUserIsNotAdmin()
    {
        $user = User::factory()->state(['is_admin'=>false])->create();
        $this->actingAs($user);
        $view = $this->view("Layouts.layout");
        $view->assertDontSee('<a href="/admin/dashboard">admin panel</a>',false);
    }
}
