<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\DTOs\LoginInputDTO;
use App\Modules\Auth\Application\DTOs\LoginOutputDTO;
use App\Modules\Auth\Application\Ports\AuthSessionManager;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Domain\Repositories\AuthUserRepository;
use App\Modules\Auth\Domain\Services\PasswordHasher;
use App\Modules\Auth\Domain\ValueObjects\Email;

class LoginUseCase
{
    public function __construct(
        private AuthUserRepository $repository,
        private PasswordHasher $hasher,
        private AuthSessionManager $sessionManager
    ) {}

    public function execute(LoginInputDTO $input): LoginOutputDTO
    {
        $email = new Email($input->email);

        $user = $this->repository->findByEmail($email);

        if (!$user) {
            throw new InvalidCredentialsException();
        }

        if (!$this->hasher->verify($input->password, $user->getPasswordHash())) {
            throw new InvalidCredentialsException();
        }

        $this->sessionManager->login($user);

        return new LoginOutputDTO(
            $user->getId(),
            $user->getName(),
            $user->getEmail()->getValue()
        );
    }
}
