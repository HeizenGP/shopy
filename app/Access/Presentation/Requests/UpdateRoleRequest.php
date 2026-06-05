<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\UpdateRoleData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/',
                Rule::unique('roles', 'slug')->ignore($this->route('role')),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function toData(): UpdateRoleData
    {
        $validated = $this->validated();

        return new UpdateRoleData(
            id: (int) $this->route('role'),
            name: $validated['name'],
            slug: $validated['slug'],
            description: $validated['description'] ?? null,
            permissionIds: $validated['permission_ids'] ?? [],
        );
    }
}
