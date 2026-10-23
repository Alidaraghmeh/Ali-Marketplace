<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    public function index(Request $request){

    $search=$request->query('search');
   


    $query=Product::with(['store','images'])
    ->where('status','approved');

    
       if(filled($search)){
          $query->where('name','like','%'.$search.'%') ;
       }

    $validated=$request->validate(
    [
          'min_price' => 'nullable|numeric|min:0',
    'max_price' => 'nullable|numeric|min:0|gte:min_price',
        'sort' => 'nullable|in:price_asc,price_desc,rating_desc',
    ]
    );

    $sort = $validated['sort'] ?? null;
    $minPrice = $validated['min_price'] ?? null;
$maxPrice = $validated['max_price'] ?? null;

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
   elseif($sort === 'rating_desc') {
    $query->withAvg('reviews', 'rating');
         $query->orderbyDesc('reviews_avg_rating');
   }


   
     



$products=$query->paginate(9)->withQueryString();



      return view('products.index',compact('products'));




    }
     public function show(Product $product)
   {

      $product->load('reviews.user');
      $product->loadAvg('reviews', 'rating');
      $product->loadCount('reviews');

       $canReview = false;
    $alreadyReviewed = false;

       if($product ->status !== 'approved'){
        abort(404);
       }
    if (Auth::check()) {

 $canReview = OrderItem::where('product_id', $product->id)
    ->whereHas('sellerOrder', function ($query) {
        $query->where('status', 'delivered')
            ->whereHas('order', function ($orderQuery) {
                $orderQuery->where('user_id', Auth::id());
            });
    })
    ->exists();

 $alreadyReviewed = Review::where('user_id', Auth::id())
    ->where('product_id', $product->id)
    ->exists();



    }
return view('products.show', compact(
    'product',
    'canReview',
    'alreadyReviewed'
));
   }
}
