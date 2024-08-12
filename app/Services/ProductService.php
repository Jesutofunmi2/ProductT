<?php

namespace App\Services;

use App\Actions\FileStorage;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Create a new product and upload associated files.
     *
     * @param array $data Array of product data including name, description, price, stock, and files.
     * 
     * @return Product The newly created product instance.
     * 
     */
    public function createProduct(array $data): Product
    {
        $upload = new FileStorage;

        DB::beginTransaction();

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->save();
    
        $fileArray = $data['files'];

        if (!empty($fileArray)) {
            foreach ($fileArray as $file) {
                $upload->upload($file, $product->id);
            }
        }

        DB::commit();

        return $product;
    }

    /**
     * Update an existing product with new data.
     *
     * @param array $data Array of product data including name, description, price, and stock.
     * @param Product $product The product instance to be updated.
     * 
     * @return Product The updated product instance.
     * 
     */
    public function updateProduct(array $data, Product $product): Product
    {
        DB::beginTransaction();

        $product->update([
            "name" => $data['name'],
            "description" => $data['description'],
            "price" => $data['price'],
            "stock" => $data['stock'],
        ]);

        $upload = new FileStorage;
        $fileArray = $data['files'] ?? [];

        if (!empty($fileArray)) {
            foreach ($fileArray as $file) {
                $upload->upload($file, $product->id);
            }
        }

        DB::commit();

        return $product;
    }
}
