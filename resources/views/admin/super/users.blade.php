@extends('layouts.app')

@section('title', 'Usuarios y Roles')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Usuarios y roles</h1>
        <p class="text-xs text-slate-400 mt-1">Define quién es cliente, Ventas Admin o Super Admin</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4">Usuario</th>
                    <th class="px-6 py-4">Correo</th>
                    <th class="px-6 py-4">Rol actual</th>
                    <th class="px-6 py-4 text-right">Cambiar rol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="inline-flex gap-2">
                                @csrf
                                <select name="role" class="text-xs px-2.5 py-1.5 border border-slate-200 rounded-lg bg-white">
                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Cliente</option>
                                    <option value="sales_admin" {{ $user->role === 'sales_admin' ? 'selected' : '' }}>Ventas Admin</option>
                                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                <button type="submit" class="py-1 px-2.5 text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                    Guardar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
