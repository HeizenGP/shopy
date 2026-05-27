<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new Exception('Credenciales incorrectas.');
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception('Credenciales incorrectas.');
        }

        return $user;
    }
}
