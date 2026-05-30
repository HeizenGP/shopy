<x-layouts.catalog title="Carrito de Compras">
    
    <div class="container-wrapper" style="padding: 2.5rem 1.5rem 4rem;">
        
        <!-- Progress Steps Tracker -->
        <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 3rem; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-primary); color: var(--color-button-text); width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">1</span>
                Carrito
            </span>
            <span style="color: var(--color-border); display: flex; align-items: center;">—</span>
            <span style="color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-border); color: var(--color-muted); width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
                Despacho
            </span>
            <span style="color: var(--color-border); display: flex; align-items: center;">—</span>
            <span style="color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-border); color: var(--color-muted); width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
                Pago
            </span>
        </div>

        <h1 style="font-size: 2.25rem; margin-bottom: 2rem; text-align: left; letter-spacing: -0.5px;">Tu Carrito de Compras</h1>

        <!-- Empty State Container -->
        <div id="cartPageEmpty" style="display: none; text-align: center; padding: 5rem 1.5rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main); box-shadow: var(--shadow-soft);">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" style="color: var(--color-muted); margin-bottom: 1.5rem;"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">El carrito está vacío</h2>
            <p style="color: var(--color-muted); margin-bottom: 2rem; max-width: 480px; margin-left: auto; margin-right: auto;">Agrega productos desde nuestro catálogo para comenzar tu proceso de pago y envío.</p>
            <a href="{{ route('products.index') }}" class="button">Explorar Productos</a>
        </div>

        <!-- Main Cart Page Content Grid -->
        <div class="cart-page-grid" id="cartPageContent">
            <!-- Left Side: Table of items -->
            <div class="cart-table-card">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Producto</th>
                            <th style="width: 15%;">Precio</th>
                            <th style="width: 20%;">Cantidad</th>
                            <th style="width: 15%;">Total</th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody">
                        <!-- Dynamic items loaded here -->
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border);">
                    <a href="{{ route('products.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--color-primary);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Seguir comprando
                    </a>
                    <button class="button outline-btn" onclick="clearFullCart()">Vaciar Carrito</button>
                </div>
            </div>

            <!-- Right Side: Order summary -->
            <div class="cart-summary-card">
                <h2>Resumen de Orden</h2>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong id="summarySubtotal">S/ 0.00</strong>
                </div>
                
                <div class="summary-row" id="couponDiscountRow" style="display: none; color: #ef4444;">
                    <span>Descuento (Cupón)</span>
                    <strong id="summaryDiscount">-S/ 0.00</strong>
                </div>

                <div class="summary-row">
                    <span>Envío</span>
                    <span style="color: var(--color-success); font-weight: 700;">¡GRATIS!</span>
                </div>

                <div class="summary-row total-row">
                    <span>Total Estimado</span>
                    <strong id="summaryTotal">S/ 0.00</strong>
                </div>

                <div class="coupon-box">
                    <label class="filter-group-label" for="couponCode">¿Tienes un cupón?</label>
                    <form onsubmit="applyCouponCode(event)">
                        <input type="text" id="couponCode" placeholder="Escribir código..." autocomplete="off">
                        <button type="submit" class="button">Aplicar</button>
                    </form>
                    <span id="couponFeedback" style="display: block; font-size: 0.8rem; margin-top: 0.4rem; font-weight: 700;"></span>
                </div>

                <a href="{{ route('checkout') }}" class="button checkout-action-btn" style="margin-top: 1.5rem; text-align: center; display: block;">
                    Ir a la Caja / Pago
                </a>
            </div>
        </div>
    </div>

    <!-- Script specifically for drawing the Cart page content -->
    <script>
        let discountVal = 0;
        let couponApplied = null;

        function drawCartPage() {
            const emptyState = document.getElementById('cartPageEmpty');
            const content = document.getElementById('cartPageContent');
            const tbody = document.getElementById('cartTableBody');
            
            const savedCart = localStorage.getItem('shopy-cart');
            const localCart = savedCart ? JSON.parse(savedCart) : [];
            
            if (localCart.length === 0) {
                emptyState.style.display = 'block';
                content.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            content.style.display = 'grid';
            
            let html = '';
            let subtotal = 0;
            
            localCart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                html += `
                    <tr>
                        <td>
                            <div class="cart-product-cell">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <h3><a href="/products/${item.slug}">${item.name}</a></h3>
                                    ${item.variant ? `<span>Variante: ${item.variant}</span>` : ''}
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600;">S/ ${item.price.toFixed(2)}</td>
                        <td>
                            <div class="qty-selector">
                                <button type="button" onclick="modifyQty('${item.uniqueId}', -1)">—</button>
                                <span>${item.quantity}</span>
                                <button type="button" onclick="modifyQty('${item.uniqueId}', 1)">+</button>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--color-primary);">S/ ${itemTotal.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
            
            // Calculate calculations
            document.getElementById('summarySubtotal').innerText = 'S/ ' + subtotal.toFixed(2);
            
            // Re-apply coupon if applied
            let finalTotal = subtotal;
            if (couponApplied === 'WELCOME10') {
                discountVal = subtotal * 0.10; // 10% discount
                document.getElementById('couponDiscountRow').style.display = 'flex';
                document.getElementById('summaryDiscount').innerText = '-S/ ' + discountVal.toFixed(2);
                finalTotal = subtotal - discountVal;
                
                // Save discount to localStorage for checkout page reference
                localStorage.setItem('shopy-checkout-discount', discountVal.toFixed(2));
                localStorage.setItem('shopy-checkout-coupon', 'WELCOME10');
            } else {
                localStorage.removeItem('shopy-checkout-discount');
                localStorage.removeItem('shopy-checkout-coupon');
            }
            
            document.getElementById('summaryTotal').innerText = 'S/ ' + finalTotal.toFixed(2);
        }

        function modifyQty(uniqueId, change) {
            updateQuantity(uniqueId, change);
            drawCartPage();
        }

        function clearFullCart() {
            if (confirm('¿Estás seguro de que deseas vaciar tu carrito de compras?')) {
                cart = [];
                saveCart();
                drawCartPage();
                showToast('Carrito vaciado', 'info');
            }
        }

        function applyCouponCode(e) {
            e.preventDefault();
            const code = document.getElementById('couponCode').value.trim().toUpperCase();
            const feedback = document.getElementById('couponFeedback');
            
            if (code === 'WELCOME10') {
                couponApplied = 'WELCOME10';
                feedback.style.color = 'var(--color-success)';
                feedback.innerText = '¡Cupón de 10% de descuento aplicado con éxito!';
                showToast('Cupón WELCOME10 aplicado', 'success');
                drawCartPage();
            } else {
                couponApplied = null;
                feedback.style.color = '#ef4444';
                feedback.innerText = 'Código de cupón inválido. Intenta con WELCOME10.';
                showToast('Código de cupón inválido', 'error');
                drawCartPage();
            }
        }

        // Draw page on load
        window.addEventListener('load', drawCartPage);
        
        // Listen to global changes
        window.addEventListener('shopy-cart-updated', drawCartPage);
    </script>

</x-layouts.catalog>
