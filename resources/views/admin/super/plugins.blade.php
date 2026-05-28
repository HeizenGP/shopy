@extends('layouts.app')

@section('title', 'Plugins')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Plugins</h1>
        <p class="text-xs text-slate-400 mt-1">Área reservada para instalar o administrar extensiones del sistema</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <p class="text-sm font-semibold text-slate-700">Todavía no hay plugins instalados.</p>
        <p class="text-xs text-slate-400 mt-2">Esta sección queda separada del panel de ventas para futuras integraciones como pasarelas, analítica o notificaciones.</p>
    </div>
</div>
@endsection
