
@extends('layouts.app')

@section('title', 'Products | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Explore Products
        </h1>

        <p class="mt-2 text-gray-500">
            Discover amazing products from our stores.
        </p>
    </div>

    @auth
        <a href="{{ route('orders.index') }}"
           class="rounded-lg border border-blue-200 bg-white px-5 py-3 text-center font-medium text-blue-600 hover:bg-blue-50">
            My Orders
        </a>
    @endauth

</div>


{{-- Search & Filters --}}
<form action="{{ route('products.index') }}"
      method="GET"
      class="mb-10 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <h2 class="mb-6 text-xl font-semibold text-gray-900">
        Search & Filters
    </h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- Search --}}
        <div>
            <label for="search"
                   class="mb-2 block text-sm font-medium text-gray-700">
                Search Products
            </label>

            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search products..."
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
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
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
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
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
        </div>

        {{-- Sorting --}}
        <div>
            <label for="sort"
                   class="mb-2 block text-sm font-medium text-gray-700">
                Sort By
            </label>

            <select
                id="sort"
                name="sort"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                <option value="">Default Sorting</option>

                <option value="price_asc"
                    @selected(request('sort') === 'price_asc')>
                    Price: Low to High
                </option>

                <option value="price_desc"
                    @selected(request('sort') === 'price_desc')>
                    Price: High to Low
                </option>

                <option value="rating_desc"
                    @selected(request('sort') === 'rating_desc')>
                    Highest Rated
                </option>

            </select>
        </div>

    </div>

    {{-- Filter Buttons --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">

        <a href="{{ route('products.index') }}"
           class="rounded-lg border border-gray-300 px-6 py-3 text-center font-semibold text-gray-700 hover:bg-gray-100">
            Clear Filters
        </a>

        <button
            type="submit"
            class="cursor-pointer rounded-lg bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">
            Search Products
        </button>

    </div>

</form>


{{-- Products Section --}}
<section>

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">
            Available Products
        </h2>

        <span class="text-sm text-gray-500">
            {{ $products->total() }} products found
        </span>
    </div>


    {{-- Products Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($products as $product)

            <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                {{-- Product Image --}}
                <div class="bg-gray-50">

                    @php
                        $primaryImage = $product->images
                            ->firstWhere('is_primary', true)
                            ?? $product->images->first();
                    @endphp

                    @if ($primaryImage)

                        <img
                            src="{{ asset('storage/' . $primaryImage->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-52 w-full object-contain p-5"
                            loading="lazy"
                        >

                    @else

                        <div class="flex h-52 items-center justify-center text-gray-400">
                            No Image Available
                        </div>

                    @endif

                </div>


                {{-- Product Information --}}
                <div class="flex flex-1 flex-col p-5">

                    {{-- Store --}}
                    <p class="mb-2 text-sm text-gray-500">
                        {{ $product->store->name }}
                    </p>

                    {{-- Name --}}
                    <h3 class="mb-3 text-lg font-semibold text-gray-900">
                        <a href="{{ route('products.show', $product) }}"
                           class="hover:text-blue-600">
                            {{ $product->name }}
                        </a>
                    </h3>

                    {{-- Description --}}
                    @if ($product->description)
                        <p class="mb-4 line-clamp-2 text-sm text-gray-500">
                            {{ $product->description }}
                        </p>
                    @endif

                    {{-- Price --}}
                    <p class="mb-3 text-2xl font-bold text-blue-600">
                        {{ number_format((float) $product->price, 2) }} JOD
                    </p>

                    {{-- Stock Status --}}
                    @if ($product->stock_quantity > 0)

                        <span class="mb-4 text-sm font-medium text-green-600">
                            ● In Stock ({{ $product->stock_quantity }})
                        </span>

                    @else

                        <span class="mb-4 text-sm font-medium text-red-600">
                            ● Out of Stock
                        </span>

                    @endif


                    {{-- Product Actions --}}
                    <div class="mt-auto flex flex-col gap-3 pt-4">

                        <a href="{{ route('products.show', $product) }}"
                           class="rounded-lg border border-gray-300 px-4 py-3 text-center font-medium text-gray-700 hover:bg-gray-100">
                            View Details
                        </a>

                        <form action="{{ route('cart.items.store') }}"
                              method="POST">

                            @csrf

                            <input
                                type="hidden"
                                name="product_id"
                                value="{{ $product->id }}"
                            >

                            <input
                                type="hidden"
                                name="quantity"
                                value="1"
                            >

                            <button
                                type="submit"
                                @disabled($product->stock_quantity <= 0)
                                class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-400">
                                Add to Cart
                            </button>

                        </form>

                    </div>

                </div>

            </article>

        @empty

            <div class="col-span-full rounded-2xl border border-gray-200 bg-white p-12 text-center">

                <h3 class="text-xl font-semibold text-gray-700">
                    No Products Found
                </h3>

                <p class="mt-2 text-gray-500">
                    Try changing your search or filters.
                </p>

                <a href="{{ route('products.index') }}"
                   class="mt-5 inline-block rounded-lg bg-blue-600 px-6 py-3 font-medium text-white hover:bg-blue-700">
                    View All Products
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

</section>

@endsection
