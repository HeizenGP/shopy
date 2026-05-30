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
                        <option value="{{ $parent->id }}" @selected((string) request('parent_id') === (string) $parent->id)>{{ $parent->name }}</option>
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
                        <th>Categoría Padre</th>
                        <th style="width: 100px;">Orden</th>
                        <th style="width: 100px; text-align: center;">Activa</th>
                        <th style="width: 200px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <!-- Inline Edit Form -->
                            <form method="POST" action="{{ route('admin.catalog.categories.update', $category) }}" id="update-form-{{ $category->id }}">
                                @csrf
                                @method('PUT')
                                <td>
                                    <div class="table-entity-cell">
                                        <span class="table-logo-thumb">
                                            @if ($category->image_path)
                                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}">
                                            @else
                                                {{ substr($category->name, 0, 1) }}
                                            @endif
                                        </span>
                                        <input type="text" name="name" value="{{ $category->name }}" class="table-inline-input" required>
                                    </div>
                                </td>
                                <td>
                                    <select name="parent_id" class="table-select">
                                        <option value="">Ninguna</option>
                                        @foreach ($parents as $parent)
                                            @if ($parent->id !== $category->id)
                                                <option value="{{ $parent->id }}" @selected($category->parent_id === $parent->id)>{{ $parent->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" class="table-inline-input">
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="is_active" value="1" @checked($category->is_active) style="width: 16px; height: 16px; accent-color: var(--primary);">
                                </td>
                                <td>
                                    <div class="actions" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                        <input type="hidden" name="slug" value="{{ $category->slug }}">
                                        <button type="submit" class="btn btn-secondary btn-sm" title="Guardar cambios">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                            Guardar
                                        </button>
                            </form>
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
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
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
