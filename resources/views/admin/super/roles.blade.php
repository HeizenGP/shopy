@extends('layouts.admin')

@section('title', 'Roles y permisos')
@section('admin_heading', 'Roles y permisos')
@section('admin_subheading', 'Crea roles y asigna permisos por módulo')

@section('content')
<div class="space-y-8">
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <section class="rounded-[30px] bg-white p-6">
            <h2 class="text-lg font-black text-slate-950">Nuevo rol</h2>
            <form action="{{ route('admin.roles.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Clave</label>
                    <input type="text" name="key" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="editor_contenido" required>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Nombre</label>
                    <input type="text" name="name" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Editor de contenido" required>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Descripción</label>
                    <textarea name="description" rows="3" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Rol para publicar y editar contenido"></textarea>
                </div>

                @foreach($permissionGroups as $groupKey => $group)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs font-black uppercase text-slate-500">{{ $group['label'] }}</p>
                        <div class="mt-3 space-y-3">
                            @foreach($group['permissions'] as $permission)
                                <label class="flex items-start gap-3 text-sm text-slate-700">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission['key'] }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-admin-primary focus:ring-admin-primary">
                                    <span>
                                        <span class="block font-bold text-slate-950">{{ $permission['name'] }}</span>
                                        <span class="block text-xs text-slate-500">{{ $permission['description'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="w-full rounded-full bg-slate-950 px-6 py-3 text-xs font-bold text-white hover:bg-slate-800">Crear rol</button>
            </form>
        </section>

        <section class="space-y-4">
            @foreach($roles as $role)
                <article class="rounded-[30px] bg-white p-6">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Clave</label>
                                <input type="text" name="key" value="{{ $role->key }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Nombre</label>
                                <input type="text" name="name" value="{{ $role->name }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Descripción</label>
                                <input type="text" name="description" value="{{ $role->description }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($permissionGroups as $groupKey => $group)
                                <div class="rounded-2xl border border-slate-100 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ $group['label'] }}</p>
                                    <div class="mt-3 space-y-3">
                                        @foreach($group['permissions'] as $permission)
                                            <label class="flex items-start gap-3 text-sm text-slate-700">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission['key'] }}" {{ $role->permissions->contains('key', $permission['key']) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-slate-300 text-admin-primary focus:ring-admin-primary">
                                                <span>
                                                    <span class="block font-bold text-slate-950">{{ $permission['name'] }}</span>
                                                    <span class="block text-xs text-slate-500">{{ $permission['description'] }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="text-xs text-slate-500">Usuarios asignados: {{ \App\Modules\Auth\Infrastructure\Database\Models\UserEloquent::where('role', $role->key)->count() }}</div>
                            <div class="flex gap-2">
                                <button type="submit" class="rounded-full bg-slate-950 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-800">Guardar</button>
                                <button type="submit" form="delete-role-{{ $role->id }}" class="rounded-full border border-red-200 px-5 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50" @if($role->key === 'super_admin') disabled @endif>Eliminar</button>
                            </div>
                        </div>
                    </form>

                    <form id="delete-role-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </article>
            @endforeach
        </section>
    </div>
</div>
@endsection
