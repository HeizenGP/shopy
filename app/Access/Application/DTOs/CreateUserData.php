<?php

namespace App\Access\Application\DTOs;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public bool $isActive = true,
        public array $roleIds = [],
    ) {}
}
