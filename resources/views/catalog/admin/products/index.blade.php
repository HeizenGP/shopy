<x-layouts.admin title="Productos">
    @php
        $adminUser = auth()->user();
        $canCreateProducts = $adminUser?->hasPermission('catalog.create');
        $canUpdateProducts = $adminUser?->hasPermission('catalog.update');
        $canDeleteProducts = $adminUser?->hasPermission('catalog.delete');
        $visibleProducts = $products->getCollection();
        $publishedCount = $visibleProducts->filter(fn ($product) => $product->status->value === 'published')->count();
        $draftCount = $visibleProducts->filter(fn ($product) => $product->status->value === 'draft')->count();
        $withVariantsCount = $visibleProducts->filter(fn ($product) => $product->has_variants)->count();
    @endphp

    <!-- Page Header -->
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Productos</h1>
            <p>Administra el catálogo de productos, variantes, imágenes y estados de publicación.</p>
        </div>
        @if ($canCreateProducts)
            <div class="page-actions-area">
                <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Producto
                </a>
            </div>
        @endif
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-cards-grid">
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $products->total() }}</span>
                <span class="stat-card-label">Total Productos</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--success-bg); color: var(--success-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $publishedCount }}</span>
                <span class="stat-card-label">Publicados</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--warning-bg); color: var(--warning-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $draftCount }}</span>
                <span class="stat-card-label">Borradores</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--info-bg); color: var(--info-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $withVariantsCount }}</span>
                <span class="stat-card-label">Con Variantes</span>
            </div>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Listado de Catálogo</h2>
            
            <div class="panel-tabs-list">
                <a class="panel-tab {{ request('status') === null ? 'is-active' : '' }}" href="{{ route('admin.catalog.products.index', request()->except('status', 'page')) }}">Todos</a>
                <a class="panel-tab {{ request('status') === 'published' ? 'is-active' : '' }}" href="{{ route('admin.catalog.products.index', [...request()->except('page'), 'status' => 'published']) }}">Publicados</a>
                <a class="panel-tab {{ request('status') === 'draft' ? 'is-active' : '' }}" href="{{ route('admin.catalog.products.index', [...request()->except('page'), 'status' => 'draft']) }}">Borradores</a>
                <a class="panel-tab {{ request('status') === 'archived' ? 'is-active' : '' }}" href="{{ route('admin.catalog.products.index', [...request()->except('page'), 'status' => 'archived']) }}">Archivados</a>
            </div>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.catalog.products.index') }}">
            <div class="form-group-inline">
                <label for="product_search">Buscar</label>
                <input type="text" id="product_search" name="search" value="{{ request('search') }}" placeholder="Nombre o SKU">
            </div>
            <div class="form-group-inline">
                <label for="product_brand">Marca</label>
                <select id="product_brand" name="brand_id">
                    <option value="">Todas</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected((string) request('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-inline">
                <label for="product_category">Categoría</label>
                <select id="product_category" name="category_id">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-inline">
                <label for="product_status">Estado</label>
                <select id="product_status" name="status">
                    <option value="">Todos</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <!-- Products Data Table -->
        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th style="width: 180px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $statusClass = match($product->status->value) {
                                'published' => 'badge-success',
                                'draft' => 'badge-warning',
                                'archived' => 'badge-danger',
                                default => 'badge-info'
                            };
                        @endphp
                        <tr>
                            @php($mainImage = $product->images->firstWhere('is_main', true) ?? $product->images->first())
                            <td>
                                <div class="admin-table-product-cell">
                                    <div class="admin-table-product-thumb">
                                        @if ($mainImage)
                                            <img src="{{ Storage::url($mainImage->path) }}" alt="{{ $product->name }}">
                                        @else
                                            {{ substr($product->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="admin-table-product-details">
                                        <span class="admin-table-product-name">{{ $product->name }}</span>
                                        <span class="admin-table-product-sku">SKU: {{ $product->sku ?: 'Sin SKU' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 500;">
                                    {{ $product->mainCategory?->name ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--primary);">
                                    {{ $product->formattedPrice() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ $product->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    @if ($canUpdateProducts)
                                        <a href="{{ route('admin.catalog.products.edit', $product) }}" class="btn btn-secondary btn-sm">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Editar
                                        </a>
                                    @endif

                                    @if ($canDeleteProducts)
                                        <form method="POST" action="{{ route('admin.catalog.products.destroy', $product) }}" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                No hay productos registrados en el catálogo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="admin-pagination-container">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
