<?php

namespace App\Access\Presentation\Requests;

use App\Access\Application\DTOs\ForgotPasswordData;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:180'],
        ];
    }

    public function toData(): ForgotPasswordData
    {
        $validated = $this->validated();

        return new ForgotPasswordData(email: $validated['email']);
    }
}
