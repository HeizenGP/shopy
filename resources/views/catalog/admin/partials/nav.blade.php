<aside class="admin-nav">
    <strong>Catalogo</strong>
    <a @class(['is-active' => request()->routeIs('admin.catalog.products.*')]) href="{{ route('admin.catalog.products.index') }}">Productos</a>
    <a @class(['is-active' => request()->routeIs('admin.catalog.categories.*')]) href="{{ route('admin.catalog.categories.index') }}">Categorias</a>
    <a @class(['is-active' => request()->routeIs('admin.catalog.brands.*')]) href="{{ route('admin.catalog.brands.index') }}">Marcas</a>
</aside>
