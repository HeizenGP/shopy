@extends('layouts.admin')

@section('title', 'Usuarios y Roles')
@section('admin_heading', 'Usuarios y roles')
@section('admin_subheading', 'Define quién es cliente, administrador de ventas o superadministrador')

@section('content')
<div class="space-y-6">
    <div class="overflow-hidden rounded-[30px] bg-white">
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
                                    <option value="sales_admin" {{ $user->role === 'sales_admin' ? 'selected' : '' }}>Administrador de ventas</option>
                                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Superadministrador</option>
                                </select>
                                <button type="submit" class="rounded-lg bg-slate-950 px-3 py-1.5 text-xs font-bold text-white hover:bg-slate-800">
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
