<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    

   public function index(Request $request){

       $validated=$request->validate(
    [
        'search' => 'nullable|string|max:100',
          'min_price' => 'nullable|numeric|min:0',
    'max_price' => 'nullable|numeric|min:0|gte:min_price',
        'sort' => 'nullable|in:price_asc,price_desc',
    'sort_status'=>'nullable|in:pending,approved,rejected'
    ]
    );

    $search = $validated['search'] ?? null;
    $sort = $validated['sort'] ?? null;
    $minPrice = $validated['min_price'] ?? null;
$maxPrice = $validated['max_price'] ?? null;
    $statusFilter=$validated['sort_status']??null;
    $user=Auth::user();

    $store=$user->store;

    
       $query=$store->products();
    
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

    if ($statusFilter !== null) {
    $query->where('status', $statusFilter);
}


$products=$query->paginate(12)->withQueryString();



    return view('seller.products.index',compact('products'));

   }

 


   public function create(){
      

     $categories=Category::whereNotNull('parent_id')->get();
     
   return view('seller.products.create',compact('categories'));

   }



public function store(ProductStoreRequest $request)
{
    $data = $request->validated();

    unset($data['images']);
    unset($data['primary_image']);
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $store = $user->store;

    $product = $store->products()->create($data);

   if($request->hasFile('images')){
       
        foreach($request->file('images') as $image){

         $path = $image->store('products','public');
                  $product->images()->create([
    'image_path' =>$path
   ]);
        }

   }

   if($request->hasFile('primary_image')){

     $path =$request->file('primary_image')->store('products','public');

     $product->images()->create([
          'image_path' =>$path,
        'is_primary'=>true
     ]);
   }

  
   return redirect()
   ->route('seller.products.index')
   ->with('success','Product submitted for approval!');

}


#[Authorize('update','product')]
public function edit(Product $product){


    $categories=Category::whereNotNull('parent_id')->get();
   return view('seller.products.edit',compact('product','categories'));

}


#[Authorize('update', 'product')]
public function update(ProductUpdateRequest $request, Product $product)
{
    $data = $request->validated();

    unset($data['images'], $data['primary_image']);

    // أولًا: فحص عدد الصور
    $currentImages = $product->images()->count();

    $primaryImage = $product->images()
        ->where('is_primary', true)
        ->first();

    $newImagesCount = count($request->file('images', []));

    $newPrimaryCount = (
        $request->hasFile('primary_image') && !$primaryImage
    ) ? 1 : 0;

    $totalImages = $currentImages
        + $newImagesCount
        + $newPrimaryCount;

    if ($totalImages > 5) {
        throw ValidationException::withMessages([
            'images' => 'Maximum 5 images allowed per product.',
        ]);
    }

    // ثانيًا: تجهيز مسارات الصور
    $uploadedPaths = [];
    $additionalPaths = [];
    $newPrimaryPath = null;
    $oldPrimaryPath = null;

    try {

        // رفع الصور الإضافية
        foreach ($request->file('images', []) as $image) {

            $path = $image->store('products', 'public');

            if ($path === false) {
                throw new \RuntimeException('Image upload failed.');
            }

            $uploadedPaths[] = $path;
            $additionalPaths[] = $path;
        }

        // رفع الصورة الرئيسية الجديدة إن وجدت
        if ($request->hasFile('primary_image')) {

            $newPrimaryPath = $request->file('primary_image')
                ->store('products', 'public');

            if ($newPrimaryPath === false) {
                throw new \RuntimeException('Primary image upload failed.');
            }

            $uploadedPaths[] = $newPrimaryPath;
        }

        // ثالثًا: تحديث قاعدة البيانات
        DB::transaction(function () use (
            $product,
            $data,
            $additionalPaths,
            $newPrimaryPath,
            &$oldPrimaryPath
        ) {

            $product->fill($data);
            $product->status = 'pending';
            $product->save();

            // ربط الصور الإضافية
            foreach ($additionalPaths as $path) {

                $product->images()->create([
                    'image_path' => $path,
                ]);
            }

            // استبدال الصورة الرئيسية
            if ($newPrimaryPath !== null) {

                $primaryImage = $product->images()
                    ->where('is_primary', true)
                    ->first();

                if ($primaryImage) {

                    $oldPrimaryPath = $primaryImage->image_path;

                    $primaryImage->update([
                        'image_path' => $newPrimaryPath,
                    ]);

                } else {

                    $product->images()->create([
                        'image_path' => $newPrimaryPath,
                        'is_primary' => true,
                    ]);
                }
            }
        });

    } catch (\Throwable $e) {

        // تنظيف الصور الجديدة عند الفشل
        foreach ($uploadedPaths as $uploadedPath) {
            try {
                Storage::disk('public')->delete($uploadedPath);
            } catch (\Throwable $cleanupError) {
                report($cleanupError);
            }
        }

        throw $e;
    }

    // رابعًا: حذف الرئيسية القديمة بعد نجاح العملية
    if ($oldPrimaryPath !== null) {
        try {
            Storage::disk('public')->delete($oldPrimaryPath);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    return redirect()
        ->route('seller.products.index')
        ->with('success', 'Product updated and submitted for approval!');
}

}
