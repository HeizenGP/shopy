# ShopCMS

Plataforma de tienda virtual tipo WooCommerce / Shopify. Sistema completo con catálogo de productos, carrito de compras e interfaz administrativa potente.

**Tienda virtual con catálogo y carrito de compras**

---

## 📦 6 Módulos Requeridos

- **Catálogo de productos con variantes** - Gestión completa de productos con múltiples opciones
- **Carrito y proceso de checkout** - Experiencia de compra fluida e intuitiva
- **Gestión de pedidos y estados** - Seguimiento completo del ciclo de vida de pedidos
- **Cupones y descuentos** - Sistema flexible de promociones y ofertas
- **Inventario y alertas de stock** - Control automático de inventario
- **Reseñas y calificaciones** - Sistema de valoraciones de productos

## 🔌 Plugins Sugeridos

- **Payment gateway** - Integración con Culqi/PayPal
- **Wishlist** - Lista de deseos con opción de compartir
- **Product comparator** - Comparar hasta 4 productos simultáneamente
- **Stock notifier** - Alertas por email cuando hay disponibilidad
- **Related products** - Upsell y cross-sell automático
- **Invoice generator** - Generación de facturas PDF por pedido

## 🎨 Temas Disponibles

| Tema | Descripción |
|------|-------------|
| **Minimal Shop** | Blanco limpio con producto centrado |
| **Fashion** | Imágenes grandes de pantalla completa |
| **Electronics** | Grilla densa con filtros laterales |
| **Food & Market** | Cálido con categorías en tarjetas |

---

## 🚀 Requisitos

- PHP 8.3+
- Composer
- Node.js y npm

## 📦 Instalación

```bash
composer run setup
```

## 💻 Desarrollo

```bash
composer run dev
```

Ejecuta simultáneamente: servidor Laravel, cola de trabajos, logs en tiempo real y Vite dev server.

## 🧪 Testing

```bash
composer run test
```

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 13 con PHP 8.3
- **Frontend**: Vite + Tailwind CSS v4
- **Testing**: Pest PHP
- **Herramientas**: Laravel Boost, Pail, Pint

## 🏗️ Arquitectura

Este proyecto utiliza **Arquitectura Hexagonal + Vertical Slicing** para máxima escalabilidad y mantenibilidad.

### Principios

- **Hexagonal (Puertos & Adaptadores)**: Aísla la lógica de negocio de dependencias externas
- **Vertical Slicing**: Cada módulo es independiente y autocontenido
- **Domain-Driven Design**: El dominio de negocio es el centro de toda decisión arquitectónica

### Estructura

```
app/
├── Shared/
│   ├── Domain/           # Entidades y valores compartidos
│   ├── Application/      # Casos de uso transversales
│   └── Infrastructure/   # Servicios compartidos
│
├── Modules/
│   ├── Catalog/
│   │   ├── Domain/       # Entities, ValueObjects, Repositories (PORT)
│   │   ├── Application/  # UseCases, DTOs, Services
│   │   ├── Infrastructure/ # Adapters, Database Queries
│   │   └── Presentation/ # Controllers, Requests, Resources
│   │
│   ├── Cart/
│   ├── Orders/
│   ├── Users/
│   ├── Payments/
│   └── Auth/
│
└── Providers/
```

### Ejemplo: Módulo Catalog

```
Modules/Catalog/
├── Domain/
│   ├── Entities/Product.php
│   ├── ValueObjects/Price.php
│   ├── Repositories/ProductRepository.php (PORT)
│   └── Services/ProductDomainService.php
│
├── Application/
│   ├── UseCases/GetProductUseCase.php
│   ├── DTOs/ProductDTO.php
│   └── Services/CatalogApplicationService.php
│
├── Infrastructure/
│   ├── Adapters/EloquentProductRepository.php
│   ├── Queries/FindProductQuery.php
│   └── Database/
│
└── Presentation/
    ├── Controllers/ProductController.php
    ├── Requests/CreateProductRequest.php
    └── Resources/ProductResource.php
```

### Ventajas

- ✅ **Independencia**: Cada módulo puede desarrollarse en paralelo
- ✅ **Testabilidad**: Lógica de negocio sin dependencias de framework
- ✅ **Flexibilidad**: Cambiar adaptadores sin afectar el dominio
- ✅ **Claridad**: Responsabilidades bien definidas
- ✅ **Escalabilidad**: Fácil agregar nuevos módulos

## 📄 Licencia

MIT
