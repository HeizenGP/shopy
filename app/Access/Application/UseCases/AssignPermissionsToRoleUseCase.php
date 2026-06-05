<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\AssignPermissionsData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;

final readonly class AssignPermissionsToRoleUseCase
{
    public function __construct(
        private RoleRepositoryInterface $roles,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(AssignPermissionsData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->roles->syncPermissions($data->roleId, $data->permissionIds);

        $this->auditLogs->record($actor?->id, 'permissions_assigned', $ipAddress, $userAgent, [
            'role_id' => $data->roleId,
            'permission_ids' => $data->permissionIds,
        ]);
    }
}
