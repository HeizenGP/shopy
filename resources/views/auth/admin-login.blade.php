@extends('layouts.admin-auth')

@section('title', 'Acceso Administrativo')

@section('content')
<div class="rounded-[30px] border border-white/10 p-6 shadow-2xl backdrop-blur-xl sm:p-8 space-y-6" style="background-color: var(--color-admin-login-panel); border-color: rgba(255, 255, 255, 0.1);">
        <div class="flex flex-col items-center gap-5 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                @if(\App\Helpers\ShopHelper::getSetting('logo_url'))
                    <img src="{{ \App\Helpers\ShopHelper::getSetting('logo_url') }}" alt="{{ config('shop.name', 'ShopCMS') }}" class="h-14 w-14 rounded-2xl object-contain shadow-sm">
                @else
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-admin-primary text-xl font-black text-white shadow-sm">{{ strtoupper(substr(config('shop.name', 'ShopCMS'), 0, 1)) }}</span>
                @endif
                <div class="text-left">
                    <p class="text-[11px] font-black uppercase tracking-[0.32em] text-admin-primary">Panel administrativo</p>
                    <h1 class="mt-1 text-2xl font-black tracking-tight sm:text-3xl" style="color: var(--color-admin-login-text);">{{ config('shop.name', 'ShopCMS') }}</h1>
                </div>
            </a>

            <div>
                <h2 class="text-3xl font-black tracking-tight sm:text-4xl" style="color: var(--color-admin-login-text);">Acceso administrativo</h2>
                <p class="mt-3 text-sm" style="color: var(--color-admin-login-muted);">Ingresa con tu cuenta autorizada para administrar la tienda.</p>
            </div>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold mb-2" style="color: var(--color-admin-login-muted);">Correo administrativo</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       style="background-color: var(--color-admin-page-bg); color: var(--color-admin-login-text); border-color: rgba(0,0,0,0.1);"
                       class="w-full text-sm px-3.5 py-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold mb-2" style="color: var(--color-admin-login-muted);">Contraseña</label>
                <input type="password" name="password" id="password" required
                       style="background-color: var(--color-admin-page-bg); color: var(--color-admin-login-text); border-color: rgba(0,0,0,0.1);"
                       class="w-full text-sm px-3.5 py-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary">
            </div>

            <button type="submit" class="w-full py-3 px-4 text-sm font-bold text-white bg-admin-primary hover:bg-admin-primary-dark rounded-2xl transition-all shadow-md">
                Entrar al panel
            </button>
        </form>

        <div class="p-4 rounded-2xl border text-left text-xs" style="background-color: var(--color-admin-page-bg); border-color: rgba(0,0,0,0.05); color: var(--color-admin-login-muted);">
            <p class="font-bold mb-1" style="color: var(--color-admin-login-text);">Cuentas internas:</p>
            <ul class="list-disc list-inside space-y-0.5 font-mono text-[10px]">
                <li><span class="font-bold" style="color: var(--color-admin-login-text);">Super Admin:</span> admin@shopcms.com / admin123</li>
                <li><span class="font-bold" style="color: var(--color-admin-login-text);">Ventas:</span> ventas@shopcms.com / ventas123</li>
            </ul>
        </div>
    </div>
@endsection
