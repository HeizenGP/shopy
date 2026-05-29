<x-layouts.catalog title="{{ $product->name }}">
    <section class="product-detail">
        <div class="product-media">
            <span>{{ strtoupper(substr($product->name, 0, 2)) }}</span>
        </div>
        <div class="product-info">
            <p class="eyebrow">{{ $product->brand?->name ?? 'Producto' }}</p>
            <h1>{{ $product->name }}</h1>
            @if ($product->sku)
                <span class="sku">SKU {{ $product->sku }}</span>
            @endif
            <p>{{ $product->short_description }}</p>
            <strong class="price">{{ $product->formattedPrice() }}</strong>
            <div class="meta-row">
                @foreach ($product->categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                @endforeach
            </div>
            @if ($product->variants->isNotEmpty())
                <h2>Variantes</h2>
                <div class="variant-list">
                    @foreach ($product->variants as $variant)
                        <span>{{ $variant->name }} · {{ $variant->sku }}</span>
                    @endforeach
                </div>
            @endif
            <article>{{ $product->description }}</article>
        </div>
    </section>
</x-layouts.catalog>
