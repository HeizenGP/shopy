<x-layouts.catalog title="Admin productos">
    <section class="admin-shell">
        @include('catalog.admin.partials.nav')
        <div class="admin-content">
            <div class="admin-panel">
                <div class="section-title">
                    <h1>Productos</h1>
                    <a class="button" href="{{ route('admin.catalog.products.create') }}">Nuevo producto</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoria</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <span>{{ $product->sku ?: 'Sin SKU' }}</span>
                                    </td>
                                    <td>{{ $product->mainCategory?->name ?? '-' }}</td>
                                    <td>{{ $product->formattedPrice() }}</td>
                                    <td><span class="pill">{{ $product->status->label() }}</span></td>
                                    <td class="actions">
                                        <a href="{{ route('admin.catalog.products.edit', $product) }}">Editar</a>
                                        <form method="POST" action="{{ route('admin.catalog.products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </section>
</x-layouts.catalog>
