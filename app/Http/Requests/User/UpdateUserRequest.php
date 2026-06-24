<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => 'sometimes|email|unique:users,email,' . $this->route('user'),
            'password' => 'sometimes|min:8',
            'role' => 'sometimes|in:admin,supervisor,intern',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
