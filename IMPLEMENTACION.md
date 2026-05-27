# 🛠️ Implementación de Login: SOLID + Arquitectura Hexagonal + Vertical Slicing

Este documento detalla la estructura y el diseño del módulo de Autenticación (`Auth`) del proyecto **Shopy**, desarrollado utilizando **Arquitectura Hexagonal**, **Vertical Slicing** y adhiriéndose rigurosamente a los principios **SOLID**.

---

## 🏗️ Estructura del Módulo (`Vertical Slicing`)

Cada módulo en la aplicación debe ser autónomo. La carpeta `app/Modules/Auth` agrupa todo el código relacionado con la autenticación, dividido en las cuatro capas hexagonales clásicas:

```
app/Modules/Auth/
├── Domain/                         # Capa de Dominio (Reglas de negocio puras, agnóstica de frameworks)
│   ├── Entities/
│   │   └── AuthUser.php            # Entidad que representa al usuario autenticable
│   ├── ValueObjects/
│   │   ├── Email.php               # VO para validación de formato de correo
│   │   └── Password.php            # VO para el manejo seguro de passwords
│   ├── Repositories/
│   │   └── AuthUserRepository.php  # [PUERTO] Contrato de persistencia de usuarios
│   ├── Services/
│   │   └── PasswordHasher.php      # [PUERTO] Contrato para encriptar/verificar contraseñas
│   └── Exceptions/
│       └── InvalidCredentialsException.php # Excepción específica de dominio
│
├── Application/                    # Capa de Aplicación (Casos de uso y flujos de la app)
│   ├── Ports/
│   │   └── AuthSessionManager.php  # [PUERTO] Contrato para interactuar con la sesión HTTP
│   ├── DTOs/
│   │   ├── LoginInputDTO.php       # DTO para recibir los parámetros del controlador
│   │   └── LoginOutputDTO.php      # DTO para retornar la respuesta del caso de uso
│   └── UseCases/
│       ├── LoginUseCase.php        # Orquesta el inicio de sesión
│       └── LogoutUseCase.php       # Orquesta el cierre de sesión
│
├── Infrastructure/                 # Capa de Infraestructura (Detalles técnicos y adaptadores concretos)
│   ├── Adapters/
│   │   ├── EloquentAuthUserRepository.php # [ADAPTADOR] Implementación usando Laravel Eloquent
│   │   ├── LaravelPasswordHasher.php      # [ADAPTADOR] Encriptador usando Hash Facade
│   │   └── LaravelSessionAuthManager.php  # [ADAPTADOR] Gestión de sesión usando Auth Facade
│   └── Providers/
│       └── AuthServiceProvider.php # Inyección de dependencias de este módulo y carga de rutas
│
└── Presentation/                   # Capa de Presentación (Controladores HTTP, validación y rutas)
    ├── Controllers/
    │   └── LoginController.php     # Endpoint HTTP / API
    ├── Requests/
    │   └── LoginRequest.php        # FormRequest para validar datos HTTP antes del DTO
    └── Routes/
        └── api.php                 # Rutas asociadas a la autenticación
```

---

## 💎 Cumplimiento de Principios SOLID

### 1. **S**ingle Responsibility Principle (SRP)
Cada clase tiene una única razón para cambiar:
- **`Email`**: Se encarga exclusivamente de verificar y mantener la consistencia de una dirección de correo válida.
- **`LoginUseCase`**: Su única responsabilidad es coordinar el flujo de negocio del login (buscar usuario, verificar contraseña, iniciar sesión). No sabe nada de HTTP ni de bases de datos.
- **`LoginController`**: Solo procesa la petición HTTP entrante, invoca el caso de uso y devuelve la respuesta HTTP formateada.

### 2. **O**pen/Closed Principle (OCP)
El diseño está abierto a la extensión pero cerrado a la modificación gracias al uso de interfaces (Puertos).
- Si el día de mañana queremos cambiar la base de datos de Eloquent a una base de datos externa vía API REST, creamos un nuevo adaptador `RestAuthUserRepository` que implemente la interfaz `AuthUserRepository`. El caso de uso (`LoginUseCase`) continuará funcionando exactamente igual sin alterar una sola línea de código.

### 3. **L**iskov Substitution Principle (LSP)
Los adaptadores implementan interfaces estrictas de dominio.
- `EloquentAuthUserRepository` puede ser sustituido por cualquier otra implementación de `AuthUserRepository` en tiempo de ejecución sin alterar el comportamiento semántico del sistema ni provocar fallos inesperados en el cliente de la interfaz.

### 4. **I**nterface Segregation Principle (ISP)
Las interfaces de dominio son altamente enfocadas y no obligan a los adaptadores a implementar métodos que no necesitan.
- `PasswordHasher` sólo define `hash` y `verify`.
- `AuthSessionManager` sólo define `login` y `logout`.

### 5. **D**ependency Inversion Principle (DIP)
- Las capas internas de alto nivel (Dominio y Aplicación) no dependen de las capas externas de bajo nivel (Infraestructura y Laravel).
- En su lugar, la Infraestructura se acopla a las interfaces definidas en el Dominio/Aplicación. `LoginUseCase` depende de las interfaces `AuthUserRepository`, `PasswordHasher` y `AuthSessionManager`. Laravel inyecta automáticamente los adaptadores concretos a través de `AuthServiceProvider`.

---

## 🧪 Estrategia de Testing

El flujo de autenticación estará cubierto por tres niveles de pruebas (con **Pest PHP**):

1. **Unitarias de Dominio (Domain)**:
   - Verifican que los Value Objects y la Entidad `AuthUser` se comporten correctamente sin inicializar Laravel ni tocar bases de datos.
2. **Unitarias de Aplicación (Use Cases)**:
   - Verifican la lógica del `LoginUseCase` utilizando dobles de prueba (Mocks/Doubles) para los puertos, asegurando que las combinaciones correctas de credenciales inicien sesión y que las incorrectas lancen la excepción adecuada.
3. **Integración/Funcionales (Presentation & Infrastructure)**:
   - Verifican el flujo completo HTTP realizando una llamada `POST /api/auth/login` real y asegurando el comportamiento del controlador, adaptadores y base de datos SQLite integrada.
