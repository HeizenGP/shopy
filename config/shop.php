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
        'login_bg' => env('COLOR_CLIENT_LOGIN_BG', '#eef2ff'),
        'success' => env('COLOR_SUCCESS', '#10b981'),
        'error' => env('COLOR_ERROR', '#ef4444'),
        'warning' => env('COLOR_WARNING', '#f59e0b'),
    ],
    
    // Colores - Admin
    'colors_admin' => [
        'sidebar_bg' => env('COLOR_ADMIN_SIDEBAR', '#202123'),
        'primary' => env('COLOR_ADMIN_PRIMARY', '#4f46e5'),
        'accent' => env('COLOR_ADMIN_ACCENT', '#ec4899'),
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
