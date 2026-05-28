@extends('layouts.admin')

@section('title', 'Plugins')
@section('admin_heading', 'Plugins')
@section('admin_subheading', 'Área reservada para instalar o administrar extensiones del sistema')

@section('content')
<div class="space-y-6">
    <div class="rounded-[30px] bg-white p-8">
        <p class="text-sm font-semibold text-slate-700">Todavía no hay plugins instalados.</p>
        <p class="text-xs text-slate-400 mt-2">Esta sección queda separada del panel de ventas para futuras integraciones como pasarelas, analítica o notificaciones.</p>
    </div>
</div>
@endsection
