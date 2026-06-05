<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\LoginData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(LoginData $data, ?string $ipAddress = null, ?string $userAgent = null): UserModel
    {
        $user = $this->users->findByEmail($data->email);

        if (! $user || ! Hash::check($data->password, $user->password) || ! $user->is_active) {
            $this->auditLogs->record(
                userId: $user?->id,
                event: 'login_failed',
                ipAddress: $ipAddress,
                userAgent: $userAgent,
                metadata: ['email' => mb_strtolower(trim($data->email))]
            );

            throw ValidationException::withMessages([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        Auth::login($user, $data->remember);
        $this->users->markLogin($user->id, $ipAddress);
        $this->auditLogs->record($user->id, 'login_success', $ipAddress, $userAgent);

        return $user->fresh('roles.permissions');
    }
}
