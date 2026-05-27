<?php

namespace App\Modules\Users\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Application\UseCases\RegisterUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DomainException;

class UserController extends Controller
{
    public function __construct(private RegisterUserUseCase $registerUserUseCase) {}

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = $this->registerUserUseCase->execute(
                name: $request->input('name'),
                email: $request->input('email'),
                password: $request->input('password')
            );

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'user' => $user,
            ], 201);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
