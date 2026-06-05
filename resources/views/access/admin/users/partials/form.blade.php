@php
    $isEdit = isset($user);
    $selectedRoles = collect(old('role_ids', $isEdit ? $user->roles->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
@endphp

<div class="dashboard-content-panel">
    <div class="panel-header">
        <h2 class="panel-title">{{ $isEdit ? 'Datos del usuario' : 'Nuevo usuario' }}</h2>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.access.users.update', $user) : route('admin.access.users.store') }}" style="display: grid; gap: 1rem;">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="panel-filter-form" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
            <div class="form-group-inline">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
            </div>

            <div class="form-group-inline">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            <div class="form-group-inline">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" autocomplete="new-password" @required(! $isEdit)>
            </div>

            <div class="form-group-inline">
                <label for="password_confirmation">Confirmación</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" @required(! $isEdit)>
            </div>
        </div>

        <input type="hidden" name="is_active" value="0">
        <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            <input type="checkbox" name="is_active" value="1" style="width: auto;" @checked(old('is_active', $user->is_active ?? true))>
            Usuario activo
        </label>

        <div>
            <h3 style="font-size: 1rem; margin-bottom: 0.75rem;">Roles</h3>
            <div style="display: grid; gap: 0.65rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                @foreach ($roles as $role)
                    <label style="display: flex; gap: 0.6rem; align-items: flex-start; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                        <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" style="width: auto; margin-top: 0.2rem;" @checked(in_array($role->id, $selectedRoles, true))>
                        <span>
                            <strong>{{ $role->name }}</strong>
                            <span style="display: block; color: var(--text-muted); font-size: 0.85rem;">{{ $role->slug }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="page-actions-area" style="justify-content: flex-start;">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Guardar cambios' : 'Crear usuario' }}</button>
            <a href="{{ route('admin.access.users.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
