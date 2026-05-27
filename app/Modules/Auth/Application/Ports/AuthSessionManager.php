<?php

namespace App\Modules\Auth\Application\Ports;

use App\Modules\Auth\Domain\Entities\AuthUser;

interface AuthSessionManager
{
    public function login(AuthUser $user): void;
    public function logout(): void;
}
