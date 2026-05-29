<x-layouts.catalog title="Productos">
    @php
        $visibleCategories = $products->getCollection()
            ->pluck('mainCategory')
            ->filter()
            ->unique('id');
    @endphp

    <section class="shop-page">
        <div class="shop-heading">
            <h1>Catalogo de productos</h1>
            <p>Explora productos, filtra por categoria, precio, marca y disponibilidad.</p>
        </div>

        <div class="catalog-layout">
            <aside class="filter-panel">
                <h2>Filtros</h2>
                <label>Buscar producto
                    <input placeholder="Nombre, SKU...">
                </label>
                <fieldset>
                    <legend>Categorias</legend>
                    @forelse ($visibleCategories as $category)
                        <label><input type="checkbox"> {{ $category->name }}</label>
                    @empty
                        <span class="filter-empty">Sin categorias disponibles</span>
                    @endforelse
                </fieldset>
                <label>Rango de precio
                    <input class="range-input" type="range" min="0" max="500" value="320">
                </label>
                <button class="button" type="button">Aplicar filtros</button>
            </aside>

            <div class="catalog-results">
                <div class="catalog-toolbar">
                    <strong>{{ $products->total() }} productos encontrados</strong>
                    <span>En stock</span>
                    <small>Orden: destacados</small>
                </div>
                <div class="product-grid">
                    @forelse ($products as $product)
                        @include('catalog.public.products.partials.card', ['product' => $product])
                    @empty
                        <p class="empty">Aun no hay productos publicados.</p>
                    @endforelse
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </section>
</x-layouts.catalog>
