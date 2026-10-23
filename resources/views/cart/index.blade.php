
@extends('layouts.app')

@section('title', 'Shopping Cart | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-900">
        🛒 Shopping Cart
    </h1>

    <p class="mt-2 text-gray-500">
        Review your products before checkout.
    </p>

</div>


{{-- Success Message --}}
@if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
        {{ session('success') }}
    </div>

@endif


{{-- Validation Errors --}}
@if ($errors->any())

    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">

        @foreach ($errors->all() as $error)

            <p>{{ $error }}</p>

        @endforeach

    </div>

@endif


@php
    $subtotal = '0.00';
@endphp


@if ($cart && $cart->items->isNotEmpty())

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- Cart Items --}}
        <section class="space-y-5 lg:col-span-2">

            <div class="flex items-center justify-between">

                <h2 class="text-xl font-semibold text-gray-900">
                    Your Products
                </h2>

                <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600">
                    {{ $cart->items->count() }} Products
                </span>

            </div>


            @foreach ($cart->items as $item)

                @php
                    $lineTotal = bcmul(
                        (string) $item->product->price,
                        (string) $item->quantity,
                        2
                    );

                    $subtotal = bcadd(
                        $subtotal,
                        $lineTotal,
                        2
                    );

                    $primaryImage = $item->product->images
                        ->firstWhere('is_primary', true)
                        ?? $item->product->images->first();
                @endphp


                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex flex-col gap-5 sm:flex-row">

                        {{-- Product Image --}}
                        <div class="flex h-44 w-full shrink-0 items-center justify-center rounded-xl bg-gray-50 sm:w-44">

                            @if ($primaryImage)

                                <img
                                    src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                    alt="{{ $item->product->name }}"
                                    class="h-full w-full object-contain p-3"
                                    loading="lazy"
                                >

                            @else

                                <span class="text-sm text-gray-400">
                                    No Image
                                </span>

                            @endif

                        </div>


                        {{-- Product Details --}}
                        <div class="min-w-0 flex-1">

                            <p class="mb-2 text-sm text-blue-600">
                                {{ $item->product->store->name }}
                            </p>

                            <h3 class="mb-3 text-lg font-semibold text-gray-900">
                                <a href="{{ route('products.show', $item->product) }}"
                                   class="hover:text-blue-600">

                                    {{ $item->product->name }}

                                </a>
                            </h3>


                            {{-- Unit Price --}}
                            <p class="mb-3 text-sm text-gray-500">
                                Unit Price:
                                {{ number_format((float) $item->product->price, 2) }} JOD
                            </p>


                            {{-- Stock --}}
                            <p class="mb-4 text-sm text-gray-500">
                                Available: {{ $item->product->stock_quantity }}
                            </p>


                            {{-- Quantity Update --}}
                            <form action="{{ route('cart.items.update', $item) }}"
                                  method="POST"
                                  class="mb-4 flex flex-wrap items-end gap-3">

                                @csrf
                                @method('PATCH')

                                <div>

                                    <label for="quantity-{{ $item->id }}"
                                           class="mb-2 block text-sm font-medium text-gray-700">
                                        Quantity
                                    </label>

                                    <input
                                        type="number"
                                        id="quantity-{{ $item->id }}"
                                        name="quantity"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        required
                                        class="w-24 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >

                                </div>

                                <button
                                    type="submit"
                                    class="cursor-pointer rounded-lg border border-blue-200 px-4 py-2 font-medium text-blue-600 transition hover:bg-blue-50">

                                    Update

                                </button>

                            </form>


                            {{-- Line Total --}}
                            <p class="mb-4 text-xl font-bold text-blue-600">
                                {{ $lineTotal }} JOD
                            </p>


                            {{-- Remove Item --}}
                            <form action="{{ route('cart.items.destroy', $item) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="cursor-pointer text-sm font-medium text-red-600 hover:text-red-800">

                                    Remove Item

                                </button>

                            </form>

                        </div>

                    </div>

                </article>

            @endforeach


            {{-- Continue Shopping --}}
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-3 font-medium text-gray-700 transition hover:bg-gray-100">

                ← Continue Shopping

            </a>

        </section>



        {{-- Order Summary --}}
        <aside class="lg:col-span-1">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">

                <h2 class="mb-6 text-xl font-bold text-gray-900">
                    Order Summary
                </h2>


                <div class="mb-4 flex items-center justify-between">

                    <span class="text-gray-500">
                        Subtotal
                    </span>

                    <span class="font-semibold text-gray-900">
                        {{ $subtotal }} JOD
                    </span>

                </div>


                <div class="mb-5 flex items-start justify-between gap-3">

                    <span class="text-gray-500">
                        Shipping
                    </span>

                    <span class="text-right text-sm text-gray-500">
                        Calculated at checkout
                    </span>

                </div>


                <div class="mb-6 border-t border-gray-200 pt-5">

                    <div class="flex items-center justify-between">

                        <span class="text-lg font-semibold text-gray-900">
                            Products Total
                        </span>

                        <span class="text-2xl font-bold text-blue-600">
                            {{ $subtotal }} JOD
                        </span>

                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Shipping fees are not included.
                    </p>

                </div>


                <a href="{{ route('checkout.create') }}"
                   class="block rounded-xl bg-blue-600 px-5 py-4 text-center font-semibold text-white transition hover:bg-blue-700">

                    Proceed to Checkout →

                </a>


                <p class="mt-4 text-center text-xs leading-5 text-gray-400">
                    Review your shipping details before placing your order.
                </p>

            </div>

        </aside>

    </div>


@else

    {{-- Empty Cart --}}
    <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">

        <div class="mb-5 text-6xl">
            🛒
        </div>

        <h2 class="mb-3 text-2xl font-bold text-gray-900">
            Your Cart Is Empty
        </h2>

        <p class="mb-8 text-gray-500">
            Looks like you haven't added any products yet.
        </p>

        <a href="{{ route('products.index') }}"
           class="inline-block rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

            Start Shopping

        </a>

    </div>

@endif

@endsection
