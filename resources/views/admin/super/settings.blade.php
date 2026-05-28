@extends('layouts.admin')

@section('title', 'Configuración Global')
@section('admin_heading', 'Configuración global')
@section('admin_subheading', 'Ajustes generales que solo debería tocar el Super Admin')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'general', selectedAdminTheme: '{{ $groups['branding']['fields']['color_theme_admin']['value'] ?? 'slate_corporate' }}', selectedWebTheme: '{{ $groups['branding']['fields']['color_theme_web']['value'] ?? 'indigo_imperial' }}' }">
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

            <!-- Predefined Themes Selection -->
            <div class="grid gap-8 lg:grid-cols-2 mb-8 pb-8 border-b border-slate-100">
                <!-- Admin Theme Selector -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-wider text-slate-700">Tema del Panel Admin & Login Admin</h4>
                        <p class="mt-1 text-xs text-slate-400">Selecciona uno de los 6 temas profesionales preprogramados. Se aplicará tanto al panel de control como al login del administrador.</p>
                    </div>
                    
                    <input type="hidden" name="color_theme_admin" x-model="selectedAdminTheme">
                    
                    <div class="grid gap-3 grid-cols-2 sm:grid-cols-3">
                        @foreach(config('shop.color_themes_admin') as $key => $theme)
                            <button type="button" 
                                    @click="selectedAdminTheme = '{{ $key }}'"
                                    :class="selectedAdminTheme === '{{ $key }}' ? 'border-admin-primary ring-2 ring-admin-primary/20 bg-slate-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'"
                                    class="text-left p-3.5 rounded-2xl border transition-all duration-200 flex flex-col justify-between h-28 relative group">
                                <span class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug">{{ $theme['name'] }}</span>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-admin-sidebar'] }}" title="Sidebar"></span>
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-admin-primary'] }}" title="Primario"></span>
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-admin-container-bg'] }}" title="Contenedor"></span>
                                </div>
                                <span x-show="selectedAdminTheme === '{{ $key }}'" class="absolute top-2 right-2 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-admin-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-admin-primary"></span>
                                </span>
                            </button>
                        @endforeach
                        
                        <!-- Custom Option -->
                        <button type="button" 
                                @click="selectedAdminTheme = 'custom'"
                                :class="selectedAdminTheme === 'custom' ? 'border-admin-primary ring-2 ring-admin-primary/20 bg-slate-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'"
                                class="text-left p-3.5 rounded-2xl border transition-all duration-200 flex flex-col justify-between h-28 relative group">
                            <span class="text-xs font-bold text-slate-800 leading-snug">🎨 Personalizado</span>
                            <span class="text-[10px] text-slate-400 leading-tight">Configura tus propios colores de panel abajo.</span>
                            <span x-show="selectedAdminTheme === 'custom'" class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-admin-primary"></span>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Web Theme Selector -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-wider text-slate-700">Tema de la Web & Login Cliente</h4>
                        <p class="mt-1 text-xs text-slate-400">Selecciona uno de los 6 temas profesionales preprogramados. Se aplicará a la tienda pública y a la pantalla de login del cliente.</p>
                    </div>
                    
                    <input type="hidden" name="color_theme_web" x-model="selectedWebTheme">
                    
                    <div class="grid gap-3 grid-cols-2 sm:grid-cols-3">
                        @foreach(config('shop.color_themes_client') as $key => $theme)
                            <button type="button" 
                                    @click="selectedWebTheme = '{{ $key }}'"
                                    :class="selectedWebTheme === '{{ $key }}' ? 'border-admin-primary ring-2 ring-admin-primary/20 bg-slate-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'"
                                    class="text-left p-3.5 rounded-2xl border transition-all duration-200 flex flex-col justify-between h-28 relative group">
                                <span class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug">{{ $theme['name'] }}</span>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-client-primary'] }}" title="Primario"></span>
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-client-page'] }}" title="Fondo"></span>
                                    <span class="h-3.5 w-3.5 rounded-full border border-black/10" style="background-color: {{ $theme['colors']['--color-client-text'] }}" title="Texto"></span>
                                </div>
                                <span x-show="selectedWebTheme === '{{ $key }}'" class="absolute top-2 right-2 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-admin-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-admin-primary"></span>
                                </span>
                            </button>
                        @endforeach
                        
                        <!-- Custom Option -->
                        <button type="button" 
                                @click="selectedWebTheme = 'custom'"
                                :class="selectedWebTheme === 'custom' ? 'border-admin-primary ring-2 ring-admin-primary/20 bg-slate-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'"
                                class="text-left p-3.5 rounded-2xl border transition-all duration-200 flex flex-col justify-between h-28 relative group">
                            <span class="text-xs font-bold text-slate-800 leading-snug">🎨 Personalizado</span>
                            <span class="text-[10px] text-slate-400 leading-tight">Configura tus propios colores de tienda abajo.</span>
                            <span x-show="selectedWebTheme === 'custom'" class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-admin-primary"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Colors -->
            @php
                $clientColorFields = ['color_client_page', 'color_client_primary', 'color_client_surface', 'color_client_surface_alt', 'color_client_border', 'color_client_text', 'color_client_muted', 'color_client_header_footer_bg', 'color_client_card', 'color_client_card_border'];
                $adminColorFields = ['color_admin_sidebar', 'color_admin_primary', 'color_admin_container_bg', 'color_admin_page_bg'];

                $professionalPalettes = [
                    'client' => [
                        ['name' => 'Elegante', 'value' => '#4f46e5', 'swatches' => ['#0f172a', '#4f46e5', '#f8fafc', '#e2e8f0']],
                        ['name' => 'Fresco', 'value' => '#0ea5e9', 'swatches' => ['#0f172a', '#0ea5e9', '#ffffff', '#dbeafe']],
                        ['name' => 'Natural', 'value' => '#22c55e', 'swatches' => ['#14532d', '#22c55e', '#f8fafc', '#dcfce7']],
                        ['name' => 'Cálido', 'value' => '#f97316', 'swatches' => ['#7c2d12', '#f97316', '#fff7ed', '#fed7aa']],
                    ],
                    'admin' => [
                        ['name' => 'Corporativo', 'value' => '#4f46e5', 'swatches' => ['#202123', '#4f46e5', '#ffffff', '#e2e8f0']],
                        ['name' => 'Sólido', 'value' => '#2563eb', 'swatches' => ['#111827', '#2563eb', '#f8fafc', '#cbd5e1']],
                        ['name' => 'Nórdico', 'value' => '#0f766e', 'swatches' => ['#1e293b', '#0f766e', '#ffffff', '#f4f7f6']],
                        ['name' => 'Enérgico', 'value' => '#f97316', 'swatches' => ['#0f172a', '#f97316', '#ffffff', '#fed7aa']],
                    ],
                ];

                $colorSections = [
                    ['title' => 'Configuración de Cliente', 'description' => 'Ajustes visuales de la tienda pública y la experiencia del cliente.', 'fields' => $clientColorFields, 'palette' => $professionalPalettes['client']],
                    ['title' => 'Panel Administrativo', 'description' => 'Colores propios del panel interno y sus módulos.', 'fields' => $adminColorFields, 'palette' => $professionalPalettes['admin']],
                ];
            @endphp

            <div class="space-y-8">
                <!-- Preset Informative Banners -->
                <div x-show="selectedAdminTheme !== 'custom'" x-transition class="p-6 rounded-[24px] border border-dashed border-slate-200 bg-slate-50/50 flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-admin-primary/10 text-admin-primary grid place-items-center shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Colores del Panel Admin controlados por preset</p>
                        <p class="text-[11px] text-slate-500">Los colores individuales están preprogramados de acuerdo al tema seleccionado. Cambia el tema a <span class="font-bold text-slate-600">Personalizado</span> si deseas editar cada color individualmente.</p>
                    </div>
                </div>

                <div x-show="selectedWebTheme !== 'custom'" x-transition class="p-6 rounded-[24px] border border-dashed border-slate-200 bg-slate-50/50 flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-admin-primary/10 text-admin-primary grid place-items-center shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Colores de la Web controlados por preset</p>
                        <p class="text-[11px] text-slate-500">Los colores individuales de la tienda están preprogramados de acuerdo al tema seleccionado. Cambia el tema a <span class="font-bold text-slate-600">Personalizado</span> si deseas editar cada color individualmente.</p>
                    </div>
                </div>

                @foreach($colorSections as $section)
                    @php
                        $isClient = $section['title'] === 'Configuración de Cliente';
                        $showCond = $isClient ? "selectedWebTheme === 'custom'" : "selectedAdminTheme === 'custom'";
                    @endphp
                    <div x-show="{{ $showCond }}" x-transition class="space-y-4 rounded-[28px] border border-slate-100 bg-slate-50/40 p-5">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-wider text-slate-700">{{ $section['title'] }}</h4>
                                <p class="mt-1 text-xs text-slate-400">{{ $section['description'] }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400">
                                <span class="inline-flex h-2 w-2 rounded-full bg-slate-300"></span>
                                Paletas profesionales
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($section['fields'] as $name)
                                @php $field = $groups['branding']['fields'][$name]; @endphp
                                <div x-data="{ color: '{{ old($name, $field['value']) }}', open: false, manual: false }" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                    <div class="flex items-start gap-4">
                                        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl border border-slate-200 shadow-sm" :style="`background-color: ${color}`">
                                            <span class="sr-only">{{ $field['label'] }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1 space-y-2">
                                            <label class="block text-xs font-semibold text-slate-500">{{ $field['label'] }}</label>
                                            <input type="hidden" name="{{ $name }}" x-model="color">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-700" x-text="color"></span>
                                                <button type="button" @click="open = true" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-100">
                                                    Cambiar
                                                </button>
                                            </div>
                                            <p class="text-[11px] text-slate-400">{{ $field['description'] }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Combinaciones profesionales</p>
                                        <div class="grid gap-2 sm:grid-cols-2">
                                            @foreach($section['palette'] as $preset)
                                                <div class="rounded-2xl border border-slate-200 p-3 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-sm">
                                                    <p class="text-xs font-black text-slate-800">{{ $preset['name'] }}</p>
                                                    <div class="flex overflow-hidden rounded-full border border-slate-200">
                                                        @foreach($preset['swatches'] as $swatch)
                                                            <button type="button" @click="color = @js($swatch)" class="h-2 flex-1 transition hover:brightness-110 focus:outline-none" :class="color === @js($swatch) ? 'ring-2 ring-inset ring-admin-primary' : ''" :style="'background-color: ' + @js($swatch) + ';'" title="{{ $swatch }}"></button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 flex items-center justify-between gap-2">
                                                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Elige cualquiera de los 4 colores</span>
                                                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Profesional</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/55 p-4 md:items-center">
                                        <div @click.outside="open = false" class="w-full max-w-3xl rounded-[30px] bg-white p-5 shadow-2xl md:p-6">
                                            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                                <div>
                                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $section['title'] }}</p>
                                                    <h5 class="mt-1 text-lg font-black text-slate-900">{{ $field['label'] }}</h5>
                                                </div>
                                                <button type="button" @click="open = false" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-100">
                                                    Cerrar
                                                </button>
                                            </div>

                                            <div class="mt-4 grid gap-3 md:grid-cols-2">
                                                @foreach($section['palette'] as $preset)
                                                        <div class="rounded-3xl border border-slate-200 p-4 text-left transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-sm">
                                                            <p class="text-sm font-black text-slate-900">{{ $preset['name'] }}</p>
                                                            <p class="text-xs text-slate-400">Paleta profesional</p>
                                                            <div class="mt-3 grid grid-cols-4 gap-2">
                                                                @foreach($preset['swatches'] as $swatch)
                                                                    <button type="button" @click="color = @js($swatch)" class="h-10 rounded-2xl border border-slate-200 shadow-sm transition hover:scale-[1.02] focus:outline-none" :class="color === @js($swatch) ? 'ring-2 ring-offset-2 ring-admin-primary' : ''" :style="'background-color: ' + @js($swatch) + ';'" title="{{ $swatch }}"></button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                            </div>

                                            <div class="mt-5 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                                <div class="flex flex-wrap items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">Más opciones</p>
                                                        <p class="text-xs text-slate-500">Aparece el selector libre para elegir cualquier color.</p>
                                                    </div>
                                                    <button type="button" @click="manual = !manual" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-white">
                                                        Más
                                                    </button>
                                                </div>

                                                <div x-show="manual" x-cloak class="mt-4 grid gap-4 md:grid-cols-[auto_1fr]">
                                                    <input type="color" x-model="color" class="h-20 w-20 cursor-pointer rounded-2xl border border-slate-200 bg-white p-1">
                                                    <div class="space-y-3">
                                                        <input type="text" x-model="color" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold uppercase tracking-wider text-slate-700 focus:border-admin-primary focus:outline-none focus:ring-1 focus:ring-admin-primary">
                                                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3">
                                                            <div class="h-10 w-10 rounded-2xl border border-slate-200" :style="`background-color: ${color}`"></div>
                                                            <div>
                                                                <p class="text-xs font-bold text-slate-700">Vista previa libre</p>
                                                                <p class="text-[11px] text-slate-400">Usa el selector visual o escribe el valor hexadecimal.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                                <button type="button" @click="open = false" class="rounded-full border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100">
                                                    Aceptar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
