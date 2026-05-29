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
            <a class="brand" href="{{ route('home') }}">ShopCMS</a>
            <nav class="nav">
                <a @class(['is-active' => request()->routeIs('home')]) href="{{ route('home') }}">Inicio</a>
                <a @class(['is-active' => request()->routeIs('products.*')]) href="{{ route('products.index') }}">Productos</a>
                <a @class(['is-active' => request()->routeIs('categories.*')]) href="{{ route('products.index') }}#categorias">Categorias</a>
                <a href="{{ route('products.index') }}#ofertas">Ofertas</a>
                <a href="{{ route('home') }}#contacto">Contacto</a>
                <a class="cart-link" href="{{ route('products.index') }}">Carrito · 2</a>
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
