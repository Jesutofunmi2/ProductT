<?php

namespace App\Database\Schema;
use Illuminate\Database\Schema\Blueprint as IlluminateBlueprint;

class Blueprint extends IlluminateBlueprint
{
    public function belongsToProduct(string $column = 'id')
    {
        return $this->unsignedBigInteger($column);
    }

    public function belongsToProductForeign(string $column = 'id')
    {
        return $this->foreign($column)
            ->references('id')
            ->on('products');
    }
}
