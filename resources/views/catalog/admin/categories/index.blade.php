<x-layouts.admin title="Categorías">
    <!-- Page Header -->
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Categorías</h1>
            <p>Organiza el catálogo de productos por categorías y jerarquías.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-primary">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Categoría
            </a>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Categorías Registradas</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.catalog.categories.index') }}">
            <div class="form-group-inline">
                <label for="category_search">Buscar</label>
                <input type="text" id="category_search" name="search" value="{{ request('search') }}" placeholder="Nombre o slug">
            </div>
            <div class="form-group-inline">
                <label for="filter_parent">Categoría Padre</label>
                <select id="filter_parent" name="parent_id">
                    <option value="">Todas</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" @selected((string) request('parent_id') === (string) $parent->id)>
                            {{ $parent->level() === 0 ? $parent->name : $parent->parent->name.' / '.$parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-inline">
                <label for="filter_category_active">Estado</label>
                <select id="filter_category_active" name="is_active">
                    <option value="">Todas</option>
                    <option value="1" @selected(request('is_active') === '1')>Activas</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactivas</option>
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <!-- Categories Data Table -->
        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th style="width: 150px;">Nivel</th>
                        <th>Categoría Padre</th>
                        <th style="width: 100px;">Orden</th>
                        <th style="width: 100px; text-align: center;">Activa</th>
                        <th style="width: 200px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                <div class="table-entity-cell">
                                    <span class="table-logo-thumb">
                                        @if ($category->image_path)
                                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}">
                                        @else
                                            {{ substr($category->name, 0, 1) }}
                                        @endif
                                    </span>
                                    <strong>{{ $category->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $category->level() === 0 ? 'badge-primary' : ($category->level() === 1 ? 'badge-info' : 'badge-success') }}">
                                    Nivel {{ $category->level() }}
                                </span>
                                <div class="theme-label-sm" style="margin-top: 0.25rem;">{{ $category->hierarchyLabel() }}</div>
                            </td>
                            <td>{{ $category->parent?->name ?? 'Ninguna' }}</td>
                            <td>{{ $category->sort_order }}</td>
                            <td style="text-align: center;">
                                <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $category->is_active ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <a href="{{ route('admin.catalog.categories.edit', $category) }}" class="btn btn-secondary btn-sm" title="Editar categoría">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.catalog.categories.destroy', $category) }}" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar categoría">
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
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="admin-pagination-container">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
