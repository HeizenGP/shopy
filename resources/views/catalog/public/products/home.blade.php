<x-layouts.catalog title="Inicio">
    
    <!-- Hero Section -->
    <section class="home-hero">
        <div class="home-copy">
            <span>Colección Premium 2026</span>
            <h1>Tecnología y Estilo que se adaptan a tu vida.</h1>
            <p>Explora nuestra curaduría de dispositivos electrónicos de alta gama, accesorios minimalistas de uso diario y organizadores ergonómicos para potenciar tu productividad.</p>
            <div class="home-actions">
                <a class="button" href="{{ route('products.index') }}">
                    Ver Catálogo Completo
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a class="home-link" href="#categorias">
                    Explorar colecciones
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                </a>
            </div>
        </div>
        
        @if ($featuredProducts->isNotEmpty())
            <div class="home-preview">
                @include('catalog.public.products.partials.card', ['product' => $featuredProducts->first()])
            </div>
        @endif
    </section>

    <!-- Value Props Section -->
    <section style="padding: 3rem 1.5rem; background: var(--color-surface); border-bottom: 1px solid var(--color-border);">
        <div class="container-wrapper" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="background: var(--color-secondary); color: var(--color-primary); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.15rem;">Pago 100% Seguro</h3>
                    <p style="font-size: 0.85rem; color: var(--color-muted);">Garantía de cifrado SSL en compras.</p>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="background: var(--color-secondary); color: var(--color-primary); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.15rem;">Envío Exprés</h3>
                    <p style="font-size: 0.85rem; color: var(--color-muted);">Despachos nacionales en 24/48 horas.</p>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="background: var(--color-secondary); color: var(--color-primary); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.15rem;">Garantía de Fábrica</h3>
                    <p style="font-size: 0.85rem; color: var(--color-muted);">Garantía de 1 año en todos los productos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section>
        <div class="container-wrapper">
            <div class="section-title">
                <div>
                    <p class="eyebrow">Lo más buscado</p>
                    <h2>Productos destacados en stock</h2>
                </div>
                <a class="section-link" href="{{ route('products.index') }}">Ver catálogo →</a>
            </div>
            
            <div class="product-grid">
                @forelse ($featuredProducts as $product)
                    @include('catalog.public.products.partials.card', ['product' => $product])
                @empty
                    <p style="grid-column: span 4; text-align: center; padding: 3rem 0; color: var(--color-muted);">No hay productos destacados publicados en este momento.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Promotion Callout Banner -->
    <section style="background: linear-gradient(135deg, var(--color-primary) 0%, color-mix(in srgb, var(--color-primary) 80%, #000) 100%); color: #fff; padding: 5rem 1.5rem;">
        <div class="container-wrapper" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1.5rem;">
            <span style="background: rgba(255, 255, 255, 0.18); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; padding: 0.35rem 0.85rem; border-radius: 20px; letter-spacing: 1px;">Descuento Especial de Temporada</span>
            <h2 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; max-width: 800px; line-height: 1.2; letter-spacing: -0.5px;">Ahorra hasta S/ 50.00 en tu primera compra con el cupón de bienvenida</h2>
            <p style="max-width: 600px; opacity: 0.85; font-size: 1.1rem; line-height: 1.6;">Aplica el código <strong style="color: var(--color-accent); text-transform: uppercase; font-size: 1.2rem; background: rgba(0,0,0,0.15); padding: 0.1rem 0.5rem; border-radius: 4px;">WELCOME10</strong> en la pantalla de checkout y obtén un descuento instantáneo.</p>
            <a class="button buy-now-btn" href="{{ route('products.index') }}" style="margin-top: 1rem; color: #000000 !important;">Comprar Ahora</a>
        </div>
    </section>

    <!-- Collections Section -->
    <section id="categorias" style="background: var(--color-bg);">
        <div class="container-wrapper">
            <div class="section-title">
                <div>
                    <p class="eyebrow">Colecciones</p>
                    <h2>Compra por categoría</h2>
                </div>
            </div>
            
            <div class="home-categories">
                @forelse ($categories as $category)
                    <a class="home-category" href="{{ route('categories.show', $category->slug) }}">
                        <strong>{{ $category->name }}</strong>
                        <span>{{ $category->description ?: 'Explora artículos especializados con stock dinámico.' }}</span>
                    </a>
                @empty
                    <p style="grid-column: span 3; text-align: center; color: var(--color-muted);">No hay categorías creadas todavía.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section style="background: var(--color-surface); border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
        <div class="container-wrapper">
            <div class="section-title" style="text-align: center; justify-content: center; border-left: 0; padding-left: 0; margin-bottom: 3.5rem;">
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <p class="eyebrow">Testimonios</p>
                    <h2 style="font-size: 2rem;">Lo que dicen nuestros clientes</h2>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-main); border: 1px solid var(--color-border); box-shadow: var(--shadow-soft);">
                    <div class="stars" style="margin-bottom: 1rem; color: #fbbf24;">
                        @for($i=0; $i<5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <p style="font-style: italic; margin-bottom: 1.5rem; color: var(--color-muted); line-height: 1.6;">"Los audífonos Nova Air tienen una cancelación de ruido increíble por el precio. El despacho fue sumamente rápido, me llegaron al día siguiente de comprar en Lima."</p>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-secondary); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">MC</div>
                        <div>
                            <h4 style="font-size: 0.9rem; font-weight: 700;">Mateo C.</h4>
                            <span style="font-size: 0.75rem; color: var(--color-muted);">Cliente Verificado</span>
                        </div>
                    </div>
                </div>

                <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-main); border: 1px solid var(--color-border); box-shadow: var(--shadow-soft);">
                    <div class="stars" style="margin-bottom: 1rem; color: #fbbf24;">
                        @for($i=0; $i<5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <p style="font-style: italic; margin-bottom: 1.5rem; color: var(--color-muted); line-height: 1.6;">"El teclado Apex Pro cambió totalmente mi setup de trabajo. Es súper silencioso y estéticamente se ve increíble. Recomiendo ShopCMS por su facilidad de compra."</p>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-secondary); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">SA</div>
                        <div>
                            <h4 style="font-size: 0.9rem; font-weight: 700;">Sofía A.</h4>
                            <span style="font-size: 0.75rem; color: var(--color-muted);">Cliente Verificado</span>
                        </div>
                    </div>
                </div>

                <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-main); border: 1px solid var(--color-border); box-shadow: var(--shadow-soft);">
                    <div class="stars" style="margin-bottom: 1rem; color: #fbbf24;">
                        @for($i=0; $i<5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <p style="font-style: italic; margin-bottom: 1.5rem; color: var(--color-muted); line-height: 1.6;">"Compré la mochila impermeable y es sumamente espaciosa. Entra mi laptop de 16 pulgadas y sobra espacio para mis libretas y cables. Todo excelente con la tienda."</p>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-secondary); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">JP</div>
                        <div>
                            <h4 style="font-size: 0.9rem; font-weight: 700;">Juan P.</h4>
                            <span style="font-size: 0.75rem; color: var(--color-muted);">Cliente Verificado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.catalog>
