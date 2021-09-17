<?php


namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ModelHelperTesting
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_insert_data()
    {
        $model = $this->Model();
        $table = $model->getTable();
        $data = $model::factory()->make()->toArray();
        if ($model instanceof User)
            $data['password'] =1234567;
        $model::create($data);
        $this->assertDatabaseHas($table, $data);

    }

    abstract protected function Model(): Model;

}
