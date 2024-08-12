<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}
    public function index(): JsonResource
    {
        $products = Product::with('files')->orderBy('created_at', 'desc')->paginate(15);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request): JsonResource
    {

        $product = $this->productService->createProduct($request->validated());
        
        return ProductResource::make($product);
    }


    public function show(string $id)
    {
        $product = Product::find($id);

        return ProductResource::make($product);
    }


    public function update(ProductRequest $request, Product $product): JsonResource
    {
        $product = $this->productService->updateProduct($request->validated(), $product);

        if ($request->input('file')) {
            $product->syncFiles($request->input('file'));
        }

        return ProductResource::make($product);
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
