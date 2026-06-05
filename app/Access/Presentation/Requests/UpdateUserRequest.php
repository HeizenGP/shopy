<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\UpdateUserData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['nullable', 'string', 'min:12', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function toData(): UpdateUserData
    {
        $validated = $this->validated();

        return new UpdateUserData(
            id: (int) $this->route('user'),
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'] ?? null,
            isActive: $this->has('is_active') ? $this->boolean('is_active') : true,
            roleIds: $validated['role_ids'] ?? [],
        );
    }
}
