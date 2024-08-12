<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\File;
use App\Enums\FileType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database with products.
     *
     * @return void
     */
    public function run()
    {
        Product::factory(50)->create();
    }
}

