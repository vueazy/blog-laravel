<?php

namespace App\Http\Requests;

use App\Traits\HandleJsonValidation;
use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles,name,' . $this->route('role'),
            'permissions' => 'nullable|array',
        ];
    }
}
