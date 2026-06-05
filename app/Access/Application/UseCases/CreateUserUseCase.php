<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\CreateUserData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;

final readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(CreateUserData $data, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): UserModel
    {
        $user = $this->users->create($data);

        $this->auditLogs->record($actor?->id, 'user_created', $ipAddress, $userAgent, [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return $user;
    }
}
