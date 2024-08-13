<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ProductService $productService
     */
    public function __construct(protected ProductService $productService) {}

    /**
     * Display a paginated list of products.
     *
     * Retrieves products with their associated files, sorted by creation date in descending order.
     *
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        $products = Product::with('files')->orderBy('created_at', 'desc')->paginate(15);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     *
     * Validates and creates a new product using the provided data.
     *
     * @param ProductRequest $request
     * @return JsonResource
     */
    public function store(ProductRequest $request): JsonResource
    {

        $product = $this->productService->createProduct($request->validated());

        return ProductResource::make($product);
    }

    /**
     * Display a specified product.
     *
     * Retrieves a single product by its ID, including its associated files.
     *
     * @param string $id
     * @return JsonResource
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        abort_if(is_null($product),  204, 'Invalid Content or Parameter' );

        return ProductResource::make($product);
    }

    /**
     * Update a specified product.
     *
     * Updates the product with new data provided in the request.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResource
     */
    public function update(ProductRequest $request, Product $product): JsonResource
    {
        $product = $this->productService->updateProduct($request->validated(), $product);

        abort_if(is_null($product),  204, 'Invalid Content or Parameter' );

        return ProductResource::make($product);
    }

    /**
     * Remove a specified product.
     *
     * Deletes the product from the database.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
