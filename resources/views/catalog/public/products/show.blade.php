<x-layouts.catalog title="{{ $product->name }}">
    
    @php
        $firstImage = $product->images->first();
        $mainImagePath = $firstImage 
            ? (str_starts_with($firstImage->path, 'http') ? $firstImage->path : asset('storage/' . $firstImage->path)) 
            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=60';
            
        $hasDiscount = $product->sale_price && $product->sale_price < $product->regular_price;
        $activePrice = $product->sale_price ?: $product->regular_price;
        
        // Load related products from the same category
        $relatedProducts = \App\Catalog\Infrastructure\Models\ProductModel::published()
            ->where('main_category_id', $product->main_category_id)
            ->where('id', '!=', $product->id)
            ->with(['brand', 'images'])
            ->limit(4)
            ->get();
    @endphp

    <section class="product-detail-page">
        <!-- Breadcrumbs -->
        <div class="container-wrapper">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Inicio</a> / 
                <a href="{{ route('products.index') }}">Catálogo</a> / 
                <a href="{{ route('categories.show', $product->mainCategory?->slug ?? '') }}">{{ $product->mainCategory?->name ?? 'Colección' }}</a> / 
                <span>{{ $product->name }}</span>
            </div>
            
            <!-- Detail Grid Layout -->
            <div class="product-detail-grid">
                <!-- Gallery Block -->
                <div class="gallery-wrapper">
                    <div class="main-image-viewport">
                        <img id="mainProductImage" src="{{ $mainImagePath }}" alt="{{ $product->name }}">
                    </div>
                    
                    @if($product->images->count() > 1)
                        <div class="thumb-row">
                            @foreach ($product->images as $img)
                                @php
                                    $imgUrl = str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path);
                                @endphp
                                <button type="button" class="thumb-btn @if($loop->first) is-active @endif" onclick="swapMainImage('{{ $imgUrl }}', this)">
                                    <img src="{{ $imgUrl }}" alt="Miniatura">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Info Block -->
                <div class="product-info-panel">
                    <span class="product-brand-kicker">{{ $product->brand?->name ?? 'Exclusivo' }}</span>
                    <h1>{{ $product->name }}</h1>
                    
                    <!-- Rating stars -->
                    <div class="product-rating">
                        <div class="stars">
                            @for ($i = 0; $i < 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                        <span style="color: var(--color-muted);">5.0 (28 opiniones escritas)</span>
                    </div>

                    <!-- Price Box -->
                    <div class="detail-price-box">
                        <span class="detail-price" id="displayPrice">S/ {{ number_format((float)$activePrice, 2) }}</span>
                        @if($hasDiscount)
                            <span class="detail-old-price" id="displayOldPrice">S/ {{ number_format((float)$product->regular_price, 2) }}</span>
                        @else
                            <span class="detail-old-price" id="displayOldPrice" style="display: none;"></span>
                        @endif
                    </div>

                    <p class="detail-short-desc">{{ $product->short_description ?: 'Este producto destaca por su diseño ergonómico, materiales de alta calidad y rendimiento superior en cualquier ambiente de uso.' }}</p>

                    <!-- Interactive Variant Selector -->
                    @if ($product->variants->isNotEmpty())
                        <div class="option-group">
                            <span class="option-group-label">Seleccionar Opción / Variante</span>
                            <div class="variant-options-grid">
                                @foreach ($product->variants as $variant)
                                    @php
                                        $variantPrice = $variant->sale_price ?: $variant->regular_price;
                                        $vHasDiscount = $variant->sale_price && $variant->sale_price < $variant->regular_price;
                                    @endphp
                                    <button type="button" 
                                            class="variant-btn @if($loop->first) is-selected @endif" 
                                            data-variant-name="{{ $variant->name }}"
                                            data-variant-sku="{{ $variant->sku }}"
                                            data-variant-price="{{ $variantPrice }}"
                                            data-variant-regular="{{ $variant->regular_price }}"
                                            data-variant-discount="{{ $vHasDiscount ? 'true' : 'false' }}"
                                            onclick="selectVariant(this)">
                                        {{ $variant->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Purchase Actions -->
                    <div class="buy-panel">
                        <div class="quantity-wrapper">
                            <label for="detailQty">Cantidad:</label>
                            <div class="qty-selector">
                                <button type="button" onclick="changeDetailQty(-1)">—</button>
                                <span id="detailQtyVal">1</span>
                                <button type="button" onclick="changeDetailQty(1)">+</button>
                            </div>
                        </div>
                        
                        <div class="buy-buttons-row">
                            <button type="button" class="button" onclick="triggerAddToCart()">Agregar al Carrito</button>
                            <button type="button" class="button buy-now-btn" onclick="triggerBuyNow()">Comprar Ahora</button>
                        </div>
                    </div>

                    <!-- Metadata Specs Box -->
                    <div class="product-metadata-box">
                        <div>
                            <span>SKU:</span>
                            <span id="metaSku">{{ $product->sku ?: 'Sin SKU' }}</span>
                        </div>
                        <div>
                            <span>Colección:</span>
                            <span>{{ $product->mainCategory?->name ?? 'Sin categoría' }}</span>
                        </div>
                        <div>
                            <span>Disponibilidad:</span>
                            <span style="color: var(--color-success);">En Stock (Despacho inmediato)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="product-details-tabs">
                <div class="tabs-header">
                    <button class="tab-link is-active" onclick="switchTab(event, 'tab-description')">Descripción Detallada</button>
                    <button class="tab-link" onclick="switchTab(event, 'tab-specs')">Especificaciones</button>
                    <button class="tab-link" onclick="switchTab(event, 'tab-shipping')">Envíos y Retornos</button>
                </div>
                
                <div class="tab-panel is-active" id="tab-description">
                    <p>{{ $product->description ?: 'No hay una descripción detallada disponible para este producto en este momento. Por favor comunícate con nuestro canal de soporte para resolver cualquier inquietud técnica.' }}</p>
                </div>
                
                <div class="tab-panel" id="tab-specs">
                    <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main); padding: 1.5rem;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                            <tr style="border-bottom: 1px solid var(--color-border);">
                                <td style="padding: 0.75rem 0; font-weight: 700; color: var(--color-muted); width: 30%;">Marca</td>
                                <td style="padding: 0.75rem 0;">{{ $product->brand?->name ?? 'Nova' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--color-border);">
                                <td style="padding: 0.75rem 0; font-weight: 700; color: var(--color-muted);">Modelo</td>
                                <td style="padding: 0.75rem 0;">{{ $product->sku ?: 'Default Model' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--color-border);">
                                <td style="padding: 0.75rem 0; font-weight: 700; color: var(--color-muted);">Garantía</td>
                                <td style="padding: 0.75rem 0;">12 Meses Oficial</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.75rem 0; font-weight: 700; color: var(--color-muted);">Condición</td>
                                <td style="padding: 0.75rem 0;">Nuevo en Caja Sellada</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="tab-panel" id="tab-shipping">
                    <p>Ofrecemos despachos express a domicilio a nivel nacional. Los envíos en la capital se realizan en un plazo máximo de 24 horas útiles de lunes a sábado.</p>
                    <p><strong>Políticas de Devolución:</strong> Tienes hasta 7 días calendario posteriores a la entrega para solicitar un cambio o reembolso si el producto presenta algún desperfecto o no cumple tus expectativas, siempre y cuando se mantenga el empaque original intacto.</p>
                </div>
            </div>

            <!-- Related Products Section -->
            @if ($relatedProducts->isNotEmpty())
                <div style="margin-top: 5rem;">
                    <div class="section-title">
                        <div>
                            <p class="eyebrow">Recomendaciones</p>
                            <h2>Productos Relacionados</h2>
                        </div>
                    </div>
                    <div class="product-grid">
                        @foreach ($relatedProducts as $relProduct)
                            @include('catalog.public.products.partials.card', ['product' => $relProduct])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Page Specific Script -->
    <script>
        let selectedVariantName = null;
        
        // Initialize default variant if exists
        window.addEventListener('DOMContentLoaded', () => {
            const defaultVariantBtn = document.querySelector('.variant-btn.is-selected');
            if (defaultVariantBtn) {
                selectedVariantName = defaultVariantBtn.getAttribute('data-variant-name');
            }
        });

        function swapMainImage(url, btn) {
            document.getElementById('mainProductImage').src = url;
            document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
        }

        function selectVariant(btn) {
            document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('is-selected'));
            btn.classList.add('is-selected');
            
            selectedVariantName = btn.getAttribute('data-variant-name');
            const sku = btn.getAttribute('data-variant-sku');
            const price = parseFloat(btn.getAttribute('data-variant-price'));
            const regPrice = parseFloat(btn.getAttribute('data-variant-regular'));
            const hasDiscount = btn.getAttribute('data-variant-discount') === 'true';
            
            // Update labels
            document.getElementById('metaSku').innerText = sku;
            document.getElementById('displayPrice').innerText = 'S/ ' + price.toFixed(2);
            
            const oldPriceEl = document.getElementById('displayOldPrice');
            if (hasDiscount) {
                oldPriceEl.style.display = 'inline';
                oldPriceEl.innerText = 'S/ ' + regPrice.toFixed(2);
            } else {
                oldPriceEl.style.display = 'none';
            }
        }

        let currentQty = 1;
        function changeDetailQty(change) {
            currentQty += change;
            if (currentQty < 1) currentQty = 1;
            document.getElementById('detailQtyVal').innerText = currentQty;
        }

        function triggerAddToCart() {
            const product = {
                id: {{ $product->id }},
                name: '{{ addslashes($product->name) }}',
                price: parseFloat(document.getElementById('displayPrice').innerText.replace('S/ ', '')),
                formattedPrice: document.getElementById('displayPrice').innerText,
                slug: '{{ $product->slug }}',
                image: document.getElementById('mainProductImage').src
            };
            
            addToCart(product, currentQty, selectedVariantName);
        }

        function triggerBuyNow() {
            triggerAddToCart();
            // Redirect immediately to checkout
            window.location.href = "{{ route('checkout') }}";
        }

        function switchTab(evt, tabId) {
            document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('is-active'));
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('is-active'));
            
            evt.currentTarget.classList.add('is-active');
            document.getElementById(tabId).classList.add('is-open', 'is-active');
        }
    </script>

</x-layouts.catalog>
