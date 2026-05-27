@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-10 text-center space-y-6">
        
        <!-- Icon -->
        <div class="inline-flex items-center justify-center h-16 w-16 bg-green-50 rounded-full text-green-500 border border-green-100 shadow-sm">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">¡Gracias por tu compra!</h1>
            <p class="text-sm text-slate-500 mt-2">Tu pedido ha sido procesado con éxito y ya está en camino.</p>
        </div>

        <!-- Order Summary Cards -->
        <div class="border-t border-b border-slate-100 py-6 text-left space-y-4">
            <div class="flex flex-wrap justify-between text-xs gap-y-2">
                <div>
                    <span class="text-slate-400 block font-semibold">Número de Pedido</span>
                    <span class="text-sm font-bold text-slate-900">#000{{ $order->id }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Fecha del pedido</span>
                    <span class="text-sm font-bold text-slate-900">{{ date('d M Y, H:i', strtotime($order->createdAt)) }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Estado del pedido</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        {{ ucfirst($order->status->value) }}
                    </span>
                </div>
            </div>

            <!-- Shipping details -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                <h3 class="font-bold text-slate-800 mb-2">Detalles de envío</h3>
                <p class="text-slate-600 font-semibold"><span class="text-slate-400">Cliente:</span> {{ $order->billingName }}</p>
                <p class="text-slate-600 font-semibold mt-1"><span class="text-slate-400">Correo:</span> {{ $order->billingEmail }}</p>
                <p class="text-slate-600 font-semibold mt-1"><span class="text-slate-400">Dirección:</span> {{ $order->billingAddress }}</p>
            </div>

            <!-- Items -->
            <div>
                <h3 class="text-xs font-bold text-slate-800 mb-2">Productos comprados</h3>
                <div class="divide-y divide-slate-50 border-t border-slate-100">
                    @foreach($items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-800">{{ $item['name'] }}</span>
                                @if($item['variant_name'])
                                    <span class="text-[10px] text-slate-400 block">{{ $item['variant_name'] }}</span>
                                @endif
                                <span class="text-slate-400 mt-0.5 block">Cant: {{ $item['quantity'] }}</span>
                            </div>
                            <span class="font-bold text-slate-950">${{ number_format($item['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Total price calculation -->
            <div class="border-t border-slate-100 pt-4 text-xs space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discountAmount > 0)
                    <div class="flex justify-between text-indigo-600">
                        <span>Descuento</span>
                        <span>-${{ number_format($order->discountAmount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm font-extrabold text-slate-950 pt-2 border-t border-slate-50">
                    <span>Total pagado</span>
                    <span class="text-base font-black">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="flex justify-center gap-4">
            <a href="{{ route('catalog.index') }}" class="py-2.5 px-6 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-colors shadow-sm">
                Seguir comprando
            </a>
            @auth
                <a href="{{ route('orders.history') }}" class="py-2.5 px-6 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                    Ver mis pedidos
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection
