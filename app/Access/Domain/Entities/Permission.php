<?php

namespace App\Access\Domain\Entities;

use App\Access\Domain\ValueObjects\PermissionSlug;

final readonly class Permission
{
    public function __construct(
        public int $id,
        public string $name,
        public PermissionSlug $slug,
        public string $module,
        public ?string $description = null,
    ) {}
}
