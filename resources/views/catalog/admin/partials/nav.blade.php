@php
    $catalogOpen = request()->routeIs('admin.catalog.*');
@endphp

<aside class="admin-sidebar">
    <a href="{{ route('admin.catalog.products.index') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">S</div>
        <span class="sidebar-logo-text">ShopCMS</span>
        <span class="sidebar-logo-badge">v1.0</span>
    </a>

    <nav class="sidebar-menu">
        <details class="sidebar-catalog-group" {{ $catalogOpen ? 'open' : '' }}>
            <summary class="sidebar-section-title sidebar-catalog-summary">
                Catálogo
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>

            <div class="sidebar-submenu-list">
                <a href="{{ route('admin.catalog.products.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.catalog.products.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25l-9-5.25-9 5.25m18 0l-9 5.25m9-5.25v7.5l-9 5.25m0-7.5l-9-5.25m9 5.25v7.5m-9-12.75v7.5l9 5.25" />
                    </svg>
                    Productos
                </a>

                <a href="{{ route('admin.catalog.categories.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.catalog.categories.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Categorías
                </a>

                <a href="{{ route('admin.catalog.brands.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.catalog.brands.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Marcas
                </a>

                <a href="{{ route('admin.catalog.variants.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.catalog.variants.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    Variantes
                </a>
            </div>
        </details>
    </nav>

    <div class="sidebar-footer">
        <div><strong>Semana 1 - Catalog</strong></div>
        <div style="font-size: 0.65rem; margin-top: 0.25rem; opacity: 0.8;">Admin Panel v1.0</div>
    </div>
</aside>
