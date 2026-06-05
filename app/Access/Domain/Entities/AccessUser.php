<?php

namespace App\Access\Domain\Entities;

use App\Access\Domain\ValueObjects\EmailAddress;

final readonly class AccessUser
{
    public function __construct(
        public int $id,
        public string $name,
        public EmailAddress $email,
        public bool $isActive,
        public array $roles = [],
    ) {}

    public function canAuthenticate(): bool
    {
        return $this->isActive;
    }

    public function hasRole(string $slug): bool
    {
        foreach ($this->roles as $role) {
            if ($role instanceof Role && $role->slug === $slug) {
                return true;
            }

            if (is_array($role) && ($role['slug'] ?? null) === $slug) {
                return true;
            }
        }

        return false;
    }
}
