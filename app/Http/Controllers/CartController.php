<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{



  public function index(){
    
  $user =Auth::user();
   /** @var \App\Models\User $user */

    $cart = $user->cart()
    ->with('items.product')
    ->first();

    return view('cart.index',compact('cart'));
    
  }


    public function store(Request $request){

    $user=Auth::user();

   /** @var \App\Models\User $user */

   
    
    $validated=$request->validate([
        'quantity'=>'required|integer|min:1',
       'product_id' => [
    'required',
    'integer',
    Rule::exists('products', 'id')->where('status', 'approved'),
],
    ]);
 $cart=$user->cart()
   ->firstOrCreate();
    
     DB::transaction(function() use ($cart,$validated){
 
 $lockedCart = Cart::whereKey($cart->id)
        ->lockForUpdate()
        ->firstOrFail();
        
  $item = $lockedCart->items()
        ->where('product_id', $validated['product_id'])
        ->first();

   $currentQuantity=$item?->quantity ??0;

   $totalQuantity=$currentQuantity+$validated['quantity'];


    $product=Product::findOrFail($validated['product_id']);

    if($totalQuantity >$product->stock_quantity){

throw ValidationException::withMessages([
    'quantity' => 'The requested quantity exceeds available stock.'
]);    }




  if($item){
     $item->update(
        [
            'quantity'=>$totalQuantity
        ]
     );
  }else{
 $lockedCart->items()->create([
    'product_id' => $validated['product_id'],
    'quantity' => $totalQuantity
]);
   
  }



     });
 

return redirect()
    ->route('products.index')
    ->with('success', 'Product added to cart successfully!');

    }





    public function update(CartItem $cartItem,Request $request){

   


      if(Auth::id() !== $cartItem->cart->user_id){

            abort(403);

      }

      $validated=$request->validate([
           'quantity' =>'required|integer|min:1'
      ]);

 DB::transaction(function() use ($cartItem,$validated){
 
$lockedCart = Cart::whereKey($cartItem->cart_id)
    ->lockForUpdate()
    ->firstOrFail();

$item = $lockedCart->items()
    ->whereKey($cartItem->id)
    ->firstOrFail();

$product = $item->product;


if($product->status !== 'approved'){

 throw ValidationException::withMessages([
        'quantity' => 'This product is currently unavailable.'
    ]);

}
if ($product->stock_quantity < $validated['quantity']) {
    throw ValidationException::withMessages([
        'quantity' => 'The requested quantity exceeds available stock.'
    ]);
}



      $item->update([
       'quantity' => $validated['quantity']
      ]);

   

    });


    return redirect()
    ->route('cart.index')
    ->with('success','Cart updated successfully!');
    }




    public function destroy(CartItem $cartItem){

 if(Auth::id() !== $cartItem->cart->user_id){

            abort(403);

      }


    $cartItem->delete();

      return redirect()
    ->route('cart.index')
    ->with('success','Item deleted successfully!');



    }
}
