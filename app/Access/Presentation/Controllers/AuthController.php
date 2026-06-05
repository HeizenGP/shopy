<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Application\UseCases\LoginUseCase;
use App\Access\Application\UseCases\LogoutUseCase;
use App\Access\Infrastructure\Models\UserModel;
use App\Access\Presentation\Requests\LoginRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('access.admin.auth.login');
    }

    public function login(LoginRequest $request, LoginUseCase $login): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);

        try {
            $login->execute($request->toData(), $request->ip(), $request->userAgent());
        } catch (ValidationException $exception) {
            RateLimiter::hit($this->throttleKey($request));

            throw $exception;
        }

        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(LogoutUseCase $logout): RedirectResponse
    {
        /** @var UserModel|null $user */
        $user = request()->user();
        $logout->execute($user, request()->ip(), request()->userAgent());

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Sesión cerrada.');
    }

    private function ensureIsNotRateLimited(LoginRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Demasiados intentos. Inténtalo nuevamente en {$seconds} segundos.",
        ]);
    }

    private function throttleKey(LoginRequest $request): string
    {
        return Str::transliterate(Str::lower($request->string('email')->toString()).'|'.$request->ip());
    }
}
