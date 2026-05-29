<x-layouts.catalog title="Shopy">
    <section class="hero">
        <div>
            <p class="eyebrow">Catalogo online</p>
            <h1>Productos seleccionados para comprar sin friccion</h1>
            <p>Explora novedades, categorias activas y productos destacados publicados desde el panel de catalogo.</p>
            <a class="button" href="{{ route('products.index') }}">Ver productos</a>
        </div>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Destacados</h2>
            <a class="section-link" href="{{ route('products.index') }}">Ver todo</a>
        </div>
        <div class="product-grid">
            @forelse ($featuredProducts as $product)
                @include('catalog.public.products.partials.card', ['product' => $product])
            @empty
                <p class="empty">Aun no hay productos destacados publicados.</p>
            @endforelse
        </div>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Categorias</h2>
        </div>
        <div class="category-grid">
            @forelse ($categories as $category)
                <a class="category-tile" href="{{ route('categories.show', $category->slug) }}">
                    <strong>{{ $category->name }}</strong>
                    <span>{{ $category->description ?: 'Ver productos' }}</span>
                </a>
            @empty
                <p class="empty">Aun no hay categorias activas.</p>
            @endforelse
        </div>
    </section>
</x-layouts.catalog>
