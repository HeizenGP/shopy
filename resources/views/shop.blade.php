<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopy Storefront - SOLID + Hexagonal</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Inline styles for a gorgeous premium dark-themed SPA -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }
        body {
            background-color: #030712;
            color: #f3f4f6;
            min-height: 100vh;
            position: relative;
            padding-bottom: 4rem;
        }
        /* Glow decorations */
        .ambient-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            top: -150px;
            left: -150px;
            z-index: 1;
            filter: blur(50px);
            pointer-events: none;
        }
        .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: 0px;
            right: -100px;
            z-index: 1;
            filter: blur(40px);
            pointer-events: none;
        }

        /* Navbar */
        header {
            position: relative;
            z-index: 10;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(3, 7, 18, 0.8);
            backdrop-filter: blur(12px);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2rem;
        }
        .brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            letter-spacing: -0.05em;
        }
        .admin-link {
            text-decoration: none;
            color: #9ca3af;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s ease;
        }
        .admin-link:hover {
            color: #ffffff;
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        /* Hero */
        .hero {
            text-align: center;
            padding: 3rem 2rem 2rem 2rem;
            position: relative;
            z-index: 10;
        }
        .hero h1 {
            font-size: 2.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #f3f4f6, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            color: #9ca3af;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.05rem;
        }

        /* Layout Grid */
        .main-layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2.5rem;
            position: relative;
            z-index: 10;
        }
        @media (max-width: 900px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            background: rgba(17, 24, 39, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            background: rgba(17, 24, 39, 0.6);
        }
        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #f9fafb;
        }
        .product-desc {
            color: #9ca3af;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            line-height: 1.4;
            flex-grow: 1;
        }
        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 1rem;
        }
        .variant-select {
            width: 100%;
            padding: 0.6rem;
            border-radius: 8px;
            background: #1f2937;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #d1d5db;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            outline: none;
        }
        .btn-add {
            width: 100%;
            padding: 0.75rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.15);
        }
        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.25);
        }

        /* Sidebar Column */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .sidebar-section {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #f9fafb;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 0.75rem;
        }

        /* Cart Items */
        .cart-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 1.25rem;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
        }
        .cart-item-info {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .cart-item-name {
            font-weight: 500;
            color: #e5e7eb;
        }
        .cart-item-qty {
            font-size: 0.75rem;
            color: #9ca3af;
        }
        .cart-item-price {
            font-weight: 600;
            color: #f3f4f6;
        }

        /* Coupon Form */
        .coupon-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .input-text {
            flex-grow: 1;
            padding: 0.6rem 0.875rem;
            border-radius: 8px;
            background: rgba(31, 41, 55, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.85rem;
            outline: none;
        }
        .input-text:focus {
            border-color: #6366f1;
        }
        .btn-secondary {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            background: #1f2937;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #d1d5db;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            color: white;
            background: #374151;
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Summary */
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: #9ca3af;
        }
        .summary-total {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            font-weight: 700;
            font-size: 1.15rem;
            color: #f9fafb;
        }

        /* Forms */
        .checkout-form {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .form-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .form-field {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .btn-primary {
            width: 100%;
            padding: 0.8rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
            text-align: center;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.35);
        }

        /* Alert notifications */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            background: #1f2937;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 100;
            animation: slide-up 0.3s ease-out;
        }
        @keyframes slide-up {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Ambient Decoration -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <header>
        <div class="nav-container">
            <a href="#" class="brand">Shopy Store.</a>
            <a href="/login" class="admin-link">Acceso Admin</a>
        </div>
    </header>

    <div class="hero">
        <h1>Demostración Visual Shopy</h1>
        <p>Esta interfaz interactiva SPA utiliza todos los controladores y casos de uso del backend desarrollados con Arquitectura Hexagonal y principios SOLID.</p>
    </div>

    <div class="main-layout">
        <!-- Catalog Column -->
        <div>
            <h2 class="section-title">Productos Disponibles</h2>
            <div id="products-container" class="product-grid">
                <!-- Products loaded dynamically -->
                <p style="color: #9ca3af;">Cargando catálogo...</p>
            </div>
        </div>

        <!-- Sidebar (Cart + Checkout + Payment) -->
        <div class="sidebar">
            <!-- Cart Box -->
            <div class="sidebar-section">
                <div class="section-title">
                    <span>Mi Carrito</span>
                    <span id="cart-count" style="font-size: 0.8rem; background: #6366f1; color: white; padding: 0.1rem 0.5rem; border-radius: 10px;">0</span>
                </div>
                <div id="cart-list" class="cart-list">
                    <p style="color: #9ca3af; text-align: center; padding: 1rem 0;">Tu carrito está vacío.</p>
                </div>

                <!-- Coupon Input -->
                <div class="coupon-form">
                    <input type="text" id="coupon-code" class="input-text" placeholder="Cupón (SAVE10 o HALFOFF)">
                    <button onclick="applyCoupon()" class="btn-secondary">Aplicar</button>
                </div>

                <!-- Totals Summary -->
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 1rem;">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">$0.00</span>
                    </div>
                    <div class="summary-row" id="discount-row" style="display: none; color: #10b981;">
                        <span>Descuento (<span id="discount-name"></span>)</span>
                        <span id="summary-discount">-$0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="summary-total">$0.00</span>
                    </div>
                </div>
            </div>

            <!-- Checkout / Payment Steps Section -->
            <div id="action-section" class="sidebar-section" style="display: none;">
                <!-- Step 1: Checkout Form -->
                <div id="checkout-step">
                    <h3 class="section-title">Datos del Pedido</h3>
                    <form onsubmit="placeOrder(event)" class="checkout-form">
                        <div class="form-field">
                            <label class="form-label" for="c_name">Nombre completo</label>
                            <input type="text" id="c_name" class="input-text" placeholder="John Doe" required value="Comprador de Prueba">
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="c_email">Correo electrónico</label>
                            <input type="email" id="c_email" class="input-text" placeholder="john@example.com" required value="comprador@example.com">
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="c_address">Dirección Envío</label>
                            <input type="text" id="c_address" class="input-text" placeholder="Av. Primavera 123" required value="Av. Diagonal 456, Lima">
                        </div>
                        <button type="submit" class="btn-primary">Proceder al Pago</button>
                    </form>
                </div>

                <!-- Step 2: Payment Form -->
                <div id="payment-step" style="display: none;">
                    <h3 class="section-title">Pagar Pedido #<span id="payment-order-id"></span></h3>
                    <div style="font-size: 0.9rem; color: #9ca3af; margin-bottom: 1.25rem;">
                        Total a pagar: <strong id="payment-total-amount" style="color: white;">$0.00</strong>
                    </div>
                    <form onsubmit="processPayment(event)" class="checkout-form">
                        <div class="form-field">
                            <label class="form-label" for="gateway">Pasarela</label>
                            <select id="gateway" class="variant-select">
                                <option value="paypal">PayPal</option>
                                <option value="culqi">Culqi</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="pay_token">Token de Pago</label>
                            <select id="pay_token" class="variant-select">
                                <option value="valid-token">Tarjeta Válida (Aprobar Pago)</option>
                                <option value="invalid-token">Tarjeta Declinada (Rechazar Pago)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary">Pagar Ahora</button>
                    </form>
                </div>

                <!-- Step 3: Success Screen -->
                <div id="success-step" style="display: none; text-align: center; padding: 1.5rem 0;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 2px solid #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                        <svg width="28" height="28" viewBox="0 0 20 20" fill="#10b981">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">¡Pedido Pagado con éxito!</h3>
                    <p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 1.5rem;">El pedido #<span id="success-order-id"></span> se ha procesado exitosamente en el sistema.</p>
                    <button onclick="resetShop()" class="btn-secondary" style="width: 100%;">Volver a Comprar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Toast -->
    <div id="toast" class="toast"></div>

    <script>
        // Storefront SPA Client Logic
        let sessionId = localStorage.getItem('shopy_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('shopy_session_id', sessionId);
        }

        let products = [];
        let cart = { items: [] };
        let activeCoupon = null;
        let couponDiscount = 0.00;
        let createdOrderId = null;

        // On Load
        window.onload = function() {
            loadCatalog();
            loadCart();
        };

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Fetch products
        function loadCatalog() {
            fetch('/api/products')
                .then(res => res.json())
                .then(data => {
                    products = data;
                    renderCatalog();
                })
                .catch(err => console.error("Error loading products", err));
        }

        // Render products
        function renderCatalog() {
            const container = document.getElementById('products-container');
            if (products.length === 0) {
                container.innerHTML = '<p style="color: #9ca3af;">No hay productos publicados.</p>';
                return;
            }

            container.innerHTML = products.map(product => {
                const hasVariants = product.variants && product.variants.length > 0;
                let variantsDropdown = '';
                if (hasVariants) {
                    variantsDropdown = `
                        <select id="variant-select-${product.id}" class="variant-select">
                            ${product.variants.map(v => `
                                <option value="${v.id}">${v.name} - ${v.formattedPrice || product.formatted_price}</option>
                            `).join('')}
                        </select>
                    `;
                }

                return `
                    <div class="product-card">
                        <div>
                            <h3 class="product-title">${product.name}</h3>
                            <p class="product-desc">${product.description || ''}</p>
                        </div>
                        <div>
                            <div class="product-price">${product.formatted_price}</div>
                            ${variantsDropdown}
                            <button onclick="addToCart(${product.id})" class="btn-add">Añadir al Carrito</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Fetch cart
        function loadCart() {
            fetch(`/api/cart?session_id=${sessionId}`)
                .then(res => res.json())
                .then(data => {
                    cart = data;
                    updateCartUI();
                })
                .catch(err => console.error("Error loading cart", err));
        }

        // Add to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;

            let variantId = null;
            const variantSelect = document.getElementById(`variant-select-${productId}`);
            if (variantSelect) {
                variantId = variantSelect.value;
            }

            fetch('/api/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    product_id: productId,
                    product_variant_id: variantId,
                    quantity: 1
                })
            })
            .then(res => res.json())
            .then(data => {
                cart = data;
                updateCartUI();
                showToast(`¡${product.name} añadido al carrito!`);
            })
            .catch(err => console.error("Error adding to cart", err));
        }

        // Update Cart UI & Recalculate summary totals
        function updateCartUI() {
            const list = document.getElementById('cart-list');
            const cartCount = document.getElementById('cart-count');

            if (!cart.items || cart.items.length === 0) {
                list.innerHTML = '<p style="color: #9ca3af; text-align: center; padding: 1.5rem 0;">Tu carrito está vacío.</p>';
                cartCount.textContent = '0';
                document.getElementById('action-section').style.display = 'none';
                updateTotals(0.00);
                return;
            }

            cartCount.textContent = cart.items.reduce((sum, item) => sum + item.quantity, 0);

            // Compute subtotal on client by looking up product & variant prices
            let subtotal = 0.00;
            list.innerHTML = cart.items.map(item => {
                const product = products.find(p => p.id === item.product_id);
                if (!product) return '';

                let name = product.name;
                let price = product.price;

                if (item.product_variant_id && product.variants) {
                    const variant = product.variants.find(v => v.id === item.product_variant_id);
                    if (variant) {
                        name += ` (${variant.name})`;
                        if (variant.price) {
                            price = variant.price;
                        }
                    }
                }

                const itemTotal = price * item.quantity;
                subtotal += itemTotal;

                return `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <span class="cart-item-name">${name}</span>
                            <span class="cart-item-qty">Cant: ${item.quantity}</span>
                        </div>
                        <div class="cart-item-price">$${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            }).join('');

            document.getElementById('action-section').style.display = 'block';
            updateTotals(subtotal);
        }

        // Update totals
        function updateTotals(subtotal) {
            document.getElementById('summary-subtotal').textContent = `$${subtotal.toFixed(2)}`;

            let discount = 0.00;
            if (activeCoupon) {
                if (activeCoupon.type === 'percentage') {
                    discount = subtotal * (activeCoupon.value / 100);
                } else {
                    discount = activeCoupon.value;
                }
                discount = Math.min(discount, subtotal);
                document.getElementById('discount-row').style.display = 'flex';
                document.getElementById('discount-name').textContent = activeCoupon.code;
                document.getElementById('summary-discount').textContent = `-$${discount.toFixed(2)}`;
            } else {
                document.getElementById('discount-row').style.display = 'none';
            }

            const total = Math.max(0.00, subtotal - discount);
            document.getElementById('summary-total').textContent = `$${total.toFixed(2)}`;
            couponDiscount = discount;
        }

        // Apply coupon
        function applyCoupon() {
            const codeInput = document.getElementById('coupon-code').value.trim();
            if (!codeInput) {
                showToast("Por favor ingresa un código.");
                return;
            }

            // Calculate current subtotal
            let subtotal = 0.00;
            cart.items.forEach(item => {
                const product = products.find(p => p.id === item.product_id);
                if (product) {
                    let price = product.price;
                    if (item.product_variant_id && product.variants) {
                        const variant = product.variants.find(v => v.id === item.product_variant_id);
                        if (variant && variant.price) price = variant.price;
                    }
                    subtotal += price * item.quantity;
                }
            });

            fetch('/api/coupons/validate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    code: codeInput,
                    total_amount: subtotal
                })
            })
            .then(res => {
                if (res.ok) return res.json();
                return res.json().then(json => { throw new Error(json.error || "Cupón inválido"); });
            })
            .then(data => {
                activeCoupon = data.coupon;
                updateCartUI();
                showToast(`¡Cupón ${activeCoupon.code} aplicado con éxito!`);
            })
            .catch(err => {
                showToast(err.message);
                activeCoupon = null;
                updateCartUI();
            });
        }

        // Checkout
        function placeOrder(e) {
            e.preventDefault();

            const cName = document.getElementById('c_name').value;
            const cEmail = document.getElementById('c_email').value;
            const cAddress = document.getElementById('c_address').value;
            const couponCode = activeCoupon ? activeCoupon.code : null;

            fetch('/api/orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    shipping_address: cAddress,
                    billing_address: cAddress,
                    customer_name: cName,
                    customer_email: cEmail,
                    coupon_code: couponCode
                })
            })
            .then(res => {
                if (res.ok) return res.json();
                return res.json().then(json => { throw new Error(json.error || "Error al procesar el pedido."); });
            })
            .then(data => {
                createdOrderId = data.id;

                // Move to Payment step
                document.getElementById('checkout-step').style.display = 'none';
                document.getElementById('payment-step').style.display = 'block';
                document.getElementById('payment-order-id').textContent = data.id;
                document.getElementById('payment-total-amount').textContent = `$${data.total_amount.toFixed(2)}`;

                // Reset cart local state (backend cleared it)
                cart = { items: [] };
                activeCoupon = null;
                document.getElementById('coupon-code').value = '';
                updateCartUI();

                showToast("¡Pedido creado! Esperando pago...");
            })
            .catch(err => {
                showToast(err.message);
            });
        }

        // Process Payment
        function processPayment(e) {
            e.preventDefault();

            const gateway = document.getElementById('gateway').value;
            const token = document.getElementById('pay_token').value;

            fetch('/api/payments/process', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: createdOrderId,
                    gateway: gateway,
                    payment_token: token
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Payment successful
                    document.getElementById('payment-step').style.display = 'none';
                    document.getElementById('success-step').style.display = 'block';
                    document.getElementById('success-order-id').textContent = createdOrderId;
                    showToast("¡Pago procesado con éxito!");
                } else {
                    showToast("Pago rechazado: " + data.message);
                }
            })
            .catch(err => {
                showToast("Error al procesar el pago");
            });
        }

        // Reset Shop
        function resetShop() {
            createdOrderId = null;
            document.getElementById('success-step').style.display = 'none';
            document.getElementById('checkout-step').style.display = 'block';
            document.getElementById('action-section').style.display = 'none';
            loadCatalog();
            loadCart();
        }
    </script>
</body>
</html>
