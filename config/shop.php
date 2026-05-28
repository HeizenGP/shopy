<?php

return [
    // Información de la tienda
    'name' => env('SHOP_NAME', 'ShopCMS'),
    'email' => env('SHOP_EMAIL', 'info@shop.com'),
    'phone' => env('SHOP_PHONE', '+1 234 567 890'),
    'description' => env('SHOP_DESCRIPTION', 'Tu tienda virtual premium'),
    
    // Branding
    'logo' => [
        'url' => env('SHOP_LOGO_URL', '/images/logo.png'),
        'alt' => 'ShopCMS Logo',
        'height' => 40,
    ],
    'favicon' => env('SHOP_FAVICON_URL', '/favicon.ico'),
    'banner' => env('SHOP_BANNER_URL', '/images/banner.jpg'),
    
    // Colores - Cliente
    'colors_client' => [
        'page_bg' => env('COLOR_CLIENT_PAGE', '#f8fafc'),
        'primary' => env('COLOR_CLIENT_PRIMARY', '#4f46e5'),
        'surface' => env('COLOR_CLIENT_SURFACE', '#ffffff'),
        'surface_alt' => env('COLOR_CLIENT_SURFACE_ALT', '#f1f5f9'),
        'border' => env('COLOR_CLIENT_BORDER', '#e2e8f0'),
        'text' => env('COLOR_CLIENT_TEXT', '#0f172a'),
        'muted' => env('COLOR_CLIENT_MUTED', '#64748b'),
        'header_footer_bg' => env('COLOR_CLIENT_HEADER_FOOTER', '#ffffff'),
        'card' => env('COLOR_CLIENT_CARD', '#ffffff'),
        'card_border' => env('COLOR_CLIENT_CARD_BORDER', '#e2e8f0'),
        'success' => env('COLOR_SUCCESS', '#10b981'),
        'error' => env('COLOR_ERROR', '#ef4444'),
        'warning' => env('COLOR_WARNING', '#f59e0b'),
    ],
    
    // Colores - Admin
    'colors_admin' => [
        'sidebar_bg' => env('COLOR_ADMIN_SIDEBAR', '#202123'),
        'primary' => env('COLOR_ADMIN_PRIMARY', '#4f46e5'),
        'accent' => env('COLOR_ADMIN_ACCENT', '#ec4899'),
        'page_bg' => env('COLOR_ADMIN_PAGE_BG', '#f3f4f6'),
    ],

    // Temas de Colores Predefinidos
    'theme_admin' => env('COLOR_THEME_ADMIN', 'slate_corporate'),
    'theme_web' => env('COLOR_THEME_WEB', 'indigo_imperial'),

    'color_themes_client' => [
        'indigo_imperial' => [
            'name' => 'Indigo Imperial (Clásico & Profesional)',
            'colors' => [
                '--color-client-page' => '#f8fafc',
                '--color-client-primary' => '#4f46e5',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#f1f5f9',
                '--color-client-border' => '#e2e8f0',
                '--color-client-text' => '#0f172a',
                '--color-client-muted' => '#64748b',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#e2e8f0',
            ]
        ],
        'emerald_luxury' => [
            'name' => 'Esmeralda Luxury (Sofisticado & Orgánico)',
            'colors' => [
                '--color-client-page' => '#fafdfb',
                '--color-client-primary' => '#059669',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#f0fdf4',
                '--color-client-border' => '#e6f4ea',
                '--color-client-text' => '#064e3b',
                '--color-client-muted' => '#047857',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#d1fae5',
            ]
        ],
        'champagne_amber' => [
            'name' => 'Champán & Ámbar (Cálido & Lujoso)',
            'colors' => [
                '--color-client-page' => '#fdfaf2',
                '--color-client-primary' => '#b45309',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#fef3c7',
                '--color-client-border' => '#fde68a',
                '--color-client-text' => '#78350f',
                '--color-client-muted' => '#b45309',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#fef3c7',
            ]
        ],
        'burgundy_cream' => [
            'name' => 'Borgoña & Crema (Elegante & Sensual)',
            'colors' => [
                '--color-client-page' => '#fdfbf7',
                '--color-client-primary' => '#881337',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#fff5f5',
                '--color-client-border' => '#fecdd3',
                '--color-client-text' => '#4c0519',
                '--color-client-muted' => '#9f1239',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#ffe4e6',
            ]
        ],
        'nordic_teal' => [
            'name' => 'Azul Nórdico (Limpio & Minimalista)',
            'colors' => [
                '--color-client-page' => '#f4f7f6',
                '--color-client-primary' => '#0f766e',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#ccfbf1',
                '--color-client-border' => '#e2e8f0',
                '--color-client-text' => '#0f172a',
                '--color-client-muted' => '#475569',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#cbd5e1',
            ]
        ],
        'lavender_platinum' => [
            'name' => 'Lavanda & Platino (Romántico & Moderno)',
            'colors' => [
                '--color-client-page' => '#fafaff',
                '--color-client-primary' => '#7c3aed',
                '--color-client-surface' => '#ffffff',
                '--color-client-surface-alt' => '#ede9fe',
                '--color-client-border' => '#e0e0ff',
                '--color-client-text' => '#2e1065',
                '--color-client-muted' => '#6d28d9',
                '--color-client-header-footer-bg' => '#ffffff',
                '--color-client-card' => '#ffffff',
                '--color-client-card-border' => '#e0e0ff',
            ]
        ],
    ],

    'color_themes_admin' => [
        'slate_corporate' => [
            'name' => 'Slate Corporativo (Clásico & Profesional)',
            'colors' => [
                '--color-admin-sidebar' => '#202123',
                '--color-admin-page-bg' => '#f3f4f6',
                '--color-admin-primary' => '#4f46e5',
                '--color-admin-accent' => '#ec4899',
                '--color-admin-login-panel' => '#ffffff',
            ]
        ],
        'emerald_night' => [
            'name' => 'Esmeralda Nocturno (Elegante & Premium)',
            'colors' => [
                '--color-admin-sidebar' => '#062f22',
                '--color-admin-page-bg' => '#f0fdf4',
                '--color-admin-primary' => '#059669',
                '--color-admin-accent' => '#10b981',
                '--color-admin-login-panel' => '#ffffff',
            ]
        ],
        'monaco_lux' => [
            'name' => 'Mónaco Lux (Exclusivo & Minimalista Dark)',
            'colors' => [
                '--color-admin-sidebar' => '#0b0f19',
                '--color-admin-page-bg' => '#f4f7f6',
                '--color-admin-primary' => '#d4af37',
                '--color-admin-accent' => '#f59e0b',
                '--color-admin-login-panel' => '#111827',
            ]
        ],
        'burgundy_prestige' => [
            'name' => 'Borgoña Prestige (Lujoso & Distinguido)',
            'colors' => [
                '--color-admin-sidebar' => '#3b0712',
                '--color-admin-page-bg' => '#fff5f5',
                '--color-admin-primary' => '#9f1239',
                '--color-admin-accent' => '#fb7185',
                '--color-admin-login-panel' => '#ffffff',
            ]
        ],
        'sapphire_deep' => [
            'name' => 'Zafiro Profundo (Tecnológico & Moderno)',
            'colors' => [
                '--color-admin-sidebar' => '#0f172a',
                '--color-admin-page-bg' => '#f1f5f9',
                '--color-admin-primary' => '#2563eb',
                '--color-admin-accent' => '#3b82f6',
                '--color-admin-login-panel' => '#ffffff',
            ]
        ],
        'nordic_charcoal' => [
            'name' => 'Nordic Charcoal (Minimalismo Escandinavo)',
            'colors' => [
                '--color-admin-sidebar' => '#1e293b',
                '--color-admin-page-bg' => '#f4f7f6',
                '--color-admin-primary' => '#0f766e',
                '--color-admin-accent' => '#14b8a6',
                '--color-admin-login-panel' => '#ffffff',
            ]
        ],
    ],
    
    // Tienda
    'currency' => env('SHOP_CURRENCY', 'USD'),
    'currency_symbol' => env('SHOP_CURRENCY_SYMBOL', '$'),
    'timezone' => env('SHOP_TIMEZONE', 'America/New_York'),
    'tax_rate' => env('SHOP_TAX_RATE', 0),
    'shipping_free_threshold' => env('SHOP_SHIPPING_FREE', 100),
    
    // Tema
    'theme' => [
        'current' => env('SHOP_THEME', 'minimal'),
        'available' => ['minimal', 'fashion', 'electronics', 'food'],
    ],
    
    // Mantenimiento
    'maintenance_mode' => env('SHOP_MAINTENANCE', false),
    'maintenance_message' => 'Estamos en mantenimiento. Vuelve pronto.',
    
    // Seguridad
    'force_https' => env('FORCE_HTTPS', true),
    'session_timeout' => env('SESSION_TIMEOUT', 120), // minutos
];
