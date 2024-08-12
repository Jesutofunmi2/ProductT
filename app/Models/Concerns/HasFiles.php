<?php

namespace App\Models\Concerns;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait HasFiles
{
    protected function scopeWithFiles($query): void
    {
        $query->with('files');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'item');
    }

    public function syncFiles(array|Collection $files)
    {
        $ids = collect($files)->map(function ($file) {
            if (gettype($file) === 'string') {
                return $file;
            }

            if (gettype($file) === 'array') {
                return $file['id'];
            }

            return $file->id;
        })->toArray();

        File::wherePrimaryKeyIn($ids)
            ->whereNull('item_type')
            ->whereNull('item_id')
            ->get()
            ->each(function (File $file) {
                $file->item_id = $this->{$this->getKeyName()};
                $file->item_type = $this->getMorphClass();
                $file->save();
            });

        $this->files()
            ->wherePrimaryKeyNotIn($ids)
            ->get()
            ->each(fn (File $file) => $file->delete());
    }
}
