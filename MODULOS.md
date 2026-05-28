# 📋 Guía de Pulido de Módulos - ShopCMS

Documento estratégico para optimizar y completar los módulos de ShopCMS con enfoque en usuario final.

---

## 🎯 Orden de Prioridad de Pulido

### Fase 1: Configuración Global & UX (CRÍTICA)
**Objetivo**: Hacer la plataforma personalizable desde el admin sin código.

#### 1. **Settings (Configuración Global)** ⚙️
**Estado**: Recientemente iniciado  
**Tareas**:
- [x] Tabla de configuración en BD
- [x] Variables CSS dinámicas (colores cliente)
- [ ] Panel admin mejorado (UI/UX)
- [ ] Caché de configuraciones
- [ ] Export/Import settings

**Variables a crear**:
```php
// app/config/shop.php o en Settings table
'store' => [
    'name' => 'ShopCMS Store',           // Nombre tienda
    'email' => 'info@shop.com',          // Email contacto
    'phone' => '+1 234 567 890',         // Teléfono
    'description' => 'Tu descripción',   // SEO description
    'logo_url' => '/logo.png',           // URL del logo
    'favicon_url' => '/favicon.ico',     // Favicon
],

'colors' => [
    'client_page_bg' => '#f8fafc',       // Fondo página
    'client_primary' => '#4f46e5',       // Color primario
    'client_login_bg' => '#eef2ff',      // Fondo login
    'admin_sidebar_bg' => '#202123',     // Sidebar admin
    'admin_primary' => '#4f46e5',        // Primario admin
],

'theme' => [
    'current' => 'minimal',              // Tema activo
    'responsive' => true,
    'dark_mode' => false,
],

'shop' => [
    'currency' => 'USD',                 // Moneda
    'tax_rate' => 16,                    // IVA/Impuesto
    'shipping_free_threshold' => 50,     // Envío gratis >
    'maintenance_mode' => false,
]
```

---

### Fase 2: Catálogo & Presentación (IMPORTANTE)
**Objetivo**: Experiencia de compra pulida y atractiva.

#### 2. **Catalog (Catálogo de Productos)** 📦
**Estado**: Funcional, necesita UX  
**Tareas**:
- [ ] Imágenes: Thumbnails, galería, zoom
- [ ] SEO: Meta tags, slugs únicos
- [ ] Búsqueda: Full-text search, filtros avanzados
- [ ] Recomendaciones: Productos relacionados
- [ ] Categorización: Jerarquía de categorías
- [ ] Stock visual: Indicadores de disponibilidad

**Configurables**:
```
- Imagen destacada por producto
- Galerías de hasta 10 imágenes
- Orden de categorías
- Filtros activos (color, talla, precio)
```

#### 3. **Reviews (Reseñas y Calificaciones)** ⭐
**Estado**: Base implementada  
**Tareas**:
- [ ] Validar que solo compradores puedan reseñar
- [ ] Sistema de helpful votes
- [ ] Mostrar promedio de calificaciones en catálogo
- [ ] Reseñas verificadas (✓ Comprador verificado)
- [ ] Moderar reseñas antes de publicar (opcional)
- [ ] Respuestas del vendedor

---

### Fase 3: Transacciones (CORE)
**Objetivo**: Proceso de compra robusto y confiable.

#### 4. **Cart (Carrito de Compras)** 🛒
**Estado**: Funcional  
**Tareas**:
- [ ] Persistencia del carrito (cookie + BD)
- [ ] Sugerencias mientras compra
- [ ] Envío estimado en tiempo real
- [ ] Resumen de costos desglosado
- [ ] Guardado de "Para después"

#### 5. **Orders (Gestión de Pedidos)** 📊
**Estado**: Funcional, mejorar UX  
**Tareas**:
- [ ] Timeline de estados con timestamps
- [ ] Notificaciones por email en cada cambio
- [ ] Tracking en tiempo real
- [ ] Recibos/Comprobantes en PDF
- [ ] Panel del cliente (historial, seguimiento)
- [ ] Devoluciones automatizadas

#### 6. **Payments (Gateway de Pagos)** 💳
**Estado**: Solo puerta, no implementado  
**Tareas**:
- [ ] Integrar Culqi o PayPal
- [ ] Múltiples métodos: tarjeta, transferencia
- [ ] Webhook para confirmación de pago
- [ ] Reintentos automáticos
- [ ] Comprobante de pago

---

### Fase 4: Control de Operaciones (IMPORTANTE)
**Objetivo**: Que admins gestionen la tienda sin desarrolladores.

#### 7. **Inventory (Inventario y Alertas)** 📈
**Estado**: Funcional  
**Tareas**:
- [ ] Dashboard de stock bajo
- [ ] Alertas automáticas (email/SMS)
- [ ] Historial de movimientos
- [ ] Análisis de rotación
- [ ] Reorden automático de proveedores

#### 8. **Coupons (Cupones y Descuentos)** 🎟️
**Estado**: Funcional  
**Tareas**:
- [ ] UI para crear cupones en admin
- [ ] Tipos: Porcentaje, fijo, BOGO
- [ ] Restricciones: Por usuario, cantidad, fecha
- [ ] Códigos únicos vs masivos
- [ ] Estadísticas de uso

---

### Fase 5: Gestión de Usuarios (IMPORTANTE)
**Objetivo**: Sistema de permisos robusto.

#### 9. **Auth (Autenticación)** 🔐
**Estado**: Básico  
**Tareas**:
- [ ] 2FA (Two-Factor Authentication)
- [ ] Social login (Google, Facebook)
- [ ] "Olvidé contraseña" fluido
- [ ] Validación de email
- [ ] Recuperación de cuenta

#### 10. **Users (Gestión de Usuarios)** 👥
**Estado**: Base implementada  
**Tareas**:
- [ ] Perfiles de usuario (datos, direcciones)
- [ ] Múltiples direcciones de envío
- [ ] Historial de compras
- [ ] Favoritos/Wishlist
- [ ] Métodos de pago guardados

---

### Fase 6: Admin & Back Office (CRÍTICA)
**Objetivo**: Panel potente pero intuitivo.

#### 11. **Admin Panel** 🎛️
**Estado**: Base diseñada  
**Tareas**:
- [x] Dashboard con stats
- [ ] Gestión de roles y permisos
- [ ] Usuarios en tabla
- [ ] Editor de categorías
- [ ] Logs de auditoría
- [ ] Respaldos automáticos
- [ ] Importación de productos (CSV/Excel)

---

## 📝 Variables de Configuración Global

### 1. **Archivo de Configuración Centralizado**
Crear `config/shop.php`:

```php
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
```

### 2. **Variables en BD (Settings Table)**
Migración existente: `2026_05_27_000008_create_settings_table.php`

Agregar más configuraciones:
```sql
INSERT INTO settings (key, value, description) VALUES
-- Tienda
('shop_name', 'ShopCMS', 'Nombre de la tienda'),
('shop_email', 'info@shop.com', 'Email de contacto'),
('shop_phone', '+1 234 567 890', 'Teléfono de la tienda'),
('shop_currency', 'USD', 'Moneda de la tienda'),
('shop_tax_rate', '16', 'Porcentaje de impuesto'),

-- Branding
('logo_url', '/images/logo.png', 'URL del logo'),
('favicon_url', '/favicon.ico', 'URL del favicon'),
('banner_url', '/images/banner.jpg', 'URL del banner principal'),

-- Colores (ya existentes)
('--color-client-page', '#f8fafc', 'Fondo página cliente'),
('--color-client-primary', '#4f46e5', 'Color primario cliente'),
('--color-client-login-bg', '#eef2ff', 'Fondo login cliente'),

-- Admin
('--color-admin-sidebar', '#202123', 'Fondo sidebar admin'),
('--color-admin-primary', '#4f46e5', 'Color primario admin'),

-- Funcionalidades
('maintenance_mode', '0', 'Modo mantenimiento (0=no, 1=sí)'),
('reviews_moderation', '1', 'Moderar reseñas antes de publicar'),
('orders_auto_email', '1', 'Enviar emails automáticos de pedidos'),
('wishlist_enabled', '1', 'Habilitar lista de deseos');
```

### 3. **Variables de Entorno (.env)**
```bash
# Tienda
SHOP_NAME="ShopCMS"
SHOP_EMAIL="info@shop.com"
SHOP_PHONE="+1 234 567 890"
SHOP_DESCRIPTION="Tu tienda virtual premium"

# Branding
SHOP_LOGO_URL="/images/logo.png"
SHOP_FAVICON_URL="/favicon.ico"

# Colores Cliente
COLOR_CLIENT_PAGE="#f8fafc"
COLOR_CLIENT_PRIMARY="#4f46e5"
COLOR_CLIENT_LOGIN_BG="#eef2ff"

# Colores Admin
COLOR_ADMIN_SIDEBAR="#202123"
COLOR_ADMIN_PRIMARY="#4f46e5"

# Tienda
SHOP_CURRENCY="USD"
SHOP_TAX_RATE=16
SHOP_MAINTENANCE=false
```

---

## 🚀 Cómo Acceder a Variables Globales

### En Blade (Vistas):
```blade
<!-- config/shop.php -->
<h1>{{ config('shop.name') }}</h1>
<img src="{{ config('shop.logo.url') }}">

<!-- Settings table -->
<style>
    :root {
        --color-client-page: {{ \App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent::where('key', '--color-client-page')->value('value') }};
    }
</style>
```

### En PHP (Controladores):
```php
use App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent;

class MyController {
    public function index() {
        $shopName = config('shop.name');
        $primaryColor = SettingEloquent::where('key', '--color-client-primary')->value('value');
        
        return view('home', compact('shopName', 'primaryColor'));
    }
}
```

### Crear Helper para facilitar:
```php
// app/Helpers/ShopHelper.php
<?php

namespace App\Helpers;

use App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent;

class ShopHelper {
    public static function getSetting($key, $default = null) {
        return SettingEloquent::where('key', $key)->value('value') ?? $default;
    }
    
    public static function getColor($name) {
        return self::getSetting("--color-{$name}");
    }
}

// Uso en vistas:
// {{ ShopHelper::getSetting('shop_name') }}
// {{ ShopHelper::getColor('client-primary') }}
```

---

## ✅ Checklist de Implementación

### Semana 1: Configuración Global
- [ ] Crear `config/shop.php`
- [ ] Agregar variables a `.env.example`
- [ ] Extender tabla settings con más campos
- [ ] Crear ShopHelper
- [ ] Mejorar UI del panel settings

### Semana 2-3: Catálogo
- [ ] Galería de imágenes
- [ ] Búsqueda y filtros
- [ ] SEO básico
- [ ] Productos relacionados

### Semana 4: Checkout
- [ ] Carrito persistente
- [ ] Validación de stock
- [ ] Cálculo de envío
- [ ] Resumen de pedido

### Semana 5-6: Admin
- [ ] Dashboard mejorado
- [ ] Gestión de productos
- [ ] Importación CSV
- [ ] Reportes

---

## 📚 Recursos Útiles

- **Patrón Helper**: `app/Helpers/ShopHelper.php`
- **Config centralizado**: `config/shop.php`
- **Settings dinámicas**: `app/Modules/Settings/`
- **Caché de settings**: Agregar `Cache::remember('shop_settings')`

---

**Última actualización**: 27 May 2026  
**Responsable**: ShopCMS Development Team
