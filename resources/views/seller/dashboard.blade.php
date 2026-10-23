
@extends('layouts.app')

@section('title', 'Seller Dashboard | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-900">
        Seller Dashboard
    </h1>

    <p class="mt-2 text-gray-500">
        Manage your store, products, orders, and notifications.
    </p>

</div>


{{-- Success Message --}}
@if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
        {{ session('success') }}
    </div>

@endif


{{-- Store Information --}}
<section class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 p-6 text-white shadow-lg md:p-8">

    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

            {{-- Store Logo --}}
            <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-white/20 bg-white/15">

                @if ($store->logo_path)

                    <img
                        src="{{ asset('storage/' . $store->logo_path) }}"
                        alt="{{ $store->name }} Logo"
                        class="h-full w-full bg-white object-contain p-2"
                    >

                @else

                    <span class="text-4xl">
                        🏪
                    </span>

                @endif

            </div>


            {{-- Store Details --}}
            <div>

                <p class="mb-2 text-sm font-medium text-blue-200">
                    Welcome to your store
                </p>

                <h2 class="text-3xl font-bold">
                    {{ $store->name }}
                </h2>

                @if ($store->description)

                    <p class="mt-3 max-w-xl leading-7 text-blue-100">
                        {{ $store->description }}
                    </p>

                @endif

                <p class="mt-4 text-sm text-blue-100">
                    🚚 Shipping Fee:
                    {{ number_format((float) $store->shipping_fee, 2) }} JOD
                </p>

            </div>

        </div>


        {{-- Manage Products --}}
        <a href="{{ route('seller.products.index') }}"
           class="shrink-0 rounded-xl bg-white px-6 py-3 text-center font-semibold text-blue-700 transition hover:bg-blue-50">

            Manage Products →

        </a>

    </div>

</section>


{{-- Statistics Heading --}}
<div class="mb-6">

    <h2 class="text-2xl font-bold text-gray-900">
        Store Overview
    </h2>

    <p class="mt-2 text-sm text-gray-500">
        A quick overview of your store performance.
    </p>

</div>


{{-- Statistics Cards --}}
<div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Total Products --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">

        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
            📦
        </div>

        <p class="text-sm font-medium text-gray-500">
            Total Products
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $products }}
        </p>

    </div>


    {{-- Total Orders --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">

        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-2xl">
            🛒
        </div>

        <p class="text-sm font-medium text-gray-500">
            Total Orders
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $sellerOrder }}
        </p>

    </div>


    {{-- Pending Orders --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">

        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-2xl">
            ⏳
        </div>

        <p class="text-sm font-medium text-gray-500">
            Pending Orders
        </p>

        <p class="mt-2 text-3xl font-bold text-amber-600">
            {{ $pendingOrders }}
        </p>

    </div>


    {{-- Delivered Orders --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">

        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">
            ✅
        </div>

        <p class="text-sm font-medium text-gray-500">
            Delivered Orders
        </p>

        <p class="mt-2 text-3xl font-bold text-green-600">
            {{ $deliveredOrders }}
        </p>

    </div>

</div>


{{-- Financial Overview --}}
<section class="mb-10">

    <h2 class="mb-6 text-2xl font-bold text-gray-900">
        Financial Overview
    </h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Unpaid Order Value --}}
        <div class="rounded-2xl border border-amber-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-center justify-between">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-2xl">
                    💳
                </div>

                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                    Unpaid
                </span>

            </div>

            <p class="text-sm font-medium text-gray-500">
                Unpaid Non-Cancelled Order Value
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-900">
                {{ number_format((float) $sumOrderNotCancelled, 2) }}
                <span class="text-base text-gray-500">JOD</span>
            </p>

        </div>


        {{-- Paid Order Value --}}
        <div class="rounded-2xl border border-green-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-center justify-between">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">
                    💰
                </div>

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    Paid
                </span>

            </div>

            <p class="text-sm font-medium text-gray-500">
                Paid Order Value
            </p>

            <p class="mt-3 text-3xl font-bold text-green-600">
                {{ number_format((float) $sumOrderPaid, 2) }}
                <span class="text-base text-gray-500">JOD</span>
            </p>

        </div>

    </div>

</section>


{{-- Best Sellers & Notifications --}}
<div class="mb-10 grid grid-cols-1 items-start gap-8 lg:grid-cols-2">


    {{-- Best Selling Products --}}
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6 flex items-center justify-between">

            <h2 class="text-xl font-bold text-gray-900">
                🏆 Best Selling Products
            </h2>

        </div>


        <div class="space-y-3">

            @forelse ($bestSellingProducts as $item)

                <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">

                    {{-- Rank --}}
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                        {{ $loop->iteration }}
                    </span>


                    {{-- Product --}}
                    <div class="min-w-0 flex-1">

                        <p class="truncate font-semibold text-gray-900">
                            {{ $item->product?->name ?? 'Product unavailable' }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Best Selling Product
                        </p>

                    </div>


                    <span class="text-xl text-amber-500">
                        ★
                    </span>

                </div>

            @empty

                <div class="rounded-xl bg-gray-50 p-8 text-center">

                    <p class="text-gray-500">
                        No sales data available yet.
                    </p>

                </div>

            @endforelse

        </div>

    </section>



    {{-- Notifications --}}
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6 flex items-center justify-between gap-3">

            <h2 class="text-xl font-bold text-gray-900">
                🔔 Notifications
            </h2>

            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ $notifications->count() }}
            </span>

        </div>


        <div class="space-y-4">

            @forelse ($notifications as $notification)

                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                    <p class="leading-6 text-gray-700">
                        {{ $notification->data['message'] ?? 'New notification' }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        {{ $notification->created_at?->diffForHumans() }}
                    </p>


                    <form action="{{ route('notifications.read', $notification->id) }}"
                          method="POST"
                          class="mt-4">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="cursor-pointer rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">

                            Mark as Read

                        </button>

                    </form>

                </div>

            @empty

                <div class="rounded-xl bg-gray-50 p-8 text-center">

                    <p class="text-gray-500">
                        No new notifications.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>


{{-- Quick Actions --}}
<section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <h2 class="mb-6 text-xl font-bold text-gray-900">
        Quick Actions
    </h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <a href="{{ route('seller.products.index') }}"
           class="rounded-xl border border-blue-200 bg-blue-50 p-5 text-center font-semibold text-blue-700 transition hover:bg-blue-100">

            📦 Manage Products

        </a>

        <a href="{{ route('seller.orders.index') }}"
           class="rounded-xl border border-indigo-200 bg-indigo-50 p-5 text-center font-semibold text-indigo-700 transition hover:bg-indigo-100">

            🛒 View Orders

        </a>

        <a href="{{ route('seller.returns.index') }}"
           class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-center font-semibold text-amber-700 transition hover:bg-amber-100">

            ↩️ Return Requests

        </a>

    </div>

</section>

@endsection
