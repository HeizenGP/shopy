<x-layouts.admin title="Marcas">
    <!-- Page Header -->
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Marcas</h1>
            <p>Mantenimiento de marcas asociadas a los productos del catálogo.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.brands.create') }}" class="btn btn-primary">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Marca
            </a>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Marcas Registradas</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.catalog.brands.index') }}">
            <div class="form-group-inline">
                <label for="brand_search">Buscar</label>
                <input type="text" id="brand_search" name="search" value="{{ request('search') }}" placeholder="Nombre o slug">
            </div>
            <div class="form-group-inline">
                <label for="brand_active">Estado</label>
                <select id="brand_active" name="is_active">
                    <option value="">Todas</option>
                    <option value="1" @selected(request('is_active') === '1')>Activas</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactivas</option>
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.catalog.brands.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <!-- Brands Data Table -->
        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Slug</th>
                        <th style="width: 100px; text-align: center;">Activa</th>
                        <th style="width: 200px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>
                                <div class="table-entity-cell">
                                    <span class="table-logo-thumb">
                                        @if ($brand->logo_path)
                                            <img src="{{ Storage::url($brand->logo_path) }}" alt="{{ $brand->name }}">
                                        @else
                                            {{ substr($brand->name, 0, 1) }}
                                        @endif
                                    </span>
                                    <strong>{{ $brand->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td style="text-align: center;">
                                <span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $brand->is_active ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <a href="{{ route('admin.catalog.brands.edit', $brand) }}" class="btn btn-secondary btn-sm" title="Editar marca">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.catalog.brands.destroy', $brand) }}" onsubmit="return confirm('¿Estás seguro de eliminar esta marca?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar marca">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No hay marcas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($brands->hasPages())
            <div class="admin-pagination-container">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
