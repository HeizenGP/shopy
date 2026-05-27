<?php

namespace App\Modules\Cart\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Application\UseCases\AddToCartUseCase;
use App\Modules\Cart\Application\UseCases\GetCartUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private GetCartUseCase $getCartUseCase,
        private AddToCartUseCase $addToCartUseCase
    ) {}

    public function show(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        $sessionId = $request->input('session_id') ?? session()->getId();

        try {
            $cartDto = $this->getCartUseCase->execute($userId, $sessionId);
            return response()->json($cartDto->toArray());
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = $request->user()?->id;
        $sessionId = $request->input('session_id') ?? session()->getId();
        $productId = (int) $request->input('product_id');
        $productVariantId = $request->input('product_variant_id') ? (int) $request->input('product_variant_id') : null;
        $quantity = (int) $request->input('quantity');

        try {
            $cartDto = $this->addToCartUseCase->execute($userId, $sessionId, $productId, $productVariantId, $quantity);
            return response()->json($cartDto->toArray());
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
