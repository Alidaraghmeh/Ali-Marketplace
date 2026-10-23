<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnRequest;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellerReturnAuthorizationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase,WithFaker;
    public function test_seller_cannot_approve_another_stores_return(): void
{
    
           $user=User::factory(3)->create();

           $store1=Store::factory()->create(['user_id'=>$user[0]->id]);
          $store2=Store::factory()->create(['user_id'=>$user[1]->id]);


          $order=Order::factory()->create(['user_id'=>$user[2]->id]);

          $sellerOrder=SellerOrder::factory()->create([
                   'order_id'=>$order->id,
            'store_id'=>$store2->id]);


            $product=Product::factory()->create(
                [
                    'store_id'=>$store2->id
                ]
            );

            $orderItem=OrderItem::factory()->create(
                 [
                    'seller_order_id'=>$sellerOrder->id,
                    'product_id'=>$product->id
                 ]
            );



            $returnRequest=ReturnRequest::factory()->create([
                'order_item_id'=>$orderItem->id,
                'user_id'=>$user[2]->id,
                'quantity'=>1,
                'reason'=>'Product is Damage.',
                'status'=>'pending'
            ]);


            $this->actingAs($user[0]);

        $response=$this->patch(route('seller.returns.approve',$returnRequest));

        $response->assertForbidden();

      $this->assertDatabaseHas('return_requests', [
    'id' => $returnRequest->id,
    'status' => 'pending',
]);
         }


         public function test_seller_can_approve_own_stores_return(): void
{
    
                  $user=User::factory(3)->create();

                  $user = User::factory(3)->create();

$user[0]->role = 'seller';
$user[0]->save();

$user[1]->role = 'seller';
$user[1]->save();

$store1 = Store::factory()->create([
    'user_id' => $user[0]->id,
]);

$store2 = Store::factory()->create([
    'user_id' => $user[1]->id,
]);
          $order=Order::factory()->create(['user_id'=>$user[2]->id]);

          $sellerOrder=SellerOrder::factory()->create([
                   'order_id'=>$order->id,
            'store_id'=>$store2->id]);


            $product=Product::factory()->create(
                [
                    'store_id'=>$store2->id
                ]
            );

            $orderItem=OrderItem::factory()->create(
                 [
                    'seller_order_id'=>$sellerOrder->id,
                    'product_id'=>$product->id
                 ]
            );
              $returnRequest=ReturnRequest::factory()->create([
                'order_item_id'=>$orderItem->id,
                'user_id'=>$user[2]->id,
                'quantity'=>1,
                'reason'=>'Product is Damage.',
                'status'=>'pending'
            ]);


          $this->actingAs($user[1]);
$this->assertTrue(
    Gate::forUser($user[1])->allows('manage-store')
);
$response = $this->from(route('seller.returns.index'))
    ->patch(route('seller.returns.approve', $returnRequest));

$response->assertRedirect(route('seller.returns.index'));
          $this->assertDatabaseHas(
            'return_requests',
            [
                'status'=>'approved'
            ]
          );


          $this->assertDatabaseHas('return_requests', [
    'id' => $returnRequest->id,
    'status' => 'approved',
]);
}


public function test_seller_can_reject_own_stores_return(): void
{
    $seller = User::factory()->create([
    'role' => 'seller',
]);

$buyer = User::factory()->create();
   $store = Store::factory()->create([
    'user_id' => $seller->id,
]);

   
   $order=Order::factory()->create(
    [
        'user_id'=>$buyer->id
    ]
   );

   $sellerOrder=SellerOrder::factory()->create(
    [
        'store_id'=>$store->id,
      'order_id'=>$order->id
    ] 
   );
    $product=Product::factory()->create(
        [
              'store_id'=>$store->id
        ]
    );
     $orderItem=OrderItem::factory()->create(
        [
            'seller_order_id'=>$sellerOrder->id,
           'product_id'=>$product->id
        ]
     );

    $returnRequest=ReturnRequest::factory()->create(
        [
            'user_id'=>$buyer->id,
                'order_item_id'=>$orderItem->id,
                'status'=>'pending'
        ]
    );

         $this->actingAs($seller);

$response = $this
    ->from(route('seller.returns.index'))
    ->patch(route('seller.returns.reject', $returnRequest), [
        'seller_response' => 'The product does not meet return conditions.',
    ]);
    $response->assertRedirect(route('seller.returns.index'));

 $this->assertDatabaseHas('return_requests', [
    'id' => $returnRequest->id,
    'status' => 'rejected',
    'seller_response' => 'The product does not meet return conditions.',
]);





}


public function test_seller_cannot_reject_approved_return(): void
{
       $seller = User::factory()->create([
    'role' => 'seller',
]);

$buyer = User::factory()->create();
   $store = Store::factory()->create([
    'user_id' => $seller->id,
]);

   
   $order=Order::factory()->create(
    [
        'user_id'=>$buyer->id
    ]
   );

 $sellerOrder = SellerOrder::factory()->create([
    'store_id' => $store->id,
    'order_id' => $order->id,
    'status' => 'delivered',
]);
    $product=Product::factory()->create(
        [
              'store_id'=>$store->id
        ]
    );
     $orderItem=OrderItem::factory()->create(
        [
            'seller_order_id'=>$sellerOrder->id,
           'product_id'=>$product->id
        ]
     );

    $returnRequest=ReturnRequest::factory()->create(
        [
            'user_id'=>$buyer->id,
                'order_item_id'=>$orderItem->id,
                'status'=>'approved'
        ]
    );

         $this->actingAs($seller);

         $response = $this
    ->from(route('seller.returns.index'))
    ->patch(route('seller.returns.reject', $returnRequest), [
        'seller_response' => 'The product does not meet return conditions.',
    ]);

$response->assertSessionHasErrors('return_request');

         $this->assertDatabaseHas('return_requests', [
    'id' => $returnRequest->id,
    'status' => 'approved',
]);
}

}
