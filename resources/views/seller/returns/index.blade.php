
@extends('layouts.app')

@section('title', 'Return Requests | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <h1 class="text-3xl font-bold text-gray-900">
            Return Requests
        </h1>

        <p class="mt-2 text-gray-500">
            Review and manage customer product returns.
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


{{-- Requests Overview --}}
<section class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-xl font-bold text-gray-900">
                Customer Return Requests
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Review requests and decide whether to approve or reject them.
            </p>

        </div>

        <div class="self-start rounded-xl bg-blue-50 px-5 py-3">

            <p class="text-sm font-medium text-blue-700">
                Requests Shown
            </p>

            <p class="mt-1 text-2xl font-bold text-blue-700">
                {{ $returnRequests->count() }}
            </p>

        </div>

    </div>

</section>


{{-- Return Requests List --}}
<div class="space-y-6">

    @forelse ($returnRequests as $returnRequest)

        @php

            $statusClass = match ($returnRequest->status) {
                'pending' => 'bg-amber-100 text-amber-700',
                'approved' => 'bg-green-100 text-green-700',
                'rejected' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };

        @endphp


        <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">


            {{-- Request Header --}}
            <div class="flex flex-col gap-4 border-b border-gray-200 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between md:p-6">

                <div>

                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Return Request
                    </p>

                    <h3 class="text-xl font-bold text-gray-900">
                        #{{ $returnRequest->id }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Submitted:
                        {{ $returnRequest->created_at?->format('d M Y') ?? 'N/A' }}
                    </p>

                </div>


                {{-- Status Badge --}}
                <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">

                    {{ ucfirst($returnRequest->status) }}

                </span>

            </div>



            {{-- Request Details --}}
            <div class="p-5 md:p-6">

                <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2">


                    {{-- Customer --}}
                    <div class="flex items-center gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-600">
                            👤
                        </div>

                        <div>

                            <p class="mb-1 text-sm text-gray-500">
                                Customer
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $returnRequest->user?->name ?? 'Customer unavailable' }}
                            </p>

                        </div>

                    </div>



                    {{-- Product --}}
                    <div>

                        <p class="mb-2 text-sm text-gray-500">
                            Product
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $returnRequest->orderItem?->product_name ?? 'Product unavailable' }}
                        </p>

                    </div>



                    {{-- Quantity --}}
                    <div>

                        <p class="mb-2 text-sm text-gray-500">
                            Return Quantity
                        </p>

                        <span class="inline-flex rounded-lg bg-blue-50 px-4 py-2 font-semibold text-blue-700">
                            {{ $returnRequest->quantity }}
                            Unit(s)
                        </span>

                    </div>



                    {{-- Request Status --}}
                    <div>

                        <p class="mb-2 text-sm text-gray-500">
                            Current Status
                        </p>

                        <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                            {{ ucfirst($returnRequest->status) }}
                        </span>

                    </div>

                </div>



                {{-- Customer Return Reason --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <h4 class="mb-3 font-bold text-gray-900">
                        📝 Reason for Return
                    </h4>

                    <p class="whitespace-pre-line leading-7 text-gray-600">{{ $returnRequest->reason }}</p>

                </div>



                {{-- Seller Response (if present) --}}
                @if ($returnRequest->seller_response)

                    <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 p-5">

                        <h4 class="mb-3 font-semibold text-blue-800">
                            Seller Response
                        </h4>

                        <p class="whitespace-pre-line leading-7 text-blue-700">{{ $returnRequest->seller_response }}</p>

                    </div>

                @endif

            </div>



            {{-- Request Actions --}}
            @if ($returnRequest->status === 'pending')

                <div class="border-t border-gray-200 bg-gray-50 p-5 md:p-6">

                    <h4 class="mb-5 text-lg font-bold text-gray-900">
                        Manage Return Request
                    </h4>

                    <p class="mb-6 text-sm text-gray-500">
                        Choose whether to approve or reject this return.
                    </p>



                    {{-- Approve Return --}}
                    <form action="{{ route('seller.returns.approve', $returnRequest) }}"
                          method="POST"
                          class="mb-5">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-green-600 px-6 py-3 font-semibold text-white transition hover:bg-green-700 sm:w-auto">

                            ✓ Approve Return

                        </button>

                    </form>



                    {{-- Reject Return --}}
                    <details class="group rounded-xl border border-red-200 bg-white">

                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-4 font-semibold text-red-600 hover:bg-red-50">

                            <span>
                                ✕ Reject Return
                            </span>

                            <span class="text-xl transition group-open:rotate-180">
                                ⌄
                            </span>

                        </summary>


                        <div class="border-t border-red-100 p-5">

                            <p class="mb-5 text-sm leading-6 text-gray-500">
                                Explain why this return request is being rejected.
                            </p>


                            <form action="{{ route('seller.returns.reject', $returnRequest) }}"
                                  method="POST"
                                  class="space-y-4">

                                @csrf
                                @method('PATCH')


                                <div>

                                    <label for="seller-response-{{ $returnRequest->id }}"
                                           class="mb-2 block text-sm font-semibold text-gray-700">

                                        Rejection Reason

                                    </label>

                                    <textarea
                                        id="seller-response-{{ $returnRequest->id }}"
                                        name="seller_response"
                                        rows="4"
                                        required
                                        placeholder="Explain the reason for rejecting this return..."
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>

                                </div>


                                <button
                                    type="submit"
                                    class="w-full cursor-pointer rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-700 sm:w-auto">

                                    Confirm Rejection

                                </button>

                            </form>

                        </div>

                    </details>

                </div>


            {{-- Approved --}}
            @elseif ($returnRequest->status === 'approved')

                <div class="border-t border-green-100 bg-green-50 p-5 md:p-6">

                    <p class="font-semibold text-green-700">
                        ✓ This return request has been approved.
                    </p>

                </div>


            {{-- Rejected --}}
            @elseif ($returnRequest->status === 'rejected')

                <div class="border-t border-red-100 bg-red-50 p-5 md:p-6">

                    <p class="font-semibold text-red-700">
                        ✕ This return request has been rejected.
                    </p>

                </div>

            @endif


        </article>

    @empty

        {{-- Empty State --}}
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">

            <div class="mb-5 text-6xl">
                📦
            </div>

            <h3 class="text-2xl font-bold text-gray-900">
                No Return Requests
            </h3>

            <p class="mt-3 text-gray-500">
                Your store has not received any return requests yet.
            </p>

            <a href="{{ route('seller.dashboard') }}"
               class="mt-6 inline-block rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

                Back to Dashboard

            </a>

        </div>

    @endforelse

</div>


{{-- Optional Pagination --}}
@if (method_exists($returnRequests, 'links'))

    @if ($returnRequests->hasPages())

        <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">

            {{ $returnRequests->links() }}

        </div>

    @endif

@endif

@endsection
