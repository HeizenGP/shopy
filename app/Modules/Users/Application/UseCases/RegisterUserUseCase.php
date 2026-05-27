<?php

namespace App\Modules\Users\Application\UseCases;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DomainException;

class RegisterUserUseCase
{
    public function execute(string $name, string $email, string $password): array
    {
        if (User::where('email', $email)->exists()) {
            throw new DomainException("El correo electrónico ya está registrado.");
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
