<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with('user', 'category', 'comments', 'tags')
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('excerpt', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            })
            ->paginate(10);

        return responseJson(
            message: 'Posts retrieved successfully',
            data: PostResource::collection($posts)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->saveChanges());

        $post->tags()->sync($request->tags);

        return responseJson(
            message: 'Post created successfully',
            data: new PostResource($post)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user', 'category', 'comments', 'tags')->findOrFail($id);

        return responseJson(
            message: 'Post retrieved successfully',
            data: new PostResource($post)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $post = Post::with('user', 'category', 'comments', 'tags')->findOrFail($id);

        $post->update($request->saveChanges());

        $post->tags()->sync($request->tags);

        return responseJson(
            message: 'Post updated successfully',
            data: new PostResource($post)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::with('user', 'category', 'comments', 'tags')->findOrFail($id);

        $post->delete();

        if (Storage::exists("post-thumbnail/{$post->thumbnail}")) {
            Storage::delete("post-thumbnail/{$post->thumbnail}");
        }

        return responseJson(
            message: 'Post deleted successfully',
            data: new PostResource($post)
        );
    }
}
