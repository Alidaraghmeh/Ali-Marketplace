<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable([
    'user_id',
    'total_amount',
    'status',
    'payment_method',
    'shipping_name',
    'shipping_phone',
    'shipping_city',
    'shipping_address',
    'original_total_amount'
])]

class Order extends Model
{
   
   use HasFactory;
    public function sellerOrders(){


     return $this->hasMany(SellerOrder::class);
    }

    public function user(){

       return $this->belongsTo(User::class);
    }

   public function getOverallStatus(){


$sellerOrders = $this->sellerOrders;

     if ($sellerOrders->isEmpty()) {
    return 'unknown';
}
     $allCancelled=$sellerOrders->every(
        fn($sellerOrders) => $sellerOrders->status==='cancelled' 
     );

     if($allCancelled){
     
        return 'cancelled';
     }

     $allDelivered=$sellerOrders->every(
        fn($sellerOrders) => $sellerOrders->status ==='delivered' 
     );

     if($allDelivered){
          return 'delivered';
     }

    $allFinished = $sellerOrders->every(
    fn($sellerOrder) => in_array(
        $sellerOrder->status,
        ['cancelled', 'delivered'],
        true
    )
);

   if($allFinished){
        return 'completed';
   }

   $allPending=$sellerOrders->every(
      fn($sellerOrders) => $sellerOrders->status === 'pending'
   );

   if($allPending){
return 'pending';
   }


   $allShipped=$sellerOrders->every(
      fn($sellerOrders) => $sellerOrders->status === 'shipped'
   );

   if($allShipped){
return 'shipped';
   }

   

   $allProcessing = $sellerOrders->every(
    fn($sellerOrder) => $sellerOrder->status === 'processing'
);

if ($allProcessing) {
    return 'processing';
}


return 'in_progress';

   }

}
