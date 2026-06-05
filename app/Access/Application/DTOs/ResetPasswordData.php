<?php

namespace App\Access\Application\DTOs;

final readonly class ResetPasswordData
{
    public string $email;

    public function __construct(
        public string $token,
        string $email,
        public string $password,
        public string $passwordConfirmation,
    ) {
        $this->email = mb_strtolower(trim($email));
    }
}
