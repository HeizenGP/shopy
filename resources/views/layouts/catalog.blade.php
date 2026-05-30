<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Shopy') }} | Premium Store</title>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Apply theme instantly before page loads to avoid flicker
            const savedTheme = localStorage.getItem('shopy-theme') || 'default';
            document.documentElement.setAttribute('data-theme', savedTheme);
        </script>
    </head>
    <body @class(['catalog-admin' => request()->routeIs('admin.catalog.*')])>
        
        <!-- Site Header -->
        <header class="site-header">
            <div class="header-container">
                <!-- Brand / Logo -->
                <a class="brand" href="{{ route('home') }}">
                    <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span>Shop<span>CMS</span></span>
                </a>

                <!-- Desktop Nav Navigation -->
                <nav class="nav desktop-nav">
                    <a @class(['is-active' => request()->routeIs('home')]) href="{{ route('home') }}">Inicio</a>
                    <a @class(['is-active' => request()->routeIs('products.index') && !request()->routeIs('categories.*')]) href="{{ route('products.index') }}">Catálogo</a>
                    
                    <!-- Categories Dropdown Trigger -->
                    <div class="nav-dropdown-wrapper">
                        <a class="dropdown-trigger" href="#categories">
                            Categorías
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </a>
                        <div class="nav-dropdown-content">
                            <a href="{{ route('products.index') }}">Todas las Categorías</a>
                            <a href="{{ route('categories.show', 'electronica') }}">Electrónica</a>
                            <a href="{{ route('categories.show', 'hogar-oficina') }}">Hogar & Oficina</a>
                            <a href="{{ route('categories.show', 'accesorios') }}">Accesorios</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('products.index') }}?featured=1">Destacados</a>
                </nav>

                <!-- Actions (Theme, Search, Cart, Mobile Menu) -->
                <div class="header-actions">
                    
                    <!-- Theme Selector Dropdown -->
                    <div class="theme-dropdown-wrapper">
                        <button class="icon-button" id="themeBtn" aria-label="Cambiar Tema" title="Cambiar Combinación de Colores">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/><path d="m5 3 2.25 2.25"/><path d="m19 19 2.25 2.25"/><path d="m19 3 2.25 2.25"/><path d="m5 19 2.25 2.25"/></svg>
                        </button>
                        <div class="theme-dropdown-content">
                            <div class="dropdown-header">Elegir Combinación Visual</div>
                            <button onclick="setGlobalTheme('default')" class="theme-opt default" data-theme-val="default">
                                <span class="theme-dot"></span> Amethyst Store (Default)
                            </button>
                            <button onclick="setGlobalTheme('navy')" class="theme-opt navy" data-theme-val="navy">
                                <span class="theme-dot"></span> Tech Navy
                            </button>
                            <button onclick="setGlobalTheme('emerald')" class="theme-opt emerald" data-theme-val="emerald">
                                <span class="theme-dot"></span> Emerald Organic
                            </button>
                            <button onclick="setGlobalTheme('orange')" class="theme-opt orange" data-theme-val="orange">
                                <span class="theme-dot"></span> Sunset Orange
                            </button>
                            <button onclick="setGlobalTheme('black')" class="theme-opt black" data-theme-val="black">
                                <span class="theme-dot"></span> Charcoal Minimal
                            </button>
                            <button onclick="setGlobalTheme('dark')" class="theme-opt dark" data-theme-val="dark">
                                <span class="theme-dot"></span> Luxury Dark Mode
                            </button>
                        </div>
                    </div>

                    <!-- Search Trigger -->
                    <button class="icon-button" onclick="openSearchModal()" aria-label="Buscar" title="Buscar productos">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>

                    <!-- Cart Trigger -->
                    <button class="cart-trigger-btn" onclick="toggleCartDrawer(true)" aria-label="Ver Carrito">
                        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        <span class="cart-badge-count" id="cartCountBadge">0</span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button class="icon-button mobile-menu-btn" onclick="toggleMobileMenu(true)" aria-label="Abrir Menú">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Side Navigation -->
        <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="toggleMobileMenu(false)"></div>
        <div class="mobile-nav-panel" id="mobileNavPanel">
            <div class="panel-header">
                <strong>Navegación</strong>
                <button class="icon-button close-btn" onclick="toggleMobileMenu(false)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <div class="panel-links">
                <a href="{{ route('home') }}" onclick="toggleMobileMenu(false)">Inicio</a>
                <a href="{{ route('products.index') }}" onclick="toggleMobileMenu(false)">Catálogo</a>
                <div class="mobile-sub-header">Categorías</div>
                <a class="sub-link" href="{{ route('categories.show', 'electronica') }}" onclick="toggleMobileMenu(false)">— Electrónica</a>
                <a class="sub-link" href="{{ route('categories.show', 'hogar-oficina') }}" onclick="toggleMobileMenu(false)">— Hogar & Oficina</a>
                <a class="sub-link" href="{{ route('categories.show', 'accesorios') }}" onclick="toggleMobileMenu(false)">— Accesorios</a>
                <div class="mobile-sub-header">Configuraciones de Color</div>
                <div class="mobile-theme-selector">
                    <button onclick="setGlobalTheme('default')" class="m-theme-btn default">Amethyst</button>
                    <button onclick="setGlobalTheme('navy')" class="m-theme-btn navy">Navy</button>
                    <button onclick="setGlobalTheme('emerald')" class="m-theme-btn emerald">Emerald</button>
                    <button onclick="setGlobalTheme('orange')" class="m-theme-btn orange">Orange</button>
                    <button onclick="setGlobalTheme('black')" class="m-theme-btn black">Charcoal</button>
                    <button onclick="setGlobalTheme('dark')" class="m-theme-btn dark">Luxury Dark</button>
                </div>
            </div>
        </div>

        <!-- Sliding Cart Drawer -->
        <div class="cart-drawer-overlay" id="cartDrawerOverlay" onclick="toggleCartDrawer(false)"></div>
        <div class="cart-drawer" id="cartDrawer">
            <div class="cart-drawer-header">
                <h3>Carrito de Compras</h3>
                <button class="icon-button close-btn" onclick="toggleCartDrawer(false)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            
            <!-- Dynamic Cart Items Container -->
            <div class="cart-drawer-items" id="cartDrawerItems">
                <!-- Javascript will render items here -->
                <div class="cart-empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <p>Tu carrito está vacío</p>
                    <a href="{{ route('products.index') }}" class="button" onclick="toggleCartDrawer(false)">Comenzar a comprar</a>
                </div>
            </div>

            <!-- Drawer Footer -->
            <div class="cart-drawer-footer" id="cartDrawerFooter" style="display: none;">
                <div class="cart-summary-line">
                    <span>Subtotal</span>
                    <strong id="cartDrawerSubtotal">S/ 0.00</strong>
                </div>
                <p class="shipping-notice">Envío e impuestos calculados al procesar el pago.</p>
                <div class="cart-actions-stack">
                    <a href="{{ route('cart') }}" class="button secondary-btn" onclick="toggleCartDrawer(false)">Ver Carrito Detallado</a>
                    <a href="{{ route('checkout') }}" class="button checkout-action-btn" onclick="toggleCartDrawer(false)">
                        Proceder al Pago
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Overlay Modal -->
        <div class="search-modal" id="searchModal">
            <div class="search-modal-backdrop" onclick="closeSearchModal()"></div>
            <div class="search-modal-content">
                <div class="search-modal-header">
                    <h2>Buscar en la tienda</h2>
                    <button class="icon-button" onclick="closeSearchModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                <form class="search-modal-form" action="{{ route('products.index') }}" method="GET">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="search" name="search" id="searchInput" placeholder="Escribe el nombre del producto, marca o SKU..." autocomplete="off">
                        <button type="submit" class="button">Buscar</button>
                    </div>
                </form>
                <div class="search-suggestions">
                    <span>Sugerencias populares:</span>
                    <div class="suggestion-tags">
                        <a href="{{ route('products.index') }}?search=Audifonos">Audífonos</a>
                        <a href="{{ route('products.index') }}?search=Teclado">Teclados</a>
                        <a href="{{ route('products.index') }}?search=Mochila">Mochilas</a>
                        <a href="{{ route('products.index') }}?search=Reloj">Relojes</a>
                        <a href="{{ route('products.index') }}?search=Lumina">Lumina</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notifications Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Flash messages -->
        @if (session('status'))
            <div class="flash-toast">
                <div class="flash">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash-toast">
                <div class="flash flash-error">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <!-- Main Slot -->
        <main>
            {{ $slot }}
        </main>

        <!-- Site Footer -->
        <footer class="site-footer">
            <div class="footer-top">
                <div class="footer-brand-area">
                    <a class="footer-brand" href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span>Shop<span>CMS</span></span>
                    </a>
                    <p class="footer-desc">El CMS definitivo para tiendas virtuales elegantes, rápidas y personalizables con control total de stock e inventario.</p>
                    <div class="footer-socials">
                        <a href="#" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
                        <a href="#" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg></a>
                        <a href="#" aria-label="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
                    </div>
                </div>
                
                <div class="footer-links-grid">
                    <div>
                        <h3>Tienda</h3>
                        <ul>
                            <li><a href="{{ route('products.index') }}">Todos los productos</a></li>
                            <li><a href="{{ route('products.index') }}?featured=1">Productos Destacados</a></li>
                            <li><a href="{{ route('products.index') }}?sale=1">En Oferta</a></li>
                            <li><a href="{{ route('home') }}#categorias">Categorías Principales</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Soporte</h3>
                        <ul>
                            <li><a href="#">Preguntas Frecuentes</a></li>
                            <li><a href="#">Políticas de Envío</a></li>
                            <li><a href="#">Devoluciones y Garantía</a></li>
                            <li><a href="#">Términos del Servicio</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Boletín Informativo</h3>
                        <p class="newsletter-text">Subscríbete para enterarte antes que nadie de ofertas y nuevos productos en catálogo.</p>
                        <form class="footer-newsletter-form" onsubmit="event.preventDefault(); showToast('¡Gracias por suscribirte!', 'success'); this.reset();">
                            <input type="email" placeholder="Tu correo electrónico" required>
                            <button type="submit" class="button">Unirme</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} ShopCMS Client. Diseñado profesionalmente para ShopCMS.</p>
                <div class="payment-methods">
                    <!-- Visa / Mastercard / Paypal mock icons -->
                    <span class="pay-badge" title="Visa">Visa</span>
                    <span class="pay-badge" title="Mastercard">Mastercard</span>
                    <span class="pay-badge" title="PayPal">PayPal</span>
                    <span class="pay-badge" title="Apple Pay">Apple Pay</span>
                </div>
            </div>
        </footer>

        <!-- Global Javascript for Frontend Operations -->
        <script>
            // Theme Manager
            function setGlobalTheme(themeName) {
                document.documentElement.setAttribute('data-theme', themeName);
                localStorage.setItem('shopy-theme', themeName);
                
                // Highlight selected theme option
                document.querySelectorAll('.theme-opt').forEach(opt => {
                    if (opt.getAttribute('data-theme-val') === themeName) {
                        opt.classList.add('is-active');
                    } else {
                        opt.classList.remove('is-active');
                    }
                });
                
                showToast(`Tema cambiado a ${themeName.charAt(0).toUpperCase() + themeName.slice(1)}`, 'success');
            }
            
            // Set initial active state in theme dropdown
            document.addEventListener('DOMContentLoaded', () => {
                const currentTheme = localStorage.getItem('shopy-theme') || 'default';
                const activeOpt = document.querySelector(`.theme-opt[data-theme-val="${currentTheme}"]`);
                if (activeOpt) activeOpt.classList.add('is-active');
            });

            // Mobile Menu
            function toggleMobileMenu(open) {
                const panel = document.getElementById('mobileNavPanel');
                const overlay = document.getElementById('mobileNavOverlay');
                if (open) {
                    panel.classList.add('is-open');
                    overlay.classList.add('is-visible');
                    document.body.style.overflow = 'hidden';
                } else {
                    panel.classList.remove('is-open');
                    overlay.classList.remove('is-visible');
                    document.body.style.overflow = '';
                }
            }

            // Search Modal
            function openSearchModal() {
                const modal = document.getElementById('searchModal');
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    document.getElementById('searchInput').focus();
                }, 100);
            }
            function closeSearchModal() {
                const modal = document.getElementById('searchModal');
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            // Toast Notifications
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                // Icon selection
                let icon = '';
                if (type === 'success') {
                    icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
                } else if (type === 'error') {
                    icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                } else {
                    icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';
                }
                
                toast.innerHTML = `
                    <span class="toast-icon">${icon}</span>
                    <span class="toast-msg">${message}</span>
                `;
                
                container.appendChild(toast);
                
                // Animation in
                setTimeout(() => toast.classList.add('is-visible'), 10);
                
                // Remove toast after 3.5s
                setTimeout(() => {
                    toast.classList.remove('is-visible');
                    setTimeout(() => toast.remove(), 300);
                }, 3500);
            }

            // Cart Drawer and LocalStorage Operations
            let cart = [];
            
            function loadCart() {
                try {
                    const savedCart = localStorage.getItem('shopy-cart');
                    cart = savedCart ? JSON.parse(savedCart) : [];
                } catch(e) {
                    cart = [];
                }
                updateCartUI();
            }

            function saveCart() {
                localStorage.setItem('shopy-cart', JSON.stringify(cart));
                updateCartUI();
                
                // Dispatch custom event to notify components (like detail or checkout)
                window.dispatchEvent(new CustomEvent('shopy-cart-updated', { detail: cart }));
            }

            function addToCart(product, quantity = 1, variantName = null) {
                const qty = parseInt(quantity) || 1;
                const uniqueId = product.id + (variantName ? `-${variantName.replace(/\s+/g, '-').toLowerCase()}` : '');
                
                const existingItemIndex = cart.findIndex(item => item.uniqueId === uniqueId);
                
                if (existingItemIndex > -1) {
                    cart[existingItemIndex].quantity += qty;
                } else {
                    cart.push({
                        uniqueId: uniqueId,
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        formattedPrice: product.formattedPrice,
                        slug: product.slug,
                        image: product.image || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&auto=format&fit=crop&q=60',
                        variant: variantName,
                        quantity: qty,
                        maxStock: 99 // mock stock limit
                    });
                }
                
                saveCart();
                toggleCartDrawer(true);
                showToast(`¡"${product.name}" agregado al carrito!`, 'success');
            }

            function updateQuantity(uniqueId, change) {
                const itemIndex = cart.findIndex(item => item.uniqueId === uniqueId);
                if (itemIndex > -1) {
                    cart[itemIndex].quantity += change;
                    if (cart[itemIndex].quantity <= 0) {
                        cart.splice(itemIndex, 1);
                        showToast('Producto eliminado del carrito', 'info');
                    }
                    saveCart();
                }
            }

            // Public method for external script files to hook into
            window.removeFromCart = function(uniqueId) {
                const itemIndex = cart.findIndex(item => item.uniqueId === uniqueId);
                if (itemIndex > -1) {
                    const name = cart[itemIndex].name;
                    cart.splice(itemIndex, 1);
                    saveCart();
                    showToast(`"${name}" eliminado del carrito`, 'info');
                }
            }

            function toggleCartDrawer(open) {
                const drawer = document.getElementById('cartDrawer');
                const overlay = document.getElementById('cartDrawerOverlay');
                if (open) {
                    drawer.classList.add('is-open');
                    overlay.classList.add('is-visible');
                    document.body.style.overflow = 'hidden';
                } else {
                    drawer.classList.remove('is-open');
                    overlay.classList.remove('is-visible');
                    document.body.style.overflow = '';
                }
            }

            function updateCartUI() {
                const itemsContainer = document.getElementById('cartDrawerItems');
                const footerContainer = document.getElementById('cartDrawerFooter');
                const badge = document.getElementById('cartCountBadge');
                
                // Calculate totals
                let totalItemsCount = 0;
                let subtotal = 0;
                
                cart.forEach(item => {
                    totalItemsCount += item.quantity;
                    subtotal += item.price * item.quantity;
                });
                
                // Update Badge
                badge.innerText = totalItemsCount;
                badge.style.display = totalItemsCount > 0 ? 'flex' : 'none';
                
                if (cart.length === 0) {
                    itemsContainer.innerHTML = `
                        <div class="cart-empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <p>Tu carrito está vacío</p>
                            <a href="{{ route('products.index') }}" class="button" onclick="toggleCartDrawer(false)">Comenzar a comprar</a>
                        </div>
                    `;
                    footerContainer.style.display = 'none';
                } else {
                    footerContainer.style.display = 'block';
                    document.getElementById('cartDrawerSubtotal').innerText = 'S/ ' + subtotal.toFixed(2);
                    
                    let itemsHtml = '';
                    cart.forEach(item => {
                        itemsHtml += `
                            <div class="cart-item">
                                <img class="cart-item-image" src="${item.image}" alt="${item.name}">
                                <div class="cart-item-details">
                                    <h4 class="cart-item-title"><a href="/products/${item.slug}">${item.name}</a></h4>
                                    ${item.variant ? `<span class="cart-item-variant">Variante: ${item.variant}</span>` : ''}
                                    <div class="cart-item-price">S/ ${item.price.toFixed(2)}</div>
                                    <div class="cart-item-qty-row">
                                        <div class="qty-selector">
                                            <button type="button" onclick="updateQuantity('${item.uniqueId}', -1)" aria-label="Disminuir cantidad">—</button>
                                            <span>${item.quantity}</span>
                                            <button type="button" onclick="updateQuantity('${item.uniqueId}', 1)" aria-label="Aumentar cantidad">+</button>
                                        </div>
                                        <button class="remove-btn" onclick="removeFromCart('${item.uniqueId}')" aria-label="Eliminar artículo">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    itemsContainer.innerHTML = itemsHtml;
                }
            }

            // Init Cart on load
            window.addEventListener('load', loadCart);
        </script>
    </body>
</html>
