<?php

namespace App\Modules\Auth\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\UseCases\LoginUseCase;
use App\Modules\Auth\Application\UseCases\RegisterUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly RegisterUseCase $registerUseCase
    ) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $user = $this->loginUseCase->execute($credentials['email'], $credentials['password']);
            
            $eloquentUser = \App\Modules\Auth\Infrastructure\Database\Models\UserEloquent::find($user->id);
            Auth::login($eloquentUser);

            $request->session()->regenerate();

            return redirect()->intended('/');
        } catch (Exception $e) {
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = $this->registerUseCase->execute($data['name'], $data['email'], $data['password']);

            $eloquentUser = \App\Modules\Auth\Infrastructure\Database\Models\UserEloquent::find($user->id);
            Auth::login($eloquentUser);

            return redirect('/');
        } catch (Exception $e) {
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
