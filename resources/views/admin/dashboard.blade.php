@extends('layouts.app')

@section('title', 'Admin Dashboard | Ali Marketplace')

@section('content')
<div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-900 px-6 py-8 text-white shadow-lg sm:px-8">
    <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Platform Administration</p>
    <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Admin Dashboard 👑</h1>
    <p class="mt-3 max-w-2xl leading-7 text-slate-200">Your Ali Marketplace overview: accounts, stores, products, orders and payment values.</p>
    <a href="{{ route('admin.products.index') }}" class="mt-6 inline-flex rounded-xl bg-white px-5 py-3 font-semibold text-indigo-800 transition hover:bg-indigo-50">Review Products →</a>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
@endif

<section class="mb-10">
    <div class="mb-6"><h2 class="text-2xl font-bold text-gray-900">Platform Overview</h2><p class="mt-2 text-sm text-gray-500">Current totals from your dashboard calculations.</p></div>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"><span class="text-3xl">👥</span><p class="mt-4 text-sm font-semibold text-gray-500">Total Users</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $users }}</p></div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"><span class="text-3xl">📦</span><p class="mt-4 text-sm font-semibold text-gray-500">Products</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $products }}</p></div>
        <div class="rounded-2xl border border-amber-200 bg-white p-6 shadow-sm"><span class="text-3xl">⏳</span><p class="mt-4 text-sm font-semibold text-gray-500">Pending Products</p><p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingProducts }}</p></div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"><span class="text-3xl">🏪</span><p class="mt-4 text-sm font-semibold text-gray-500">Total Stores</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $stores }}</p></div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"><span class="text-3xl">🛒</span><p class="mt-4 text-sm font-semibold text-gray-500">Total Orders</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $orders }}</p></div>
        <div class="rounded-2xl border border-green-200 bg-white p-6 shadow-sm"><span class="text-3xl">🚚</span><p class="mt-4 text-sm font-semibold text-gray-500">Delivered Orders</p><p class="mt-2 text-3xl font-bold text-green-700">{{ $deliveredOrders }}</p></div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"><span class="text-3xl">💳</span><p class="mt-4 text-sm font-semibold text-gray-500">Confirmed Payments</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $confirmedPayment }}</p></div>
        <a href="{{ route('admin.products.index') }}" class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm transition hover:bg-indigo-100"><span class="text-3xl">🛡️</span><p class="mt-4 text-sm font-semibold text-indigo-600">Quick Action</p><p class="mt-2 text-lg font-bold text-indigo-800">Review Products →</p></a>
    </div>
</section>

<section class="mb-10">
    <h2 class="mb-6 text-2xl font-bold text-gray-900">Financial Overview</h2>
    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-amber-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-3xl">💸</span><span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">Unpaid</span></div>
            <p class="mt-5 text-sm font-semibold text-gray-500">Unpaid Orders Value</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format((float) $UnpaidOrdersValue, 2) }} <span class="text-base text-gray-400">JOD</span></p>
        </div>
        <div class="rounded-2xl border border-indigo-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-3xl">📊</span><span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">Order Value</span></div>
            <p class="mt-5 text-sm font-semibold text-gray-500">Total Order Value</p>
            <p class="mt-3 text-3xl font-bold text-indigo-700">{{ number_format((float) $TotalOrderValue, 2) }} <span class="text-base text-gray-400">JOD</span></p>
        </div>
    </div>
    <p class="mt-3 text-xs text-gray-500">Order values are not the same as net platform profit.</p>
</section>

<div class="grid items-start gap-7 lg:grid-cols-2">
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-xl font-bold text-gray-900">🏆 Best Selling Stores</h2>
        <div class="space-y-3">
            @forelse ($bestSellingStores as $bestSellingStore)
                <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700">{{ $loop->iteration }}</div>
                    <div class="min-w-0 flex-1"><p class="font-semibold text-gray-900">{{ $bestSellingStore?->store?->name ?? 'Store unavailable' }}</p><p class="mt-1 text-xs text-gray-500">Paid Order Value</p></div>
                    <p class="text-right font-bold text-indigo-700">{{ number_format((float) $bestSellingStore->total_paid, 2) }} <span class="text-xs">JOD</span></p>
                </div>
            @empty
                <p class="rounded-xl bg-gray-50 p-8 text-center text-sm text-gray-500">No store sales data yet.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-xl font-bold text-gray-900">🔥 Best Selling Products</h2>
        <div class="space-y-3">
            @forelse ($bestSellingProducts as $item)
                <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-700">{{ $loop->iteration }}</div>
                    <div class="min-w-0 flex-1"><p class="font-semibold text-gray-900">{{ $item->product?->name ?? 'Product unavailable' }}</p><p class="mt-1 text-xs text-gray-500">Units Sold</p></div>
                    <p class="font-bold text-gray-900">{{ $item->total_sold }}</p>
                </div>
            @empty
                <p class="rounded-xl bg-gray-50 p-8 text-center text-sm text-gray-500">No product sales data yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
