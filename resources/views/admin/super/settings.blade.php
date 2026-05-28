@extends('layouts.admin')

@section('title', 'Configuración Global')
@section('admin_heading', 'Configuración global')
@section('admin_subheading', 'Ajustes generales que solo debería tocar el Super Admin')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'general' }">
    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-px">
        <button @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'border-admin-primary text-admin-primary' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            General
        </button>

        <button @click="activeTab = 'branding'" 
                :class="activeTab === 'branding' ? 'border-admin-primary text-admin-primary' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
            </svg>
            Diseño & Colores
        </button>

        <button @click="activeTab = 'store'" 
                :class="activeTab === 'store' ? 'border-admin-primary text-admin-primary' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Moneda & Región
        </button>

        <button @click="activeTab = 'features'" 
                :class="activeTab === 'features' ? 'border-admin-primary text-admin-primary' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Funcionalidades
        </button>

        <button @click="activeTab = 'backup'" 
                :class="activeTab === 'backup' ? 'border-admin-primary text-admin-primary' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            Respaldo / Backup
        </button>
    </div>

    <!-- Main settings form (covers general, branding, store, features tabs) -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Tab -->
        <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Información General de la Tienda</h3>
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($groups['general']['fields'] as $name => $field)
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                        @if($field['type'] === 'textarea')
                            <textarea name="{{ $name }}" rows="4" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">{{ old($name, $field['value']) }}</textarea>
                        @else
                            <input type="{{ $field['type'] }}" name="{{ $name }}" value="{{ old($name, $field['value']) }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">
                        @endif
                        <p class="text-xs text-slate-400">{{ $field['description'] }}</p>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-full bg-admin-primary px-8 py-3 text-xs font-bold text-white hover:bg-admin-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Guardar Cambios
                </button>
            </div>
        </div>

        <!-- Design & Branding Tab -->
        <div x-show="activeTab === 'branding'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Identidad Visual & Branding</h3>
            
            <!-- Logo, favicon & banner paths -->
            <div class="grid gap-6 md:grid-cols-3 mb-8 pb-8 border-b border-slate-100">
                @foreach(['logo_url', 'favicon_url', 'banner_url'] as $key)
                    @php $field = $groups['branding']['fields'][$key]; @endphp
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                        <input type="text" name="{{ $key }}" value="{{ old($key, $field['value']) }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">
                        <p class="text-xs text-slate-400">{{ $field['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Colors -->
            <h4 class="text-sm font-bold text-slate-700 mb-4">Paleta de Colores</h4>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($groups['branding']['fields'] as $name => $field)
                    @if($field['type'] === 'color')
                        <div class="space-y-2 rounded-2xl border border-slate-100 p-4 bg-slate-50/50" x-data="{ color: '{{ old($name, $field['value']) }}' }">
                            <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                            <div class="flex items-center gap-4">
                                <input type="color"
                                       name="{{ $name }}"
                                       x-model="color"
                                       class="h-11 w-16 cursor-pointer rounded-xl border border-slate-200 bg-white p-1">
                                <input type="text" 
                                       x-model="color"
                                       class="w-24 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-mono text-slate-700 uppercase focus:border-admin-primary focus:outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">{{ $field['description'] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-full bg-admin-primary px-8 py-3 text-xs font-bold text-white hover:bg-admin-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Guardar Cambios
                </button>
            </div>
        </div>

        <!-- Store Tab (Region & Money) -->
        <div x-show="activeTab === 'store'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Moneda, Impuestos & Región</h3>
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($groups['store']['fields'] as $name => $field)
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" 
                               name="{{ $name }}" 
                               value="{{ old($name, $field['value']) }}"
                               @if($field['type'] === 'number') step="any" @endif
                               class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">
                        <p class="text-xs text-slate-400">{{ $field['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-full bg-admin-primary px-8 py-3 text-xs font-bold text-white hover:bg-admin-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Guardar Cambios
                </button>
            </div>
        </div>

        <!-- Features Tab -->
        <div x-show="activeTab === 'features'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Funcionalidades de la Plataforma</h3>
            
            <div class="grid gap-8 md:grid-cols-2">
                @foreach($groups['features']['fields'] as $name => $field)
                    @if($field['type'] === 'boolean')
                        <div class="flex items-start gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                            <div class="flex items-center h-5">
                                <input type="hidden" name="{{ $name }}" value="0">
                                <input type="checkbox" 
                                       name="{{ $name }}" 
                                       value="1" 
                                       id="checkbox-{{ $name }}"
                                       @checked(old($name, $field['value']) == '1')
                                       class="h-4.5 w-4.5 rounded border-slate-300 text-admin-primary focus:ring-admin-primary">
                            </div>
                            <div class="space-y-1">
                                <label for="checkbox-{{ $name }}" class="text-xs font-semibold text-slate-700 cursor-pointer select-none">{{ $field['label'] }}</label>
                                <p class="text-[11px] text-slate-400 leading-normal">{{ $field['description'] }}</p>
                            </div>
                        </div>
                    @else
                        <!-- Text/Message settings related to feature flags -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                            <input type="{{ $field['type'] }}" name="{{ $name }}" value="{{ old($name, $field['value']) }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">
                            <p class="text-xs text-slate-400">{{ $field['description'] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-full bg-admin-primary px-8 py-3 text-xs font-bold text-white hover:bg-admin-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>

    <!-- Backup & Restore Tab -->
    <div x-show="activeTab === 'backup'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <!-- Export Settings Card -->
        <div class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Exportar Configuración</h3>
            <p class="text-xs text-slate-400 mb-6 leading-relaxed">Descarga una copia de seguridad local de todos tus ajustes en formato JSON. Podrás usar este archivo en cualquier momento para restaurar la tienda a este estado.</p>
            
            <a href="{{ route('admin.settings.export') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-8 py-3.5 text-xs font-bold text-white hover:bg-slate-800 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Descargar Respaldo (.json)
            </a>
        </div>

        <!-- Import Settings Card -->
        <div class="rounded-[30px] bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Importar Configuración</h3>
            <p class="text-xs text-slate-400 mb-6 leading-relaxed">Sube un archivo de copia de seguridad JSON previamente exportado. Esto sobrescribirá todos los valores actuales en la base de datos de manera inmediata.</p>
            
            <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-md">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-500">Seleccionar archivo JSON</label>
                    <input type="file" name="settings_file" accept=".json" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                </div>
                
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-8 py-3.5 text-xs font-bold text-white hover:bg-slate-800 transition-all" onclick="return confirm('¿Estás seguro de que deseas importar este archivo? Los ajustes actuales se sobrescribirán.')">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Restaurar Configuración
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
