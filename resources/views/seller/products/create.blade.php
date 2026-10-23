@extends('layouts.app')

@section('title', 'Add Product | Ali Marketplace')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8">
        <a href="{{ route('seller.products.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">← Back to My Products</a>
        <h1 class="mt-5 text-3xl font-bold text-gray-900">Add New Product 📦</h1>
        <p class="mt-2 text-gray-500">Add product details and images for your store.</p>
    </div>

    @if (session('success'))
        <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
            <p class="font-semibold">Please review these errors:</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="grid items-start gap-7 lg:grid-cols-3">
        @csrf
        <div class="space-y-7 lg:col-span-2">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-bold text-gray-900">Product Information</h2>
                <p class="mb-6 mt-2 text-sm text-gray-500">Help customers understand what you are selling.</p>
                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Product Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter product name" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-semibold text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">Choose Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="5" placeholder="Describe your product..." class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-bold text-gray-900">Pricing & Inventory</h2>
                <p class="mb-6 mt-2 text-sm text-gray-500">Set your selling price and available quantity.</p>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="price" class="mb-2 block text-sm font-semibold text-gray-700">Price (JOD) <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" min="0.01" step="0.01" value="{{ old('price') }}" placeholder="0.00" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock_quantity" class="mb-2 block text-sm font-semibold text-gray-700">Stock Quantity <span class="text-red-500">*</span></label>
                        <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="{{ old('stock_quantity') }}" placeholder="0" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('stock_quantity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900">Product Images</h2>
                <p class="mb-6 mt-2 text-sm text-gray-500">Choose your main image and optional additional photos.</p>
                <div class="mb-6">
                    <label for="primary_image" class="mb-2 block text-sm font-semibold text-gray-700">Primary Image</label>
                    <input id="primary_image" type="file" name="primary_image" accept="image/*" class="block w-full rounded-xl border border-dashed border-gray-300 p-3 text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:text-blue-700">
                    @error('primary_image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="images" class="mb-2 block text-sm font-semibold text-gray-700">Additional Images</label>
                    <input id="images" type="file" name="images[]" multiple accept="image/*" class="block w-full rounded-xl border border-dashed border-gray-300 p-3 text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:text-blue-700">
                    <p class="mt-2 text-xs text-gray-500">You can select more than one image.</p>
                    @error('images') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-5 text-sm leading-6 text-blue-900">🛡️ Product approval is managed by the admin through the existing review system.</div>
            <button type="submit" class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 font-bold text-white transition hover:bg-blue-700">Add Product →</button>
        </aside>
    </form>
</div>
@endsection
