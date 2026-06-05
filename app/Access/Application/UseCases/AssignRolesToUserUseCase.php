<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\AssignRolesData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;

final readonly class AssignRolesToUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(AssignRolesData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->users->syncRoles($data->userId, $data->roleIds);

        $this->auditLogs->record($actor?->id, 'roles_assigned', $ipAddress, $userAgent, [
            'user_id' => $data->userId,
            'role_ids' => $data->roleIds,
        ]);
    }
}
