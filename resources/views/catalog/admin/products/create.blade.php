<x-layouts.catalog title="Nuevo producto">
    <section class="admin-shell">
        @include('catalog.admin.partials.nav')
        <div class="admin-content">
            <div class="admin-panel">
                <div class="section-title"><h1>Nuevo producto</h1></div>
                <form class="form-grid" method="POST" action="{{ route('admin.catalog.products.store') }}">
                    @csrf
                    @include('catalog.admin.products.partials.form', ['product' => null])
                </form>
            </div>
        </div>
    </section>
</x-layouts.catalog>
