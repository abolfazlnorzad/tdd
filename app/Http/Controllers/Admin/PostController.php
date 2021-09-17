<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Post::query()->latest()->paginate(15);
        return view("admin.post.index", compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $tags = Tag::query()->latest()->get()->all();
        return view("admin.post.create", compact("tags"));
    }



    public function store(PostRequest $request)
    {
        // request data (user_id, title, description, image, tags)

        $post = auth()->user()->posts()->create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('image'),
        ]);

        $post->tags()->attach($request->input('tags'));

        return redirect(route('post.index'))
            ->with('message', 'new post has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Post $post)
    {
        $tags = Tag::query()->latest()->get()->all();
        return view("admin.post.edit", compact("tags", "post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PostRequest $request, Post $post)
    {

        $post->update([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('image'),
        ]);

        $post->tags()->sync($request->tags);

        return  redirect(route("post.index"))
            ->with('message', 'the post has been updated');
    }


    public function destroy(Post $post)
    {
        $post->tags()->detach();
        $post->comments()->delete();
        $post->delete();

        return  redirect(route("post.index"))->with('message', 'the post has been deleted.');
    }
}
