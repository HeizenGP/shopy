<?php

namespace App\Modules\Auth\Application\DTOs;

class LoginInputDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
