<?php

namespace App\Modules\Orders\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Application\UseCases\UpdateOrderStatusUseCase;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly UpdateOrderStatusUseCase $updateOrderStatusUseCase
    ) {}

    public function index()
    {
        $orders = $this->orderRepository->findAll();
        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|string',
        ]);

        try {
            $this->updateOrderStatusUseCase->execute($id, $data['status']);
            return redirect()->back()->with('success', 'El estado del pedido se ha actualizado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
