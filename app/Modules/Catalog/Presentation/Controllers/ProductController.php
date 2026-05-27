<?php

namespace App\Modules\Catalog\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Application\UseCases\GetProductUseCase;
use App\Modules\Catalog\Application\UseCases\ListProductsUseCase;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ListProductsUseCase $listProductsUseCase,
        private GetProductUseCase $getProductUseCase
    ) {}

    public function index(): JsonResponse
    {
        $products = $this->listProductsUseCase->execute();
        $data = array_map(fn ($p) => $p->toArray(), $products);

        return response()->json($data);
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $product = $this->getProductUseCase->execute($slug);
            return response()->json($product->toArray());
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
