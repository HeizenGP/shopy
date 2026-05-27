<?php

namespace App\Modules\Auth\Infrastructure\Adapters;

use App\Modules\Auth\Application\Ports\AuthSessionManager;
use App\Modules\Auth\Domain\Entities\AuthUser;
use Illuminate\Support\Facades\Auth;

class LaravelSessionAuthManager implements AuthSessionManager
{
    public function login(AuthUser $user): void
    {
        Auth::loginUsingId($user->getId());
    }

    public function logout(): void
    {
        Auth::logout();

        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }
}
