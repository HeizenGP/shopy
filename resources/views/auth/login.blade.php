@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="rounded-[30px] border border-white/70 bg-white/85 p-6 shadow-2xl backdrop-blur-xl sm:p-8 space-y-6">
        
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bienvenido de nuevo</h1>
            <p class="text-xs text-slate-400 mt-2">Inicia sesión para comprar y revisar tus pedidos</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20 focus:border-client-primary bg-white">
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-semibold text-slate-500">Contraseña</label>
                </div>
                <input type="password" name="password" id="password" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20 focus:border-client-primary bg-white">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-client-primary hover:bg-client-primary/90 rounded-xl transition-all shadow-md">
                Iniciar sesión
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="font-bold text-client-primary hover:text-client-primary/80 transition-colors">
                    Regístrate aquí
                </a>
            </p>
        </div>

        <div class="bg-client-login-bg p-4 rounded-xl border border-client-primary/10 text-left text-xs text-slate-600">
            <p class="font-bold text-client-primary mb-1">Cuentas de prueba:</p>
            <ul class="list-disc list-inside space-y-0.5 font-mono text-[10px]">
                <li><span class="font-bold">Cliente:</span> user@example.com / password</li>
            </ul>
        </div>

    </div>
</div>
@endsection
