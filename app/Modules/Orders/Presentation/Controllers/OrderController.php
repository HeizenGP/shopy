<?php

namespace App\Modules\Orders\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Application\UseCases\CreateOrderUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DomainException;

class OrderController extends Controller
{
    public function __construct(private CreateOrderUseCase $createOrderUseCase) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'coupon_code' => 'nullable|string',
        ]);

        $userId = $request->user()?->id;
        $sessionId = $request->input('session_id') ?? session()->getId();

        try {
            $orderDto = $this->createOrderUseCase->execute(
                userId: $userId,
                sessionId: $sessionId,
                shippingAddress: $request->input('shipping_address'),
                billingAddress: $request->input('billing_address'),
                customerName: $request->input('customer_name'),
                customerEmail: $request->input('customer_email'),
                couponCode: $request->input('coupon_code')
            );

            return response()->json($orderDto->toArray(), 201);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
