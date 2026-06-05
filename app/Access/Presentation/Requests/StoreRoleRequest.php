<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\CreateRoleData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function toData(): CreateRoleData
    {
        $validated = $this->validated();

        return new CreateRoleData(
            name: $validated['name'],
            slug: $validated['slug'] ?? Str::slug($validated['name'], '_'),
            description: $validated['description'] ?? null,
            permissionIds: $validated['permission_ids'] ?? [],
        );
    }
}
