<!DOCTYPE html>
<html lang="es" class="h-full bg-[#f3f3f3] text-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ config('shop.name', 'ShopCMS') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\ShopHelper::getSetting('favicon_url', '/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- AlpineJS for interactive actions (tabs, forms) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-admin-sidebar: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-sidebar', '#202123') }};
            --color-admin-primary: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-primary', '#4f46e5') }};
            --color-admin-accent: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-accent', '#ec4899') }};
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }

        .bg-admin-sidebar {
            background-color: var(--color-admin-sidebar) !important;
        }

        .bg-admin-primary {
            background-color: var(--color-admin-primary) !important;
        }

        .text-admin-primary {
            color: var(--color-admin-primary) !important;
        }

        .border-admin-primary {
            border-color: var(--color-admin-primary) !important;
        }

        [x-cloak] {
            display: none !important;
        }
        
        .hover\:bg-admin-primary-dark:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f3f3f3]">
    @php
        $adminUser = Auth::user();
        $adminLinks = [
            ['label' => 'Panel', 'route' => 'admin.dashboard', 'icon' => 'grid', 'visible' => $adminUser?->hasPermission('admin.dashboard.view')],
            ['label' => 'Pedidos', 'route' => 'admin.orders.index', 'icon' => 'orders', 'visible' => $adminUser?->hasPermission('orders.manage')],
            ['label' => 'Inventario', 'route' => 'admin.inventory.index', 'icon' => 'inventory', 'visible' => $adminUser?->hasPermission('inventory.manage')],
            ['label' => 'Usuarios', 'route' => 'admin.users.index', 'icon' => 'users', 'visible' => $adminUser?->hasPermission('users.manage'), 'submenu' => [
                ['label' => 'Usuarios administrativos', 'route' => 'admin.users.admin.index'],
                ['label' => 'Usuarios de la web', 'route' => 'admin.users.web.index'],
            ]],
            ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'settings', 'visible' => $adminUser?->hasPermission('roles.manage')],
            ['label' => 'Reportes', 'route' => 'admin.reports', 'icon' => 'reports', 'visible' => $adminUser?->hasPermission('reports.view')],
            ['label' => 'Complementos', 'route' => 'admin.plugins', 'icon' => 'plugins', 'visible' => $adminUser?->hasPermission('plugins.manage')],
            ['label' => 'Configuración', 'route' => 'admin.settings', 'icon' => 'settings', 'visible' => $adminUser?->hasPermission('settings.manage')],
        ];
    @endphp

    <div class="min-h-screen lg:flex" x-data="{ sidebarCollapsed: false }">
        <aside class="sticky top-0 hidden h-screen shrink-0 flex-col overflow-y-auto bg-admin-sidebar text-white transition-all duration-300 lg:flex" :style="sidebarCollapsed ? 'width: 5.75rem' : 'width: 16rem'">
            <div class="px-4 py-8">
                <a href="{{ route('admin.home') }}" class="flex items-center gap-2 text-lg font-black tracking-tight hover:opacity-90 transition-opacity" :class="sidebarCollapsed ? 'justify-center' : ''">
                    @if(\App\Helpers\ShopHelper::getSetting('logo_url'))
                        <img src="{{ \App\Helpers\ShopHelper::getSetting('logo_url') }}" alt="Logo" class="h-8 w-auto shrink-0 object-contain rounded-lg" style="max-width: 80px;" x-show="!sidebarCollapsed" x-cloak>
                    @endif
                    <span class="truncate" x-show="!sidebarCollapsed" x-cloak>{{ config('shop.name', 'ShopCMS') }}</span>
                </a>
            </div>

            <nav class="flex-1 px-3 py-2">
                <div class="space-y-1" x-data="{ usersOpen: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                    @foreach($adminLinks as $link)
                        @if($link['visible'])
                            @php $active = request()->routeIs($link['route']) || (!empty($link['submenu']) && request()->routeIs('admin.users.*')); @endphp
                            @if(!empty($link['submenu']))
                                <button type="button"
                                        @click="usersOpen = !usersOpen"
                                        class="flex w-full items-center justify-between gap-3 rounded-[18px] px-4 py-3 text-sm font-bold transition {{ $active ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/5 hover:text-white' }}" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                                    <span class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                                        <span class="grid h-5 w-5 place-items-center">
                                            @if($link['icon'] === 'users')
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm8.5 1a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7ZM2 20a6 6 0 0 1 12 0v1H2v-1Zm12.5 1v-1a7.5 7.5 0 0 0-1.5-4.5A5 5 0 0 1 22 18.5V21h-7.5Z"/></svg>
                                            @endif
                                        </span>
                                        <span x-show="!sidebarCollapsed" x-cloak>{{ $link['label'] }}</span>
                                    </span>
                                    <svg x-show="!sidebarCollapsed" x-cloak class="h-4 w-4 transition-transform" :class="usersOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                                </button>
                                <div x-show="usersOpen && !sidebarCollapsed" x-cloak class="mt-2 space-y-1 pl-4">
                                    @foreach($link['submenu'] as $submenu)
                                        @php $submenuActive = request()->routeIs($submenu['route']); @endphp
                                                     <a href="{{ route($submenu['route']) }}"
                                                         class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $submenuActive ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/5 hover:text-white' }}">
                                            <span class="h-2 w-2 rounded-full bg-current opacity-70"></span>
                                            {{ $submenu['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                            <a href="{{ route($link['route']) }}"
                                         class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-sm font-bold transition {{ $active ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/5 hover:text-white' }}" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                                <span class="grid h-5 w-5 place-items-center">
                                    @if($link['icon'] === 'grid')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/></svg>
                                    @elseif($link['icon'] === 'orders')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 3h10a2 2 0 0 1 2 2v14l-3-2-2 2-2-2-2 2-2-2-3 2V5a2 2 0 0 1 2-2Zm2 5h6V6H9v2Zm0 4h8v-2H9v2Zm0 4h5v-2H9v2Z"/></svg>
                                    @elseif($link['icon'] === 'inventory')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 9 5v10l-9 5-9-5V7l9-5Zm0 2.3L6.2 7.5 12 10.7l5.8-3.2L12 4.3ZM5 9.2v6.6l6 3.3v-6.6L5 9.2Zm14 0-6 3.3v6.6l6-3.3V9.2Z"/></svg>
                                    @elseif($link['icon'] === 'users')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm8.5 1a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7ZM2 20a6 6 0 0 1 12 0v1H2v-1Zm12.5 1v-1a7.5 7.5 0 0 0-1.5-4.5A5 5 0 0 1 22 18.5V21h-7.5Z"/></svg>
                                    @elseif($link['icon'] === 'reports')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm3 14h2V9H8v8Zm4 0h2V6h-2v11Zm4 0h2v-5h-2v5Z"/></svg>
                                    @elseif($link['icon'] === 'plugins')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h4v6h2V2h4v6h2v4a7 7 0 0 1-6 6.93V22h-2v-3.07A7 7 0 0 1 5 12V8h2V2Z"/></svg>
                                    @elseif($link['icon'] === 'settings')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.4 13.5a7.8 7.8 0 0 0 .1-1.5 7.8 7.8 0 0 0-.1-1.5l2.1-1.6-2-3.5-2.5 1a8 8 0 0 0-2.6-1.5L14 2h-4l-.4 2.9A8 8 0 0 0 7 6.4l-2.5-1-2 3.5 2.1 1.6a7.8 7.8 0 0 0-.1 1.5 7.8 7.8 0 0 0 .1 1.5l-2.1 1.6 2 3.5 2.5-1a8 8 0 0 0 2.6 1.5L10 22h4l.4-2.9a8 8 0 0 0 2.6-1.5l2.5 1 2-3.5-2.1-1.6ZM12 15.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z"/></svg>
                                    @else
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.4 13.5a7.8 7.8 0 0 0 .1-1.5 7.8 7.8 0 0 0-.1-1.5l2.1-1.6-2-3.5-2.5 1a8 8 0 0 0-2.6-1.5L14 2h-4l-.4 2.9A8 8 0 0 0 7 6.4l-2.5-1-2 3.5 2.1 1.6a7.8 7.8 0 0 0-.1 1.5 7.8 7.8 0 0 0 .1 1.5l-2.1 1.6 2 3.5 2.5-1a8 8 0 0 0 2.6 1.5L10 22h4l.4-2.9a8 8 0 0 0 2.6-1.5l2.5 1 2-3.5-2.1-1.6ZM12 15.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z"/></svg>
                                    @endif
                                </span>
                                <span x-show="!sidebarCollapsed" x-cloak>{{ $link['label'] }}</span>
                            </a>
                            @endif
                        @endif
                    @endforeach
                </div>
            </nav>

            <div class="relative px-4 py-8 text-sm text-zinc-500">
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ route('admin.login') }}">
                    <button type="submit" class="flex items-center gap-3 text-zinc-400 hover:text-white" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3h9a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-9v-2h9V5h-9V3Zm1.7 5.3L15.4 12l-3.7 3.7-1.4-1.4 1.3-1.3H3v-2h8.6l-1.3-1.3 1.4-1.4Z"/></svg>
                        <span x-show="!sidebarCollapsed" x-cloak>Salir</span>
                    </button>
                </form>
                <button type="button"
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        class="absolute bottom-4 right-4 grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white shadow-lg backdrop-blur transition hover:bg-white/20"
                        :title="sidebarCollapsed ? 'Expandir menú' : 'Colapsar menú'">
                    <svg x-show="!sidebarCollapsed" x-cloak class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.24a.75.75 0 0 1 0 1.08l-4.5 4.24a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd"/></svg>
                    <svg x-show="sidebarCollapsed" x-cloak class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.832 10l3.938 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.24a.75.75 0 0 1 0-1.08l4.5-4.24a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 border-b border-black/5 bg-white">
                <div class="flex min-h-24 items-center justify-between gap-4 px-5 py-5 lg:px-14">
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-950">@yield('admin_heading', 'Panel administrativo')</h1>
                        <p class="mt-1 text-sm text-slate-500">@yield('admin_subheading', 'Gestiona la operación y configuración de ShopCMS')</p>
                    </div>

                    <div class="hidden items-center gap-5 md:flex">
                        <div class="flex h-11 w-48 items-center gap-3 rounded-full bg-[#f1f1f1] px-5 text-sm text-slate-400">
                            <svg class="h-5 w-5 text-slate-950" viewBox="0 0 24 24" fill="currentColor"><path d="m20.7 19.3-4.2-4.2a7 7 0 1 0-1.4 1.4l4.2 4.2 1.4-1.4ZM5 11a6 6 0 1 1 12 0 6 6 0 0 1-12 0Z"/></svg>
                            <span>Buscar</span>
                        </div>
                        <div class="relative">
                            <svg class="h-6 w-6 text-slate-700" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-6v-5a7 7 0 0 0-5-6.7V3a2 2 0 1 0-4 0v1.3A7 7 0 0 0 5 11v5l-2 2v1h18v-1l-2-2Z"/></svg>
                            <span class="absolute -right-2 -top-2 grid h-5 w-5 place-items-center rounded-full bg-slate-950 text-[10px] font-black text-white">4</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-full bg-slate-200 text-sm font-black text-slate-700">
                                {{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="text-sm font-bold text-slate-950">{{ $adminUser?->name }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-5 py-8 lg:px-14">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-sm font-bold text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
