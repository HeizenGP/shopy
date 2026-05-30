<x-layouts.catalog title="{{ $category->name }}">
    
    <div class="container-wrapper" style="padding: 2rem 1.5rem 0;">
        <!-- Category Banner -->
        <div class="category-hero">
            <h1>Colección: {{ $category->name }}</h1>
            <p>{{ $category->description ?: 'Explora nuestra gama de artículos curados bajo altos estándares de calidad y durabilidad.' }}</p>
        </div>

        <div style="margin-bottom: 2rem;">
            <a href="{{ route('products.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--color-primary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver al catálogo completo
            </a>
        </div>

        <!-- Collection Results Grid -->
        <div class="category-products">
            <div class="product-grid" style="grid-column: span 3; width: 100%;">
                @forelse ($products as $product)
                    @include('catalog.public.products.partials.card', ['product' => $product])
                @empty
                    <div style="grid-column: span 4; text-align: center; padding: 5rem 0; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-muted); margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.25rem;">Colección vacía</h3>
                        <p style="color: var(--color-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Esta categoría no tiene productos publicados en este momento.</p>
                        <a href="{{ route('products.index') }}" class="button">Ver otros productos</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-nav">
            {{ $products->links() }}
        </div>
    </div>

</x-layouts.catalog>
