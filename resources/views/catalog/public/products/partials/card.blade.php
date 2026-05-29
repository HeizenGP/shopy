<a class="product-card" href="{{ route('products.show', $product->slug) }}">
    <div class="product-thumb">PRODUCT</div>
    <div>
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->short_description }}</p>
        <strong class="card-price">{{ $product->formattedPrice() }}</strong>
        <div class="card-footer">
            <span class="stock-text">{{ $product->status->label() }}</span>
            <span class="detail-button">Ver detalle</span>
        </div>
    </div>
</a>
