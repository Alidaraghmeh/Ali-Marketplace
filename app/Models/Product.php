<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable('name','description','stock_quantity','price','category_id')]
class Product extends Model
{
  use HasFactory;
    public  function store(){

     return $this->belongsTo(Store::class);
    }


    public function category(){


      return $this->belongsTo(Category::class);
    }

    public function images(){

      return $this->hasMany(ProductImage::class);
    }

    public function cartItems(){
      return $this->hasMany(CartItem::class);
    }


    public function orderItems(){
      return $this->hasMany(OrderItem::class);
    }


    public function reviews(){

   return $this->hasMany(Review::class);

    }
}
