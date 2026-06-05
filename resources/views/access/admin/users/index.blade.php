<x-layouts.admin title="Usuarios">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Usuarios</h1>
            <p>Administra cuentas administrativas, estado y roles.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.access.users.create') }}" class="btn btn-primary">Nuevo usuario</a>
        </div>
    </div>

    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Listado de usuarios</h2>
        </div>

        <form class="panel-filter-form" method="GET" action="{{ route('admin.access.users.index') }}">
            <div class="form-group-inline">
                <label for="user_search">Buscar</label>
                <input id="user_search" type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o email">
            </div>
            <div class="form-group-inline">
                <label for="is_active">Estado</label>
                <select id="is_active" name="is_active">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('is_active') === '1')>Activos</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactivos</option>
                </select>
            </div>
            <div class="panel-filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.access.users.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Roles</th>
                        <th>Último login</th>
                        <th>Estado</th>
                        <th style="width: 180px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                <span style="display: block; color: var(--text-muted);">{{ $user->email }}</span>
                            </td>
                            <td>{{ $user->roles->pluck('name')->join(', ') ?: 'Sin roles' }}</td>
                            <td>{{ $user->last_login_at?->format('Y-m-d H:i') ?? 'N/D' }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.access.users.edit', $user) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('admin.access.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar este usuario?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="admin-pagination-container">{{ $users->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
