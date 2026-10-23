<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerRequestController extends Controller
{
    public function index(){

    $requests=SellerRequest::with('user')->where('status','pending')->get();


    return view('admin.seller-requests.index',compact('requests'));

    }



    public function approve(SellerRequest $sellerRequest){

         DB::transaction(function () use ($sellerRequest) {
 
       $lockedRequest = SellerRequest::where('id', $sellerRequest->id)
    ->lockForUpdate()
    ->firstOrFail();

             if( $lockedRequest->status !== 'pending'){

                abort(409,'Request already reviewed');
             }
             $lockedRequest->reviewed_at=now();
             $lockedRequest->reviewed_by=Auth::id();
             $lockedRequest->status='approved';             
             $lockedRequest->user->role='seller';

             $lockedRequest->save();
             $lockedRequest->user->save();
       });

             return redirect()
             ->route('admin.seller-requests.index')
             ->with('success','Seller request approved successfully!');
    }




     public function reject(SellerRequest $sellerRequest ,Request $request){

  $data=$request->validate(
            [
                'rejection_reason'=>'required|string|max:1000'
            ]
          );
     DB::transaction(function() use ($sellerRequest,$data){

        

          $lockedRequest = SellerRequest::where('id', $sellerRequest->id)
    ->lockForUpdate()
    ->firstOrFail();

       if($lockedRequest->status !== 'pending'){
         
       abort(409,'Request already reviewed');
       }
      
        $lockedRequest->status='rejected';
        $lockedRequest->reviewed_at=now();
        $lockedRequest->reviewed_by=Auth::user()->id;
        $lockedRequest->rejection_reason=$data['rejection_reason'];
        $lockedRequest->save();

     });



     return redirect()
     ->route('admin.seller-requests.index')
     ->with('success','Seller request rejected successfully!');

    }



    public function history(){
    
    $requests=SellerRequest::with('user','reviewer')
    ->
    whereIn('status',['approved','rejected'])->get();



    return view('admin.seller-requests.history',compact('requests'));


    }

   
}
