<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SellerReturnRequestController extends Controller
{
    public function index(){

      $user=Auth::user();
      $store=$user->store ;

      if (!$store) {
    abort(403, 'You do not have a store.');
}
     $returnRequests = ReturnRequest::query()
    ->whereHas('orderItem.sellerOrder', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->get();


          return view('seller.returns.index',compact('returnRequests'));
    }



    public function approve(ReturnRequest $returnRequest){
     
     

    $store = Auth::user()->store;

if (!$store) {
    abort(403);
}



     DB::transaction(function () use ($returnRequest, $store) {

    $lockedReturnRequest = ReturnRequest::query()
        ->whereKey($returnRequest->id)
        ->lockForUpdate()
        ->firstOrFail();

    if ($lockedReturnRequest->orderItem->sellerOrder->store_id !== $store->id) {
        abort(403);
    }

    if ($lockedReturnRequest->status !== 'pending') {
        throw ValidationException::withMessages([
            'return_request' => 'The request is not pending',
        ]);
    }

    $lockedReturnRequest->update([
        'status' => 'approved',
    ]);
});
      


       return back()
       ->with('success','The return Request is approved successfully.');
    }

    public function reject(Request $request,ReturnRequest $returnRequest){

             $validated=$request->validate(['seller_response'=>'required|string|max:1000']);

     
      $store = Auth::user()->store;

if (!$store) {
    abort(403);
}



DB::transaction(function () use ($returnRequest, $store,$validated){

 $lockedReturnRequest = ReturnRequest::query()
        ->whereKey($returnRequest->id)
        ->lockForUpdate()
        ->firstOrFail();

    if ($lockedReturnRequest->orderItem->sellerOrder->store_id !== $store->id) {
        abort(403);
    }


     if($lockedReturnRequest->status !== 'pending'){
       throw ValidationException::withMessages(['return_request' => 'The request is not pending']);
        }


          $lockedReturnRequest->update(
        [
            'status'=>'rejected',
            'seller_response'=>$validated['seller_response']
        ]
       );
}) ;

       return back()
       ->with('success','The return Request is rejected successfully.');
    }
}
