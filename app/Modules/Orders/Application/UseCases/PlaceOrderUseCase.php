<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Entities\OrderItem;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;
use App\Modules\Inventory\Application\UseCases\VerifyAndDeductStockUseCase;
use App\Modules\Coupons\Domain\Repositories\CouponRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class PlaceOrderUseCase
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly VerifyAndDeductStockUseCase $verifyAndDeductStockUseCase,
        private readonly CouponRepositoryInterface $couponRepository
    ) {}

    public function execute(
        ?int $userId,
        ?string $sessionId,
        string $billingName,
        string $billingEmail,
        string $billingAddress,
        ?string $couponCode = null
    ): Order {
        return DB::transaction(function () use ($userId, $sessionId, $billingName, $billingEmail, $billingAddress, $couponCode) {
            
            // 1. Fetch Cart
            $cart = $this->cartRepository->findBySessionOrUser($userId, $sessionId);
            if (!$cart || empty($cart->items)) {
                throw new Exception("El carrito está vacío.");
            }

            // 2. Validate Items, calculate subtotal, verify & deduct stock
            $subtotal = 0.00;
            $orderItems = [];

            foreach ($cart->items as $item) {
                // Fetch catalog info
                $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($item->productId);
                if (!$product || !$product->is_active) {
                    throw new Exception("El producto con ID {$item->productId} ya no está disponible.");
                }

                $price = $product->price;
                if ($item->productVariantId) {
                    $variant = $product->variants()->find($item->productVariantId);
                    if (!$variant) {
                        throw new Exception("La variante seleccionada para {$product->name} no existe.");
                    }
                    $price = $variant->price;
                }

                // Verify and deduct stock (raises alert if low, throws if out)
                $this->verifyAndDeductStockUseCase->execute($item->productId, $item->productVariantId, $item->quantity);

                $itemTotal = $price * $item->quantity;
                $subtotal += $itemTotal;

                $orderItems[] = new OrderItem(
                    id: 0,
                    productId: $item->productId,
                    productVariantId: $item->productVariantId,
                    quantity: $item->quantity,
                    unitPrice: $price,
                    total: $itemTotal
                );
            }

            // 3. Apply Coupon if provided
            $discountAmount = 0.00;
            $couponId = null;

            if ($couponCode) {
                $coupon = $this->couponRepository->findByCode(strtoupper(trim($couponCode)));
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    
                    // Increment times used
                    $this->couponRepository->incrementTimesUsed($coupon->id);
                }
            }

            $total = max(0.00, $subtotal - $discountAmount);

            // 4. Create Order Domain Entity
            $order = new Order(
                id: 0,
                userId: $userId,
                status: OrderStatus::PENDING,
                subtotal: $subtotal,
                discountAmount: $discountAmount,
                total: $total,
                couponId: $couponId,
                billingName: $billingName,
                billingEmail: $billingEmail,
                billingAddress: $billingAddress,
                items: $orderItems
            );

            // 5. Save Order
            $savedOrder = $this->orderRepository->save($order);

            // 6. Delete Cart
            $this->cartRepository->delete($cart->id);

            return $savedOrder;
        });
    }
}
