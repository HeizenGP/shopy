@extends('layouts.admin-auth')

@section('title', 'Acceso Administrativo')

@section('content')
<div class="rounded-[30px] border border-white/70 bg-white/90 p-6 shadow-2xl backdrop-blur-xl sm:p-8 space-y-6">
        <div class="flex flex-col items-center gap-5 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                @if(\App\Helpers\ShopHelper::getSetting('logo_url'))
                    <img src="{{ \App\Helpers\ShopHelper::getSetting('logo_url') }}" alt="{{ config('shop.name', 'ShopCMS') }}" class="h-14 w-14 rounded-2xl object-contain shadow-sm">
                @else
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-admin-primary text-xl font-black text-white shadow-sm">{{ strtoupper(substr(config('shop.name', 'ShopCMS'), 0, 1)) }}</span>
                @endif
                <div class="text-left">
                    <p class="text-[11px] font-black uppercase tracking-[0.32em] text-admin-primary">Panel administrativo</p>
                    <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">{{ config('shop.name', 'ShopCMS') }}</h1>
                </div>
            </a>

            <div>
                <h2 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Acceso administrativo</h2>
                <p class="mt-3 text-sm text-slate-500">Ingresa con tu cuenta autorizada para administrar la tienda.</p>
            </div>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-2">Correo administrativo</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full text-sm px-3.5 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary bg-white">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 mb-2">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="w-full text-sm px-3.5 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary bg-white">
            </div>

            <button type="submit" class="w-full py-3 px-4 text-sm font-bold text-white bg-slate-950 hover:bg-slate-900 rounded-2xl transition-all shadow-md shadow-slate-950/15">
                Entrar al panel
            </button>
        </form>

        <div class="bg-slate-50/90 p-4 rounded-2xl border border-slate-100 text-left text-xs text-slate-600">
            <p class="font-bold text-slate-700 mb-1">Cuentas internas:</p>
            <ul class="list-disc list-inside space-y-0.5 font-mono text-[10px]">
                <li><span class="font-bold">Super Admin:</span> admin@shopcms.com / admin123</li>
                <li><span class="font-bold">Ventas:</span> ventas@shopcms.com / ventas123</li>
            </ul>
        </div>
    </div>
@endsection
