@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Crear una cuenta</h1>
            <p class="text-xs text-slate-400 mt-2">Únete a ShopCMS para realizar compras y guardar tu historial</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 mb-2">Nombre completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 mb-2">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 mb-2">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-md">
                Registrarse
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                    Inicia sesión aquí
                </a>
            </p>
        </div>

    </div>
</div>
@endsection
