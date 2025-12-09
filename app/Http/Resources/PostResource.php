<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->category),
            'user' => new UserResource($this->user),
            'comments' => CommentResource::collection($this->comments),
            'tags' => TagResource::collection($this->tags),
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'slug' => $this->slug,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->published_at,
        ];
    }
}
