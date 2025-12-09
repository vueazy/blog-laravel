<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
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
            ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->paginate($request->per_page ?? 10);

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

    /**
     * Send comments to the specific post
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(CommentRequest $request, string $id)
    {
        $comments = Comment::create($request->validated() + ['post_id' => $id]);

        return responseJson(
            message: 'Comment added successfully',
            data: new CommentResource($comments)
        );
    }

    /**
     * Display the specified resource.
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, string $id)
    {
        $comment = Comment::query()
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where('content', 'like', "%{$request->search}%");
            })
            ->where('post_id', $id)
            ->get();

        return responseJson(
            message: 'Comments retrieved successfully',
            data: CommentResource::collection($comment)
        );
    }

    public function publish(string $id)
    {
        $post = Post::findOrFail($id);
        
        if (Auth::user()->hasRole(['admin', 'editor'])) {
            $post->update([
                'status' => 'published', 
                'published_at' => now(),
                'published_by' => Auth::user()->email
            ]);

            return responseJson(
                message: 'Post successfullly published',
                data: new PostResource($post),
            );
        }

        return responseJson(
            success: false,
            errors: 'You are not authorized to publish this post',
            code: Response::HTTP_UNAUTHORIZED
        );
    }
}
