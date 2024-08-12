<?php

namespace App\Models\Concerns;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

trait BelongsToProduct
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function scopeWithProduct(Builder $query): void
    {
        $query->with('product');
    }

    protected function scopeForProduct(Builder $query, int|Product|string $product, bool $not = false): void
    {
        $exp = $not ? '<>' : '=';
        if (Str::isUuid($product) && ($uuidProduct = Product::whereUuid($product)->first())) {
            $user = $uuidProduct->id;
        }

        $query->when(
            $user instanceof Product,
            fn (Builder $query) => $query->where('id', $exp, $product->id),
            fn (Builder $query) => $query->where('id', $exp, $product)
        );
    }
}
