@extends('layouts.app')

@section('title', 'Reportes Globales')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reportes globales</h1>
        <p class="text-xs text-slate-400 mt-1">Resumen ejecutivo de ventas, pedidos y catálogo</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($stats as $label => $value)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-bold uppercase text-slate-400">{{ str_replace(['Orders', 'Revenue', 'Products'], [' pedidos', ' ingresos', ' productos'], ucfirst($label)) }}</p>
                <p class="text-3xl font-black text-slate-900 mt-2">
                    {{ str_contains($label, 'Revenue') ? '$'.number_format($value, 2) : $value }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
