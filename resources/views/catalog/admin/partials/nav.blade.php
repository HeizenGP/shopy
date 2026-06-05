@php
    $catalogOpen = request()->routeIs('admin.catalog.*');
    $accessOpen = request()->routeIs('admin.access.*') || request()->routeIs('admin.dashboard');
@endphp

<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">S</div>
        <span class="sidebar-logo-text">ShopCMS</span>
        <span class="sidebar-logo-badge">v2.0</span>
    </a>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.dashboard')])>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h3.75c.621 0 1.125.504 1.125 1.125v6.75C9 20.496 8.496 21 7.875 21h-3.75A1.125 1.125 0 013 19.875v-6.75zM9.75 4.125C9.75 3.504 10.254 3 10.875 3h3.75c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125V4.125zM16.5 8.625c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125V8.625z" />
            </svg>
            Dashboard
        </a>

        <details class="sidebar-catalog-group" {{ $accessOpen ? 'open' : '' }}>
            <summary class="sidebar-section-title sidebar-catalog-summary">
                <span class="sidebar-catalog-label">
                    <svg class="sidebar-catalog-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1116.5 0v.75H4.5v-.75z" />
                    </svg>
                    Access
                </span>
                <svg class="sidebar-catalog-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>

            <div class="sidebar-submenu-list">
                <a href="{{ route('admin.access.users.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.access.users.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 7.5a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                    </svg>
                    Usuarios
                </a>

                <a href="{{ route('admin.access.roles.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.access.roles.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Roles
                </a>

                <a href="{{ route('admin.access.permissions.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.access.permissions.*')])>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Permisos
                </a>
            </div>
        </details>

        <details class="sidebar-catalog-group" {{ $catalogOpen ? 'open' : '' }}>
            <summary class="sidebar-section-title sidebar-catalog-summary">
                <span class="sidebar-catalog-label">
                    <svg class="sidebar-catalog-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25l-9-5.25-9 5.25m18 0l-9 5.25m9-5.25v7.5l-9 5.25m0-7.5l-9-5.25m9 5.25v7.5m-9-12.75v7.5l9 5.25" />
                    </svg>
                    Catálogo
                </span>
                <svg class="sidebar-catalog-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
        <div><strong>Semana 2 - Access</strong></div>
        <div style="font-size: 0.65rem; margin-top: 0.25rem; opacity: 0.8;">Admin Panel v2.0</div>
    </div>
</aside>
