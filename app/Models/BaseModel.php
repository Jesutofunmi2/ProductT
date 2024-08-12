<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

abstract class BaseModel extends Model
{
    public static function paginatedResult(Request $request, Closure $callback = null)
    {
        $filters = $request->get('filters', []);
        $search_query = $request->get('query');
        $user = $request->user();

        if ($search_query && in_array(Searchable::class, class_uses(static::class))) {
            return static::search($search_query)
                ->query(function (Builder $builder) use ($filters, $user, $callback) {
                    $builder->tap(fn (Builder $builder) => static::fromQuery($builder, $filters, $user))
                        ->when($callback, fn (Builder $builder) => $callback($builder));
                })
                ->simplePaginate();
        }

        return static::query()
            ->tap(fn (Builder $builder) => static::fromQuery($builder, $filters, $user))
            ->when($callback, fn (Builder $builder) => $callback($builder))
            ->cursorPaginate();
    }
}

