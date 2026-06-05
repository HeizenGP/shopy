<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Validation\ValidationException;

final readonly class DeleteUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(int $userId, ?UserModel $actor = null, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        if ($actor && $actor->id === $userId) {
            throw ValidationException::withMessages([
                'user' => 'No puedes eliminar tu propio usuario.',
            ]);
        }

        $user = $this->users->find($userId);
        $this->users->delete($userId);

        $this->auditLogs->record($actor?->id, 'user_deleted', $ipAddress, $userAgent, [
            'user_id' => $userId,
            'email' => $user?->email,
        ]);
    }
}
