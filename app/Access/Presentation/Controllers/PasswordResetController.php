<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Application\UseCases\ResetPasswordUseCase;
use App\Access\Application\UseCases\SendPasswordResetLinkUseCase;
use App\Access\Presentation\Requests\ForgotPasswordRequest;
use App\Access\Presentation\Requests\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm(): View
    {
        return view('access.admin.auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request, SendPasswordResetLinkUseCase $sendResetLink): RedirectResponse
    {
        $message = $sendResetLink->execute($request->toData(), $request->ip(), $request->userAgent());

        return back()->with('status', $message);
    }

    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('access.admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function reset(ResetPasswordRequest $request, ResetPasswordUseCase $resetPassword): RedirectResponse
    {
        $status = $resetPassword->execute($request->toData(), $request->ip(), $request->userAgent());

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.login')
                ->with('status', 'Contraseña actualizada. Inicia sesión con tu nueva contraseña.');
        }

        throw ValidationException::withMessages([
            'email' => 'No se pudo restablecer la contraseña. Inténtalo nuevamente.',
        ]);
    }
}
