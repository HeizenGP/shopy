@extends('layouts.app')

@section('title', 'Administración de Pedidos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Panel de Pedidos</h1>
            <p class="text-xs text-slate-400 mt-1">Gestión administrativa de compras y estados de pedido</p>
        </div>
    </div>

    @if(count($orders) === 0)
        <div class="bg-white text-center py-16 px-4 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="mt-4 text-sm font-semibold text-slate-900">No hay pedidos registrados</h3>
            <p class="mt-1 text-sm text-slate-500">Los pedidos de tus clientes aparecerán en este listado.</p>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm text-slate-500">
                    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-700 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-4">Pedido</th>
                            <th scope="col" class="px-6 py-4">Cliente</th>
                            <th scope="col" class="px-6 py-4">Monto total</th>
                            <th scope="col" class="px-6 py-4">Dirección</th>
                            <th scope="col" class="px-6 py-4">Fecha</th>
                            <th scope="col" class="px-6 py-4">Estado actual</th>
                            <th scope="col" class="px-6 py-4 text-right">Actualizar estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <a href="{{ route('orders.show', $order->id) }}" class="hover:text-indigo-600">
                                        #000{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $order->billingName }}
                                    <span class="block text-[10px] text-slate-400 font-mono">{{ $order->billingEmail }}</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-950">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 text-xs max-w-xs truncate">{{ $order->billingAddress }}</td>
                                <td class="px-6 py-4 text-xs font-medium">{{ date('d M, H:i', strtotime($order->createdAt)) }}</td>
                                <td class="px-6 py-4">
                                    @if($order->status->value === 'completed')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-bold text-green-700">
                                            Completado
                                        </span>
                                    @elseif($order->status->value === 'processing')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                            Procesando
                                        </span>
                                    @elseif($order->status->value === 'cancelled')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700">
                                            Cancelado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" 
                                                class="text-xs px-2.5 py-1.5 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                            <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="processing" {{ $order->status->value === 'processing' ? 'selected' : '' }}>Procesando</option>
                                            <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>Completado</option>
                                            <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
