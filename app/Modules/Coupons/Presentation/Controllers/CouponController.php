<?php

namespace App\Modules\Coupons\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupons\Application\UseCases\ApplyCouponUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DomainException;

class CouponController extends Controller
{
    public function __construct(private ApplyCouponUseCase $applyCouponUseCase) {}

    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $code = $request->input('code');
        $totalAmount = (float) $request->input('total_amount');

        try {
            $result = $this->applyCouponUseCase->execute($code, $totalAmount);
            return response()->json($result);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
