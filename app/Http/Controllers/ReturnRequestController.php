<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReturnRequestController extends Controller
{
    

   public function store(Request $request,OrderItem $orderItem){


      $validated=$request->validate([
             'quantity'=>'required|integer|min:1',
             'reason'=>'required|string|max:1000',
      ]);
   

    if ($orderItem->sellerOrder->order->user_id !== Auth::id()) {
    throw ValidationException::withMessages([
        'order_item' => 'This is not yours'
    ]);
}




    if($orderItem->sellerOrder->status !== 'delivered'){
       throw ValidationException::withMessages([
        'order_item' => 'Your order is not delivered'
    ]);
    }

    if($orderItem->quantity < $validated['quantity'] ){
 throw ValidationException::withMessages([
        'order_item' => 'The quantity is more than quantity in your order'
    ]);
    }



    if($orderItem->returns()->exists()){
      throw ValidationException::withMessages([
        'order_item' => 'You have already requested a return for this item.'
    ]);
    }


    $orderItem->returns()->create([
             'user_id'=>Auth::id(),
             'quantity'=>$validated['quantity'],
               'reason'=>$validated['reason']
    ]);


    return back()
    ->with('success','The return request created successfully.');

}







   }
