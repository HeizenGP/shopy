<x-layouts.admin title="Editar {{ $product->name }}">
    <!-- Page Header -->
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Editar Producto</h1>
            <p>Gestiona la información principal, categorías y variantes comerciales de <strong>{{ $product->name }}</strong>.</p>
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

    <!-- Product Edit Form Wrapper -->
    <form class="admin-split-form-grid" method="POST" action="{{ route('admin.catalog.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('catalog.admin.products.partials.form', ['product' => $product])
    </form>
</x-layouts.admin>
