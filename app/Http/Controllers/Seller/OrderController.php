<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Notifications\SellerOrderShippedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(){

  /** @var \App\Models\User $user */
$user = Auth::user();

$store = $user->store;

if (!$store) {
    abort(403);
}

$sellerOrders = $store->sellerOrders()
    ->with('order')
    ->latest()
    ->paginate(15)
    ->withQueryString();

return view('seller.orders.index', compact('sellerOrders'));
    }


    public function updateStatus(SellerOrder $sellerOrder,Request $request){

 /** @var \App\Models\User $user */
$user = Auth::user();





   DB::transaction(function() use ($sellerOrder,$user){

     $lockedSellerOrder=SellerOrder::whereKey($sellerOrder->id)
     ->lockForUpdate()
     ->firstOrFail();

if ($user->store?->id !== $lockedSellerOrder->store_id) {
    abort(403);
}




  if ($lockedSellerOrder->status !== 'pending') {
    throw ValidationException::withMessages([
        'status' => 'Only pending orders can be moved to processing.'
    ]);
}




     $lockedSellerOrder->update([
    'status' => 'processing'
]);

   });

   return redirect()->route('seller.orders.index')
   ->with('success','The order is start processing.');

    }






public function ship(SellerOrder $sellerOrder)
{
 /** @var \App\Models\User $user */
$user = Auth::user();



    DB::transaction(function() use($sellerOrder,$user){

       $lockedSellerOrder=SellerOrder::whereKey($sellerOrder->id)
     ->lockForUpdate()
     ->firstOrFail();

if ($user->store?->id !== $lockedSellerOrder->store_id) {
    abort(403);
}


if ($lockedSellerOrder->status !== 'processing') {
    throw ValidationException::withMessages([
        'status' => 'Only processing orders can be shipped.'
    ]);
}
  $lockedSellerOrder->update([
    'status' => 'shipped'
]);
   
       
     DB::afterCommit(function () use ($lockedSellerOrder) {
    $buyer = $lockedSellerOrder->order->user;

    $buyer->notify(
        new SellerOrderShippedNotification($lockedSellerOrder)
    );
});
    });

   return redirect()
    ->route('seller.orders.index')
    ->with('success', 'Order shipped successfully!');
}



public function confirmPayment(SellerOrder $sellerOrder){

  
 /** @var \App\Models\User $user */
$user = Auth::user();


    DB::transaction(function() use ($sellerOrder,$user){
    

 $lockedSellerOrder=SellerOrder::whereKey($sellerOrder->id)
     ->lockForUpdate()
     ->firstOrFail();

if ($user->store?->id !== $lockedSellerOrder->store_id) {
    abort(403);
}


   if ($lockedSellerOrder->status !== 'delivered') {
    throw ValidationException::withMessages([
        'status' => 'The order has not been delivered yet.'
    ]);
}


   if($lockedSellerOrder->payment_status !== 'unpaid'){
      throw ValidationException::withMessages(['payment_status'=>'This payment has already been confirmed or is not eligible.']);
   }


     $lockedSellerOrder->update([
    
      'payment_status'=>'paid'

     ]);









    });



    return redirect()
    ->route('seller.orders.index')
    ->with('success','Payment confirmed successfully.');
}






}