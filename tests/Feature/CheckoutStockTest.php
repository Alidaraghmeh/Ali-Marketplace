<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutStockTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase,WithFaker;



    public function test_checkout_fails_when_quantity_exceeds_stock(): void
{
     
     $buyer=User::factory()->create();


     $product=Product::factory()->create([

         'status'=>'approved',
         'stock_quantity'=>3
     ]);

     $cart=$buyer->cart()->create();


     $cart->items()->create(
          [
            'product_id'=>$product->id,
            'quantity'=>5
          ]
     );

   $this->actingAs($buyer);

$response = $this->post(route('checkout.store'), [
    'shipping_name' => 'Ahmad',
    'shipping_city' => 'Amman',
    'shipping_phone' => '0791234567',
    'shipping_address' => 'Amman, Jordan',
]);

$response->assertSessionHasErrors('quantity');

   
   $this->assertDatabaseMissing(
    'orders',
    [
        'user_id'=>$buyer->id,
    ]
   );
$this->assertDatabaseHas('products', [
    'id' => $product->id,
    'stock_quantity' => 3,
]);

$this->assertDatabaseCount('orders', 0);








} 



public function test_checkout_succeeds_when_stock_is_available(): void
{

$buyer=User::factory()->create();


     $product=Product::factory()->create([

         'status'=>'approved',
         'stock_quantity'=>5
     ]);
  


 $cart=$buyer->cart()->create();


     $cart->items()->create(
          [
            'product_id'=>$product->id,
            'quantity'=>3
          ]
     );



   $this->actingAs($buyer);




$response = $this->post(route('checkout.store'), [
    'shipping_name' => 'Ahmad',
    'shipping_city' => 'Amman',
    'shipping_phone' => '0791234567',
    'shipping_address' => 'Amman, Jordan',
]);


$response->assertRedirect(route('products.index'));

$response->assertSessionHasNoErrors();

$this->assertDatabaseHas('orders', [
    'user_id' => $buyer->id,
]);
$this->assertDatabaseHas('products', [
    'id' => $product->id,
    'stock_quantity' => 2,
]);

$order = Order::where('user_id', $buyer->id)->firstOrFail();

$this->assertDatabaseHas(
    'seller_orders',
    [
        'order_id'=>$order->id
    ]
     
);
$sellerOrder = $order->sellerOrders()->firstOrFail();
$this->assertDatabaseHas('order_items', [
    'seller_order_id' => $sellerOrder->id,
    'product_id' => $product->id,
    'quantity' => 3,
]);



$this->assertDatabaseMissing('cart_items', [
    'cart_id' => $cart->id,
    'product_id' => $product->id,
]);




}



public function test_checkout_rolls_back_when_one_product_has_insufficient_stock(): void
{

    $buyer=User::factory()->create();

    $product=Product::factory(2)->create(
        [
            'status'=>'approved'
        ]
    );

     $cart=$buyer->cart()->create();


     $cart->items()->create(
          [
            'product_id'=>$product[0]->id,
            'quantity'=>2
          ]     
     );
     $cart->items()->create(

       [
            'product_id'=>$product[1]->id,
            'quantity'=>3
          ]
          );

$product[0]->update([
    'stock_quantity' => 5
]);

$product[1]->update([
    'stock_quantity' => 1
]);

   $this->actingAs($buyer);




$response = $this->post(route('checkout.store'), [
    'shipping_name' => 'Ahmad',
    'shipping_city' => 'Amman',
    'shipping_phone' => '0791234567',
    'shipping_address' => 'Amman, Jordan',
]);


$response->assertSessionHasErrors('quantity');
     
  $this->assertDatabaseHas('products', [
    'id' => $product[0]->id,
    'stock_quantity' => 5,
]);
     $this->assertDatabaseHas(
        'products',
        [
            'id'=>$product[1]->id,
            'stock_quantity'=>1
        ]
    );

    $this->assertDatabaseMissing(
        'orders',
        [
            'user_id'=>$buyer->id
        ]

    );
   
$this->assertDatabaseHas('cart_items', [
     'cart_id'=>$cart->id,
     'product_id'=>$product[0]->id,
     'quantity'=>2
]);

$this->assertDatabaseHas('cart_items', [
    'cart_id'=>$cart->id,
    'product_id'=>$product[1]->id,
         'quantity'=>3

]);


}
}

