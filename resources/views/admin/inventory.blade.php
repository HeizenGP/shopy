@extends('layouts.admin')

@section('title', 'Gestión de Inventario')
@section('admin_heading', 'Inventario Admin')
@section('admin_subheading', 'Monitorea existencias, edita stock y atiende alertas')

@section('content')
<div class="space-y-6">
    @if(count($alerts) > 0)
        <div class="rounded-[30px] border border-red-200 bg-red-50 p-6">
            <h2 class="text-sm font-bold text-red-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                Alertas de Stock Activas
            </h2>
            <div class="space-y-2">
                @foreach($alerts as $alert)
                    @php
                        $alertProduct = $products->get($alert->productId);
                    @endphp
                    <div class="bg-white p-3 rounded-xl border border-red-100 flex items-center justify-between text-xs text-slate-700 font-semibold shadow-sm">
                        <span>
                            {{ $alert->message }} 
                            @if($alertProduct)
                                <span class="text-indigo-600 font-bold">({{ $alertProduct->name }})</span>
                            @endif
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 text-red-800">
                            Crítico
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-[30px] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-500">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-700 border-b border-slate-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Producto</th>
                        <th scope="col" class="px-6 py-4">Variante SKU</th>
                        <th scope="col" class="px-6 py-4">Stock actual</th>
                        <th scope="col" class="px-6 py-4">Límite stock bajo</th>
                        <th scope="col" class="px-6 py-4">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right">Actualizar inventario</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($inventories as $inv)
                        @php
                            $prod = $products->get($inv->product_id);
                            $var = $inv->product_variant_id && $prod ? $prod->variants->firstWhere('id', $inv->product_variant_id) : null;
                        @endphp
                        @if($prod)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $prod->name }}
                                    @if($var)
                                        <span class="block text-[10px] text-slate-400 font-semibold mt-0.5">
                                            Variante: {{ $var->options->map(fn($o) => "{$o->name}: {$o->value}")->join(', ') }}
                                        </span>
                                    @else
                                        <span class="block text-[10px] text-slate-400 font-semibold mt-0.5">Producto Base</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $var ? $var->sku : $prod->slug }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $inv->stock }} uds
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-600">
                                    {{ $inv->low_stock_threshold }} uds
                                </td>
                                <td class="px-6 py-4">
                                    @if($inv->stock <= 0)
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-bold text-red-700">
                                            Agotado
                                        </span>
                                    @elseif($inv->stock <= $inv->low_stock_threshold)
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-bold text-amber-700">
                                            Stock Bajo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-bold text-green-700">
                                            Suficiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.inventory.update', $inv->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <div class="flex items-center gap-1">
                                            <input type="number" name="stock" value="{{ $inv->stock }}" min="0" required
                                                   class="w-16 text-center text-xs py-1 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <span class="text-slate-400 text-xs font-semibold">/</span>
                                            <input type="number" name="low_stock_threshold" value="{{ $inv->low_stock_threshold }}" min="0" required
                                                   class="w-16 text-center text-xs py-1 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500" 
                                                   title="Límite stock bajo">
                                        </div>
                                        <button type="submit" class="py-1 px-2.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                            Guardar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
