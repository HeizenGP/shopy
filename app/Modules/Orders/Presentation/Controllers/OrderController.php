<?php

namespace App\Modules\Orders\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Application\UseCases\PlaceOrderUseCase;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Cart\Application\UseCases\GetCartUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly PlaceOrderUseCase $placeOrderUseCase,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly GetCartUseCase $getCartUseCase
    ) {}

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $cart = $this->getCartUseCase->execute($userId, $sessionId);

        if (empty($cart->items)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        // Calculate checkout summary
        $subtotal = 0.00;
        $items = [];

        foreach ($cart->items as $item) {
            $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($item->productId);
            $variant = $item->productVariantId ? 
                \App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent::with('options')->find($item->productVariantId) : 
                null;
            
            if ($product) {
                $price = $variant ? $variant->price : $product->price;
                $itemSubtotal = $price * $item->quantity;
                $subtotal += $itemSubtotal;
                $items[] = [
                    'name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                    'variant_name' => $variant ? $variant->options->map(fn($o) => "{$o->name}: {$o->value}")->join(', ') : null,
                ];
            }
        }

        // Apply session coupon if valid
        $couponCode = null;
        $discount = 0.00;
        
        $sessionCoupon = session()->get('applied_coupon');
        if ($sessionCoupon) {
            $couponCode = $sessionCoupon['code'];
            // Re-validate coupon in case cart changed
            try {
                $couponRepo = app(\App\Modules\Coupons\Domain\Repositories\CouponRepositoryInterface::class);
                $coupon = $couponRepo->findByCode($couponCode);
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    // Update session
                    session()->put('applied_coupon', [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'discount' => $discount,
                    ]);
                } else {
                    session()->forget('applied_coupon');
                    $couponCode = null;
                }
            } catch (\Exception $e) {
                session()->forget('applied_coupon');
                $couponCode = null;
            }
        }

        $total = max(0.00, $subtotal - $discount);

        return view('checkout.index', compact('items', 'subtotal', 'discount', 'total', 'couponCode'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_address' => 'required|string|max:500',
        ]);

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $couponCode = null;
        $sessionCoupon = session()->get('applied_coupon');
        if ($sessionCoupon) {
            $couponCode = $sessionCoupon['code'];
        }

        try {
            $order = $this->placeOrderUseCase->execute(
                $userId,
                $sessionId,
                $data['billing_name'],
                $data['billing_email'],
                $data['billing_address'],
                $couponCode
            );

            // Clear session coupon
            session()->forget('applied_coupon');

            return redirect()->route('orders.show', $order->id)->with('success', 'Pedido creado con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }
    }

    public function show(int $id)
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            abort(404, 'Pedido no encontrado.');
        }

        // Access control: Guest can view if email matches or if they are authenticated and own it
        if ($order->userId && $order->userId !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        // Fetch catalog product details for rendering items list
        $items = [];
        foreach ($order->items as $item) {
            $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($item->productId);
            $variant = $item->productVariantId ? 
                \App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent::with('options')->find($item->productVariantId) : 
                null;
            
            $items[] = [
                'name' => $product ? $product->name : "Producto Eliminado (ID {$item->productId})",
                'variant_name' => $variant ? $variant->options->map(fn($o) => "{$o->name}: {$o->value}")->join(', ') : null,
                'quantity' => $item->quantity,
                'unit_price' => $item->unitPrice,
                'total' => $item->total,
            ];
        }

        return view('checkout.confirmation', compact('order', 'items'));
    }

    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = $this->orderRepository->findByUserId(Auth::id());

        return view('checkout.history', compact('orders'));
    }
}
