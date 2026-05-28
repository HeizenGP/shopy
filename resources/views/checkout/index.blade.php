@extends('layouts.app')

@section('title', 'Proceso de Pago')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-8">Finalizar compra</h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Billing Details Form -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Detalles de facturación</h2>
            
            <form action="{{ route('checkout.place') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="billing_name" class="block text-xs font-semibold text-slate-500 mb-2">Nombre completo</label>
                    <input type="text" name="billing_name" id="billing_name" 
                           value="{{ old('billing_name', Auth::check() ? Auth::user()->name : '') }}" required
                           class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20 focus:border-client-primary">
                </div>

                <!-- Email -->
                <div>
                    <label for="billing_email" class="block text-xs font-semibold text-slate-500 mb-2">Correo electrónico</label>
                    <input type="email" name="billing_email" id="billing_email" 
                           value="{{ old('billing_email', Auth::check() ? Auth::user()->email : '') }}" required
                           class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20 focus:border-client-primary">
                </div>

                <!-- Address -->
                <div>
                    <label for="billing_address" class="block text-xs font-semibold text-slate-500 mb-2">Dirección de envío</label>
                    <textarea name="billing_address" id="billing_address" rows="4" required
                              class="w-full text-sm px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20 focus:border-client-primary" 
                              placeholder="Calle, Número, Departamento, Ciudad...">{{ old('billing_address') }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex items-center justify-center py-3 px-4 text-sm font-bold text-white bg-client-primary hover:bg-client-primary/90 rounded-xl transition-all shadow-md shadow-client-primary/10">
                        Confirmar y realizar pedido
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Order Summary & Coupon Code -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Coupon Validation Section -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-2">¿Tienes un cupón?</h3>
                
                @if($couponCode)
                    <div class="flex items-center justify-between p-3 border border-client-primary/10 rounded-xl bg-client-primary/10">
                        <div>
                            <span class="text-xs text-slate-400 block font-semibold">Cupón Activo</span>
                            <span class="text-sm font-bold text-client-primary">{{ $couponCode }}</span>
                        </div>
                        <form action="{{ route('coupon.remove') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                Quitar
                            </button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $subtotal }}">
                        <input type="text" name="code" placeholder="Código de cupón" required
                               class="w-full text-xs px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-client-primary/20">
                        <button type="submit" class="py-2 px-4 text-xs font-bold text-client-primary bg-client-primary/10 border border-client-primary/10 rounded-xl hover:bg-client-primary/15 transition-colors">
                            Aplicar
                        </button>
                    </form>
                @endif
            </div>

            <!-- Order Summary Section -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tu pedido</h3>

                <div class="divide-y divide-slate-50 max-h-56 overflow-y-auto pr-2">
                    @foreach($items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-800">{{ $item['name'] }}</span>
                                @if($item['variant_name'])
                                    <span class="text-[10px] text-slate-400 block">{{ $item['variant_name'] }}</span>
                                @endif
                                <span class="text-slate-400 mt-0.5 block">Cant: {{ $item['quantity'] }}</span>
                            </div>
                            <span class="font-bold text-slate-900">${{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-semibold">Subtotal</span>
                        <span class="text-slate-950 font-bold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-client-primary font-semibold">
                            <span>Descuento</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm pt-2 border-t border-slate-50 font-extrabold text-slate-950">
                        <span>Total a pagar</span>
                        <span class="text-base font-black">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
