<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SellerRequestController as AdminSellerRequestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController as ControllersProductController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\SellerRequestController;
use App\Http\Controllers\SellerReturnRequestController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('products.index');
});



Route::get('/products',[ControllersProductController::class,'index'])->name('products.index');

Route::middleware('guest')->group(function(){
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register',[RegisterController::class,'store'])->name('register.store');

Route::get('/login',[LoginController::class,'showLogin'])->name('login');
       Route::post('/login',[LoginController::class,'login'])->name('login.store');
}
);

 Route::get('/product/{product}',[ControllersProductController::class,'show'])
     ->name('products.show');

Route::middleware('auth')->group(
   function(){

       Route::post('/logout',[LoginController::class,'logout'])->name('logout');
       Route::get('/seller/apply',[SellerRequestController::class,'create'])->name('seller.apply.create');
     Route::post('/seller/apply',[SellerRequestController::class,'store'])->name('seller.apply.store');
     Route::get('/cart',[CartController::class,'index'])->name('cart.index');

  Route::post('/cart/items',[CartController::class,'store'])->name('cart.items.store');
    Route::patch('/cart/items/{cartItem}',[CartController::class,'update'])->name('cart.items.update');
Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])
    ->name('cart.items.destroy');

    Route::get('/checkout',[CheckoutController::class,'create'])->name('checkout.create');
    Route::post('/checkout',[CheckoutController::class,'store'])->name('checkout.store');
    Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
    Route::patch('/orders/{sellerOrder}/status',[OrderController::class,'delivered'])
    ->name('orders.delivered');
    Route::patch('/orders/{sellerOrder}/cancel',[OrderController::class,'cancel'])
    ->name('orders.cancelled');

    
        Route::patch('/reviews/{review}',[ReviewController::class,'update'])
     ->name('reviews.update');
    Route::post('/products/{product}/reviews',[ReviewController::class,'store'])
    ->name('reviews.store');
    
   Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');

    Route::post('/returns/{orderItem}',[ReturnRequestController::class,'store'])
    ->name('returns.store');

   }
);


Route::middleware(['auth', 'can:manage-seller-requests'])
    ->group(function () {

        Route::get(
            '/admin/seller-requests',
            [AdminSellerRequestController::class, 'index']
        )->name('admin.seller-requests.index');

        Route::patch(
            '/admin/seller-requests/{sellerRequest}/approve',
            [AdminSellerRequestController::class, 'approve']
        )->name('admin.seller-requests.approve');

        Route::patch('/admin/seller-requests/{sellerRequest}/reject',
        [AdminSellerRequestController::class,'reject'])
        ->name('admin.seller-requests.reject');

        Route::get('/admin/seller-requests/history',
        [AdminSellerRequestController::class,'history']
        );

    });



    Route::middleware(['auth','can:create-store'])
    ->group(function(){

       Route::get('/seller/store/create',[StoreController::class,'create'])
       ->name('seller.store.create');

       Route::post('/seller/store',[StoreController::class,'store'])
       ->name('seller.store.store');

    });



      Route::middleware(['auth','can:manage-store'])
      ->prefix('seller')
    ->name('seller.')
    ->group(function(){

      Route::get('dashboard',[StoreController::class,'dashboard'])
      ->name('dashboard');

      Route::resource('products',ProductController::class)
      ->except(['show']);
      Route::get('orders',[SellerOrderController::class,'index'])->name('orders.index');
      Route::patch(
    'orders/{sellerOrder}/status',
    [SellerOrderController::class, 'updateStatus']
)->name('orders.update-status');


 Route::patch(
    'orders/{sellerOrder}/ship',
    [SellerOrderController::class, 'ship']
)->name('orders.ship');



Route::patch('orders/{sellerOrder}/confirm-payment',[SellerOrderController::class,'confirmPayment'])
->name('orders.confirm-payment');

  Route::get('/returns',[SellerReturnRequestController::class,'index'])
  ->name('returns.index');
  Route::patch('/returns/{returnRequest}/approve',[SellerReturnRequestController::class,'approve'])
  ->name('returns.approve');
  Route::patch('/returns/{returnRequest}/reject',[SellerReturnRequestController::class,'reject'])
  ->name('returns.reject');
});


     Route::middleware(['auth', 'can:manage-products'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

           Route::get('products',[AdminProductController::class,'index'])
           ->name('products.index');

           Route::patch('products/{product}/approve',[AdminProductController::class,'approve'])
           ->name('products.approve');

           Route::patch('products/{product}/reject',[AdminProductController::class,'reject'])
           ->name('products.reject');
    });


    Route::get('admin/dashboard',[DashboardController::class,'index'])
    ->name('admin.dashboard')
    ->middleware(['auth','can:view-admin-dashboard']);



    Route::view('/ajax-demo','ajax-demo');

    Route::get('/ajax-demo/product',function(){
       
      return response()->json([
        'name'=>'iPone 16',
        'price'=>500
      ]      
      );
    });


Route::post('/ajax-demo/quantity', function (Request $request) {

    $data = $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    return response()->json([
        'message' => 'تم استقبال الكمية',
        'quantity' => $data['quantity'],
    ]);

})->name('ajax.quantity');