<x-layouts.admin title="Variantes">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Variantes</h1>
            <p>Crea variantes comerciales asociadas a productos, como talla, color o capacidad.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.variants.create') }}" class="btn btn-primary">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Variante
            </a>
        </div>
    </div>

    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Variantes Registradas</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.catalog.variants.index') }}">
            <div class="form-group-inline">
                <label for="variant_search">Buscar</label>
                <input type="text" id="variant_search" name="search" value="{{ request('search') }}" placeholder="Variante, SKU o producto">
            </div>
            <div class="form-group-inline">
                <label for="filter_variant_product">Producto</label>
                <select id="filter_variant_product" name="product_id">
                    <option value="">Todos</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((string) request('product_id') === (string) $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-inline">
                <label for="filter_variant_active">Estado</label>
                <select id="filter_variant_active" name="is_active">
                    <option value="">Todas</option>
                    <option value="1" @selected(request('is_active') === '1')>Activas</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactivas</option>
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.catalog.variants.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Variante</th>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th style="width: 100px; text-align: center;">Activa</th>
                        <th style="width: 140px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variants as $variant)
                        <tr>
                            <td style="font-weight: 600;">{{ $variant->name }}</td>
                            <td>{{ $variant->product?->name ?? 'Sin producto' }}</td>
                            <td>{{ $variant->sku }}</td>
                            <td>S/ {{ number_format((float) ($variant->sale_price ?: $variant->regular_price), 2) }}</td>
                            <td style="text-align: center;">
                                <span class="badge {{ $variant->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $variant->is_active ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions" style="display: flex; justify-content: flex-end;">
                                    <form method="POST" action="{{ route('admin.catalog.variants.destroy', $variant) }}" onsubmit="return confirm('¿Eliminar esta variante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No hay variantes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($variants->hasPages())
            <div class="admin-pagination-container">
                {{ $variants->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
