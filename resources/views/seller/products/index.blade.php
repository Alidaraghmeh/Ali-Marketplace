
@extends('layouts.app')

@section('title', 'My Products | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            My Products
        </h1>

        <p class="mt-2 text-gray-500">
            Manage your products, prices, stock, and approval status.
        </p>
    </div>

    <a href="{{ route('seller.products.create') }}"
       class="rounded-xl bg-blue-600 px-6 py-3 text-center font-semibold text-white transition hover:bg-blue-700">

        + Add Product

    </a>

</div>


{{-- Success Message --}}
@if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
        {{ session('success') }}
    </div>

@endif


{{-- Search & Filters --}}
<section class="mb-10 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <h2 class="mb-6 text-xl font-bold text-gray-900">
        Search & Filters
    </h2>

    <form action="{{ route('seller.products.index') }}"
          method="GET">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            {{-- Search --}}
            <div>

                <label for="search"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Product Name
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>


            {{-- Min Price --}}
            <div>

                <label for="min_price"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Min Price
                </label>

                <input
                    type="number"
                    id="min_price"
                    name="min_price"
                    min="0"
                    step="0.01"
                    value="{{ request('min_price') }}"
                    placeholder="0.00"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>


            {{-- Max Price --}}
            <div>

                <label for="max_price"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Max Price
                </label>

                <input
                    type="number"
                    id="max_price"
                    name="max_price"
                    min="0"
                    step="0.01"
                    value="{{ request('max_price') }}"
                    placeholder="1000.00"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>


            {{-- Sort --}}
            <div>

                <label for="sort"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Sort By
                </label>

                <select
                    id="sort"
                    name="sort"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">Default Sorting</option>

                    <option value="price_asc"
                        @selected(request('sort') === 'price_asc')>
                        Price: Low to High
                    </option>

                    <option value="price_desc"
                        @selected(request('sort') === 'price_desc')>
                        Price: High to Low
                    </option>

                </select>

            </div>


            {{-- Product Status --}}
            <div>

                <label for="sort_status"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Product Status
                </label>

                <select
                    id="sort_status"
                    name="sort_status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">All Statuses</option>

                    <option value="pending"
                        @selected(request('sort_status') === 'pending')>
                        Pending
                    </option>

                    <option value="approved"
                        @selected(request('sort_status') === 'approved')>
                        Approved
                    </option>

                    <option value="rejected"
                        @selected(request('sort_status') === 'rejected')>
                        Rejected
                    </option>

                </select>

            </div>

        </div>


        {{-- Filter Actions --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">

            <a href="{{ route('seller.products.index') }}"
               class="rounded-lg border border-gray-300 px-6 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-100">
                Clear Filters
            </a>

            <button
                type="submit"
                class="cursor-pointer rounded-lg bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

                Apply Filters

            </button>

        </div>

    </form>

</section>


{{-- Products Heading --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <h2 class="text-2xl font-bold text-gray-900">
            Your Inventory
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            View and manage products in your store.
        </p>

    </div>

    <span class="self-start rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
        {{ $products->total() }} Products
    </span>

</div>


{{-- Product Grid --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

    @forelse ($products as $product)

        @php

            $statusClass = match ($product->status) {
                'approved' => 'bg-green-100 text-green-700',
                'pending' => 'bg-amber-100 text-amber-700',
                'rejected' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };

            $primaryImage = $product->images
                ->firstWhere('is_primary', true)
                ?? $product->images->first();

        @endphp


        <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

            {{-- Product Image --}}
            <div class="relative flex h-52 items-center justify-center bg-gray-50">

                @if ($primaryImage)

                    <img
                        src="{{ asset('storage/' . $primaryImage->image_path) }}"
                        alt="{{ $product->name }}"
                        class="h-full w-full object-contain p-5"
                        loading="lazy"
                    >

                @else

                    <span class="text-sm text-gray-400">
                        No Image Available
                    </span>

                @endif


                {{-- Approval Status --}}
                <span class="absolute right-4 top-4 rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                    {{ ucfirst($product->status) }}
                </span>

            </div>


            {{-- Product Details --}}
            <div class="flex flex-1 flex-col p-5">

                {{-- Product Name --}}
                <h3 class="mb-3 text-xl font-bold text-gray-900">
                    {{ $product->name }}
                </h3>


                {{-- Price --}}
                <p class="mb-4 text-2xl font-bold text-blue-600">
                    {{ number_format((float) $product->price, 2) }} JOD
                </p>


                {{-- Stock --}}
                <div class="mb-5">

                    @if ($product->stock_quantity > 0)

                        <span class="inline-flex rounded-lg bg-green-50 px-3 py-2 text-sm font-medium text-green-700">
                            In Stock: {{ $product->stock_quantity }}
                        </span>

                    @else

                        <span class="inline-flex rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700">
                            Out of Stock
                        </span>

                    @endif

                </div>


                {{-- Product Actions --}}
                <div class="mt-auto border-t border-gray-100 pt-5">

                    <a href="{{ route('seller.products.edit', $product->id) }}"
                       class="block rounded-xl bg-blue-600 px-5 py-3 text-center font-semibold text-white transition hover:bg-blue-700">

                        Edit Product

                    </a>

                </div>

            </div>

        </article>

    @empty

        {{-- Empty Products --}}
        <div class="col-span-full rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">

            <div class="mb-5 text-5xl">
                📦
            </div>

            <h3 class="text-2xl font-bold text-gray-900">
                No Products Found
            </h3>

            <p class="mt-3 text-gray-500">
                You don't have products matching your current filters.
            </p>

            <a href="{{ route('seller.products.create') }}"
               class="mt-6 inline-block rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

                Add Product

            </a>

            <a href="{{ route('seller.products.index') }}"
               class="mt-4 block text-sm font-medium text-blue-600 hover:underline">

                Clear Filters

            </a>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if ($products->hasPages())

    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">
        {{ $products->links() }}
    </div>

@endif


{{-- Back to Orders --}}
<div class="mt-10">

    <a href="{{ route('seller.orders.index') }}"
       class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-100">

        ← View My Orders

    </a>

</div>

@endsection
