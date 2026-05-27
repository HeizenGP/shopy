<?php

namespace App\Modules\Cart\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Application\UseCases\GetCartUseCase;
use App\Modules\Cart\Application\UseCases\AddCartItemUseCase;
use App\Modules\Cart\Application\UseCases\UpdateCartItemUseCase;
use App\Modules\Cart\Application\UseCases\RemoveCartItemUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly GetCartUseCase $getCartUseCase,
        private readonly AddCartItemUseCase $addCartItemUseCase,
        private readonly UpdateCartItemUseCase $updateCartItemUseCase,
        private readonly RemoveCartItemUseCase $removeCartItemUseCase
    ) {}

    public function index(Request $request)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $cart = $this->getCartUseCase->execute($userId, $sessionId);

        $items = [];
        $total = 0.00;
        foreach ($cart->items as $item) {
            $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($item->productId);
            $variant = $item->productVariantId ? 
                \App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent::with('options')->find($item->productVariantId) : 
                null;
            
            if ($product) {
                $price = $variant ? $variant->price : $product->price;
                $subtotal = $price * $item->quantity;
                $total += $subtotal;
                $items[] = [
                    'product_id' => $item->productId,
                    'variant_id' => $item->productVariantId,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'variant_name' => $variant ? $variant->options->map(fn($o) => "{$o->name}: {$o->value}")->join(', ') : null,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        try {
            $this->addCartItemUseCase->execute(
                $userId,
                $sessionId,
                (int) $data['product_id'],
                $data['variant_id'] ? (int) $data['variant_id'] : null,
                (int) $data['quantity']
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Producto añadido al carrito.']);
            }

            return redirect()->back()->with('success', 'Producto añadido al carrito.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        try {
            $this->updateCartItemUseCase->execute(
                $userId,
                $sessionId,
                (int) $data['product_id'],
                $data['variant_id'] ? (int) $data['variant_id'] : null,
                (int) $data['quantity']
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Carrito actualizado.']);
            }

            return redirect()->route('cart.index')->with('success', 'Carrito actualizado.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
        ]);

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        try {
            $this->removeCartItemUseCase->execute(
                $userId,
                $sessionId,
                (int) $data['product_id'],
                $data['variant_id'] ? (int) $data['variant_id'] : null
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Producto eliminado del carrito.']);
            }

            return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
