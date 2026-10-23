
@extends('layouts.app')

@section('title', 'Seller Orders | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <h1 class="text-3xl font-bold text-gray-900">
            Seller Orders
        </h1>

        <p class="mt-2 text-gray-500">
            Manage customer orders, shipping, and payments.
        </p>

    </div>

    <a href="{{ route('seller.dashboard') }}"
       class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-100">

        ← Seller Dashboard

    </a>

</div>


{{-- Success Message --}}
@if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">

        {{ session('success') }}

    </div>

@endif


{{-- Validation Errors --}}
@if ($errors->any())

    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

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


{{-- Orders Overview --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-xl font-bold text-gray-900">
                Customer Orders
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Review orders and update their fulfillment status.
            </p>

        </div>

        <div class="self-start rounded-xl bg-blue-50 px-5 py-3">

            <p class="text-sm font-medium text-blue-700">
                Total Orders
            </p>

            <p class="mt-1 text-2xl font-bold text-blue-700">
                {{ $sellerOrders->total() }}
            </p>

        </div>

    </div>

</div>


{{-- Orders List --}}
<div class="space-y-6">

    @forelse ($sellerOrders as $sellerOrder)

        @php

            $statusClass = match ($sellerOrder->status) {
                'pending' => 'bg-gray-100 text-gray-700',
                'processing' => 'bg-amber-100 text-amber-700',
                'shipped' => 'bg-blue-100 text-blue-700',
                'delivered' => 'bg-green-100 text-green-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };

            $paymentClass = match ($sellerOrder->payment_status) {
                'paid' => 'bg-green-100 text-green-700',
                'unpaid' => 'bg-amber-100 text-amber-700',
                default => 'bg-gray-100 text-gray-700',
            };

        @endphp


        <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- Order Header --}}
            <div class="border-b border-gray-200 bg-gray-50 p-5 md:p-6">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Seller Order
                        </p>

                        <h3 class="text-2xl font-bold text-gray-900">
                            #{{ $sellerOrder->id }}
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Customer Order #{{ $sellerOrder->order->id }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $sellerOrder->created_at?->format('d M Y') }}
                        </p>

                    </div>


                    {{-- Status Badge --}}
                    <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">

                        {{ ucfirst($sellerOrder->status) }}

                    </span>

                </div>

            </div>


            {{-- Order Information --}}
            <div class="grid grid-cols-1 gap-8 p-5 md:grid-cols-2 md:p-6">


                {{-- Customer Details --}}
                <section>

                    <h4 class="mb-6 text-lg font-bold text-gray-900">
                        👤 Customer Information
                    </h4>

                    <div class="space-y-5">


                        {{-- Customer Name --}}
                        <div>

                            <p class="mb-1 text-sm text-gray-500">
                                Customer Name
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $sellerOrder->order->shipping_name }}
                            </p>

                        </div>


                        {{-- Phone --}}
                        <div>

                            <p class="mb-1 text-sm text-gray-500">
                                Phone Number
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $sellerOrder->order->shipping_phone }}
                            </p>

                        </div>


                        {{-- City --}}
                        <div>

                            <p class="mb-1 text-sm text-gray-500">
                                City
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $sellerOrder->order->shipping_city }}
                            </p>

                        </div>


                        {{-- Address --}}
                        <div>

                            <p class="mb-1 text-sm text-gray-500">
                                Shipping Address
                            </p>

                            <p class="whitespace-pre-line leading-6 text-gray-900">{{ $sellerOrder->order->shipping_address }}</p>

                        </div>

                    </div>

                </section>



                {{-- Financial Details --}}
                <section>

                    <h4 class="mb-6 text-lg font-bold text-gray-900">
                        💰 Order Summary
                    </h4>

                    <div class="rounded-xl bg-gray-50 p-5">

                        {{-- Subtotal --}}
                        <div class="mb-4 flex items-center justify-between gap-3">

                            <span class="text-sm text-gray-500">
                                Subtotal
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ number_format((float) $sellerOrder->subtotal, 2) }} JOD
                            </span>

                        </div>


                        {{-- Shipping Fee --}}
                        <div class="mb-4 flex items-center justify-between gap-3">

                            <span class="text-sm text-gray-500">
                                Shipping Fee
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ number_format((float) $sellerOrder->shipping_fee, 2) }} JOD
                            </span>

                        </div>


                        {{-- Total --}}
                        <div class="flex items-center justify-between gap-3 border-t border-gray-200 pt-4">

                            <span class="font-bold text-gray-900">
                                Total
                            </span>

                            <span class="text-2xl font-bold text-blue-600">
                                {{ number_format((float) $sellerOrder->total_amount, 2) }} JOD
                            </span>

                        </div>

                    </div>


                    {{-- Payment Status --}}
                    <div class="mt-5 flex items-center justify-between gap-3 rounded-xl border border-gray-200 p-4">

                        <span class="font-medium text-gray-600">
                            Payment Status
                        </span>

                        <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $paymentClass }}">

                            {{ ucfirst($sellerOrder->payment_status) }}

                        </span>

                    </div>

                </section>

            </div>



            {{-- Order Actions --}}
            <div class="border-t border-gray-200 bg-gray-50 p-5 md:p-6">

                <h4 class="mb-5 text-lg font-bold text-gray-900">
                    Order Actions
                </h4>


                {{-- Pending: Start Processing --}}
                @if ($sellerOrder->status === 'pending')

                    <form action="{{ route('seller.orders.update-status', $sellerOrder) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 sm:w-auto">

                            Start Processing →

                        </button>

                    </form>


                {{-- Processing: Ship Order --}}
                @elseif ($sellerOrder->status === 'processing')

                    <form action="{{ route('seller.orders.ship', $sellerOrder) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700 sm:w-auto">

                            📦 Ship Order →

                        </button>

                    </form>


                {{-- Delivered & Unpaid: Confirm COD Payment --}}
                @elseif (
                    $sellerOrder->status === 'delivered'
                    && $sellerOrder->payment_status === 'unpaid'
                )

                    <form action="{{ route('seller.orders.confirm-payment', $sellerOrder) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-green-600 px-6 py-3 font-semibold text-white transition hover:bg-green-700 sm:w-auto">

                            💵 Confirm Payment

                        </button>

                    </form>


                {{-- Shipped --}}
                @elseif ($sellerOrder->status === 'shipped')

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                        <p class="font-medium text-blue-700">
                            🚚 This order has been shipped.
                        </p>

                        <p class="mt-2 text-sm text-blue-600">
                            Waiting for the customer to confirm delivery.
                        </p>

                    </div>


                {{-- Delivered & Paid --}}
                @elseif (
                    $sellerOrder->status === 'delivered'
                    && $sellerOrder->payment_status === 'paid'
                )

                    <div class="rounded-xl border border-green-200 bg-green-50 p-4">

                        <p class="font-semibold text-green-700">
                            ✅ Order Delivered & Payment Confirmed
                        </p>

                    </div>


                {{-- Cancelled --}}
                @elseif ($sellerOrder->status === 'cancelled')

                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">

                        <p class="font-medium text-red-700">
                            This order has been cancelled.
                        </p>

                    </div>


                @else

                    <p class="text-sm text-gray-500">
                        No actions available for this order.
                    </p>

                @endif

            </div>

        </article>


    @empty

        {{-- Empty State --}}
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">

            <div class="mb-5 text-6xl">
                📦
            </div>

            <h3 class="text-2xl font-bold text-gray-900">
                No Orders Yet
            </h3>

            <p class="mt-3 text-gray-500">
                Your store has not received any orders yet.
            </p>

            <a href="{{ route('seller.products.index') }}"
               class="mt-6 inline-block rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

                Manage Products

            </a>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if ($sellerOrders->hasPages())

    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">

        {{ $sellerOrders->links() }}

    </div>

@endif

@endsection
