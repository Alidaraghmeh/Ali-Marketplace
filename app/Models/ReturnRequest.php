<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
#[Fillable(
    'order_item_id',
    'user_id',
    'quantity',
    'reason',
    'status',
    'seller_response',
)]
class ReturnRequest extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }

     public function orderItem(){
        return $this->belongsTo(orderItem::class);
    }
}
