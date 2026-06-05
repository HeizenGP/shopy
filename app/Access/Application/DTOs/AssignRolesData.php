<?php

namespace App\Access\Application\DTOs;

final readonly class AssignRolesData
{
    public function __construct(
        public int $userId,
        public array $roleIds,
    ) {}
}
