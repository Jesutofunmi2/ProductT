<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    use HasFactory;
    use HasUuids;
    use HasApiTokens; 
    use HasFactory; 
    use Notifiable;


    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
