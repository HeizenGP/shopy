@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-8">Tu carrito</h1>

    @if(count($items) === 0)
        <div class="bg-white text-center py-16 px-4 rounded-3xl border border-slate-100 shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <h3 class="mt-4 text-sm font-semibold text-slate-900">El carrito está vacío</h3>
            <p class="mt-1 text-sm text-slate-500">¡Explora nuestra tienda y añade productos hoy mismo!</p>
            <div class="mt-6">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all">
                    Volver a la tienda
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Items list -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($items as $item)
                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-5 items-center">
                        <!-- Image -->
                        <div class="h-20 w-20 flex-shrink-0 rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 relative">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full w-full text-slate-400 font-extrabold bg-slate-200 text-xs">
                                    {{ $item['name'] }}
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="flex-grow text-center sm:text-left">
                            <h3 class="text-sm font-bold text-slate-800">
                                <a href="{{ route('catalog.show', $item['slug']) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $item['name'] }}
                                </a>
                            </h3>
                            @if($item['variant_name'])
                                <p class="text-xs text-slate-400 mt-1 font-medium">
                                    {{ $item['variant_name'] }}
                                </p>
                            @endif
                            <span class="text-xs font-bold text-slate-950 mt-2 block">
                                ${{ number_format($item['price'], 2) }} c/u
                            </span>
                        </div>

                        <!-- Actions (Quantity & Remove) -->
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <!-- Update Quantity Form -->
                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-slate-200 rounded-xl overflow-hidden">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                
                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="p-2 text-slate-500 hover:bg-slate-50 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                    </svg>
                                </button>
                                
                                <span class="px-4 text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>
                                
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="p-2 text-slate-500 hover:bg-slate-50 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </form>

                            <!-- Delete Item Form -->
                            <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Resumen de compra</h2>

                    <div class="flex items-center justify-between text-sm border-b border-slate-50 pb-4">
                        <span class="text-slate-500 font-semibold">Subtotal</span>
                        <span class="text-slate-900 font-bold">${{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center py-2.5 px-4 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md shadow-indigo-600/10">
                        Proceder al pago
                    </a>

                    <div class="pt-4 border-t border-slate-100 text-center">
                        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                            Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
