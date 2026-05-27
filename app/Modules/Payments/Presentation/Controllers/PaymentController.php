<?php

namespace App\Modules\Payments\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Application\UseCases\ProcessPaymentUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DomainException;

class PaymentController extends Controller
{
    public function __construct(private ProcessPaymentUseCase $processPaymentUseCase) {}

    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'gateway' => 'required|string',
            'payment_token' => 'required|string',
        ]);

        $orderId = (int) $request->input('order_id');
        $gateway = $request->input('gateway');
        $paymentToken = $request->input('payment_token');

        try {
            $result = $this->processPaymentUseCase->execute($orderId, $gateway, $paymentToken);
            return response()->json($result);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
