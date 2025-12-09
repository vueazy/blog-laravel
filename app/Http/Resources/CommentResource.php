<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post' => $this->post,
            'user' => $this->user,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'content' => $this->content,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
