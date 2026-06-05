<?php

namespace App\Access\Application\DTOs;

final readonly class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
