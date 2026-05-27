<?php

namespace App\Modules\Auth\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\DTOs\LoginInputDTO;
use App\Modules\Auth\Application\UseCases\LoginUseCase;
use App\Modules\Auth\Application\UseCases\LogoutUseCase;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Presentation\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LogoutUseCase $logoutUseCase
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $inputDto = new LoginInputDTO(
                email: $request->input('email'),
                password: $request->input('password')
            );

            $outputDto = $this->loginUseCase->execute($inputDto);

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso.',
                'user' => $outputDto->toArray(),
            ]);
        } catch (InvalidCredentialsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        $this->logoutUseCase->execute();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.',
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function webLogin(LoginRequest $request)
    {
        try {
            $inputDto = new LoginInputDTO(
                email: $request->input('email'),
                password: $request->input('password')
            );

            $this->loginUseCase->execute($inputDto);

            return redirect()->intended('/dashboard')->with('success', '¡Inicio de sesión exitoso!');
        } catch (InvalidCredentialsException $e) {
            return redirect()->back()
                ->withErrors(['email' => $e->getMessage()])
                ->withInput();
        }
    }

    public function webLogout()
    {
        $this->logoutUseCase->execute();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
