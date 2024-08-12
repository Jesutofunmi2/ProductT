<?php

namespace App\Models;

use App\Enums\FileType;
use App\Models\Concerns\BelongsToProduct;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    use HasUlids;

    protected $casts = [
        'type' => FileType::class,
    ];

    protected $guarded = [];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->path)
        );
    }
}