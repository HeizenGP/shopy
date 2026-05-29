<x-layouts.catalog title="{{ $product->name }}">
    <section class="product-detail-page">
        <div class="breadcrumb">Productos / {{ $product->mainCategory?->name ?? 'Catalogo' }} / {{ $product->name }}</div>

        <div class="product-detail">
            <div class="gallery-card">
                <div class="product-media">
                    <span>{{ strtoupper($product->name) }}</span>
                </div>
                <div class="thumb-row">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                <span class="stock-pill">{{ $product->status->label() }}</span>
                <strong class="price">{{ $product->formattedPrice() }}</strong>
                <p>{{ $product->short_description }}</p>

                @if ($product->variants->isNotEmpty())
                    <div class="option-group">
                        <span>Variantes</span>
                        <div>
                            @foreach ($product->variants as $variant)
                                <button type="button" @class(['option', 'is-selected' => $loop->first])>{{ $variant->name }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="buy-row">
                    <label>Cantidad
                        <input type="number" min="1" value="1">
                    </label>
                    <button type="button" class="button">Agregar al carrito</button>
                    <button type="button" class="button buy-now">Comprar ahora</button>
                </div>

                <div class="info-box">
                    <h2>Informacion del producto</h2>
                    <p>SKU: {{ $product->sku ?: 'Sin SKU' }}</p>
                    <p>Categoria: {{ $product->mainCategory?->name ?? 'Sin categoria' }}</p>
                    <p>Marca: {{ $product->brand?->name ?? 'Sin marca' }}</p>
                    <p>Variantes: {{ $product->variants->count() }}</p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.catalog>
