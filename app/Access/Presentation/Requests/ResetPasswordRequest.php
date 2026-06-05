<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\ResetPasswordData;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:180'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ];
    }

    public function toData(): ResetPasswordData
    {
        $validated = $this->validated();

        return new ResetPasswordData(
            token: $validated['token'],
            email: $validated['email'],
            password: $validated['password'],
            passwordConfirmation: $this->string('password_confirmation')->toString(),
        );
    }
}
