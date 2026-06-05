<?php

namespace App\Access\Application\DTOs;

final readonly class CreateRoleData
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description = null,
        public array $permissionIds = [],
    ) {}
}
