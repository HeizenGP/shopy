@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bienvenido de nuevo</h1>
            <p class="text-xs text-slate-400 mt-2">Inicia sesión en tu cuenta de ShopCMS</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-semibold text-slate-500">Contraseña</label>
                </div>
                <input type="password" name="password" id="password" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-md">
                Iniciar sesión
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                    Regístrate aquí
                </a>
            </p>
        </div>

        <!-- Testing accounts info -->
        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 text-left text-xs text-slate-600">
            <p class="font-bold text-indigo-700 mb-1">Cuentas de prueba:</p>
            <ul class="list-disc list-inside space-y-0.5 font-mono text-[10px]">
                <li><span class="font-bold">Admin:</span> admin / admin123</li>
                <li><span class="font-bold">Cliente:</span> user@example.com / password</li>
            </ul>
        </div>

    </div>
</div>
@endsection
