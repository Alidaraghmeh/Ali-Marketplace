<?php

namespace App\Http\Controllers;
use App\Models\SellerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRequestController extends Controller
{
    
   public function create(){


   return view('seller.apply');
   }



   public function store(){

    /** @var \App\Models\User $user */
$user = Auth::user();
    if($user->role !== 'buyer'){
     
      return back()->with('error','You are seller already!' );

    }
     if($user->sellerRequests()->where('status','pending')->exists()){
      
       return back()->with('error','You already have a pending seller request' );
        
   }

    $user->sellerRequests()->create([]);


return back()
->with('success','Your seller application has been submitted successfully!');

       
}
}