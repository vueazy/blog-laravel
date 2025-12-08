<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Traits\HandleUploadFile;
use App\Traits\HandleJsonValidation;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    use HandleJsonValidation, HandleUploadFile;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function saveChanges(): array
    {   
        $validatedData =  $this->validated();

        $post = Post::where('id', $this->route('post'))->first();
        
        if ($this->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = !empty($validatedData['thumbnail'])
                ? $this->syncUploadFile($validatedData['thumbnail'], $post?->thumbnail, 'post-thumbnail')
                : $post?->thumbnail;
        }

        return $validatedData;
    }
}
