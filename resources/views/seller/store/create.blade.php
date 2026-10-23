@extends('layouts.app')

@section('title', 'Create Store | Ali Marketplace')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-8">
        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">← Back to Marketplace</a>
        <p class="mt-6 text-sm font-semibold uppercase tracking-widest text-blue-600">Seller Setup</p>
        <h1 class="mt-2 text-3xl font-bold text-gray-900 sm:text-4xl">Create Your Store 🏪</h1>
        <p class="mt-3 text-gray-500">Set up your storefront and start preparing your business on Ali Marketplace.</p>
    </div>

    @if (session('success'))
        <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
            <p class="mb-2 font-semibold">Please check the following:</p>
            <ul class="list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-7 lg:grid-cols-3">
        <form action="{{ route('seller.store.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-2">
            @csrf
            <div class="mb-7 border-b border-gray-100 pb-5">
                <h2 class="text-xl font-bold text-gray-900">Store Information</h2>
                <p class="mt-2 text-sm text-gray-500">Tell customers about your shop.</p>
            </div>

            <div class="mb-6">
                <label for="store-name" class="mb-2 block text-sm font-semibold text-gray-700">Store Name <span class="text-red-500">*</span></label>
                <input id="store-name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ali Tech Store" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="store-description" class="mb-2 block text-sm font-semibold text-gray-700">Description</label>
                <textarea id="store-description" name="description" rows="5" placeholder="What makes your store special?" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>
                @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="store-logo" class="mb-2 block text-sm font-semibold text-gray-700">Store Logo</label>
                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-5">
                    <div class="mb-3 text-3xl">🖼️</div>
                    <input id="store-logo" type="file" name="logo_path" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-200">
                    <p class="mt-2 text-xs text-gray-500">Choose an image for your storefront logo.</p>
                </div>
                @error('logo_path') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label for="shipping-fee" class="mb-2 block text-sm font-semibold text-gray-700">Shipping Fee (JOD) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input id="shipping-fee" type="number" name="shipping_fee" min="0" step="0.01" value="{{ old('shipping_fee') }}" placeholder="0.00" required class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-16 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-medium text-gray-400">JOD</span>
                </div>
                @error('shipping_fee') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 font-bold text-white transition hover:bg-blue-700">Create Store →</button>
        </form>

        <aside class="h-fit rounded-2xl border border-blue-100 bg-blue-50 p-6 lg:sticky lg:top-24">
            <h2 class="text-lg font-bold text-gray-900">✨ Your Storefront</h2>
            <p class="mt-3 text-sm leading-6 text-gray-600">Your store name, description and logo help customers recognize your business.</p>
            <div class="my-5 border-t border-blue-100"></div>
            <h3 class="font-semibold text-gray-900">🚚 Shipping Fee</h3>
            <p class="mt-2 text-sm leading-6 text-gray-600">Enter your store’s shipping fee. Order calculations remain handled securely by the backend.</p>
            <div class="mt-5 rounded-xl bg-white p-4 text-sm text-gray-500">Tip: You can manage your products after your store is created.</div>
        </aside>
    </div>
</div>
@endsection
