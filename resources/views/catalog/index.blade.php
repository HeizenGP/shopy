@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm sticky top-24">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Filtros</h3>
                
                <form action="{{ route('catalog.index') }}" method="GET" class="space-y-6">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-xs font-semibold text-slate-500 mb-2">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="w-full text-sm px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" 
                               placeholder="¿Qué estás buscando?">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-xs font-semibold text-slate-500 mb-2">Categoría</label>
                        <select name="category" id="category" 
                                class="w-full text-sm px-3 py-2 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price range -->
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 mb-2">Precio</span>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   class="w-full text-xs px-2.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20" 
                                   placeholder="Min">
                            <span class="text-slate-400 text-xs">a</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   class="w-full text-xs px-2.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20" 
                                   placeholder="Max">
                        </div>
                    </div>

                    <!-- Submit & Clear buttons -->
                    <div class="space-y-2 pt-2">
                        <button type="submit" class="w-full flex items-center justify-center py-2 px-4 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm">
                            Aplicar filtros
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price']))
                            <a href="{{ route('catalog.index') }}" class="w-full flex items-center justify-center py-2 px-4 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                                Limpiar filtros
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-grow">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Productos</h1>
                    <p class="text-xs text-slate-400 mt-1">Mostrando {{ count($products) }} productos disponibles</p>
                </div>
            </div>

            @if(count($products) === 0)
                <div class="bg-white text-center py-16 px-4 rounded-3xl border border-slate-100 shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900">No hay productos</h3>
                    <p class="mt-1 text-sm text-slate-500">Prueba cambiando tus términos de búsqueda o filtros.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white border border-slate-100 rounded-3xl overflow-hidden hover:shadow-lg transition-all flex flex-col h-full">
                            <!-- Image container -->
                            <div class="aspect-square w-full bg-slate-100 relative overflow-hidden">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="flex items-center justify-center h-full w-full text-slate-400 font-bold bg-gradient-to-tr from-slate-200 to-slate-100">
                                        {{ $product->name }}
                                    </div>
                                @endif
                                
                                @if(!empty($product->variants))
                                    <span class="absolute top-3 left-3 bg-white/95 text-slate-800 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm border border-slate-100">
                                        {{ count($product->variants) }} variantes
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 mb-1">
                                    {{ $product->category ?: 'General' }}
                                </span>
                                <h3 class="text-base font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('catalog.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                                
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                                    <div>
                                        <span class="text-xs text-slate-400 block">Desde</span>
                                        <span class="text-base font-bold text-slate-900">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="inline-flex items-center justify-center p-2 rounded-xl text-indigo-600 bg-indigo-50 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
