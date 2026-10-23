<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Notifications\NewSellerOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{


  public function create()
  {
   $user=Auth::user();
       /** @var \App\Models\User $user */
   $cart=$user->cart()
   ->with('items.product')->first();

  if($cart === null || $cart->items->isEmpty() ){
    return redirect()
    ->route('cart.index')
    ->with('error', 'Your cart is empty. Please add products before checkout.');
         }
   return view('checkout.create',compact('cart'));

  }



 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. Lock cart
            $lockedCart = $user->cart()
                ->lockForUpdate()
                ->first();

            if (
                $lockedCart === null ||
                $lockedCart->items()->doesntExist()
            ) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.'
                ]);
            }

            // 2. Get cart items
            $items = $lockedCart->items()
                ->orderBy('product_id')
                ->get();

            $groupedItems = [];

            // 3. Validate products and deduct stock
            foreach ($items as $item) {

                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->status !== 'approved') {
                    throw ValidationException::withMessages([
                        'product' => 'This product is currently unavailable.'
                    ]);
                }

                if ($item->quantity <= 0) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Invalid quantity.'
                    ]);
                }

                if ($product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'The requested quantity exceeds available stock.'
                    ]);
                }

                $product->decrement(
                    'stock_quantity',
                    $item->quantity
                );

                $storeId = $product->store_id;

                $groupedItems[$storeId][] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                ];
            }

            // 4. Calculate totals per store
            $sellerOrderData = [];
            $grandTotal = '0.00';

            foreach ($groupedItems as $storeId => $products) {

                $subtotal = '0.00';

                foreach ($products as $productData) {

                    $lineTotal = bcmul(
                        (string) $productData['unit_price'],
                        (string) $productData['quantity'],
                        2
                    );

                    $subtotal = bcadd(
                        $subtotal,
                        $lineTotal,
                        2
                    );
                }

                $store = Store::whereKey($storeId)
                    ->firstOrFail();

                $shippingFee = $store->shipping_fee;

                $totalAmount = bcadd(
                    $subtotal,
                    (string) $shippingFee,
                    2
                );

                $grandTotal = bcadd(
                    $grandTotal,
                    $totalAmount,
                    2
                );

                $sellerOrderData[$storeId] = [
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $totalAmount,
                    'products' => $products,
                ];
            }

            // 5. Create main order
            $order = $user->orders()->create([
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => $grandTotal,
                 'original_total_amount' =>$grandTotal
            ]);

            // 6. Create seller orders and order items
            foreach ($sellerOrderData as $storeId => $storeData) {

                $sellerOrder = $order->sellerOrders()->create([
                    'store_id' => $storeId,
                    'subtotal' => $storeData['subtotal'],
                    'shipping_fee' => $storeData['shipping_fee'],
                    'total_amount' => $storeData['total_amount'],
                ]);



                foreach ($storeData['products'] as $productData) {

                    $sellerOrder->items()->create([
                        'product_id' => $productData['product_id'],
                        'product_name' => $productData['product_name'],
                        'quantity' => $productData['quantity'],
                        'unit_price' => $productData['unit_price'],
                    ]);
                }

                DB::afterCommit(function() use ($sellerOrder){
                        $seller=$sellerOrder->store->user;
                        $seller->notify(new NewSellerOrderNotification($sellerOrder));
                });
            }

            // 7. Empty cart after successful order creation
            $lockedCart->items()->delete();

        });

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Your order has been placed successfully!'
            );
    }
}
