<?php

namespace App\Access\Application\DTOs;

final readonly class UpdateUserData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $password,
        public bool $isActive,
        public array $roleIds = [],
    ) {}
}
