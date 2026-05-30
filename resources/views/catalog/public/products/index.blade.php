<x-layouts.catalog title="Catálogo de Productos">
    
    @php
        // Load all active categories and brands for the sidebar checkboxes
        $allCategories = \App\Catalog\Infrastructure\Models\CategoryModel::where('is_active', true)->orderBy('name')->get();
        $allBrands = \App\Catalog\Infrastructure\Models\BrandModel::where('is_active', true)->orderBy('name')->get();
        
        // Retrieve current active filters from the request
        $searchQuery = request('search', '');
        $selectedCategories = (array) request('category', []);
        $selectedBrands = (array) request('brand', []);
        $maxPrice = request('max_price', 500);
        $activeSort = request('sort', 'latest');
    @endphp

    <section class="shop-page">
        <div class="shop-heading">
            <h1>Catálogo de Productos</h1>
            <p>Descubre nuestra colección curada con stock actualizado en tiempo real. Filtra por categoría, rango de precios o marca.</p>
        </div>

        <div class="container-wrapper">
            <div class="catalog-search-bar">
                <label class="filter-group-label" for="catalogSearch">Buscar productos</label>
                <div class="catalog-search-control">
                    <input type="text" form="filterForm" name="search" id="catalogSearch" placeholder="Nombre, SKU, marca o descripción del producto..." value="{{ $searchQuery }}">
                    <button type="submit" form="filterForm" class="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Buscar
                    </button>
                </div>
            </div>

            <div class="catalog-layout">
                <!-- Sidebar Filters Form (Connected to Backend) -->
                <form id="filterForm" action="{{ route('products.index') }}" method="GET" class="filter-panel">
                    <h2>Filtros</h2>
                    
                    <!-- Categories Checklist -->
                    <fieldset>
                        <legend>Categorías</legend>
                        <div class="filter-checkbox-list">
                            @forelse ($allCategories as $cat)
                                <label>
                                    <input type="checkbox" name="category[]" value="{{ $cat->slug }}" onchange="this.form.submit()" @checked(in_array($cat->slug, $selectedCategories))>
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
                                    <input type="checkbox" name="brand[]" value="{{ $br->slug }}" onchange="this.form.submit()" @checked(in_array($br->slug, $selectedBrands))>
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
                        <input class="range-input" type="range" name="max_price" id="priceRange" min="0" max="500" value="{{ $maxPrice }}" oninput="updatePriceLabel(this.value);" onchange="this.form.submit()">
                        <div class="range-values">
                            <span>S/ 0.00</span>
                            <span id="priceMaxLabel" style="color: var(--color-primary); font-weight: 800;">S/ {{ number_format((float)$maxPrice, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Keep featured parameter if active -->
                    @if(request()->has('featured'))
                        <input type="hidden" name="featured" value="1">
                    @endif

                    <!-- Hidden sort input, filled and submitted by toolbar dropdown -->
                    <input type="hidden" name="sort" id="formSortInput" value="{{ $activeSort }}">

                    <a href="{{ route('products.index') }}" class="button secondary-btn" style="width: 100%; text-align: center; display: block;">Limpiar Filtros</a>
                </form>

                <!-- Grid and Results Toolbar -->
                <div class="catalog-results">
                    <div class="catalog-toolbar">
                        <strong><span>{{ $products->total() }}</span> productos encontrados</strong>
                        
                        <div class="toolbar-controls">
                            <!-- Sort Selector -->
                            <label for="sortSelect" style="font-size: 0.8rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase;">Ordenar por</label>
                            <select id="sortSelect" onchange="sortProducts(this.value)">
                                <option value="latest" @selected($activeSort === 'latest')>Novedades</option>
                                <option value="price-asc" @selected($activeSort === 'price-asc')>Precio: Menor a Mayor</option>
                                <option value="price-desc" @selected($activeSort === 'price-desc')>Precio: Mayor a Menor</option>
                                <option value="name-asc" @selected($activeSort === 'name-asc')>Nombre: A - Z</option>
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
                        @empty
                            <div style="grid-column: span 3; text-align: center; padding: 5rem 0; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-muted); margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.25rem;">Sin resultados</h3>
                                <p style="color: var(--color-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">No se encontraron productos que coincidan con los filtros seleccionados.</p>
                                <a href="{{ route('products.index') }}" class="button">Limpiar Filtros</a>
                            </div>
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

    <!-- Client-side script connecting toolbar sorting and slider labels to the form -->
    <script>
        function updatePriceLabel(value) {
            document.getElementById('priceMaxLabel').innerText = 'S/ ' + parseFloat(value).toFixed(2);
        }

        function sortProducts(sortBy) {
            document.getElementById('formSortInput').value = sortBy;
            document.getElementById('filterForm').submit();
        }
    </script>
</x-layouts.catalog>
