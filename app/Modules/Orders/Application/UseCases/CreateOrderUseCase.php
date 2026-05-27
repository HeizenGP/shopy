<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Catalog\Domain\Repositories\ProductRepository;
use App\Modules\Cart\Domain\Repositories\CartRepository;
use App\Modules\Coupons\Domain\Repositories\CouponRepository;
use App\Modules\Orders\Application\DTOs\OrderResponseDTO;
use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Entities\OrderItem;
use App\Modules\Orders\Domain\Repositories\OrderRepository;
use App\Modules\Cart\Domain\Entities\Cart;
use DomainException;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private CartRepository $cartRepository,
        private CouponRepository $couponRepository
    ) {}

    public function execute(
        ?int $userId,
        ?string $sessionId,
        string $shippingAddress,
        string $billingAddress,
        string $customerName,
        string $customerEmail,
        ?string $couponCode
    ): OrderResponseDTO {
        // 1. Get Cart
        $cart = null;
        if ($userId) {
            $cart = $this->cartRepository->findByUserId($userId);
        } elseif ($sessionId) {
            $cart = $this->cartRepository->findBySessionId($sessionId);
        }

        if (!$cart || count($cart->getItems()) === 0) {
            throw new DomainException("El carrito está vacío.");
        }

        // 2. Build Order Items and calculate Subtotal
        $orderItems = [];
        $subtotal = 0.00;

        foreach ($cart->getItems() as $cartItem) {
            $product = $this->productRepository->findById($cartItem->getProductId());
            if (!$product) {
                throw new DomainException("Producto ID {$cartItem->getProductId()} no existe.");
            }

            $priceVal = $product->getPrice()->getAmount();
            $nameVal = $product->getName();

            // Check variant overrides
            if ($cartItem->getProductVariantId()) {
                foreach ($product->getVariants() as $variant) {
                    if ($variant->getId() === $cartItem->getProductVariantId()) {
                        if ($variant->getPrice()) {
                            $priceVal = $variant->getPrice()->getAmount();
                        }
                        $nameVal .= " - " . $variant->getName();
                    }
                }
            }

            $orderItem = new OrderItem(
                id: null,
                orderId: null,
                productId: $cartItem->getProductId(),
                productVariantId: $cartItem->getProductVariantId(),
                name: $nameVal,
                quantity: $cartItem->getQuantity(),
                price: $priceVal
            );

            $orderItems[] = $orderItem;
            $subtotal += $orderItem->getSubtotal();
        }

        // 3. Apply Coupon if present
        $discountAmount = 0.00;
        if ($couponCode) {
            $coupon = $this->couponRepository->findByCode($couponCode);
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        $totalAmount = max(0.00, $subtotal - $discountAmount);

        // 4. Create and Save Order
        $order = new Order(
            id: null,
            userId: $userId,
            status: 'pending',
            totalAmount: $totalAmount,
            discountAmount: $discountAmount,
            couponCode: $couponCode,
            shippingAddress: $shippingAddress,
            billingAddress: $billingAddress,
            customerName: $customerName,
            customerEmail: $customerEmail,
            items: $orderItems
        );

        $savedOrder = $this->orderRepository->save($order);

        // 5. Clear Cart items
        $cartToClear = new Cart(
            id: $cart->getId(),
            userId: $cart->getUserId(),
            sessionId: $cart->getSessionId(),
            items: []
        );
        $this->cartRepository->save($cartToClear);

        return OrderResponseDTO::fromEntity($savedOrder);
    }
}
