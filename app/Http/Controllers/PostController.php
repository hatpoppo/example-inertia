<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        if  ($request->user_id !== null){
            $posts = Post::with('user')->where('user_id',$request->user_id)->latest()->paginate(10);
        }else{
            $posts = Post::with('user')->latest()->paginate(10);
        }
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'users' => User::get(),
            'selected_user_id' => $request->user_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Posts/Edit', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request):RedirectResponse
    {
        $post = new Post;
        $post->fill($request->validated());
        $post->save();
        return Redirect::route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): Response
    {
        return Inertia::render('Posts/Show', [
            'post' => $post,
            'isFavourite' => Auth::user()->favorite_posts()->where('post_id', $post->id)->exists(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): Response
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): Response
    {
        $post->fill($request->validated());
        $post->save();
        return Inertia::render('Posts/Show', [
            'post' => $post,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post):RedirectResponse
    {
        $post->delete();
        return Redirect::route('posts.index');
    }
}
