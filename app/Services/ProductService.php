<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
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

        if ($data['file']) {
            $upload->upload($data['file'], $product->id);
        }

        DB::commit();

        return $product;
    }
}