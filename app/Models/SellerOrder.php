<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'order_id',
    'store_id',
    'subtotal',
    'shipping_fee',
    'total_amount',
    'status',
    'payment_status'
)]


class SellerOrder extends Model
{
    use HasFactory;
    public function order(){

     return $this->belongsTo(Order::class);
    }

    public function store(){
    
     return $this->belongsTo(Store::class);
    
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

}
