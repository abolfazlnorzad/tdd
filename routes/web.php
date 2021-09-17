<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, "index"])->name("home");
Route::get('/single/{post}', [\App\Http\Controllers\SingleController::class, "index"])->name("single");
Route::post('/single/{post}/comment', [\App\Http\Controllers\SingleController::class, 'comment'])
    ->middleware('auth:web')
    ->name('single.comment');

Auth::routes();

Route::prefix("admin")->middleware('admin')->group(function () {
    Route::resource("post", \App\Http\Controllers\Admin\PostController::class)
        ->except(['show']);
    Route::resource("tag", \App\Http\Controllers\Admin\TagController::class);
    Route::post("/upload",[\App\Http\Controllers\Admin\UploadImageController::class,"upload"])->name("upload");
});


