<?php

namespace App\Modules\Auth\Domain\Entities;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $emailVerifiedAt = null
    ) {}
}
