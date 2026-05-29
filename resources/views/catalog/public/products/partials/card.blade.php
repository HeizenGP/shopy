<a class="product-card" href="{{ route('products.show', $product->slug) }}">
    <div class="product-thumb">{{ strtoupper(substr($product->name, 0, 2)) }}</div>
    <div>
        <span class="card-kicker">{{ $product->mainCategory?->name ?? $product->brand?->name ?? 'Catalogo' }}</span>
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->short_description }}</p>
        <strong class="card-price">{{ $product->formattedPrice() }}</strong>
    </div>
</a>
