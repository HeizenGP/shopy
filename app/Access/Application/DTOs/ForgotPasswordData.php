<?php

namespace App\Access\Application\DTOs;

final readonly class ForgotPasswordData
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = mb_strtolower(trim($email));
    }
}
