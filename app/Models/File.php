<?php

namespace App\Models;

use App\Enums\FileType;
use App\Models\Concerns\BelongsToProduct;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends BaseModel
{
    use BelongsToProduct;
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'type' => FileType::class,
    ];

    protected $guarded = [];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->path)
        );
    }
}