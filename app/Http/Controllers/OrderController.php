<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(){

      /** @var \App\Models\User $user */
      $user=Auth::user();

     $orders = $user->orders()
    ->with('sellerOrders.store', 'sellerOrders.items.returns')
    ->latest()
    ->paginate(15)
    ->withQueryString();

    $notifications = $user->unreadNotifications()
    ->latest()
    ->get();
      return view('orders.index',compact('orders','notifications'));
       
    }


    public function delivered(SellerOrder $sellerOrder){




    DB::transaction(function() use ($sellerOrder){

    $lockedSellerOrder=SellerOrder::whereKey($sellerOrder->id)
    ->lockForUpdate()
    ->firstOrFail();

if (Auth::id() !== $lockedSellerOrder->order->user_id) {
    abort(403);
}

       
      if($lockedSellerOrder->status !== 'shipped'){
      
       throw ValidationException::withMessages(['status'=>'The order Not be shipped yet']);
      }


    
        $lockedSellerOrder->update([
            'status'=>'delivered'
        ]);




    });
     
    return redirect()
    ->route('orders.index')
    ->with('success','The order is delivered successfully');

    }




    public function cancel(SellerOrder $sellerOrder){


    DB::transaction(function() use ($sellerOrder){

    $lockedSellerOrder=SellerOrder::whereKey($sellerOrder->id)
    ->lockForUpdate()
    ->firstOrFail();

 if (Auth::id() !== $lockedSellerOrder->order->user_id) {
    abort(403);
}

     
      if ($lockedSellerOrder->status !== 'pending') {
    throw ValidationException::withMessages([
        'status' => 'You cannot cancel this order now.'
    ]);
}


      
$orderItems = $lockedSellerOrder->items()
    ->orderBy('product_id')
    ->get();


    foreach($orderItems as $orderItem){
    
     $product=Product::whereKey($orderItem->product_id)
     ->lockForUpdate()
     ->firstOrFail();


$product->increment('stock_quantity', $orderItem->quantity);

    }


    $lockedOrder = $lockedSellerOrder->order()
    ->lockForUpdate()
    ->firstOrFail();

     if (Auth::id() !== $lockedOrder->user_id) {
    abort(403);
}


   $newTotal=bcsub($lockedOrder->total_amount,$lockedSellerOrder->total_amount,2);



    $lockedOrder->update([
       'total_amount'=>$newTotal
    ]);
    $lockedSellerOrder->update([
      
     'status'=>'cancelled'
    ]);



   
    });


   return redirect()
   ->route('orders.index')
   ->with('sucess', 'The order From this seller is cancelled');;




    }




    
}
