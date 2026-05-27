<?php

namespace App\Modules\Coupons\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupons\Application\UseCases\ValidateCouponUseCase;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(
        private readonly ValidateCouponUseCase $validateCouponUseCase
    ) {}

    public function apply(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->validateCouponUseCase->execute($data['code'], (float) $data['amount']);
            
            session()->put('applied_coupon', [
                'id' => $result['id'],
                'code' => $result['code'],
                'discount' => $result['discount'],
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cupón aplicado correctamente.',
                    'coupon' => $result
                ]);
            }

            return redirect()->back()->with('success', "Cupón {$result['code']} aplicado.");
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return redirect()->back()->withErrors(['coupon' => $e->getMessage()]);
        }
    }

    public function remove(Request $request)
    {
        session()->forget('applied_coupon');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cupón removido.'
            ]);
        }

        return redirect()->back()->with('success', 'Cupón removido.');
    }
}
