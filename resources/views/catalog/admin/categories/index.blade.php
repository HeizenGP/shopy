<x-layouts.catalog title="Admin categorias">
    <section class="admin-shell">
        @include('catalog.admin.partials.nav')
        <div class="admin-content">
            <div class="admin-panel">
                <div class="section-title"><h1>Categorias</h1></div>
                <form class="inline-form" method="POST" action="{{ route('admin.catalog.categories.store') }}">
                    @csrf
                    <input name="name" placeholder="Nombre" required>
                    <input name="slug" placeholder="slug-opcional">
                    <select name="parent_id">
                        <option value="">Sin padre</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <input name="sort_order" type="number" min="0" value="0">
                    <label class="check-inline"><input type="checkbox" name="is_active" value="1" checked> Activa</label>
                    <button class="button" type="submit">Agregar</button>
                </form>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Nombre</th><th>Padre</th><th>Orden</th><th>Activa</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <form method="POST" action="{{ route('admin.catalog.categories.update', $category) }}">
                                        @csrf
                                        @method('PUT')
                                        <td><input name="name" value="{{ $category->name }}"></td>
                                        <td>
                                            <select name="parent_id">
                                                <option value="">Sin padre</option>
                                                @foreach ($parents as $parent)
                                                    <option value="{{ $parent->id }}" @selected($category->parent_id === $parent->id)>{{ $parent->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input name="sort_order" type="number" min="0" value="{{ $category->sort_order }}"></td>
                                        <td><input type="checkbox" name="is_active" value="1" @checked($category->is_active)></td>
                                        <td class="actions">
                                            <input type="hidden" name="slug" value="{{ $category->slug }}">
                                            <button type="submit">Guardar</button>
                                    </form>
                                            <form method="POST" action="{{ route('admin.catalog.categories.destroy', $category) }}">
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
                {{ $categories->links() }}
            </div>
        </div>
    </section>
</x-layouts.catalog>
