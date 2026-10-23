<?php

namespace Tests\Feature;

use Database\Factories\OrderFactory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class BuyerOrdersAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase,WithFaker;
    public function test_guest_cannot_access_my_orders(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }

 
    public function test_authenticated_user_can_access_my_orders():void{

          $user=User::factory()->create();

          $this->actingAs($user);

          $response=$this->get(route('orders.index'));

          $response->assertSee('My orders');

    
    }
public function test_buyer_cannot_see_another_buyers_orders(): void
{
       $user=User::factory(2)->create();

       $this->actingAs($user[0]);

       $order1=Order::factory()->create([
        'user_id'=>$user[0]->id
       ]);
      $order2=Order::factory()->create([
        'user_id'=>$user[1]->id
      ]);

      $response=$this->get(route('orders.index'));

       $response->assertOk();
      $response->assertSee('Order #'.$order1->id);

      $response->assertDontSee('Order #'.$order2->id);


}

public function test_guest_cannot_submit_return_request(): void
{
    $response = $this->post(route('returns.store', 1), [
    'quantity' => 2,
    'reason' => 'Product is damaged',
]);

        
       
    $response->assertRedirect(route('login'));
}

public function test_buyer_cannot_return_another_buyers_item(): void
{
    $user=User::factory(2)->create();

    $order=Order::factory()->create(
        [
            'user_id'=>$user[1]->id
        ]
    );

    $sellerOrder=SellerOrder::factory()->create(
          [
            'order_id'=>$order->id,
            'status'=>'delivered'
          ]
    );

    $product=Product::factory()->create([

       'store_id'=>$sellerOrder->store_id
    ]);

    $orderItem=OrderItem::factory()->create(
        [
           'seller_order_id'=>$sellerOrder->id,
           'product_id'=>$product->id
        ]
    );

     $this->actingAs($user[0]);

  $response = $this->post(route('returns.store', $orderItem), [
    'quantity' => 1,
    'reason' => 'Product is damaged',
]);
    $response->assertSessionHasErrors('order_item');

    $this->assertDatabaseMissing('return_requests', [
    'order_item_id' => $orderItem->id,
]);
}


public function test_buyer_can_return_own_delivered_item(): void
{
    
$buyer = User::factory()->create();
    $order=Order::factory()->create(
        [
            'user_id'=>$buyer->id
        ]
    );

    $sellerOrder=SellerOrder::factory()->create(
          [
            'order_id'=>$order->id,
            'status'=>'delivered'
          ]
    );

    $product=Product::factory()->create([

       'store_id'=>$sellerOrder->store_id
    ]);

    $orderItem=OrderItem::factory()->create(
        [
           'seller_order_id'=>$sellerOrder->id,
           'product_id'=>$product->id
        ]
    );

     $this->actingAs($buyer);

  $response = $this->post(route('returns.store', $orderItem), [
    'quantity' => 1,
    'reason' => 'Product is damaged',
]);
    $response->assertSessionHasNoErrors('order_item');

    $this->assertDatabaseHas(
        'return_requests',
        [
            'order_item_id'=>$orderItem->id,
            'user_id'=>$buyer->id,
            'status'=>'pending'
        ]
    );
    $response->assertSessionHas(
    'success',
    'The return request created successfully.'
);

}
}
