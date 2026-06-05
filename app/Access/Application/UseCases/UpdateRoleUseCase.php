<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\UpdateRoleData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;

final readonly class UpdateRoleUseCase
{
    public function __construct(
        private RoleRepositoryInterface $roles,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(UpdateRoleData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): RoleModel
    {
        $role = $this->roles->update($data->id, $data);

        $this->auditLogs->record($actor?->id, 'role_updated', $ipAddress, $userAgent, [
            'role_id' => $role->id,
            'slug' => $role->slug,
        ]);

        return $role;
    }
}
