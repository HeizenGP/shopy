<?php

namespace App\Modules\Reviews\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reviews\Application\UseCases\AddReviewUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        private readonly AddReviewUseCase $addReviewUseCase
    ) {}

    public function store(Request $request, int $productId)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        $name = $userId ? Auth::user()->name : ($data['name'] ?: 'Anónimo');

        try {
            $this->addReviewUseCase->execute(
                $productId,
                $userId,
                $name,
                (int) $data['rating'],
                $data['comment']
            );

            return redirect()->back()->with('success', 'Tu valoración ha sido añadida correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['review' => $e->getMessage()]);
        }
    }
}
