@extends('layouts.app')

@section('title', 'Edit Product | Ali Marketplace')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8">
        <a href="{{ route('seller.products.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">← Back to My Products</a>
        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Product ✏️</h1>
                <p class="mt-2 text-gray-500">Update {{ $product->name }} and manage its images.</p>
            </div>
            @php
                $statusClass = match ($product->status) {
                    'approved' => 'bg-green-100 text-green-700',
                    'pending' => 'bg-amber-100 text-amber-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">{{ ucfirst($product->status) }}</span>
        </div>
    </div>

    @if (session('success'))
        <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
            <p class="font-semibold">Please correct these errors:</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="grid items-start gap-7 lg:grid-cols-3">
        @csrf
        @method('PUT')
        <div class="space-y-7 lg:col-span-2">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="mb-6 text-xl font-bold text-gray-900">Product Information</h2>
                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Product Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-semibold text-gray-700">Category</label>
                        <select id="category_id" name="category_id" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="6" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="mb-6 text-xl font-bold text-gray-900">Pricing & Inventory</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="price" class="mb-2 block text-sm font-semibold text-gray-700">Price (JOD)</label>
                        <input id="price" name="price" type="number" min="0.01" step="0.01" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock_quantity" class="mb-2 block text-sm font-semibold text-gray-700">Stock Quantity</label>
                        <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @error('stock_quantity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900">Current Images</h2>
                <p class="mb-5 mt-2 text-sm text-gray-500">Your uploaded product photos.</p>
                @if ($product->images->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($product->images as $image)
                            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-2">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }} image" class="h-28 w-full object-contain" loading="lazy">
                                @if ($image->is_primary)
                                    <span class="mt-2 block rounded-md bg-amber-100 px-2 py-1 text-center text-xs font-semibold text-amber-800">⭐ Primary Image</span>
                                @endif
                            </div>