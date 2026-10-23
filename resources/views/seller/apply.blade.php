@extends('layouts.app')

@section('title', 'Become a Seller | Ali Marketplace')

@section('content')
<div class="mx-auto max-w-3xl py-3 sm:py-8">
    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">← Explore Products</a>

    <div class="mt-7 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-7 py-10 text-white sm:px-10">
            <div class="mb-5 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-4xl">🏪</div>
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-200">Sell on Ali Marketplace</p>
            <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Become a Seller</h1>
            <p class="mt-4 max-w-xl leading-7 text-blue-100">Apply for a seller account and take the first step toward opening your own store.</p>
        </div>

        <div class="p-7 sm:p-10">
            @if (session('success'))
                <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <h2 class="text-xl font-bold text-gray-900">Your Selling Journey</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4"><span class="text-2xl">📝</span><h3 class="mt-3 font-semibold text-gray-900">1. Apply</h3><p class="mt-1 text-sm text-gray-500">Submit a request to become a seller.</p></div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4"><span class="text-2xl">✅</span><h3 class="mt-3 font-semibold text-gray-900">2. Review</h3><p class="mt-1 text-sm text-gray-500">An administrator reviews your request.</p></div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4"><span class="text-2xl">📦</span><h3 class="mt-3 font-semibold text-gray-900">3. Sell</h3><p class="mt-1 text-sm text-gray-500">Once eligible, set up a store and list products.</p></div>
            </div>

            <div class="mt-8 rounded-xl border border-blue-100 bg-blue-50 p-5">
                <p class="text-sm leading-6 text-blue-900">Applying does not instantly approve your account. Your request follows the existing admin review process.</p>
            </div>

            <form action="{{ route('seller.apply.store') }}" method="POST" class="mt-7">
                @csrf
                <button type="submit" class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 text-lg font-bold text-white transition hover:bg-blue-700">Apply to Become a Seller →</button>
            </form>
            <p class="mt-4 text-center text-xs text-gray-500">Already applied? The backend will determine whether another request is allowed.</p>
        </div>
    </div>
</div>
@endsection
