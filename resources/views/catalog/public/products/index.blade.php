<x-layouts.catalog title="Catálogo de Productos">
    
    @php
        // Dynamically load all active categories and brands for filtering
        $allCategories = \App\Catalog\Infrastructure\Models\CategoryModel::where('is_active', true)->orderBy('name')->get();
        $allBrands = \App\Catalog\Infrastructure\Models\BrandModel::where('is_active', true)->orderBy('name')->get();
        
        // Check if there is an active search query from request
        $searchQuery = request('search', '');
        $isFeaturedFilter = request('featured', '');
    @endphp

    <section class="shop-page">
        <div class="shop-heading">
            <h1>Catálogo de Productos</h1>
            <p>Descubre nuestra colección curada con stock actualizado en tiempo real. Filtra por categoría, rango de precios o marca.</p>
        </div>

        <div class="container-wrapper">
            <div class="catalog-layout">
                <!-- Sidebar Filters -->
                <aside class="filter-panel">
                    <h2>Filtros</h2>
                    
                    <!-- Search Input -->
                    <div class="option-group">
                        <label class="filter-group-label" for="catalogSearch">Buscar</label>
                        <input type="text" id="catalogSearch" placeholder="Nombre, SKU o marca..." value="{{ $searchQuery }}" onkeyup="filterProducts()">
                    </div>
                    
                    <!-- Categories Checklist -->
                    <fieldset>
                        <legend>Categorías</legend>
                        <div class="filter-checkbox-list">
                            @forelse ($allCategories as $cat)
                                <label>
                                    <input type="checkbox" class="category-filter" value="{{ $cat->slug }}" onchange="filterProducts()">
                                    {{ $cat->name }}
                                </label>
                            @empty
                                <span class="filter-empty">Sin categorías</span>
                            @endforelse
                        </div>
                    </fieldset>
                    
                    <!-- Brands Checklist -->
                    <fieldset>
                        <legend>Marcas</legend>
                        <div class="filter-checkbox-list">
                            @forelse ($allBrands as $br)
                                <label>
                                    <input type="checkbox" class="brand-filter" value="{{ $br->slug }}" onchange="filterProducts()">
                                    {{ $br->name }}
                                </label>
                            @empty
                                <span class="filter-empty">Sin marcas</span>
                            @endforelse
                        </div>
                    </fieldset>
                    
                    <!-- Price Range Slider -->
                    <div class="range-slider-wrapper">
                        <label class="filter-group-label" for="priceRange">Precio Máximo</label>
                        <input class="range-input" type="range" id="priceRange" min="0" max="500" value="450" oninput="updatePriceLabel(this.value); filterProducts();">
                        <div class="range-values">
                            <span>S/ 0.00</span>
                            <span id="priceMaxLabel" style="color: var(--color-primary); font-weight: 800;">S/ 450.00</span>
                        </div>
                    </div>
                    
                    <button class="button secondary-btn" type="button" style="width: 100%;" onclick="clearFilters()">Limpiar Filtros</button>
                </aside>

                <!-- Grid and Results Toolbar -->
                <div class="catalog-results">
                    <div class="catalog-toolbar">
                        <strong><span id="resultsCount">{{ $products->total() }}</span> productos encontrados</strong>
                        
                        <div class="toolbar-controls">
                            <!-- Sort Selector -->
                            <label for="sortSelect" style="font-size: 0.8rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase;">Ordenar por</label>
                            <select id="sortSelect" onchange="sortProducts(this.value)">
                                <option value="default">Destacados</option>
                                <option value="price-asc">Precio: Menor a Mayor</option>
                                <option value="price-desc">Precio: Mayor a Menor</option>
                                <option value="name-asc">Nombre: A - Z</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Product Cards Grid -->
                    <div class="product-grid" id="catalogProductGrid">
                        @forelse ($products as $product)
                            @php
                                $hasDiscount = $product->sale_price && $product->sale_price < $product->regular_price;
                                $activePrice = $product->sale_price ?: $product->regular_price;
                                $firstImage = $product->images->first();
                                $imagePath = $firstImage 
                                    ? (str_starts_with($firstImage->path, 'http') ? $firstImage->path : asset('storage/' . $firstImage->path)) 
                                    : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=60';
                                
                                $discountPercentage = 0;
                                if ($hasDiscount && $product->regular_price > 0) {
                                    $discountPercentage = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                                }
                            @endphp
                            
                            <!-- Wraps card with filter attributes -->
                            <div class="product-card-wrapper" 
                                 data-name="{{ strtolower($product->name) }}"
                                 data-price="{{ $activePrice }}"
                                 data-category="{{ $product->mainCategory?->slug ?? '' }}"
                                 data-brand="{{ $product->brand?->slug ?? '' }}"
                                 data-featured="{{ $product->is_featured ? 'true' : 'false' }}">
                                
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
                                            price: {{ $activePrice }},
                                            formattedPrice: 'S/ {{ number_format((float)$activePrice, 2) }}',
                                            slug: '{{ $product->slug }}',
                                            image: '{{ $imagePath }}'
                                        }, 1)">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="grid-column: span 3; text-align: center; padding: 4rem 0; color: var(--color-muted);">Aún no hay productos publicados.</p>
                        @endforelse
                    </div>
                    
                    <!-- Paginator Links -->
                    <div class="pagination-nav">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client-side Interactive Filter Script -->
    <script>
        function updatePriceLabel(value) {
            document.getElementById('priceMaxLabel').innerText = 'S/ ' + parseFloat(value).toFixed(2);
        }

        function filterProducts() {
            const searchQuery = document.getElementById('catalogSearch').value.toLowerCase();
            const maxPrice = parseFloat(document.getElementById('priceRange').value);
            
            // Get selected categories
            const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked'))
                .map(cb => cb.value);
            
            // Get selected brands
            const selectedBrands = Array.from(document.querySelectorAll('.brand-filter:checked'))
                .map(cb => cb.value);
                
            const wrappers = document.querySelectorAll('.product-card-wrapper');
            let visibleCount = 0;
            
            wrappers.forEach(wrap => {
                const name = wrap.getAttribute('data-name');
                const price = parseFloat(wrap.getAttribute('data-price'));
                const category = wrap.getAttribute('data-category');
                const brand = wrap.getAttribute('data-brand');
                
                // Matches filters
                const matchesSearch = name.includes(searchQuery);
                const matchesPrice = price <= maxPrice;
                const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category);
                const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(brand);
                
                if (matchesSearch && matchesPrice && matchesCategory && matchesBrand) {
                    wrap.style.display = 'block';
                    visibleCount++;
                } else {
                    wrap.style.display = 'none';
                }
            });
            
            document.getElementById('resultsCount').innerText = visibleCount;
        }

        function sortProducts(sortBy) {
            const grid = document.getElementById('catalogProductGrid');
            const wrappers = Array.from(grid.querySelectorAll('.product-card-wrapper'));
            
            if (sortBy === 'default') {
                // Keep original seeded order (no action or reload)
                return;
            }
            
            wrappers.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                const nameA = a.getAttribute('data-name');
                const nameB = b.getAttribute('data-name');
                
                if (sortBy === 'price-asc') return priceA - priceB;
                if (sortBy === 'price-desc') return priceB - priceA;
                if (sortBy === 'name-asc') return nameA.localeCompare(nameB);
                return 0;
            });
            
            // Re-append in new order
            wrappers.forEach(wrap => grid.appendChild(wrap));
        }

        function clearFilters() {
            document.getElementById('catalogSearch').value = '';
            document.getElementById('priceRange').value = 500;
            updatePriceLabel(500);
            
            document.querySelectorAll('.category-filter').forEach(cb => cb.checked = false);
            document.querySelectorAll('.brand-filter').forEach(cb => cb.checked = false);
            document.getElementById('sortSelect').value = 'default';
            
            // Reset styles
            const wrappers = document.querySelectorAll('.product-card-wrapper');
            wrappers.forEach(wrap => wrap.style.display = 'block');
            document.getElementById('resultsCount').innerText = wrappers.length;
            
            showToast('Filtros limpiados', 'info');
        }

        // Initialize features on load
        window.addEventListener('DOMContentLoaded', () => {
            const searchVal = document.getElementById('catalogSearch').value;
            if (searchVal) {
                filterProducts();
            }
            
            // Check if there is an active featured query
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('featured')) {
                const wrappers = document.querySelectorAll('.product-card-wrapper');
                let count = 0;
                wrappers.forEach(wrap => {
                    if (wrap.getAttribute('data-featured') === 'true') {
                        wrap.style.display = 'block';
                        count++;
                    } else {
                        wrap.style.display = 'none';
                    }
                });
                document.getElementById('resultsCount').innerText = count;
            }
        });
    </script>
</x-layouts.catalog>
