<?php

namespace App\Access\Application\UseCases;

use App\Access\Application\DTOs\ForgotPasswordData;
use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Password;

final readonly class SendPasswordResetLinkUseCase
{
    public const GENERIC_MESSAGE = 'Si el correo existe en nuestro sistema, recibirás un enlace para restablecer tu contraseña.';

    public function __construct(
        private UserRepositoryInterface $users,
        private AuditLogRepositoryInterface $auditLogs,
    ) {}

    public function execute(ForgotPasswordData $data, ?string $ipAddress = null, ?string $userAgent = null): string
    {
        $email = $data->email;
        $user = $this->users->findByEmail($email);

        Password::sendResetLink(['email' => $email]);

        $this->auditLogs->record(
            userId: $user?->id,
            event: 'password_reset_requested',
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            metadata: ['email' => $email],
        );

        return self::GENERIC_MESSAGE;
    }
}
