<x-layouts.catalog title="Procesar Pago">
    
    <div class="container-wrapper" style="padding: 2.5rem 1.5rem 4rem;">
        <!-- Checkout Steps Breadcrumb -->
        <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 3rem; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
            <span style="color: var(--color-success); display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-success);"><polyline points="20 6 9 17 4 12"/></svg>
                Carrito
            </span>
            <span style="color: var(--color-border); display: flex; align-items: center;">—</span>
            <span style="color: var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-primary); color: var(--color-button-text); width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
                Despacho & Pago
            </span>
            <span style="color: var(--color-border); display: flex; align-items: center;">—</span>
            <span style="color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-border); color: var(--color-muted); width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
                Completado
            </span>
        </div>

        <h1 style="font-size: 2.25rem; margin-bottom: 2.5rem; text-align: left; letter-spacing: -0.5px;">Completar Compra</h1>

        <!-- Empty Checkout State -->
        <div id="checkoutEmpty" style="display: none; text-align: center; padding: 4rem 1.5rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main);">
            <h2>No hay artículos para procesar</h2>
            <p style="color: var(--color-muted); margin: 1rem 0 2rem;">Regresa a la tienda y añade productos al carrito antes de realizar el pago.</p>
            <a href="{{ route('products.index') }}" class="button">Ir a la Tienda</a>
        </div>

        <!-- Checkout Split Columns -->
        <div class="checkout-grid" id="checkoutGrid">
            <!-- Left: Address and billing forms -->
            <form class="checkout-form-section" id="checkoutForm" onsubmit="processPayment(event)">
                
                <!-- 1. Contact Information -->
                <div>
                    <h3 class="checkout-step-title">
                        <span>1</span> Contacto de Envío
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="contactEmail">Correo Electrónico</label>
                            <input type="email" id="contactEmail" required placeholder="correo@ejemplo.com">
                        </div>
                        <div class="form-field">
                            <label for="contactPhone">Teléfono / Celular</label>
                            <input type="tel" id="contactPhone" required placeholder="999 999 999">
                        </div>
                    </div>
                </div>

                <!-- 2. Shipping Address -->
                <div>
                    <h3 class="checkout-step-title">
                        <span>2</span> Dirección de Despacho
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="shipName">Nombres</label>
                            <input type="text" id="shipName" required placeholder="Juan Carlos">
                        </div>
                        <div class="form-field">
                            <label for="shipLast">Apellidos</label>
                            <input type="text" id="shipLast" required placeholder="Pérez Gómez">
                        </div>
                        <div class="form-field full-width">
                            <label for="shipAddr">Dirección Completa (Calle, Avenida, Dpto, Of.)</label>
                            <input type="text" id="shipAddr" required placeholder="Av. Larco 456, Dpto 302">
                        </div>
                        <div class="form-field">
                            <label for="shipDist">Distrito / Ciudad</label>
                            <input type="text" id="shipDist" required placeholder="Miraflores, Lima">
                        </div>
                        <div class="form-field">
                            <label for="shipZip">Código Postal</label>
                            <input type="text" id="shipZip" placeholder="15074">
                        </div>
                    </div>
                </div>

                <!-- 3. Shipping Methods -->
                <div>
                    <h3 class="checkout-step-title">
                        <span>3</span> Método de Envío
                    </h3>
                    <div class="shipping-methods-list">
                        <label class="shipping-method-option is-selected" id="shippingStandardOpt">
                            <input type="radio" name="shipping_method" value="standard" checked onclick="setShippingCost(0, 'shippingStandardOpt')">
                            <span class="method-details">
                                <span><strong>Despacho Estándar</strong> (3-4 días hábiles útiles)</span>
                                <span class="method-price">¡Gratis!</span>
                            </span>
                        </label>
                        <label class="shipping-method-option" id="shippingExpressOpt">
                            <input type="radio" name="shipping_method" value="express" onclick="setShippingCost(15.00, 'shippingExpressOpt')">
                            <span class="method-details">
                                <span><strong>Envío Express</strong> (24h hábiles prioritario)</span>
                                <span class="method-price">S/ 15.00</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 4. Payment Method -->
                <div>
                    <h3 class="checkout-step-title">
                        <span>4</span> Método de Pago
                    </h3>
                    
                    <!-- Tabs buttons -->
                    <div class="payment-selector-tabs">
                        <div class="pay-tab is-active" id="tabCcBtn" onclick="switchPaymentMethod('cc')">Tarjeta Crédito</div>
                        <div class="pay-tab" id="tabPpBtn" onclick="switchPaymentMethod('pp')">PayPal</div>
                        <div class="pay-tab" id="tabBankBtn" onclick="switchPaymentMethod('bank')">Transferencia</div>
                    </div>

                    <!-- Payment content blocks -->
                    <div id="paymentCcContent">
                        
                        <!-- Premium CC Card Mockup -->
                        <div class="card-mockup-wrapper">
                            <div class="card-logo">
                                <span style="font-weight: 800; font-style: italic; font-size: 1.1rem; letter-spacing: -0.5px;">VISA Premium</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="card-num" id="ccMockNum">•••• •••• •••• ••••</div>
                            <div class="card-details-row">
                                <div>
                                    <div style="font-size: 0.6rem; opacity: 0.6; margin-bottom: 0.1rem;">Titular</div>
                                    <span id="ccMockName">TITULAR TARJETA</span>
                                </div>
                                <div>
                                    <div style="font-size: 0.6rem; opacity: 0.6; margin-bottom: 0.1rem;">Vence</div>
                                    <span id="ccMockExp">MM/AA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Fields -->
                        <div class="form-grid">
                            <div class="form-field full-width">
                                <label for="ccNum">Número de la Tarjeta</label>
                                <input type="text" id="ccNum" placeholder="4111 2222 3333 4444" maxlength="19" oninput="formatCardNumber(this)">
                            </div>
                            <div class="form-field full-width">
                                <label for="ccName">Nombre impreso en Tarjeta</label>
                                <input type="text" id="ccName" placeholder="JUAN C PEREZ" oninput="updateCardHolder(this.value)">
                            </div>
                            <div class="form-field">
                                <label for="ccExp">Fecha de Vencimiento</label>
                                <input type="text" id="ccExp" placeholder="MM/AA" maxlength="5" oninput="formatExpiry(this)">
                            </div>
                            <div class="form-field">
                                <label for="ccCvv">CVV / Código Seguridad</label>
                                <input type="password" id="ccCvv" placeholder="123" maxlength="3">
                            </div>
                        </div>
                    </div>

                    <!-- PayPal Mock details -->
                    <div id="paymentPpContent" style="display: none; padding: 2rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main); text-align: center; box-shadow: var(--shadow-soft);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0079c1" stroke-width="2" style="margin-bottom: 1rem;"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
                        <p style="font-weight: 600; margin-bottom: 0.5rem;">Serás redirigido a PayPal de forma segura</p>
                        <p style="font-size: 0.85rem; color: var(--color-muted);">Podrás iniciar sesión con tu cuenta de Paypal para completar la compra de manera veloz.</p>
                    </div>

                    <!-- Bank Transfer Mock details -->
                    <div id="paymentBankContent" style="display: none; padding: 1.5rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-main); box-shadow: var(--shadow-soft); display: flex; flex-direction: column; gap: 0.75rem;">
                        <h4 style="font-weight: 700; color: var(--color-primary);">Cuentas Bancarias de Depósito:</h4>
                        <div style="font-size: 0.9rem; line-height: 1.6;">
                            <p><strong>Banco Central (BCP):</strong> 191-98765432-0-98</p>
                            <p><strong>Código CCI:</strong> 002-19198765432098055</p>
                            <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--color-muted);">* Por favor envía el voucher de depósito adjunto a ventas@shopcms.com indicando tu correo de compra.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <!-- Place order button -->
                    <button type="submit" class="button checkout-place-btn" id="placeOrderBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        Confirmar y Realizar Pago
                    </button>
                </div>
            </form>

            <!-- Right: Order summary with cart items -->
            <div class="checkout-summary-card">
                <h2 style="font-size: 1.35rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">Productos en la Orden</h2>
                
                <!-- Cart Items Container -->
                <div class="checkout-items-list" id="checkoutItemsContainer">
                    <!-- Dynamic Cart Items loaded here -->
                </div>

                <!-- Calculations block -->
                <div style="border-top: 1px solid var(--color-border); padding-top: 1rem; display: flex; flex-direction: column; gap: 0.6rem;">
                    <div class="summary-row" style="border-bottom: 0; padding: 0.25rem 0;">
                        <span>Subtotal</span>
                        <strong id="chkSubtotal">S/ 0.00</strong>
                    </div>
                    <div class="summary-row" id="chkDiscountRow" style="border-bottom: 0; padding: 0.25rem 0; color: #ef4444; display: none;">
                        <span>Descuento (Cupón WELCOME10)</span>
                        <strong id="chkDiscount">-S/ 0.00</strong>
                    </div>
                    <div class="summary-row" style="border-bottom: 0; padding: 0.25rem 0;">
                        <span>Costo de Envío</span>
                        <strong id="chkShipping">S/ 0.00</strong>
                    </div>
                    <div class="summary-row total-row" style="border-top: 1px solid var(--color-border); padding-top: 0.85rem;">
                        <span>Total de Compra</span>
                        <strong id="chkTotal">S/ 0.00</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal Box -->
    <div class="success-modal" id="successModal">
        <div class="success-modal-backdrop" onclick="closeSuccessModalAndRedirect()"></div>
        <div class="success-modal-content">
            <div class="success-icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h2>¡Compra Procesada con Éxito!</h2>
            <p>Tu orden ha sido generada correctamente. El comprobante de pago y número de seguimiento para el despacho han sido enviados al correo proporcionado.</p>
            <button class="button" onclick="closeSuccessModalAndRedirect()" style="width: 100%;">
                Volver a la tienda
            </button>
        </div>
    </div>

    <!-- Page Specific Script -->
    <script>
        let currentShippingCost = 0.00;
        let checkoutCart = [];
        let chkSubtotal = 0;
        let chkDiscount = 0;
        let selectedPaymentMethod = 'cc';

        function loadCheckoutDetails() {
            const emptyState = document.getElementById('checkoutEmpty');
            const grid = document.getElementById('checkoutGrid');
            const itemsContainer = document.getElementById('checkoutItemsContainer');
            
            const savedCart = localStorage.getItem('shopy-cart');
            checkoutCart = savedCart ? JSON.parse(savedCart) : [];
            
            if (checkoutCart.length === 0) {
                emptyState.style.display = 'block';
                grid.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            grid.style.display = 'grid';
            
            // Draw order list
            let html = '';
            chkSubtotal = 0;
            
            checkoutCart.forEach(item => {
                const total = item.price * item.quantity;
                chkSubtotal += total;
                
                html += `
                    <div class="checkout-item-line">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="checkout-item-info">
                            <h4>${item.name}</h4>
                            <span>Ctd: ${item.quantity} ${item.variant ? ` · Var: ${item.variant}` : ''}</span>
                        </div>
                        <div class="checkout-item-price">S/ ${total.toFixed(2)}</div>
                    </div>
                `;
            });
            
            itemsContainer.innerHTML = html;
            
            // Fetch discounts
            const savedDiscount = localStorage.getItem('shopy-checkout-discount');
            chkDiscount = savedDiscount ? parseFloat(savedDiscount) : 0;
            
            if (chkDiscount > 0) {
                document.getElementById('chkDiscountRow').style.display = 'flex';
                document.getElementById('chkDiscount').innerText = '-S/ ' + chkDiscount.toFixed(2);
            } else {
                document.getElementById('chkDiscountRow').style.display = 'none';
            }
            
            recalculateTotals();
        }

        function recalculateTotals() {
            document.getElementById('chkSubtotal').innerText = 'S/ ' + chkSubtotal.toFixed(2);
            document.getElementById('chkShipping').innerText = currentShippingCost === 0 ? '¡Gratis!' : 'S/ ' + currentShippingCost.toFixed(2);
            
            const finalTotal = chkSubtotal - chkDiscount + currentShippingCost;
            document.getElementById('chkTotal').innerText = 'S/ ' + finalTotal.toFixed(2);
        }

        function setShippingCost(cost, selectedId) {
            currentShippingCost = cost;
            
            // Toggle highlight in DOM options
            document.querySelectorAll('.shipping-method-option').forEach(el => {
                el.classList.remove('is-selected');
            });
            document.getElementById(selectedId).classList.add('is-selected');
            
            recalculateTotals();
        }

        function switchPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Toggle active tabs
            document.querySelectorAll('.pay-tab').forEach(el => el.classList.remove('is-active'));
            document.getElementById('paymentCcContent').style.display = 'none';
            document.getElementById('paymentPpContent').style.display = 'none';
            document.getElementById('paymentBankContent').style.display = 'none';
            
            if (method === 'cc') {
                document.getElementById('tabCcBtn').classList.add('is-active');
                document.getElementById('paymentCcContent').style.display = 'block';
            } else if (method === 'pp') {
                document.getElementById('tabPpBtn').classList.add('is-active');
                document.getElementById('paymentPpContent').style.display = 'block';
            } else if (method === 'bank') {
                document.getElementById('tabBankBtn').classList.add('is-active');
                document.getElementById('paymentBankContent').style.display = 'flex';
            }
        }

        /* CC Interactive Mockup Helpers */
        function formatCardNumber(input) {
            let v = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let matches = v.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];

            for (let i=0, len=match.length; i<len; i+=4) {
                parts.push(match.substring(i, i+4));
            }

            if (parts.length > 0) {
                input.value = parts.join(' ');
                document.getElementById('ccMockNum').innerText = parts.join(' ');
            } else {
                input.value = v;
                document.getElementById('ccMockNum').innerText = v || '•••• •••• •••• ••••';
            }
        }

        function updateCardHolder(name) {
            document.getElementById('ccMockName').innerText = name.toUpperCase() || 'TITULAR TARJETA';
        }

        function formatExpiry(input) {
            let v = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (v.length >= 2) {
                input.value = v.substring(0, 2) + '/' + v.substring(2, 4);
                document.getElementById('ccMockExp').innerText = input.value;
            } else {
                input.value = v;
                document.getElementById('ccMockExp').innerText = v || 'MM/AA';
            }
        }

        /* Place Order Trigger */
        function processPayment(e) {
            e.preventDefault();
            
            // Require fields check
            if (selectedPaymentMethod === 'cc') {
                const ccNum = document.getElementById('ccNum').value;
                const ccName = document.getElementById('ccName').value;
                const ccExp = document.getElementById('ccExp').value;
                const ccCvv = document.getElementById('ccCvv').value;
                
                if (!ccNum || !ccName || !ccExp || !ccCvv) {
                    showToast('Por favor completa todos los campos de la tarjeta', 'error');
                    return;
                }
            }
            
            // Open modal
            const modal = document.getElementById('successModal');
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            
            // Clear cart
            localStorage.removeItem('shopy-cart');
            localStorage.removeItem('shopy-checkout-discount');
            localStorage.removeItem('shopy-checkout-coupon');
            
            // Refresh Header cart state variables
            cart = [];
            saveCart();
        }

        function closeSuccessModalAndRedirect() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
            
            // Redirect to home page
            window.location.href = "{{ route('home') }}";
        }

        window.addEventListener('load', loadCheckoutDetails);
    </script>
</x-layouts.catalog>
