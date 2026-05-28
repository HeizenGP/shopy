@extends('layouts.app')

@section('title', 'Acceso Administrativo')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Panel interno</p>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-2">Acceso administrativo</h1>
            <p class="text-xs text-slate-400 mt-2">Solo Super Admin y Ventas Admin pueden ingresar aquí</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-2">Correo administrativo</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 mb-2">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-md">
                Entrar al panel
            </button>
        </form>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-left text-xs text-slate-600">
            <p class="font-bold text-slate-700 mb-1">Cuentas internas:</p>
            <ul class="list-disc list-inside space-y-0.5 font-mono text-[10px]">
                <li><span class="font-bold">Super Admin:</span> admin@shopcms.com / admin123</li>
                <li><span class="font-bold">Ventas:</span> ventas@shopcms.com / ventas123</li>
            </ul>
        </div>
    </div>
</div>
@endsection
