<x-layouts.admin title="Roles">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Roles</h1>
            <p>Administra grupos de permisos para usuarios administrativos.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.access.roles.create') }}" class="btn btn-primary">Nuevo rol</a>
        </div>
    </div>

    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Listado de roles</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.access.roles.index') }}">
            <div class="form-group-inline">
                <label for="role_search">Buscar</label>
                <input id="role_search" type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o slug">
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.access.roles.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th>Usuarios</th>
                        <th>Permisos</th>
                        <th>Tipo</th>
                        <th style="width: 180px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>
                                <strong>{{ $role->name }}</strong>
                                <span style="display: block; color: var(--text-muted);">{{ $role->slug }}</span>
                            </td>
                            <td>{{ $role->users_count }}</td>
                            <td>{{ $role->permissions_count }}</td>
                            <td>
                                <span class="badge {{ $role->is_system ? 'badge-info' : 'badge-success' }}">
                                    {{ $role->is_system ? 'Sistema' : 'Personalizado' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.access.roles.edit', $role) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('admin.access.roles.destroy', $role) }}" onsubmit="return confirm('¿Eliminar este rol?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" @disabled($role->is_system)>Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No hay roles registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="admin-pagination-container">{{ $roles->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
