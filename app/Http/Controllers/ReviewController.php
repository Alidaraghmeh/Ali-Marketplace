<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Validation\ValidationException;
class ReviewController extends Controller
{
    
 public function store(Request $request,Product $product){

   $validated = $request->validate([
    'rating' => 'required|integer|between:1,5',
    'comment' => 'nullable|string|max:1000',
]);


   $canReview = OrderItem::where('product_id', $product->id)
    ->whereHas('sellerOrder', function ($query) {
        $query->where('status', 'delivered')
            ->whereHas('order', function ($orderQuery) {
                $orderQuery->where('user_id', Auth::id());
            });
    })
    ->exists();

    if(!$canReview){
 throw ValidationException::withMessages([
        'review' => 'You can only review products you have purchased and received.',
    ]);
    }


   $alreadyReviewed = Review::where('user_id', Auth::id())
    ->where('product_id', $product->id)
    ->exists();

    if($alreadyReviewed){
          throw ValidationException::withMessages(['review'=>'You have already reviewed this product.']);
    }

    Review::create([
        'user_id'=>Auth::id(),
         'product_id'=>$product->id,
         'comment'=>$validated['comment']??null,
         'rating'=>$validated['rating']
    ]);


     return back()->with('success','Review submitted successfully.');

 }


public function update(Request $request, Review $review)
{
   
if(Auth::id() !== $review->user_id){


 abort(403);

}

 $validated = $request->validate([
    'rating' => 'required|integer|between:1,5',
    'comment' => 'nullable|string|max:1000',
]);


 $review->update([
'rating' => $validated['rating'],
 'comment'=>$validated['comment']??null
 ]);



 return redirect()
    ->route('products.show', $review->product)
    ->with('success', 'Review updated successfully.');
}
}
