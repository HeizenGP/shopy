<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\LoginData;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function toData(): LoginData
    {
        $validated = $this->validated();

        return new LoginData(
            email: $validated['email'],
            password: $validated['password'],
            remember: $this->boolean('remember'),
        );
    }
}
