<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable('image_path','is_primary')]
class ProductImage extends Model
{
    public function product(){

     return $this->belongsTo(Product::class);
    }
}
