<x-layouts.catalog title="{{ $category->name }}">
    <section class="category-page">
        <div class="category-hero">
            <h1>Categoria: {{ $category->name }}</h1>
            <p>{{ $category->description ?: 'Tecnologia, accesorios y gadgets con stock actualizado desde Catalog + Inventory.' }}</p>
            <a class="button" href="{{ route('products.index') }}#ofertas">Ver ofertas</a>
        </div>

        <div class="category-products">
            @forelse ($products as $product)
                <a class="category-product-card" href="{{ route('products.show', $product->slug) }}">
                    <div class="mini-thumb"></div>
                    <div>
                        <h3>{{ $product->name }}</h3>
                        <span>{{ $product->mainCategory?->name ?? $category->name }}</span>
                        <strong>{{ $product->formattedPrice() }}</strong>
                        <small>{{ $product->status->label() }}</small>
                    </div>
                </a>
            @empty
                <p class="empty">Esta categoria aun no tiene productos publicados.</p>
            @endforelse
        </div>
        {{ $products->links() }}
    </section>
</x-layouts.catalog>
