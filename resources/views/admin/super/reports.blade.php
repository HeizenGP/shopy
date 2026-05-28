@extends('layouts.admin')

@section('title', 'Reportes Globales')
@section('admin_heading', 'Reportes globales')
@section('admin_subheading', 'Resumen ejecutivo de ventas, pedidos y catálogo')

@section('content')
<div class="space-y-6">
    @php
        $labels = [
            'pendingOrders' => 'Pedidos pendientes',
            'processingOrders' => 'Pedidos en proceso',
            'completedOrders' => 'Pedidos completados',
            'cancelledOrders' => 'Pedidos cancelados',
            'totalRevenue' => 'Ingresos completados',
            'activeProducts' => 'Productos activos',
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($stats as $label => $value)
            <div class="rounded-[26px] bg-white p-6">
                <p class="text-xs font-bold uppercase text-slate-400">{{ $labels[$label] ?? $label }}</p>
                <p class="text-3xl font-black text-slate-900 mt-2">
                    {{ str_contains($label, 'Revenue') ? '$'.number_format($value, 2) : $value }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
