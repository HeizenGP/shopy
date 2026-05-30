@php
    $firstImage = $product->images->first();
    $imagePath = $firstImage 
        ? (str_starts_with($firstImage->path, 'http') ? $firstImage->path : asset('storage/' . $firstImage->path)) 
        : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=60';

    $hasDiscount = $product->sale_price && $product->sale_price < $product->regular_price;
    $discountPercentage = 0;
    if ($hasDiscount && $product->regular_price > 0) {
        $discountPercentage = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
    }
@endphp

<div class="product-card">
    <a href="{{ route('products.show', $product->slug) }}" style="display: block; color: inherit;">
        <div class="product-thumb">
            @if($hasDiscount)
                <span class="card-discount-badge">-{{ $discountPercentage }}%</span>
            @endif
            <img src="{{ $imagePath }}" alt="{{ $product->name }}" loading="lazy">
        </div>
        
        <div class="card-kicker">
            {{ $product->brand?->name ?? 'Exclusivo' }}
        </div>
        
        <h3>{{ $product->name }}</h3>
        
        <!-- Star Rating Mockup -->
        <div class="product-rating" style="margin-bottom: 0.5rem;">
            <div class="stars">
                @for ($i = 0; $i < 5; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                @endfor
            </div>
            <span style="color: var(--color-muted); font-size: 0.78rem;">(4.9)</span>
        </div>

        <div class="price-row-card">
            @if($hasDiscount)
                <span class="card-price">S/ {{ number_format((float)$product->sale_price, 2) }}</span>
                <span class="card-old-price">S/ {{ number_format((float)$product->regular_price, 2) }}</span>
            @else
                <span class="card-price">S/ {{ number_format((float)$product->regular_price, 2) }}</span>
            @endif
        </div>
    </a>

    <div class="card-footer">
        <span class="stock-badge">
            <span class="stock-dot"></span>
            En Stock
        </span>
        <button type="button" class="detail-button" onclick="event.preventDefault(); addToCart({
            id: {{ $product->id }},
            name: '{{ addslashes($product->name) }}',
            price: {{ $product->sale_price ?: $product->regular_price }},
            formattedPrice: 'S/ {{ number_format((float)($product->sale_price ?: $product->regular_price), 2) }}',
            slug: '{{ $product->slug }}',
            image: '{{ $imagePath }}'
        }, 1)">
            Agregar
        </button>
    </div>
</div>
