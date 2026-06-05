<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\CreateRoleData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;

final readonly class CreateRoleUseCase
{
    public function __construct(
        private RoleRepositoryInterface $roles,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(CreateRoleData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): RoleModel
    {
        $role = $this->roles->create($data);

        $this->auditLogs->record($actor?->id, 'role_created', $ipAddress, $userAgent, [
            'role_id' => $role->id,
            'slug' => $role->slug,
        ]);

        return $role;
    }
}
