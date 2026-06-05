<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\UpdateUserData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;

final readonly class UpdateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(UpdateUserData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): UserModel
    {
        $user = $this->users->update($data->id, $data);

        $this->auditLogs->record($actor?->id, 'user_updated', $ipAddress, $userAgent, [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return $user;
    }
}
