<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\Ports\AuthSessionManager;

class LogoutUseCase
{
    public function __construct(
        private AuthSessionManager $sessionManager
    ) {}

    public function execute(): void
    {
        $this->sessionManager->logout();
    }
}
