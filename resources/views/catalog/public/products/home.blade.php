<x-layouts.catalog title="ShopCMS">
    <section class="home-hero">
        <div class="home-copy">
            <span>Catalog + Inventory</span>
            <h1>Compra productos modernos con stock actualizado.</h1>
            <p>Una experiencia publica limpia para explorar productos, revisar detalles y pasar de catalogo a compra sin friccion.</p>
            <div class="home-actions">
                <a class="button" href="{{ route('products.index') }}">Ver catalogo</a>
                <a class="home-link" href="{{ route('products.index') }}#categorias">Explorar categorias</a>
            </div>
        </div>
        @if ($featuredProducts->isNotEmpty())
            <div class="home-preview">
                @include('catalog.public.products.partials.card', ['product' => $featuredProducts->first()])
            </div>
        @endif
    </section>

    <section class="home-section">
        <div class="section-title">
            <div>
                <p class="eyebrow">Destacados</p>
                <h2>Productos listos para vender</h2>
            </div>
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

    <section class="home-section" id="categorias">
        <div class="section-title">
            <div>
                <p class="eyebrow">Categorias</p>
                <h2>Compra por coleccion</h2>
            </div>
        </div>
        <div class="home-categories">
            @forelse ($categories as $category)
                <a class="home-category" href="{{ route('categories.show', $category->slug) }}">
                    <strong>{{ $category->name }}</strong>
                    <span>{{ $category->description ?: 'Productos con stock disponible' }}</span>
                </a>
            @empty
                <a class="home-category" href="{{ route('products.index') }}">
                    <strong>Electronics</strong>
                    <span>Wearables, audio y accesorios</span>
                </a>
            @endforelse
        </div>
    </section>
</x-layouts.catalog>
