<x-layouts.admin title="Editar Usuario">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Editar usuario</h1>
            <p>Actualiza acceso, estado y roles de la cuenta administrativa.</p>
        </div>
    </div>

    @include('access.admin.users.partials.form', ['user' => $user])
</x-layouts.admin>
