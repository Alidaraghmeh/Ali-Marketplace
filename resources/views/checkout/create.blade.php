
@extends('layouts.app')

@section('title', 'Checkout | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-10">

    <a href="{{ route('cart.index') }}"
       class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">

        ← Back to Cart

    </a>

    <h1 class="text-3xl font-bold text-gray-900">
        Secure Checkout
    </h1>

    <p class="mt-2 text-gray-500">
        Complete your shipping information to place your order.
    </p>

</div>


{{-- Validation Errors --}}
@if ($errors->any())

    <div role="alert"
         class="mb-8 rounded-xl border border-red-200 bg-red-50 p-5">

        <h2 class="mb-3 font-semibold text-red-700">
            Please check the following errors:
        </h2>

        <ul class="list-inside list-disc space-y-1 text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Checkout Layout --}}
<div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">


    {{-- Shipping Form --}}
    <form action="{{ route('checkout.store') }}"
          method="POST"
          class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8 lg:col-span-2">

        @csrf

        {{-- Section Heading --}}
        <div class="mb-8 border-b border-gray-200 pb-6">

            <h2 class="text-2xl font-bold text-gray-900">
                📦 Shipping Details
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Enter the address where you want to receive your order.
            </p>

        </div>


        {{-- Name and Phone --}}
        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Full Name --}}
            <div>

                <label for="shipping_name"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Full Name
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="shipping_name"
                    name="shipping_name"
                    value="{{ old('shipping_name') }}"
                    placeholder="Enter your full name"
                    autocomplete="name"
                    maxlength="255"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >

            </div>


            {{-- Phone --}}
            <div>

                <label for="shipping_phone"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Phone Number
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="tel"
                    id="shipping_phone"
                    name="shipping_phone"
                    value="{{ old('shipping_phone') }}"
                    placeholder="0791234567"
                    autocomplete="tel"
                    maxlength="30"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >

            </div>

        </div>


        {{-- City --}}
        <div class="mb-6">

            <label for="shipping_city"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                City
                <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="shipping_city"
                name="shipping_city"
                value="{{ old('shipping_city') }}"
                placeholder="Enter your city"
                autocomplete="address-level2"
                maxlength="255"
                required
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >

        </div>


        {{-- Address --}}
        <div class="mb-8">

            <label for="shipping_address"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                Shipping Address
                <span class="text-red-500">*</span>
            </label>

            <textarea
                id="shipping_address"
                name="shipping_address"
                rows="4"
                maxlength="1000"
                placeholder="Street, building number, apartment, landmarks..."
                autocomplete="street-address"
                required
                class="w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('shipping_address') }}</textarea>

        </div>


        {{-- Payment Method --}}
        <div class="mb-8 rounded-xl border border-green-200 bg-green-50 p-5">

            <h3 class="mb-2 font-semibold text-gray-900">
                💵 Payment Method
            </h3>

            <p class="font-medium text-green-700">
                Cash on Delivery (COD)
            </p>

            <p class="mt-2 text-sm leading-6 text-gray-600">
                Pay when your order is delivered.
            </p>

        </div>


        {{-- Submit --}}
        <button
            type="submit"
            class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

            Confirm Order →

        </button>

        <p class="mt-4 text-center text-xs text-gray-500">
            Your order will be checked for product availability
            before it is placed.
        </p>

    </form>



    {{-- Order Information --}}
    <aside class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">

        <h2 class="mb-6 text-xl font-bold text-gray-900">
            Order Information
        </h2>


        {{-- Cart Link --}}
        <div class="mb-6 rounded-xl bg-blue-50 p-5">

            <h3 class="mb-2 font-semibold text-gray-900">
                Review Your Products
            </h3>

            <p class="mb-4 text-sm leading-6 text-gray-600">
                Check your selected products and quantities
                before completing checkout.
            </p>

            <a href="{{ route('cart.index') }}"
               class="inline-flex items-center gap-2 font-semibold text-blue-600 hover:text-blue-700">

                View Shopping Cart →

            </a>

        </div>


        {{-- Shipping Info --}}
        <div class="mb-5 border-b border-gray-200 pb-5">

            <h3 class="mb-2 font-semibold text-gray-900">
                🚚 Shipping Fees
            </h3>

            <p class="text-sm leading-6 text-gray-500">
                Shipping fees are calculated separately
                for each store in your order.
            </p>

        </div>


        {{-- Multi Vendor --}}
        <div class="mb-5 border-b border-gray-200 pb-5">

            <h3 class="mb-2 font-semibold text-gray-900">
                🏪 Multiple Stores
            </h3>

            <p class="text-sm leading-6 text-gray-500">
                Your order may include products from
                different sellers.
            </p>

        </div>


        {{-- Reminder --}}
        <div class="rounded-xl bg-gray-50 p-4">

            <p class="text-sm leading-6 text-gray-600">
                Please review your cart and shipping information
                carefully before confirming your purchase.
            </p>

        </div>

    </aside>

</div>

@endsection
