<x-layouts.catalog title="{{ $category->name }}">
    <section class="section">
        <div class="section-title">
            <div>
                <p class="eyebrow">Categoria</p>
                <h1>{{ $category->name }}</h1>
                <p>{{ $category->description }}</p>
            </div>
        </div>
        <div class="product-grid">
            @forelse ($products as $product)
                @include('catalog.public.products.partials.card', ['product' => $product])
            @empty
                <p class="empty">Esta categoria aun no tiene productos publicados.</p>
            @endforelse
        </div>
        {{ $products->links() }}
    </section>
</x-layouts.catalog>
