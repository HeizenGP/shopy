<x-layouts.admin title="Permisos">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Permisos</h1>
            <p>Consulta los permisos disponibles para asignar acceso por módulo.</p>
        </div>
    </div>

    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Listado de permisos</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.access.permissions.index') }}">
            <div class="form-group-inline">
                <label for="permission_search">Buscar</label>
                <input id="permission_search" type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, slug o módulo">
            </div>
            <div class="form-group-inline">
                <label for="module">Módulo</label>
                <select id="module" name="module">
                    <option value="">Todos</option>
                    @foreach ($permissionsByModule->keys() as $module)
                        <option value="{{ $module }}" @selected(request('module') === $module)>{{ ucfirst($module) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.access.permissions.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Permiso</th>
                        <th>Slug</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $permission)
                        <tr>
                            <td><strong>{{ $permission->name }}</strong></td>
                            <td><code>{{ $permission->slug }}</code></td>
                            <td><span class="badge badge-info">{{ $permission->module }}</span></td>
                            <td>{{ $permission->description ?? 'N/D' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">No hay permisos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($permissions->hasPages())
            <div class="admin-pagination-container">{{ $permissions->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
