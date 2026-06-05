<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\CreateUserData;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function toData(): CreateUserData
    {
        $validated = $this->validated();

        return new CreateUserData(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            isActive: $this->has('is_active') ? $this->boolean('is_active') : true,
            roleIds: $validated['role_ids'] ?? [],
        );
    }
}
