<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        | Categorías del catálogo. Soporta jerarquía: categoría padre / subcategoría.
        */
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();

            $table->string('image_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | Brands
        |--------------------------------------------------------------------------
        | Marcas asociadas a productos.
        */
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();

            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | Product Attributes
        |--------------------------------------------------------------------------
        | Ejemplo: Color, Talla, Material, Capacidad.
        */
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);
            $table->string('slug', 150)->unique();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Product Attribute Values
        |--------------------------------------------------------------------------
        | Ejemplo: Color -> Negro, Blanco, Azul.
        */
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attribute_id')
                ->constrained('product_attributes')
                ->cascadeOnDelete();

            $table->string('value', 120);
            $table->string('slug', 150);

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['attribute_id', 'slug'], 'attribute_value_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        | Producto principal del catálogo.
        */
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->foreignId('main_category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('name', 180);
            $table->string('slug', 220)->unique();
            $table->string('sku', 100)->nullable()->unique();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('regular_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->nullable();

            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            $table->boolean('is_featured')->default(false);
            $table->boolean('has_variants')->default(false);

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_featured');
            $table->index('brand_id');
            $table->index('main_category_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Product Category Pivot
        |--------------------------------------------------------------------------
        | Permite que un producto pertenezca a varias categorías.
        */
        Schema::create('product_category', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['product_id', 'category_id'], 'product_category_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Product Variants
        |--------------------------------------------------------------------------
        | Variantes comerciales: color/talla/capacidad.
        */
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('name', 180);
            $table->string('sku', 120)->unique();

            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();

            $table->decimal('weight', 10, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('is_active');
        });

        /*
        |--------------------------------------------------------------------------
        | Product Variant Attribute Values
        |--------------------------------------------------------------------------
        | Relaciona variantes con valores de atributos.
        | Ejemplo: Variante A -> Color Negro, Talla M.
        */
        Schema::create('product_variant_attribute_value', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->cascadeOnDelete();

            $table->foreignId('attribute_value_id')
                ->constrained('product_attribute_values')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['product_variant_id', 'attribute_value_id'],
                'variant_attribute_value_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        | Imágenes del producto y opcionalmente de una variante.
        */
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            $table->string('path');
            $table->string('alt_text')->nullable();

            $table->boolean('is_main')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('product_id');
            $table->index('product_variant_id');
            $table->index('is_main');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variant_attribute_value');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
