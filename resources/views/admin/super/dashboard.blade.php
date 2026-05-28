@extends('layouts.app')

@section('title', 'Super Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Super Admin</h1>
            <p class="text-xs text-slate-400 mt-1">Vista global de usuarios, ventas, inventario y configuración del sistema</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Usuarios</p>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ $stats['users'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Pedidos</p>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ $stats['orders'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Ingresos completados</p>
            <p class="text-3xl font-black text-slate-900 mt-2">${{ number_format($stats['revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Productos</p>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ $stats['products'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Stock bajo</p>
            <p class="text-3xl font-black text-amber-600 mt-2">{{ $stats['lowStock'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400">Alertas activas</p>
            <p class="text-3xl font-black text-red-600 mt-2">{{ $stats['alerts'] }}</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:border-indigo-200 transition-colors">
            <p class="font-bold text-slate-900">Usuarios y roles</p>
            <p class="text-xs text-slate-400 mt-1">Administra accesos internos y clientes.</p>
        </a>
        <a href="{{ route('admin.reports') }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:border-indigo-200 transition-colors">
            <p class="font-bold text-slate-900">Reportes globales</p>
            <p class="text-xs text-slate-400 mt-1">Revisa ventas y estados generales.</p>
        </a>
        <a href="{{ route('admin.plugins') }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:border-indigo-200 transition-colors">
            <p class="font-bold text-slate-900">Plugins</p>
            <p class="text-xs text-slate-400 mt-1">Espacio reservado para extensiones.</p>
        </a>
        <a href="{{ route('admin.settings') }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:border-indigo-200 transition-colors">
            <p class="font-bold text-slate-900">Configuración</p>
            <p class="text-xs text-slate-400 mt-1">Parámetros globales del sistema.</p>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-900">Pedidos recientes</h2>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Pedido</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="px-6 py-4 font-bold text-slate-900">#000{{ $order->id }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->billing_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ ucfirst($order->status) }}</td>
                        <td class="px-6 py-4 text-right font-bold">${{ number_format($order->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Aún no hay pedidos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
