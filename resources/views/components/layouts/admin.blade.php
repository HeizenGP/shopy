<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-theme-color="indigo">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin Panel' }} · Shopy</title>
        
        <!-- Theme Initialization Script to prevent FOUC -->
        <script>
            (function() {
                const mode = localStorage.getItem('admin-theme-mode') || 
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                const color = localStorage.getItem('admin-theme-color') || 'indigo';
                document.documentElement.setAttribute('data-theme-mode', mode);
                document.documentElement.setAttribute('data-theme-color', color);
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-shell-layout">
            
            <!-- Sidebar Navigation -->
            @include('catalog.admin.partials.nav')

            <!-- Main Content Container -->
            <div class="admin-main-container">
                
                <!-- Topbar Header -->
                <header class="admin-topbar-header">
                    <div class="topbar-left">
                        <form class="topbar-search-form" onsubmit="event.preventDefault();">
                            <svg class="topbar-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input class="topbar-search-input" type="search" placeholder="Buscar productos, marcas, categorías...">
                        </form>
                    </div>

                    <div class="topbar-right">
                        <!-- Theme & Color Customizer -->
                        <div class="theme-picker-container">
                            <button id="themePickerBtn" class="theme-picker-btn" title="Personalizar diseño">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>

                            <!-- Customizer Dropdown Panel -->
                            <div id="themeDropdown" class="theme-dropdown-panel">
                                <h4 class="theme-dropdown-title">Personalización</h4>
                                
                                <!-- Mode selection -->
                                <div class="theme-mode-section">
                                    <span class="theme-label-sm">Modo de pantalla</span>
                                    <div class="mode-selector-grid">
                                        <button type="button" class="mode-btn" data-mode-target="light">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Claro
                                        </button>
                                        <button type="button" class="mode-btn" data-mode-target="dark">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                            Oscuro
                                        </button>
                                    </div>
                                </div>

                                <!-- Theme colors selection -->
                                <div class="theme-color-section">
                                    <span class="theme-label-sm">Color de acento (6 opciones)</span>
                                    <div class="color-selector-grid">
                                        <button type="button" class="color-dot-btn indigo-dot" data-color-target="indigo" title="Indigo"></button>
                                        <button type="button" class="color-dot-btn emerald-dot" data-color-target="emerald" title="Emerald"></button>
                                        <button type="button" class="color-dot-btn violet-dot" data-color-target="violet" title="Violet"></button>
                                        <button type="button" class="color-dot-btn rose-dot" data-color-target="rose" title="Rose"></button>
                                        <button type="button" class="color-dot-btn amber-dot" data-color-target="amber" title="Amber"></button>
                                        <button type="button" class="color-dot-btn slate-dot" data-color-target="slate" title="Slate"></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Widget -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="user-profile-widget">
                            @csrf
                            @php($adminUser = auth()->user())
                            <span class="user-avatar">{{ $adminUser ? strtoupper(substr($adminUser->name, 0, 2)) : 'AD' }}</span>
                            <div class="user-details">
                                <span class="user-name">{{ $adminUser?->name ?? 'Administrador' }}</span>
                                <button type="submit" class="user-role" style="background: transparent; border: 0; padding: 0; color: inherit; font: inherit; cursor: pointer;">Cerrar sesión</button>
                            </div>
                        </form>
                    </div>
                </header>

                <!-- Page Content Area -->
                <main class="admin-page-content">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Toast System for Session Feedbacks -->
        @if (session('status'))
            <div class="admin-flash-toast" id="toastStatus">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="toast-message-text">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="admin-flash-toast toast-error" id="toastError">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--danger-text);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="toast-message-text">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Client-Side UI Handlers -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Toast Auto-Dismissal
                ['toastStatus', 'toastError'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                            el.style.opacity = '0';
                            el.style.transform = 'translateY(20px)';
                            setTimeout(() => el.remove(), 500);
                        }, 4000);
                    }
                });

                // Theme Dropdown toggle
                const themePickerBtn = document.getElementById('themePickerBtn');
                const themeDropdown = document.getElementById('themeDropdown');
                
                if (themePickerBtn && themeDropdown) {
                    themePickerBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        themeDropdown.classList.toggle('is-open');
                    });
                    
                    document.addEventListener('click', (e) => {
                        if (!themeDropdown.contains(e.target) && e.target !== themePickerBtn) {
                            themeDropdown.classList.remove('is-open');
                        }
                    });
                }

                // Theme Mode Switcher
                const activeMode = localStorage.getItem('admin-theme-mode') || 
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                
                const modeButtons = document.querySelectorAll('[data-mode-target]');
                modeButtons.forEach(btn => {
                    const targetMode = btn.getAttribute('data-mode-target');
                    if (targetMode === activeMode) {
                        btn.classList.add('is-active');
                    }
                    
                    btn.addEventListener('click', () => {
                        modeButtons.forEach(b => b.classList.remove('is-active'));
                        btn.classList.add('is-active');
                        document.documentElement.setAttribute('data-theme-mode', targetMode);
                        localStorage.setItem('admin-theme-mode', targetMode);
                    });
                });

                // Accent Color Switcher
                const activeColor = localStorage.getItem('admin-theme-color') || 'indigo';
                const colorButtons = document.querySelectorAll('[data-color-target]');
                
                colorButtons.forEach(btn => {
                    const targetColor = btn.getAttribute('data-color-target');
                    if (targetColor === activeColor) {
                        btn.classList.add('is-active');
                    }
                    
                    btn.addEventListener('click', () => {
                        colorButtons.forEach(b => b.classList.remove('is-active'));
                        btn.classList.add('is-active');
                        document.documentElement.setAttribute('data-theme-color', targetColor);
                        localStorage.setItem('admin-theme-color', targetColor);
                    });
                });
            });
        </script>
    </body>
</html>
