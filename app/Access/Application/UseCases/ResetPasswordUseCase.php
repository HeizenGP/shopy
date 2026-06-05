<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\ResetPasswordData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final readonly class ResetPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(ResetPasswordData $data, ?string $ipAddress = null, ?string $userAgent = null): string
    {
        $email = $data->email;
        $resetUser = null;

        $status = Password::reset(
            [
                'email' => $email,
                'token' => $data->token,
                'password' => $data->password,
                'password_confirmation' => $data->passwordConfirmation,
            ],
            function (UserModel $user, string $password) use (&$resetUser): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                    'password_changed_at' => now(),
                ])->save();

                $resetUser = $user;
            }
        );

        $user = $resetUser ?? $this->users->findByEmail($email);

        $this->auditLogs->record(
            userId: $user?->id,
            event: $status === Password::PASSWORD_RESET ? 'password_reset_success' : 'password_reset_failed',
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            metadata: ['email' => $email],
        );

        return $status;
    }
}
