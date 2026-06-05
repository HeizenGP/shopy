@php
    $isEdit = isset($role);
    $selectedPermissions = collect(old('permission_ids', $isEdit ? $role->permissions->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
@endphp

<div class="dashboard-content-panel">
    <div class="panel-header">
        <h2 class="panel-title">{{ $isEdit ? 'Datos del rol' : 'Nuevo rol' }}</h2>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.access.roles.update', $role) : route('admin.access.roles.store') }}" style="display: grid; gap: 1rem;">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="panel-filter-form" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
            <div class="form-group-inline">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name', $role->name ?? '') }}" required>
            </div>

            <div class="form-group-inline">
                <label for="slug">Slug</label>
                <input id="slug" type="text" name="slug" value="{{ old('slug', $role->slug ?? '') }}" placeholder="catalog_manager">
            </div>
        </div>

        <div class="form-group-inline">
            <label for="description">Descripción</label>
            <textarea id="description" name="description" rows="3" maxlength="500">{{ old('description', $role->description ?? '') }}</textarea>
        </div>

        <div>
            <h3 style="font-size: 1rem; margin-bottom: 0.75rem;">Permisos</h3>
            <div style="display: grid; gap: 1rem;">
                @foreach ($permissionsByModule as $module => $permissions)
                    <section style="border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 1rem;">
                        <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem; text-transform: capitalize;">{{ $module }}</h4>
                        <div style="display: grid; gap: 0.65rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                            @foreach ($permissions as $permission)
                                <label style="display: flex; gap: 0.6rem; align-items: flex-start;">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" style="width: auto; margin-top: 0.2rem;" @checked(in_array($permission->id, $selectedPermissions, true))>
                                    <span>
                                        <strong>{{ $permission->name }}</strong>
                                        <span style="display: block; color: var(--text-muted); font-size: 0.85rem;">{{ $permission->slug }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

        <div class="page-actions-area" style="justify-content: flex-start;">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Guardar cambios' : 'Crear rol' }}</button>
            <a href="{{ route('admin.access.roles.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
