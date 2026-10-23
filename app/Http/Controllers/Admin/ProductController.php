<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    
  
public function index(Request $request){


   $query=Product::with('store');

  $validated=$request->validate(
    [
        'search' => 'nullable|string|max:100',
          'min_price' => 'nullable|numeric|min:0',
    'max_price' => 'nullable|numeric|min:0|gte:min_price',
        'sort' => 'nullable|in:price_asc,price_desc',
    'sort_status'=>'nullable|in:pending,approved,rejected,all_status'
    ]
    );

 $search = $validated['search'] ?? null;
    $sort = $validated['sort'] ?? null;
    $minPrice = $validated['min_price'] ?? null;
$maxPrice = $validated['max_price'] ?? null;
    $statusFilter=$validated['sort_status']??'pending';

  if(filled($search)){
          $query->where('name','like','%'.$search.'%') ;
       }

    if($minPrice !== null){
      $query->where('price','>=',$minPrice);
   }

   if($maxPrice !== null){
      $query->where('price','<=',$maxPrice);
   }

   if ($sort === 'price_asc') {
         $query->orderBy('price','asc');
   } elseif($sort === 'price_desc') {
         $query->orderBy('price','desc');
   }

   
     if ($statusFilter !== 'all_status') {
    $query->where('status', $statusFilter);
}


$products=$query->paginate(12)->withQueryString();

    return view('admin.products.index',compact('products'));



}


public function approve(Product $product){
    

    DB::transaction(function() use ($product){

     $lockedProduct=Product::where('id',$product->id)
     ->lockForUpdate()->firstOrFail();


     if($lockedProduct->status !== 'pending' ){

        abort(409,'Product already reviewed');
     }


     $lockedProduct->status='approved';
     $lockedProduct->save();
    });

 return redirect()
    ->route('admin.products.index')
    ->with('success', 'Product approved successfully!');


}

public function reject(Product $product){
    

    DB::transaction(function() use ($product){

     $lockedProduct=Product::where('id',$product->id)
     ->lockForUpdate()->firstOrFail();


     if($lockedProduct->status !== 'pending' ){

        abort(409,'Product already reviewed');
     }


     $lockedProduct->status='rejected';
     $lockedProduct->save();
    });

 return redirect()
    ->route('admin.products.index')
    ->with('success', 'Product rejected successfully!');


}






}


