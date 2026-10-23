<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\OrderItem;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    

   public function create(){

       
    return view('seller.store.create');

   }


   public function store(StoreRequest $storeRequest){
   
        $data=$storeRequest->validated();
        $logo=$storeRequest->hasFile('logo_path');
     if($logo){
       $path=  $storeRequest->file('logo_path')->store('store-logos','public');

       $data['logo_path']=$path;
     }
       /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->store()->create($data);


          return
          redirect('/')
          ->with('success','Store created successfully!');

   }


   public function dashboard(){

/** @var \App\Models\User $user */
$user = Auth::user();

   $store=$user->store;
    
$notifications = $user->unreadNotifications()
    ->latest()
    ->get();

   $products=$store->products()->count();
   $sellerOrder=$store->sellerOrders()->count();
   $pendingOrders=$store->sellerOrders()->where('status','pending')->count();

   $deliveredOrders=$store->sellerOrders()->where('status','delivered')->count();

    $sumOrderNotCancelled=$store->sellerOrders()->whereNot('status','cancelled')->sum('total_amount');
    $sumOrderPaid=$store->sellerOrders()->where('payment_status','paid')->sum('total_amount');

  $bestSellingProducts = OrderItem::query()
  ->with('product:id,name')
    ->whereHas('sellerOrder', function ($query) use ($store) {

             $query->whereNot('status','cancelled')
             ->where('store_id',$store->id);
    })->select('product_id')
->selectRaw('SUM(quantity) as total_sold')
->groupBy('product_id')
->orderByDesc('total_sold')
->limit(5)
->get();

   return view('seller.dashboard',compact('store','products'
   ,'sellerOrder','pendingOrders','deliveredOrders','sumOrderNotCancelled','sumOrderPaid','bestSellingProducts','notifications'
   ));
   }
}
