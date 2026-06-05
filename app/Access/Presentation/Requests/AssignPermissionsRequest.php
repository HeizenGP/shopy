<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\AssignPermissionsData;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function toData(): AssignPermissionsData
    {
        $validated = $this->validated();

        return new AssignPermissionsData(
            roleId: (int) $this->route('role'),
            permissionIds: $validated['permission_ids'],
        );
    }
}
