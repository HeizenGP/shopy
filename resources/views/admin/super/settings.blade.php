@extends('layouts.admin')

@section('title', 'Configuración Global')
@section('admin_heading', 'Configuración global')
@section('admin_subheading', 'Ajustes generales que solo debería tocar el Super Admin')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="rounded-[30px] bg-white p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Colores del frontend cliente</p>
                    <p class="text-xs text-slate-400 mt-2">Actualiza la paleta que ven tus clientes en el sitio público.</p>
                </div>
                <button type="submit" class="rounded-full bg-slate-950 px-6 py-2 text-xs font-bold text-white hover:bg-slate-800">
                    Guardar cambios
                </button>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                @foreach($fields as $name => $field)
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                        <div class="flex items-center gap-4">
                            <input type="color"
                                   name="{{ $name }}"
                                   value="{{ old($name, $field['value']) }}"
                                   class="h-11 w-16 rounded-xl border border-slate-200 bg-white p-1">
                            <span class="text-xs font-mono text-slate-500">{{ old($name, $field['value']) }}</span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $field['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</div>
@endsection
