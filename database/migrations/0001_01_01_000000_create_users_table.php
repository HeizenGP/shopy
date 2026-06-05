<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        | Tabla base de autenticación para el módulo Access.
        | Se mantiene compatible con Laravel Auth, pero se agregan campos
        | necesarios para seguridad OWASP ASVS Nivel 2.
        */
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('email', 180)->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamp('password_changed_at')->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->index(['email', 'is_active']);
        });

        /*
        |--------------------------------------------------------------------------
        | Password Reset Tokens
        |--------------------------------------------------------------------------
        | Tabla estándar de Laravel para recuperación de contraseña.
        | Aunque no implementes recuperación todavía, se mantiene preparada.
        */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 180)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | Sessions
        |--------------------------------------------------------------------------
        | Tabla estándar de Laravel para sesiones web.
        | Se usará para el login administrativo basado en sesión.
        */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        | Tabla de roles del módulo Access.
        | Ejemplos: super_admin, admin, catalog_manager.
        */
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);
            $table->string('slug', 150)->unique();
            $table->string('description', 500)->nullable();
            $table->boolean('is_system')->default(false)->index();

            $table->timestamps();

            $table->index('name');
        });

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        | Tabla de permisos granulares.
        | Formato recomendado del slug: modulo.accion
        | Ejemplos: dashboard.view, access.manage_users, catalog.manage_products.
        */
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('module', 80)->index();
            $table->string('description', 500)->nullable();

            $table->timestamps();

            $table->index('name');
        });

        /*
        |--------------------------------------------------------------------------
        | Role User
        |--------------------------------------------------------------------------
        | Relación muchos a muchos entre usuarios y roles.
        */
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | Permission Role
        |--------------------------------------------------------------------------
        | Relación muchos a muchos entre roles y permisos.
        */
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | Access Audit Logs
        |--------------------------------------------------------------------------
        | Auditoría básica para eventos sensibles del módulo Access.
        | No se deben guardar contraseñas, tokens ni datos secretos aquí.
        */
        Schema::create('access_audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('event', 120)->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'event']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_audit_logs');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
