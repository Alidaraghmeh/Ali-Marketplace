<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[Fillable('seller_order_id','product_id','quantity','product_name','unit_price')]
class OrderItem extends Model
{
  
   use HasFactory;
    public function sellerOrder(){
        return $this->belongsTo(SellerOrder::class);


    }


    public function product(){

     return $this->belongsTo(Product::class);
    }

    public function returns(){
        return $this->hasOne(ReturnRequest::class);
    }

}
