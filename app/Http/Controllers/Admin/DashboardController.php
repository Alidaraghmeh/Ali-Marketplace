<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

public function index(){

    
    $users=User::count();
    $products =Product::count();
    $pendingProducts=Product::where('status','pending')->count();
    $stores=Store::count();
    $orders=Order::count();
    $deliveredOrders=SellerOrder::where('status','delivered')->count();

 
     $TotalOrderValue=SellerOrder::whereNot('status','cancelled')
     ->sum('total_amount');

     $confirmedPayment=SellerOrder::where('payment_status','paid')
     ->sum('total_amount');

      $UnpaidOrdersValue = SellerOrder::whereNot('status', 'cancelled')
    ->where('payment_status', 'unpaid')
    ->sum('total_amount');


     $bestSellingStores = SellerOrder::query()
    ->where('payment_status', 'paid')
    ->select('store_id')
    ->selectRaw('SUM(total_amount) as total_paid')
    ->groupBy('store_id')
    ->orderByDesc('total_paid')
    ->limit(5)
    ->with('store:id,name')
    ->get();


      $bestSellingProducts = OrderItem::query()
      ->whereHas('sellerOrder', function ($query) {
    $query->whereNot('status', 'cancelled');
})
  ->with('product:id,name')
   ->select('product_id')
->selectRaw('SUM(quantity) as total_sold')
->groupBy('product_id')
->orderByDesc('total_sold')
->limit(5)
->get();



       return view('admin.dashboard',
       compact('users','products','pendingProducts','confirmedPayment',
       'stores','bestSellingProducts','bestSellingStores','deliveredOrders'
       ,'orders','TotalOrderValue','UnpaidOrdersValue'));

      





}
}


