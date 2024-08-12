<?php

namespace App\Enums;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

enum ItemType: string
{
    case PRODUCT = 'product';

    public static function morphMap(): array
    {
        return [
            'product' => Product::class,
        ];
    }

    public function getModelClass(): string
    {
        return app(static::morphMap()[$this->value])::class;
    }

    public function query(): Builder
    {
        return $this->getModelClass()::query();
    }

    public function wherePrimaryKey(int|string $item_id): Builder
    {
        return $this->query()->wherePrimaryKey($item_id);
    }
}
