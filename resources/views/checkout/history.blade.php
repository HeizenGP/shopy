@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-8">Mis Pedidos</h1>

    @if(count($orders) === 0)
        <div class="bg-white text-center py-16 px-4 rounded-3xl border border-slate-100 shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-.621-.504-1.125-1.125-1.125H9.75M3 16.5V19.5m0-3M3 19.5h3.5m-3.5 0v-3m0 3a1.5 1.5 0 0 1-1.5-1.5v-3.5A1.5 1.5 0 0 1 3 10.5h18a1.5 1.5 0 0 1 1.5 1.5v3.5a1.5 1.5 0 0 1-1.5 1.5m0-3V19.5m0-3H17.5m3.5 3v-3" />
            </svg>
            <h3 class="mt-4 text-sm font-semibold text-slate-900">Aún no has realizado pedidos</h3>
            <p class="mt-1 text-sm text-slate-500">Cuando realices compras, podrás darles seguimiento aquí.</p>
            <div class="mt-6">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all">
                    Ir a la tienda
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm text-slate-500">
                    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-700 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-4">ID Pedido</th>
                            <th scope="col" class="px-6 py-4">Fecha</th>
                            <th scope="col" class="px-6 py-4">Total</th>
                            <th scope="col" class="px-6 py-4">Dirección</th>
                            <th scope="col" class="px-6 py-4">Estado</th>
                            <th scope="col" class="px-6 py-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">#000{{ $order->id }}</td>
                                <td class="px-6 py-4 font-medium text-slate-600">{{ date('d M Y, H:i', strtotime($order->createdAt)) }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 text-xs max-w-xs truncate">{{ $order->billingAddress }}</td>
                                <td class="px-6 py-4">
                                    @if($order->status->value === 'completed')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-bold text-green-700">
                                            Completado
                                        </span>
                                    @elseif($order->status->value === 'processing')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700">
                                            Procesando
                                        </span>
                                    @elseif($order->status->value === 'cancelled')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-bold text-red-700">
                                            Cancelado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-1 text-xs font-bold text-amber-700">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                        Ver detalles
                                    </a>
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
