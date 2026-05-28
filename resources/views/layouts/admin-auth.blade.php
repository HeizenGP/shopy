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
            --color-admin-sidebar: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-sidebar', '#202123') }};
            --color-admin-primary: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-primary', '#4f46e5') }};
            --color-admin-accent: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-accent', '#ec4899') }};
            --color-client-page: {{ \App\Helpers\ShopHelper::getSetting('--color-client-page', '#f8fafc') }};
            --color-client-text: {{ \App\Helpers\ShopHelper::getSetting('--color-client-text', '#0f172a') }};
            --color-client-muted: {{ \App\Helpers\ShopHelper::getSetting('--color-client-muted', '#64748b') }};
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#eef1fa] text-client-text">
    <main class="relative min-h-screen overflow-hidden px-4 py-8 sm:px-6 lg:px-8">

        <div class="relative mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-5xl items-center justify-center">
            <section class="w-full max-w-md">
                @yield('content')
            </section>
        </div>
    </main>
</body>
</html>
