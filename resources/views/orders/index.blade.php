
@extends('layouts.app')

@section('title', 'My Orders | Ali Marketplace')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            My Orders
        </h1>

        <p class="mt-2 text-gray-500">
            Track your purchases, deliveries, and returns.
        </p>
    </div>

    <a href="{{ route('products.index') }}"
       class="rounded-xl bg-blue-600 px-6 py-3 text-center font-semibold text-white transition hover:bg-blue-700">
        Continue Shopping
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
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach

    </div>
@endif


{{-- Notifications --}}
@if ($notifications->isNotEmpty())

    <section class="mb-10 rounded-2xl border border-blue-100 bg-blue-50 p-6">

        <div class="mb-5 flex items-center justify-between">

            <h2 class="text-xl font-bold text-gray-900">
                🔔 Notifications
            </h2>

            <span class="rounded-full bg-blue-600 px-3 py-1 text-sm text-white">
                {{ $notifications->count() }} Unread
            </span>

        </div>

        <div class="space-y-3">

            @foreach ($notifications as $notification)

                <div class="flex flex-col gap-4 rounded-xl bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $notification->data['message'] ?? 'You have a new notification.' }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $notification->created_at?->diffForHumans() }}
                        </p>
                    </div>

                    <form action="{{ route('notifications.read', $notification) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="cursor-pointer rounded-lg border border-blue-200 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100">
                            Mark as Read
                        </button>

                    </form>

                </div>

            @endforeach

        </div>

    </section>

@endif


{{-- Orders Header --}}
<div class="mb-6 flex items-center justify-between">

    <h2 class="text-2xl font-bold text-gray-900">
        Order History
    </h2>

    <span class="text-sm text-gray-500">
        {{ $orders->total() }} Orders
    </span>

</div>


{{-- Orders List --}}
<div class="space-y-8">

    @forelse ($orders as $order)

        @php
            $overallStatus = $order->getOverallStatus();

            $overallStatusClass = match ($overallStatus) {
                'delivered' => 'bg-green-100 text-green-700',
                'shipped' => 'bg-blue-100 text-blue-700',
                'processing' => 'bg-amber-100 text-amber-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };
        @endphp


        <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- Main Order Header --}}
            <div class="border-b border-gray-200 bg-gray-50 p-6">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Order #{{ $order->id }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Placed on {{ $order->created_at?->format('d M Y') }}
                        </p>
                    </div>

                    <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $overallStatusClass }}">
                        {{ ucwords(str_replace('_', ' ', $overallStatus)) }}
                    </span>

                </div>


                <div class="mt-5 flex items-center justify-between border-t border-gray-200 pt-5">

                    <span class="font-medium text-gray-600">
                        Order Total
                    </span>

                    <span class="text-2xl font-bold text-blue-600">
                        {{ number_format((float) $order->total_amount, 2) }} JOD
                    </span>

                </div>

            </div>


            {{-- Seller Orders --}}
            <div class="space-y-6 p-5 md:p-6">

                @foreach ($order->sellerOrders as $sellerOrder)

                    @php
                        $sellerStatusClass = match ($sellerOrder->status) {
                            'delivered' => 'bg-green-100 text-green-700',
                            'shipped' => 'bg-blue-100 text-blue-700',
                            'processing' => 'bg-amber-100 text-amber-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp


                    <section class="overflow-hidden rounded-xl border border-gray-200">

                        {{-- Store Header --}}
                        <div class="flex flex-col gap-3 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between">

                            <div>
                                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Seller
                                </p>

                                <h4 class="text-lg font-bold text-gray-900">
                                    🏪 {{ $sellerOrder->store->name }}
                                </h4>
                            </div>

                            <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $sellerStatusClass }}">
                                {{ ucfirst($sellerOrder->status) }}
                            </span>

                        </div>


                        {{-- Seller Order Items --}}
                        <div class="space-y-5 p-5">

                            @foreach ($sellerOrder->items as $item)

                                <div class="border-b border-gray-100 pb-5 last:border-b-0 last:pb-0">

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                        <div>
                                            <h5 class="font-semibold text-gray-900">
                                                {{ $item->product_name }}
                                            </h5>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Quantity: {{ $item->quantity }}
                                            </p>
                                        </div>

                                        <div class="sm:text-right">
                                            <p class="font-bold text-blue-600">
                                                {{ number_format((float) $item->unit_price, 2) }} JOD
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                Unit price
                                            </p>
                                        </div>

                                    </div>


                                    {{-- Return Request --}}
                                    @if ($sellerOrder->status === 'delivered')

                                        @if (!$item->returns)

                                            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">

                                                <h6 class="mb-4 font-semibold text-gray-900">
                                                    Request a Return
                                                </h6>

                                                <form action="{{ route('returns.store', $item) }}"
                                                      method="POST"
                                                      class="space-y-4">

                                                    @csrf

                                                    <div>
                                                        <label for="return-quantity-{{ $item->id }}"
                                                               class="mb-2 block text-sm font-medium text-gray-700">
                                                            Return Quantity
                                                        </label>

                                                        <input
                                                            type="number"
                                                            id="return-quantity-{{ $item->id }}"
                                                            name="quantity"
                                                            min="1"
                                                            max="{{ $item->quantity }}"
                                                            required
                                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:w-40"
                                                        >
                                                    </div>

                                                    <div>
                                                        <label for="return-reason-{{ $item->id }}"
                                                               class="mb-2 block text-sm font-medium text-gray-700">
                                                            Reason
                                                        </label>

                                                        <textarea
                                                            id="return-reason-{{ $item->id }}"
                                                            name="reason"
                                                            rows="3"
                                                            maxlength="1000"
                                                            required
                                                            placeholder="Why would you like to return this product?"
                                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                                    </div>

                                                    <button type="submit"
                                                            class="cursor-pointer rounded-lg bg-amber-500 px-6 py-3 font-semibold text-white transition hover:bg-amber-600">
                                                        Request Return
                                                    </button>

                                                </form>

                                            </div>

                                        @else

                                            <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                <p class="text-sm font-semibold text-gray-700">
                                                    Return Status:
                                                    {{ ucfirst($item->returns->status) }}
                                                </p>

                                                @if ($item->returns->seller_response)
                                                    <p class="mt-2 text-sm text-gray-600">
                                                        Seller Response:
                                                        {{ $item->returns->seller_response }}
                                                    </p>
                                                @endif

                                            </div>

                                        @endif

                                    @endif

                                </div>

                            @endforeach

                        </div>


                        {{-- Financial Summary --}}
                        <div class="space-y-3 border-t border-gray-200 bg-gray-50 p-5">

                            <div class="flex justify-between gap-3 text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-medium">
                                    {{ number_format((float) $sellerOrder->subtotal, 2) }} JOD
                                </span>
                            </div>

                            <div class="flex justify-between gap-3 text-sm">
                                <span class="text-gray-500">Shipping Fee</span>
                                <span class="font-medium">
                                    {{ number_format((float) $sellerOrder->shipping_fee, 2) }} JOD
                                </span>
                            </div>

                            <div class="flex justify-between gap-3 border-t border-gray-200 pt-3">
                                <span class="font-semibold text-gray-900">
                                    Seller Total
                                </span>

                                <span class="font-bold text-blue-600">
                                    {{ number_format((float) $sellerOrder->total_amount, 2) }} JOD
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 pt-2">

                                <span class="text-sm text-gray-500">
                                    Payment Status
                                </span>

                                @if ($sellerOrder->payment_status === 'paid')

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                                        Paid
                                    </span>

                                @else

                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                                        {{ ucfirst($sellerOrder->payment_status) }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Order Actions --}}
                        @if (in_array($sellerOrder->status, ['shipped', 'pending'], true))

                            <div class="flex flex-wrap gap-3 border-t border-gray-200 p-5">

                                {{-- Confirm Delivery --}}
                                @if ($sellerOrder->status === 'shipped')

                                    <form action="{{ route('orders.delivered', $sellerOrder) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="cursor-pointer rounded-lg bg-green-600 px-6 py-3 font-semibold text-white transition hover:bg-green-700">
                                            Confirm Delivery
                                        </button>

                                    </form>

                                @endif


                                {{-- Cancel Order --}}
                                @if ($sellerOrder->status === 'pending')

                                    <form action="{{ route('orders.cancelled', $sellerOrder) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="cursor-pointer rounded-lg border border-red-200 bg-red-50 px-6 py-3 font-semibold text-red-600 transition hover:bg-red-100">
                                            Cancel Order
                                        </button>

                                    </form>

                                @endif

                            </div>

                        @endif

                    </section>

                @endforeach

            </div>

        </article>


    @empty

        {{-- Empty Orders --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm">

            <div class="mb-5 text-6xl">
                📦
            </div>

            <h3 class="text-2xl font-bold text-gray-900">
                No Orders Yet
            </h3>

            <p class="mt-3 text-gray-500">
                You haven't placed any orders yet.
            </p>

            <a href="{{ route('products.index') }}"
               class="mt-6 inline-block rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white hover:bg-blue-700">
                Explore Products
            </a>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if ($orders->hasPages())

    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">
        {{ $orders->links() }}
    </div>

@endif

@endsection
