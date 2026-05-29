<x-layouts.catalog title="Admin marcas">
    <section class="admin-shell">
        @include('catalog.admin.partials.nav')
        <div class="admin-content">
            <div class="admin-panel">
                <div class="section-title"><h1>Marcas</h1></div>
                <form class="inline-form brands-form" method="POST" action="{{ route('admin.catalog.brands.store') }}">
                    @csrf
                    <input name="name" placeholder="Nombre" required>
                    <input name="slug" placeholder="slug-opcional">
                    <label class="check-inline"><input type="checkbox" name="is_active" value="1" checked> Activa</label>
                    <button class="button" type="submit">Agregar</button>
                </form>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Marca</th><th>Slug</th><th>Activa</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <form method="POST" action="{{ route('admin.catalog.brands.update', $brand) }}">
                                        @csrf
                                        @method('PUT')
                                        <td><input name="name" value="{{ $brand->name }}"></td>
                                        <td><input name="slug" value="{{ $brand->slug }}"></td>
                                        <td><input type="checkbox" name="is_active" value="1" @checked($brand->is_active)></td>
                                        <td class="actions">
                                            <button type="submit">Guardar</button>
                                    </form>
                                            <form method="POST" action="{{ route('admin.catalog.brands.destroy', $brand) }}">
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
                {{ $brands->links() }}
            </div>
        </div>
    </section>
</x-layouts.catalog>
