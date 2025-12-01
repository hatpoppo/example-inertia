<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        if  ($request->user_id !== null){
            $posts = Post::with('user', 'favorite_users')->where('user_id',$request->user_id)->latest()->paginate(10);
        }elseif  ($request->selectedTags !== null){
            $posts = Post::with('user', 'favorite_users')->whereHas('tags',function (Builder $query) use ($request) { 
                $query->where('tags.user_id', Auth::user()->id)->whereIn('tags.id',$request->selectedTags);
            })->latest()->paginate(10);
        }else{
            $posts = Post::with('user', 'favorite_users')->latest()->paginate(10);
        }
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'users' => User::get(),
            'selected_user_id' => $request->user_id,
            'tags' => $request->user()->tags,
            'selectedTags' => $request->selectedTags ? array_map('intval', $request->selectedTags) : [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Posts/Edit', [
            'tags' => $request->user()->tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $post = new Post;
            $post->fill($request->validated());
            $post->save();
            $post->tags()->sync($request->tags);
        });
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
            'comments' => Comment::where('post_id',$post->id)->with(['user'])->latest()->get(),
            'tags' => $post->tags,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): Response
    {
        $post->tags;
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'tags' => Auth::user()->tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        DB::transaction(function () use ($request, $post) {
            $post->fill($request->validated());
            $post->save();
            $post->tags()->sync($request->tags);
        });
        return Redirect::route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return Redirect::route('posts.index');
    }
}
