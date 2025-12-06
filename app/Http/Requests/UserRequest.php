<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\HandleJsonValidation;
use App\Traits\Traits\HandleUploadFile;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use HandleJsonValidation, HandleUploadFile;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->route('user'),
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8',
        ];

        if (boolval(request()->query('is_register'))) {
            $rules['password'] = str_replace('nullable', 'required', $rules['password']) . '|confirmed';
        }

        return $rules;
    }

    public function saveChanges(): array
    {   
        $validatedData =  $this->validated();

        $user = User::where('id', $this->route('user'))->first();

        if ($this->hasFile('avatar')) {
            $validatedData['avatar'] = !empty($validatedData['avatar'])
                ? $this->syncUploadFile($validatedData['avatar'], $user?->avatar, 'avatar')
                : $user?->avatar;
        }

        return $validatedData;
    }
}
