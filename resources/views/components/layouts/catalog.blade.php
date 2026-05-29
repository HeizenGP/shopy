<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ request('theme', 'default') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Shopy') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body @class(['catalog-admin' => request()->routeIs('admin.catalog.*')])>
        <header class="site-header">
            <a class="brand" href="{{ route('home') }}">Shopy</a>
            <nav class="nav">
                <a @class(['is-active' => request()->routeIs('products.*') || request()->routeIs('categories.*')]) href="{{ route('products.index') }}">Productos</a>
                <a @class(['is-active' => request()->routeIs('admin.catalog.*')]) href="{{ route('admin.catalog.products.index') }}">Admin</a>
            </nav>
        </header>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error">
                {{ $errors->first() }}
            </div>
        @endif

        <main>
            {{ $slot }}
        </main>
    </body>
</html>
