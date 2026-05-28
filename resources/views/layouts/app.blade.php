<!DOCTYPE html>
<html lang="es" class="h-full bg-client-page text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('shop.name', 'ShopCMS')) — {{ \App\Helpers\ShopHelper::getSetting('shop_description', 'Tienda Virtual Premium') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\ShopHelper::getSetting('favicon_url', '/favicon.ico') }}">
    
    <!-- Instrument Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    <!-- AlpineJS for interactive actions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind & App Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-client-page: {{ \App\Helpers\ShopHelper::getSetting('--color-client-page', '#f8fafc') }};
            --color-client-primary: {{ \App\Helpers\ShopHelper::getSetting('--color-client-primary', '#4f46e5') }};
            --color-client-surface: {{ \App\Helpers\ShopHelper::getSetting('--color-client-surface', '#ffffff') }};
            --color-client-surface-alt: {{ \App\Helpers\ShopHelper::getSetting('--color-client-surface-alt', '#f1f5f9') }};
            --color-client-border: {{ \App\Helpers\ShopHelper::getSetting('--color-client-border', '#e2e8f0') }};
            --color-client-text: {{ \App\Helpers\ShopHelper::getSetting('--color-client-text', '#0f172a') }};
            --color-client-muted: {{ \App\Helpers\ShopHelper::getSetting('--color-client-muted', '#64748b') }};
            --color-client-header-footer-bg: {{ \App\Helpers\ShopHelper::getSetting('--color-client-header-footer-bg', '#ffffff') }};
            --color-client-card: {{ \App\Helpers\ShopHelper::getSetting('--color-client-card', '#ffffff') }};
            --color-client-card-border: {{ \App\Helpers\ShopHelper::getSetting('--color-client-card-border', '#e2e8f0') }};
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-client-page text-client-text">
    
    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-client-border" style="background-color: var(--color-client-header-footer-bg);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-95 transition-all">
                        @if(\App\Helpers\ShopHelper::getSetting('logo_url'))
                            <img src="{{ \App\Helpers\ShopHelper::getSetting('logo_url') }}" alt="{{ config('shop.name', 'ShopCMS') }}" class="h-8 w-auto object-contain" style="max-width: 150px;">
                        @else
                            <span class="text-xl font-bold tracking-tight text-client-text">
                                {{ config('shop.name', 'ShopCMS') }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8 text-sm font-medium">
                    <a href="{{ route('catalog.index') }}" class="text-client-muted hover:text-client-text transition-colors">Catálogo</a>
                    @auth
                        @if(Auth::user()->hasRole('super_admin'))
                            <a href="{{ route('admin.home') }}" class="text-client-primary hover:text-client-primary/80 transition-colors font-semibold">Panel Admin</a>
                        @endif
                        <a href="{{ route('orders.history') }}" class="text-client-muted hover:text-client-text transition-colors">Mis Pedidos</a>
                    @endauth
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Indicator -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-client-muted hover:text-client-text transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        @php
                            $cartRepo = app(\App\Modules\Cart\Domain\Repositories\CartRepositoryInterface::class);
                            $cart = $cartRepo->findBySessionOrUser(Auth::id(), session()->getId());
                            $cartCount = $cart ? array_sum(array_column($cart->items, 'quantity')) : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-client-primary rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login -->
                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="hidden sm:inline-block text-xs font-semibold text-client-text bg-client-surface-alt py-1 px-3.5 rounded-full">
                                {{ Auth::user()->name }}
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-client-muted hover:text-red-600 transition-colors">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-client-muted hover:text-client-text transition-colors">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-semibold text-white bg-client-primary hover:bg-client-primary/90 rounded-lg transition-all">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Success & Error Alert Banners -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50" role="alert">
                <div class="flex items-center mb-1">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                    </svg>
                    <span class="font-bold">Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-xs pl-6 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-client-border mt-16" style="background-color: var(--color-client-header-footer-bg);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} {{ config('shop.name', 'ShopCMS') }}. Todos los derechos reservados.</p>
            <p class="mt-1 font-mono text-[10px] text-slate-300">Construido con Arquitectura Hexagonal + Vertical Slicing & Laravel 13</p>
        </div>
    </footer>
    
</body>
</html>
