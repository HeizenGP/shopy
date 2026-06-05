<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Support\Facades\Auth;

final readonly class LogoutUseCase
{
    public function __construct(private AuditLogRepositoryInterface $auditLogs) {}

    public function execute(?UserModel $user, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->auditLogs->record($user?->id, 'logout', $ipAddress, $userAgent);

        Auth::logout();
    }
}
