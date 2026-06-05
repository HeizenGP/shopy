<?php

namespace App\Access\Application\DTOs;

final readonly class AssignPermissionsData
{
    public function __construct(
        public int $roleId,
        public array $permissionIds,
    ) {}
}
