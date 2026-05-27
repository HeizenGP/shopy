<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;

class RegisterUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $name, string $email, string $password): User
    {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            throw new Exception('El correo electrónico ya está registrado.');
        }

        $hashedPassword = Hash::make($password);
        $user = new User(
            id: 0,
            name: $name,
            email: $email,
            password: $hashedPassword
        );

        return $this->userRepository->save($user);
    }
}
