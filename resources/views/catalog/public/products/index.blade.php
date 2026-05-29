<x-layouts.catalog title="Productos">
    <section class="section">
        <div class="section-title">
            <div>
                <p class="eyebrow">Tienda</p>
                <h1>Productos</h1>
            </div>
        </div>
        <div class="product-grid">
            @forelse ($products as $product)
                @include('catalog.public.products.partials.card', ['product' => $product])
            @empty
                <p class="empty">Aun no hay productos publicados.</p>
            @endforelse
        </div>
        {{ $products->links() }}
    </section>
</x-layouts.catalog>
