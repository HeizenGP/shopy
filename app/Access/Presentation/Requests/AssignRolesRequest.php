<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\AssignRolesData;
use Illuminate\Foundation\Http\FormRequest;

class AssignRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function toData(): AssignRolesData
    {
        $validated = $this->validated();

        return new AssignRolesData(
            userId: (int) $this->route('user'),
            roleIds: $validated['role_ids'],
        );
    }
}
