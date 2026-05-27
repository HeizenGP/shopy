@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
     x-data="{
         variants: {{ json_encode($product->variants) }},
         selectedVariantId: null,
         price: {{ $product->price }},
         stock: 0,
         sku: '{{ $product->variants[0]->sku ?? 'N/A' }}',
         lowStock: false,
         outOfStock: false,
         
         init() {
             if (this.variants.length > 0) {
                 this.selectVariant(this.variants[0].id);
             } else {
                 // Check product inventory
                 let baseInv = {{ isset($inventoryLevels['base']) ? $inventoryLevels['base']->stock : 0 }};
                 let baseThres = {{ isset($inventoryLevels['base']) ? $inventoryLevels['base']->low_stock_threshold : 5 }};
                 this.stock = baseInv;
                 this.lowStock = baseInv <= baseThres && baseInv > 0;
                 this.outOfStock = baseInv <= 0;
             }
         },

         selectVariant(id) {
             this.selectedVariantId = id;
             let variant = this.variants.find(v => v.id === id);
             if (variant) {
                 this.price = variant.price;
                 this.sku = variant.sku;
                 
                 // Get inventory for variant from PHP injected object
                 let variantInvs = {
                     @foreach($inventoryLevels as $key => $inv)
                         '{{ $key }}': { stock: {{ $inv->stock }}, threshold: {{ $inv->low_stock_threshold }} },
                     @endforeach
                 };
                 
                 let inv = variantInvs[id] || { stock: 0, threshold: 5 };
                 this.stock = inv.stock;
                 this.lowStock = inv.stock <= inv.threshold && inv.stock > 0;
                 this.outOfStock = inv.stock <= 0;
             }
         }
     }">
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8 lg:p-12 mb-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
            
            <!-- Left Column: Product Image -->
            <div class="w-full max-w-lg mx-auto lg:max-w-none">
                <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 shadow-sm relative">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                    @else
                        <div class="flex items-center justify-center h-full w-full text-slate-400 font-extrabold bg-gradient-to-tr from-slate-200 to-slate-100 text-3xl">
                            {{ $product->name }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Product Detail & Selector Form -->
            <div class="mt-8 lg:mt-0 flex flex-col justify-between">
                <div>
                    <!-- Category & Title -->
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                        {{ $product->category ?: 'General' }}
                    </span>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 mt-2">
                        {{ $product->name }}
                    </h1>

                    <!-- Rating Summary -->
                    <div class="flex items-center mt-3 gap-2">
                        <div class="flex items-center text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= round($averageRating) ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499c.178-.385.736-.385.915 0l2.09 4.238 4.678.68c.425.062.595.585.288.89l-3.386 3.298.8 4.66c.073.424-.37.747-.75.548L12 15.429l-4.185 2.2c-.38.198-.823-.125-.75-.548l.8-4.66-3.386-3.298c-.307-.305-.137-.827.288-.89l4.678-.68 2.09-4.238Z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-semibold text-slate-500">
                            ({{ count($reviews) }} valoraciones)
                        </span>
                    </div>

                    <!-- Price & SKU -->
                    <div class="mt-6 flex items-baseline justify-between border-b border-slate-100 pb-6">
                        <span class="text-3xl font-extrabold text-slate-950" x-text="'$' + price.toFixed(2)">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="text-xs text-slate-400 font-mono" x-text="'SKU: ' + sku">
                            SKU: {{ $sku }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-slate-800">Descripción</h3>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Form block -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Dynamic Variant selectors -->
                        @if(!empty($product->variants))
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">Opciones disponibles</h3>
                                <input type="hidden" name="variant_id" :value="selectedVariantId">
                                
                                <div class="flex flex-wrap gap-3">
                                    @foreach($product->variants as $variant)
                                        <button type="button" 
                                                class="px-4 py-2 text-sm border rounded-xl font-medium transition-all focus:outline-none"
                                                :class="selectedVariantId === {{ $variant->id }} ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300'"
                                                @click="selectVariant({{ $variant->id }})">
                                            {{ $variant->options[0]->value ?? 'Opción' }}
                                            @if(count($variant->options) > 1)
                                                ({{ $variant->options[1]->value }})
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock alerts -->
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-2 w-2 rounded-full" 
                                  :class="outOfStock ? 'bg-red-500' : (lowStock ? 'bg-amber-500' : 'bg-green-500')"></span>
                            <span class="text-xs font-semibold text-slate-600" 
                                  x-text="outOfStock ? 'Agotado' : (lowStock ? 'Stock bajo: ' + stock + ' disponibles' : 'Disponible: ' + stock + ' unidades')">
                            </span>
                        </div>

                        <!-- Quantity Selector & Action Button -->
                        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                            <div class="w-32">
                                <label for="quantity" class="sr-only">Cantidad</label>
                                <select name="quantity" id="quantity" 
                                        class="w-full text-sm py-2 px-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                        :disabled="outOfStock">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'unidad' : 'unidades' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <button type="submit" 
                                    class="flex-grow flex items-center justify-center py-2.5 px-6 border text-sm font-bold rounded-xl text-white transition-all shadow-sm"
                                    :class="outOfStock ? 'bg-slate-300 border-slate-300 cursor-not-allowed' : 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700 shadow-indigo-600/10'"
                                    :disabled="outOfStock"
                                    x-text="outOfStock ? 'Agotado' : 'Añadir al carrito'">
                                Añadir al carrito
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Review Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8 lg:p-12">
        <h2 class="text-xl font-bold text-slate-900 mb-8 border-b border-slate-100 pb-4">
            Reseñas y valoraciones de clientes
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column: Summary and Add Review form -->
            <div class="md:col-span-1 space-y-6">
                <!-- Add Review Form -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Escribe tu opinión</h3>
                    
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Name input if guest -->
                        @guest
                            <div>
                                <label for="reviewer_name" class="block text-xs font-semibold text-slate-500 mb-2">Tu nombre</label>
                                <input type="text" name="name" id="reviewer_name" required 
                                       class="w-full text-sm px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-white">
                            </div>
                        @endguest

                        <!-- Rating selector -->
                        <div x-data="{ rating: 5 }">
                            <label class="block text-xs font-semibold text-slate-500 mb-2">Calificación</label>
                            <input type="hidden" name="rating" :value="rating">
                            <div class="flex items-center gap-1.5 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}" class="focus:outline-none transition-transform active:scale-95">
                                        <svg class="h-6 w-6" :class="rating >= {{ $i }} ? 'fill-current' : 'stroke-current fill-none'" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499c.178-.385.736-.385.915 0l2.09 4.238 4.678.68c.425.062.595.585.288.89l-3.386 3.298.8 4.66c.073.424-.37.747-.75.548L12 15.429l-4.185 2.2c-.38.198-.823-.125-.75-.548l.8-4.66-3.386-3.298c-.307-.305-.137-.827.288-.89l4.678-.68 2.09-4.238Z" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="comment" class="block text-xs font-semibold text-slate-500 mb-2">Opinión</label>
                            <textarea name="comment" id="comment" rows="4" 
                                      class="w-full text-sm px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-white" 
                                      placeholder="Comparte tu experiencia con este producto..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-2 px-4 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-colors shadow-sm">
                            Enviar valoración
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Reviews list -->
            <div class="md:col-span-2 space-y-6">
                @if(count($reviews) === 0)
                    <div class="text-center py-12 text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-sm">Aún no hay valoraciones para este producto. ¡Sé el primero en compartir tu opinión!</p>
                    </div>
                @else
                    <div class="space-y-6 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($reviews as $review)
                            <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-bold text-slate-800 block">
                                            {{ $review->name ?: ($review->user?->name ?: 'Anónimo') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ date('d M, Y', strtotime($review->created_at)) }}
                                        </span>
                                    </div>
                                    <!-- Stars -->
                                    <div class="flex items-center text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499c.178-.385.736-.385.915 0l2.09 4.238 4.678.68c.425.062.595.585.288.89l-3.386 3.298.8 4.66c.073.424-.37.747-.75.548L12 15.429l-4.185 2.2c-.38.198-.823-.125-.75-.548l.8-4.66-3.386-3.298c-.307-.305-.137-.827.288-.89l4.678-.68 2.09-4.238Z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 mt-2">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
