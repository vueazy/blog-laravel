<?php

namespace App\Http\Requests;

use App\Traits\HandleJsonValidation;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    use HandleJsonValidation;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_name' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'content' => 'required|string',
        ];
    }
}
