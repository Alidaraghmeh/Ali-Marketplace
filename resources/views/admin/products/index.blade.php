@extends('layouts.app')

@section('title', 'Product Requests | Ali Marketplace')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Admin Panel</p>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Product Requests 🛡️</h1>
        <p class="mt-2 text-gray-500">Review seller listings and manage approval decisions.</p>
    </div>
    <div class="self-start rounded-xl border border-indigo-100 bg-indigo-50 px-5 py-3 text-sm font-semibold text-indigo-700">Product Moderation</div>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
        @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
    </div>
@endif

{{-- Preserve the existing query parameter names and all_status value. --}}
<section class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="mb-6 text-xl font-bold text-gray-900">Search & Filters</h2>
    <form action="{{ route('admin.products.index') }}" method="GET">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div>
                <label for="search" class="mb-2 block text-sm font-semibold text-gray-700">Product Name</label>
                <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <div>
                <label for="min_price" class="mb-2 block text-sm font-semibold text-gray-700">Min Price (JOD)</label>
                <input id="min_price" type="number" min="0" step="0.01" name="min_price" value="{{ request('min_price') }}" placeholder="0.00" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <div>
                <label for="max_price" class="mb-2 block text-sm font-semibold text-gray-700">Max Price (JOD)</label>
                <input id="max_price" type="number" min="0" step="0.01" name="max_price" value="{{ request('max_price') }}" placeholder="1000.00" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <div>
                <label for="sort" class="mb-2 block text-sm font-semibold text-gray-700">Sort By</label>
                <select id="sort" name="sort" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">Default Sorting</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                </select>
            </div>
            <div>
                <label for="sort_status" class="mb-2 block text-sm font-semibold text-gray-700">Approval Status</label>
                <select id="sort_status" name="sort_status" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="pending" @selected(request('sort_status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('sort_status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('sort_status') === 'rejected')>Rejected</option>
                    <option value="all_status" @selected(request('sort_status') === 'all_status')>All Statuses</option>
                </select>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-gray-300 px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50">Clear Filters</a>
            <button type="submit" class="cursor-pointer rounded-xl bg-indigo-600 px-7 py-3 font-semibold text-white hover:bg-indigo-700">Apply Filters</button>
        </div>
    </form>
</section>

<div class="mb-5 flex items-center justify-between gap-3">
    <h2 class="text-2xl font-bold text-gray-900">Product Listings</h2>
    <span class="rounded-full bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">{{ $products->count() }} Shown</span>
</div>

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    @forelse ($products as $product)
        @php
            $statusClass = match ($product->status) {
                'approved' => 'bg-green-100 text-green-700',
                'pending' => 'bg-amber-100 text-amber-700',
                'rejected' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };
        @endphp
        <article class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-2xl">📦</div>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ ucfirst($product->status) }}</span>
            </div>
            <h3 class="mt-5 break-words text-xl font-bold text-gray-900">{{ $product->name }}</h3>
            <p class="mt-2 text-sm text-gray-500">🏪 {{ $product->store?->name ?? 'Store unavailable' }}</p>
            <p class="mt-4 text-2xl font-bold text-indigo-600">{{ number_format((float) $product->price, 2) }} JOD</p>
            <div class="mt-auto pt-6">
                @if ($product->status === 'pending')
                    <div class="grid gap-3 sm:grid-cols-2">
                        <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full cursor-pointer rounded-xl bg-green-600 px-4 py-3 font-semibold text-white transition hover:bg-green-700">✓ Approve</button>
                        </form>
                        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full cursor-pointer rounded-xl border border-red-200 bg-red-50 px-4 py-3 font-semibold text-red-700 transition hover:bg-red-100">✕ Reject</button>
                        </form>
                    </div>
                @else
                    <div class="rounded-xl bg-gray-50 px-4 py-3 text-center text-sm font-medium text-gray-500">Decision already recorded</div>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm md:col-span-2 xl:col-span-3">
            <p class="text-5xl">📭</p>
            <h3 class="mt-4 text-xl font-bold text-gray-900">No Products Found</h3>
            <p class="mt-2 text-gray-500">There are no products matching the selected filters.</p>
            <a href="{{ route('admin.products.index') }}" class="mt-5 inline-block font-semibold text-indigo-600 hover:underline">Reset Filters →</a>
        </div>
    @endforelse
</div>

@if (method_exists($products, 'links') && $products->hasPages())
    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">{{ $products->links() }}</div>
@endif
@endsection
