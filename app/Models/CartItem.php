<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable('quantity','product_id')]
class CartItem extends Model
{
    public function cart(){

      return $this->belongsTo(Cart::class);
    }

    public function product(){

     return $this->belongsTo(Product::class);
    }
}
