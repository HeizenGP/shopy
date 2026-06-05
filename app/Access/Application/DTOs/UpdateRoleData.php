<?php

namespace App\Access\Application\DTOs;

final readonly class UpdateRoleData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description = null,
        public array $permissionIds = [],
    ) {}
}
