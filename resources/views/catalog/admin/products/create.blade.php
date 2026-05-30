<x-layouts.admin title="Nuevo Producto">
    <!-- Page Header -->
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Crear Producto</h1>
            <p>Formulario para registrar un nuevo producto en el catálogo comercial.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a la lista
            </a>
        </div>
    </div>

    <!-- Product Create Form Wrapper -->
    <form class="admin-split-form-grid" method="POST" action="{{ route('admin.catalog.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('catalog.admin.products.partials.form', ['product' => null])
    </form>
</x-layouts.admin>
