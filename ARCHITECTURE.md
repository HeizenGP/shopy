# 🏗️ Arquitectura Hexagonal + Vertical Slicing

Guía completa para trabajar con la arquitectura del proyecto ShopCMS.

---

## 📚 Tabla de Contenidos

1. [Principios Fundamentales](#principios-fundamentales)
2. [Flujo de Datos](#flujo-de-datos)
3. [Estructura de Carpetas](#estructura-de-carpetas)
4. [Guía Paso a Paso](#guía-paso-a-paso)
5. [Ejemplos Prácticos](#ejemplos-prácticos)
6. [Convenciones](#convenciones)
7. [Testing](#testing)
8. [Buenas Prácticas](#buenas-prácticas)

---

## Principios Fundamentales

### 🎯 Arquitectura Hexagonal (Puertos & Adaptadores)

La lógica de negocio (Domain) está protegida en el centro, separada de:
- **Puertos**: Interfaces que definen contratos
- **Adaptadores**: Implementaciones concretas (BD, APIs externas, etc.)

**Ventaja**: Cambiar la BD de SQLite a PostgreSQL sin tocar la lógica de negocio.

### 📦 Vertical Slicing

Cada módulo es independiente y autocontenido. No hay capas horizontales compartidas.

**Ventaja**: El equipo A trabaja en Catalog y el B en Orders sin conflictos de merge.

### 🎲 Domain-Driven Design

El negocio es el centro. Las decisiones técnicas se adaptan al negocio, no al revés.

---

## Flujo de Datos

```
Request HTTP
    ↓
[Presentation] Controller
    ↓ (ValidateRequest)
[Presentation] Request/FormRequest
    ↓ (TransformToDTO)
[Application] UseCase
    ↓ (Ejecuta reglas de negocio)
[Domain] Entities & Services
    ↓ (Persiste datos)
[Infrastructure] Repository Adapter
    ↓ (Ejecuta query)
Database (Eloquent/Query Builder)
    ↓
Response JSON/View
```

---

## Estructura de Carpetas

### Por Módulo

```
Modules/Catalog/
├── Domain/
│   ├── Entities/
│   │   └── Product.php           # Entidad con lógica de negocio
│   ├── ValueObjects/
│   │   ├── Price.php             # Valor inmutable (precio = 19.99)
│   │   └── ProductStatus.php      # Enum-like: draft, published, archived
│   ├── Repositories/
│   │   └── ProductRepository.php  # PORT (Interfaz, contrato)
│   └── Services/
│       └── ProductDomainService.php # Lógica pura de negocio
│
├── Application/
│   ├── UseCases/
│   │   ├── GetProductUseCase.php       # Obtener 1 producto
│   │   ├── ListProductsUseCase.php     # Listar con filtros
│   │   ├── CreateProductUseCase.php    # Crear producto
│   │   └── UpdateProductUseCase.php    # Actualizar
│   ├── DTOs/
│   │   ├── GetProductDTO.php           # Input
│   │   └── ProductResponseDTO.php      # Output
│   └── Services/
│       └── CatalogApplicationService.php # Orquesta UseCases
│
├── Infrastructure/
│   ├── Adapters/
│   │   └── EloquentProductRepository.php # ADAPTER (Implementa PORT)
│   ├── Queries/
│   │   ├── FindProductQuery.php         # Query dinámica
│   │   └── FindProductsWithFiltersQuery.php
│   └── Database/
│       ├── ProductModel.php             # Eloquent Model
│       └── Factories/ProductFactory.php # Para tests
│
└── Presentation/
    ├── Controllers/
    │   └── ProductController.php        # HTTP endpoint
    ├── Requests/
    │   ├── CreateProductRequest.php     # Form validation
    │   └── UpdateProductRequest.php
    └── Resources/
        └── ProductResource.php          # Transformar a JSON
```

### Carpeta Shared

```
Shared/
├── Domain/
│   ├── Entities/
│   │   └── User.php                    # Entidad compartida
│   └── ValueObjects/
│       └── Email.php                   # Value Object reutilizable
│
├── Application/
│   ├── UseCases/
│   │   └── AuthenticateUserUseCase.php # Autenticación global
│   └── Services/
│       └── NotificationService.php     # Para todos los módulos
│
└── Infrastructure/
    ├── Adapters/
    │   └── MailAdapter.php             # Enviar emails
    └── Providers/
        └── ServiceProvider.php
```

---

## Guía Paso a Paso

### 🎯 Crear un Feature: "Obtener Producto por ID"

#### 1️⃣ Crear la Entidad (Domain)

**Archivo**: `app/Modules/Catalog/Domain/Entities/Product.php`

```php
<?php

namespace App\Modules\Catalog\Domain\Entities;

use App\Modules\Catalog\Domain\ValueObjects\Price;
use App\Modules\Catalog\Domain\ValueObjects\ProductStatus;

class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description,
        private Price $price,
        private ProductStatus $status,
        private int $stock,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function isAvailable(): bool
    {
        return $this->status->isPublished() && $this->stock > 0;
    }

    public function decreaseStock(int $quantity): void
    {
        if ($this->stock < $quantity) {
            throw new \DomainException('Stock insuficiente');
        }
        $this->stock -= $quantity;
    }
}
```

#### 2️⃣ Crear Value Objects (Domain)

**Archivo**: `app/Modules/Catalog/Domain/ValueObjects/Price.php`

```php
<?php

namespace App\Modules\Catalog\Domain\ValueObjects;

class Price
{
    public function __construct(
        private float $amount,
        private string $currency = 'USD',
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException('El precio no puede ser negativo');
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function format(): string
    {
        return "{$this->currency} " . number_format($this->amount, 2);
    }
}
```

#### 3️⃣ Crear el Puerto (Repository Interface)

**Archivo**: `app/Modules/Catalog/Domain/Repositories/ProductRepository.php`

```php
<?php

namespace App\Modules\Catalog\Domain\Repositories;

use App\Modules\Catalog\Domain\Entities\Product;

interface ProductRepository
{
    public function findById(int $id): ?Product;

    public function save(Product $product): void;

    public function delete(int $id): void;

    /**
     * @return Product[]
     */
    public function findAll(array $filters = []): array;
}
```

#### 4️⃣ Implementar el Adaptador (Infrastructure)

**Archivo**: `app/Modules/Catalog/Infrastructure/Adapters/EloquentProductRepository.php`

```php
<?php

namespace App\Modules\Catalog\Infrastructure\Adapters;

use App\Modules\Catalog\Domain\Entities\Product;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;
use App\Modules\Catalog\Domain\ValueObjects\Price;
use App\Modules\Catalog\Infrastructure\Database\ProductModel;

class EloquentProductRepository implements ProductRepository
{
    public function findById(int $id): ?Product
    {
        $model = ProductModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->mapToDomain($model);
    }

    public function save(Product $product): void
    {
        ProductModel::updateOrCreate(
            ['id' => $product->getId()],
            [
                'name' => $product->getName(),
                'price' => $product->getPrice()->getAmount(),
                // ... más campos
            ]
        );
    }

    public function delete(int $id): void
    {
        ProductModel::destroy($id);
    }

    public function findAll(array $filters = []): array
    {
        $query = ProductModel::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        return $query->get()->map(fn($model) => $this->mapToDomain($model))->toArray();
    }

    private function mapToDomain(ProductModel $model): Product
    {
        return new Product(
            $model->id,
            $model->name,
            $model->description,
            new Price($model->price, 'USD'),
            ProductStatus::from($model->status),
            $model->stock,
        );
    }
}
```

#### 5️⃣ Crear el UseCase (Application)

**Archivo**: `app/Modules/Catalog/Application/UseCases/GetProductUseCase.php`

```php
<?php

namespace App\Modules\Catalog\Application\UseCases;

use App\Modules\Catalog\Application\DTOs\GetProductDTO;
use App\Modules\Catalog\Application\DTOs\ProductResponseDTO;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;

class GetProductUseCase
{
    public function __construct(private ProductRepository $repository) {}

    public function execute(GetProductDTO $input): ProductResponseDTO
    {
        $product = $this->repository->findById($input->productId);

        if (!$product) {
            throw new \DomainException('Producto no encontrado');
        }

        return new ProductResponseDTO(
            $product->getId(),
            $product->getName(),
            $product->getPrice()->format(),
            $product->isAvailable(),
        );
    }
}
```

#### 6️⃣ Crear DTOs (Application)

**Archivo**: `app/Modules/Catalog/Application/DTOs/GetProductDTO.php`

```php
<?php

namespace App\Modules\Catalog\Application\DTOs;

class GetProductDTO
{
    public function __construct(public int $productId) {}
}
```

**Archivo**: `app/Modules/Catalog/Application/DTOs/ProductResponseDTO.php`

```php
<?php

namespace App\Modules\Catalog\Application\DTOs;

class ProductResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $price,
        public bool $isAvailable,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'available' => $this->isAvailable,
        ];
    }
}
```

#### 7️⃣ Crear el Controller (Presentation)

**Archivo**: `app/Modules/Catalog/Presentation/Controllers/ProductController.php`

```php
<?php

namespace App\Modules\Catalog\Presentation\Controllers;

use App\Modules\Catalog\Application\DTOs\GetProductDTO;
use App\Modules\Catalog\Application\UseCases\GetProductUseCase;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function __construct(private GetProductUseCase $useCase) {}

    public function show(int $id): JsonResponse
    {
        try {
            $dto = new GetProductDTO($id);
            $result = $this->useCase->execute($dto);

            return response()->json($result->toArray());
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
```

#### 8️⃣ Registrar en Service Provider

**Archivo**: `app/Modules/Catalog/Infrastructure/Providers/CatalogServiceProvider.php`

```php
<?php

namespace App\Modules\Catalog\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;
use App\Modules\Catalog\Infrastructure\Adapters\EloquentProductRepository;
use App\Modules\Catalog\Application\UseCases\GetProductUseCase;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interface a implementación
        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        // Bind UseCase con sus dependencias
        $this->app->bind(
            GetProductUseCase::class,
            fn($app) => new GetProductUseCase(
                $app->make(ProductRepository::class)
            )
        );
    }
}
```

#### 9️⃣ Registrar Rutas

**Archivo**: `routes/api.php`

```php
Route::get('/products/{id}', [ProductController::class, 'show']);
```

---

## Ejemplos Prácticos

### Ejemplo: Crear un Producto

```php
// UseCase
class CreateProductUseCase
{
    public function __construct(private ProductRepository $repository) {}

    public function execute(CreateProductDTO $input): int
    {
        $price = new Price($input->price);
        $status = ProductStatus::DRAFT;

        $product = new Product(
            id: null, // Auto-generated
            name: $input->name,
            description: $input->description,
            price: $price,
            status: $status,
            stock: $input->stock,
        );

        $this->repository->save($product);

        return $product->getId();
    }
}
```

### Ejemplo: Validación en Request

```php
class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
}
```

---

## Convenciones

### Nomenclatura

| Concepto | Patrón | Ejemplo |
|----------|--------|---------|
| Entidad | `{Nombre}` | `Product`, `Order` |
| Value Object | `{Nombre}` | `Price`, `Email` |
| Repository Interface | `{Entidad}Repository` | `ProductRepository` |
| Repository Implementation | `Eloquent{Entidad}Repository` | `EloquentProductRepository` |
| UseCase | `{Acción}{Entidad}UseCase` | `CreateProductUseCase` |
| DTO Input | `{Acción}{Entidad}DTO` | `CreateProductDTO` |
| DTO Output | `{Entidad}ResponseDTO` | `ProductResponseDTO` |
| Service Provider | `{Módulo}ServiceProvider` | `CatalogServiceProvider` |

### Directorios

- **Domain**: Código agnóstico de framework
- **Application**: Lógica de aplicación, orquestación
- **Infrastructure**: Detalles técnicos (BD, APIs, etc.)
- **Presentation**: HTTP, validación, transformación de datos

---

## Testing

### Testear Domain (sin BD)

```php
class ProductTest extends TestCase
{
    public function test_product_becomes_unavailable_when_out_of_stock()
    {
        $product = new Product(
            1, 'Test', 'Desc',
            new Price(10.0),
            ProductStatus::PUBLISHED,
            0 // stock = 0
        );

        $this->assertFalse($product->isAvailable());
    }

    public function test_cannot_decrease_stock_below_zero()
    {
        $product = new Product(
            1, 'Test', 'Desc',
            new Price(10.0),
            ProductStatus::PUBLISHED,
            5
        );

        $this->expectException(\DomainException::class);
        $product->decreaseStock(10);
    }
}
```

### Testear UseCase

```php
class GetProductUseCaseTest extends TestCase
{
    public function test_get_product_returns_product_data()
    {
        $mockRepository = \Mockery::mock(ProductRepository::class);
        $product = ProductFactory::create();

        $mockRepository->shouldReceive('findById')
            ->with(1)
            ->andReturn($product);

        $useCase = new GetProductUseCase($mockRepository);
        $result = $useCase->execute(new GetProductDTO(1));

        $this->assertEquals($product->getName(), $result->name);
    }
}
```

### Testear Integration (con BD real)

```php
class ProductControllerTest extends TestCase
{
    public function test_get_product_endpoint()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $product->id]);
    }
}
```

---

## Buenas Prácticas

✅ **Hazlo**:
- Mantener Domain libre de dependencias de Laravel
- Usar Value Objects para valores que tienen reglas
- Inyectar dependencias en constructor
- Separar lectura (queries) de escritura (commands)
- Testear Domain sin mock (pruebas de verdad)
- Usar DTOs para input/output de UseCases

❌ **No hagas**:
- Importar Eloquent Models en Domain
- Lógica de negocio en Controllers
- Queries complejas sin abstracción
- Múltiples responsabilidades en una clase
- UseCases muy grandes (> 50 líneas = dividir)
- Compartir DTOs entre módulos

---

## Flujo Completo: Request a Response

```
POST /api/products
{
    "name": "iPhone 15",
    "price": 999.99,
    "stock": 10
}

↓

CreateProductRequest (Validar)
    ✓ name requerido
    ✓ price >= 0

↓

ProductController->store()
    Crear CreateProductDTO

↓

CreateProductUseCase->execute()
    1. Validar datos en Domain
    2. Crear entidad Product
    3. Llamar repository->save()

↓

EloquentProductRepository->save()
    1. Map Domain → Eloquent
    2. ProductModel::create()

↓

Response: { "id": 1, "message": "Producto creado" }
```

---

## Recursos

- [Domain-Driven Design - Eric Evans](https://www.domainlanguage.com/ddd/)
- [Hexagonal Architecture - Alistair Cockburn](https://alistair.cockburn.us/hexagonal-architecture/)
- [Clean Architecture - Uncle Bob](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
