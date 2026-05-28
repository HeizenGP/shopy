<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('shop.name', 'ShopCMS'))</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\ShopHelper::getSetting('favicon_url', '/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen text-client-text bg-[var(--color-client-page)]" style="background-color: var(--color-client-page);">
    <main class="relative min-h-screen overflow-hidden px-4 py-8 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 opacity-40">
            <div class="absolute -left-20 top-16 h-72 w-72 rounded-full bg-client-primary/5 blur-3xl"></div>
            <div class="absolute right-0 top-40 h-96 w-96 rounded-full bg-client-primary/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-white/30 blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-6xl items-center justify-center">
            <div class="grid w-full gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <section class="mx-auto w-full max-w-xl text-center lg:text-left">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-4 rounded-full border border-white/70 bg-white/80 px-5 py-3 shadow-sm backdrop-blur-md">
                        @if(\App\Helpers\ShopHelper::getSetting('logo_url'))
                            <img src="{{ \App\Helpers\ShopHelper::getSetting('logo_url') }}" alt="{{ config('shop.name', 'ShopCMS') }}" class="h-14 w-14 rounded-2xl object-contain shadow-sm sm:h-16 sm:w-16">
                        @else
                            <span class="grid h-14 w-14 place-items-center rounded-2xl bg-client-primary text-xl font-black text-white shadow-sm sm:h-16 sm:w-16">{{ strtoupper(substr(config('shop.name', 'ShopCMS'), 0, 1)) }}</span>
                        @endif
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.3em] text-client-muted">Acceso seguro</p>
                            <h1 class="mt-1 text-2xl font-black tracking-tight text-client-text sm:text-3xl">{{ config('shop.name', 'ShopCMS') }}</h1>
                        </div>
                    </a>

                    <div class="mt-8 hidden max-w-lg rounded-4xl border border-white/70 bg-white/70 p-8 shadow-xl backdrop-blur-md lg:block">
                        <p class="text-xs font-bold uppercase tracking-[0.32em] text-client-primary">Bienvenido</p>
                        <h2 class="mt-4 text-4xl font-black tracking-tight text-slate-950">
                            Inicia sesión o crea tu cuenta con una experiencia limpia y elegante.
                        </h2>
                        <p class="mt-4 text-sm leading-7 text-client-muted">
                            Diseñado para que el logo y el nombre de tu tienda sean protagonistas, sin mostrar navegación ni contenido de la tienda pública.
                        </p>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-md">
                    @yield('content')
                </section>
            </div>
        </div>
    </main>
</body>
</html>
