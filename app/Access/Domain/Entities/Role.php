<?php

namespace App\Access\Domain\Entities;

final readonly class Role
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description = null,
        public bool $isSystem = false,
        public array $permissions = [],
    ) {}

    public function canBeDeleted(): bool
    {
        return ! $this->isSystem;
    }
}
