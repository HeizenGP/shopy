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

    <script>
        if (localStorage.getItem('admin_dark_mode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        :root {
            --color-admin-sidebar: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-sidebar', '#202123') }};
            --color-admin-primary: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-primary', '#4f46e5') }};
            --color-admin-container-bg: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-container-bg', '#ffffff') }};
            --color-admin-page-bg: {{ \App\Helpers\ShopHelper::getSetting('--color-admin-page-bg', '#f3f4f6') }};
            --color-admin-login-panel: var(--color-admin-container-bg);
            --color-admin-login-text: {{ in_array(\App\Helpers\ShopHelper::getSetting('--color-admin-container-bg', '#ffffff'), ['#202123', '#062f22', '#0b0f19', '#3b0712', '#0f172a', '#1e293b']) ? '#f1f5f9' : '#0f172a' }};
            --color-admin-login-muted: {{ in_array(\App\Helpers\ShopHelper::getSetting('--color-admin-container-bg', '#ffffff'), ['#202123', '#062f22', '#0b0f19', '#3b0712', '#0f172a', '#1e293b']) ? '#94a3b8' : '#64748b' }};
            --color-client-page: {{ \App\Helpers\ShopHelper::getSetting('--color-client-page', '#f8fafc') }};
            --color-client-text: {{ \App\Helpers\ShopHelper::getSetting('--color-client-text', '#0f172a') }};
            --color-client-muted: {{ \App\Helpers\ShopHelper::getSetting('--color-client-muted', '#64748b') }};
        }

        .dark {
            --color-admin-page-bg: #090d16;
            --color-admin-container-bg: #111827;
            --color-admin-login-panel: var(--color-admin-container-bg);
            --color-admin-login-text: #f8fafc;
            --color-admin-login-muted: #94a3b8;
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--color-admin-login-text);
        }

        .dark input {
            background-color: #1f2937 !important;
            color: #f8fafc !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .dark input::placeholder {
            color: #64748b !important;
        }
        
        .dark div.p-4.rounded-2xl.border {
            background-color: #1f2937 !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-admin-page-bg)] text-client-text" style="background-color: var(--color-admin-page-bg);">
    <main class="relative min-h-screen overflow-hidden px-4 py-8 sm:px-6 lg:px-8">

        <div class="relative mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-5xl items-center justify-center">
            <section class="w-full max-w-md">
                @yield('content')
            </section>
        </div>
    </main>
</body>
</html>
