<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('name','description','logo_path','shipping_fee')]
class Store extends Model
{

     use HasFactory;
    public function user(){


      return $this->belongsTo(User::class);
    }


    public function products(){


      return $this->hasMany(Product::class);
    }


    public function sellerOrders(){
      return $this ->hasMany(SellerOrder::class);
    }
}
