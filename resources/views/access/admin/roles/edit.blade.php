<x-layouts.admin title="Editar Rol">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Editar rol</h1>
            <p>Actualiza la descripción y permisos asignados al rol.</p>
        </div>
    </div>

    @include('access.admin.roles.partials.form', ['role' => $role])
</x-layouts.admin>
