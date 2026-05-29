<x-layouts.catalog title="Editar {{ $product->name }}">
    <section class="admin-shell">
        @include('catalog.admin.partials.nav')
        <div class="admin-content">
            <div class="admin-panel">
                <div class="section-title"><h1>Editar producto</h1></div>
                <form class="form-grid" method="POST" action="{{ route('admin.catalog.products.update', $product) }}">
                    @csrf
                    @method('PUT')
                    @include('catalog.admin.products.partials.form', ['product' => $product])
                </form>
            </div>
        </div>
    </section>
</x-layouts.catalog>
