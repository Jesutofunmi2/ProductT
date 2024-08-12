<?php

namespace App\Models;

use App\Models\Concerns\HasFiles;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory;
    use HasUlids;
    use HasApiTokens; 
    use HasFactory; 
    use Notifiable;
    use SoftDeletes;
    use HasFiles;

    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

}
