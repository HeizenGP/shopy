<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Validation\ValidationException;

final readonly class DeleteRoleUseCase
{
    public function __construct(
        private RoleRepositoryInterface $roles,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(int $roleId, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $role = $this->roles->find($roleId);

        if (! $role) {
            return;
        }

        if (! $role->canBeDeleted()) {
            throw ValidationException::withMessages([
                'role' => 'Los roles de sistema no pueden eliminarse.',
            ]);
        }

        $this->roles->delete($roleId);

        $this->auditLogs->record($actor?->id, 'role_deleted', $ipAddress, $userAgent, [
            'role_id' => $roleId,
            'slug' => $role->slug,
        ]);
    }
}
