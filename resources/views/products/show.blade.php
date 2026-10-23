
@extends('layouts.app')

@section('title', $product->name . ' | Ali Marketplace')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-8 flex items-center gap-2 text-sm text-gray-500">

    <a href="{{ route('products.index') }}"
       class="hover:text-blue-600">
        Products
    </a>

    <span>/</span>

    <span class="font-medium text-gray-900">
        {{ $product->name }}
    </span>

</div>


{{-- Validation Messages --}}
@if ($errors->any())

    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach

    </div>

@endif


{{-- Product Main Section --}}
<div class="grid grid-cols-1 gap-10 lg:grid-cols-2">

    {{-- Product Images --}}
    <div>

        @php
            $primaryImage = $product->images
                ->firstWhere('is_primary', true)
                ?? $product->images->first();
        @endphp

        <div class="flex min-h-80 items-center justify-center rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            @if ($primaryImage)

                <img
                    src="{{ asset('storage/' . $primaryImage->image_path) }}"
                    alt="{{ $product->name }}"
                    class="h-80 w-full object-contain"
                >

            @else

                <div class="text-center text-gray-400">
                    No Image Available
                </div>

            @endif

        </div>


        {{-- Additional Images --}}
        @if ($product->images->count() > 1)

            <div class="mt-4 grid grid-cols-4 gap-3">

                @foreach ($product->images as $image)

                    <a href="{{ asset('storage/' . $image->image_path) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="rounded-xl border border-gray-200 bg-white p-2 transition hover:border-blue-500">

                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-20 w-full object-contain"
                            loading="lazy"
                        >

                    </a>

                @endforeach

            </div>

        @endif

    </div>


    {{-- Product Information --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        {{-- Store --}}
        <p class="mb-3 text-sm font-medium text-blue-600">
            {{ $product->store->name }}
        </p>


        {{-- Product Name --}}
        <h1 class="mb-4 text-3xl font-bold text-gray-900">
            {{ $product->name }}
        </h1>


        {{-- Price --}}
        <p class="mb-5 text-3xl font-bold text-blue-600">
            {{ number_format((float) $product->price, 2) }} JOD
        </p>


        {{-- Stock --}}
        @if ($product->stock_quantity > 0)

            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-green-50 px-4 py-2 text-sm font-medium text-green-700">

                <span>●</span>

                In Stock ({{ $product->stock_quantity }} available)

            </div>

        @else

            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-medium text-red-700">

                <span>●</span>

                Out of Stock

            </div>

        @endif


        {{-- Short Description --}}
        @if ($product->description)

            <p class="mb-6 leading-7 text-gray-600">
                {{ $product->description }}
            </p>

        @endif


        <div class="my-6 border-t border-gray-200"></div>


        {{-- Add To Cart --}}
        <form action="{{ route('cart.items.store') }}"
              method="POST">

            @csrf

            <input
                type="hidden"
                name="product_id"
                value="{{ $product->id }}"
            >

            <div class="mb-5">

                <label for="quantity"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Quantity
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="1"
                    min="1"
                    max="{{ $product->stock_quantity }}"
                    @disabled($product->stock_quantity <= 0)
                    class="w-28 rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >

            </div>


            <button
                type="submit"
                @disabled($product->stock_quantity <= 0)
                class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-400">

                Add to Cart

            </button>

        </form>


        {{-- Back Button --}}
        <a href="{{ route('products.index') }}"
           class="mt-4 block rounded-xl border border-gray-300 px-6 py-3 text-center font-medium text-gray-700 transition hover:bg-gray-100">

            Continue Shopping

        </a>

    </div>

</div>


{{-- Description Section --}}
<section class="mt-12 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">

    <h2 class="mb-5 text-2xl font-bold text-gray-900">
        Product Description
    </h2>

    @if ($product->description)

        <p class="whitespace-pre-line leading-8 text-gray-600">{{ $product->description }}</p>

    @else

        <p class="text-gray-500">
            No description available for this product.
        </p>

    @endif

</section>

{{-- Customer Reviews --}}
<section class="mt-12 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

    {{-- Reviews Header --}}
    <div class="mb-8 flex flex-col gap-4 border-b border-gray-200 pb-6 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Customer Reviews
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                See what customers think about this product.
            </p>
        </div>

        {{-- Average Rating --}}
        <div class="rounded-xl bg-amber-50 px-6 py-4 text-center">

            @if ($product->reviews_avg_rating !== null)

                <div class="text-3xl font-bold text-gray-900">
                    {{ number_format((float) $product->reviews_avg_rating, 1) }}
                    <span class="text-amber-500">★</span>
                </div>

            @else

                <p class="font-semibold text-gray-600">
                    No ratings yet
                </p>

            @endif

            <p class="mt-1 text-sm text-gray-500">
                {{ $product->reviews->count() }} Reviews
            </p>

        </div>

    </div>


    {{-- Success Message --}}
    @if (session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Review Error --}}
    @error('review')
        <p class="mb-5 rounded-lg bg-red-50 p-3 text-red-600">
            {{ $message }}
        </p>
    @enderror


    {{-- Reviews List --}}
    <div class="space-y-6">

        @forelse ($product->reviews as $review)

            <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                {{-- Customer --}}
                <div class="mb-4 flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $review->user->name }}
                        </p>

                        <div class="text-xl tracking-wide text-amber-400">

                            @for ($i = 1; $i <= 5; $i++)

                                @if ($i <= $review->rating)
                                    ★
                                @else
                                    <span class="text-gray-300">☆</span>
                                @endif

                            @endfor

                        </div>
                    </div>

                </div>


                {{-- Comment --}}
                @if ($review->comment)

                    <p class="whitespace-pre-line leading-7 text-gray-600">{{ $review->comment }}</p>

                @endif


                {{-- Edit Own Review --}}
                @if (Auth::id() === $review->user_id)

                    <div class="mt-5 border-t border-gray-200 pt-5">

                        <h3 class="mb-4 font-semibold text-gray-900">
                            Edit Your Review
                        </h3>

                        <form action="{{ route('reviews.update', $review) }}"
                              method="POST"
                              class="space-y-4">

                            @csrf
                            @method('PATCH')

                            {{-- Rating --}}
                            <div>

                                <label for="edit-rating-{{ $review->id }}"
                                       class="mb-2 block text-sm font-medium text-gray-700">
                                    Rating
                                </label>

                                <select
                                    id="edit-rating-{{ $review->id }}"
                                    name="rating"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                    @for ($i = 1; $i <= 5; $i++)

                                        <option value="{{ $i }}"
                                            @selected(old('rating', $review->rating) == $i)>
                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                        </option>

                                    @endfor

                                </select>

                            </div>


                            {{-- Comment --}}
                            <div>

                                <label for="edit-comment-{{ $review->id }}"
                                       class="mb-2 block text-sm font-medium text-gray-700">
                                    Comment
                                </label>

                                <textarea
                                    id="edit-comment-{{ $review->id }}"
                                    name="comment"
                                    rows="3"
                                    maxlength="1000"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comment', $review->comment) }}</textarea>

                            </div>


                            <button
                                type="submit"
                                class="cursor-pointer rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
                                Update Review
                            </button>

                        </form>

                    </div>

                @endif

            </div>

        @empty

            <div class="rounded-xl bg-gray-50 p-10 text-center">

                <p class="text-lg font-semibold text-gray-700">
                    No Reviews Yet
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Be the first customer to review this product.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Add Review --}}
    @auth

        @if ($canReview && !$alreadyReviewed)

            <div class="mt-10 rounded-xl border border-blue-100 bg-blue-50 p-6">

                <h3 class="mb-2 text-xl font-bold text-gray-900">
                    Write a Review
                </h3>

                <p class="mb-6 text-sm text-gray-500">
                    Share your experience with this product.
                </p>


                <form action="{{ route('reviews.store', $product) }}"
                      method="POST"
                      class="space-y-5">

                    @csrf

                    {{-- Rating --}}
                    <fieldset>

                        <legend class="mb-3 text-sm font-semibold text-gray-700">
                            Your Rating
                        </legend>

                        <div class="flex flex-wrap gap-2">

                            @for ($i = 1; $i <= 5; $i++)

                                <label class="cursor-pointer">

                                    <input
                                        type="radio"
                                        name="rating"
                                        value="{{ $i }}"
                                        @checked(old('rating') == $i)
                                        required
                                        class="peer sr-only"
                                    >

                                    <span class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-lg text-gray-500 transition hover:border-amber-400 hover:text-amber-500 peer-checked:border-amber-500 peer-checked:bg-amber-100 peer-checked:text-amber-600 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500">

                                        ★ {{ $i }}

                                    </span>

                                </label>

                            @endfor

                        </div>

                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </fieldset>


                    {{-- Comment --}}
                    <div>

                        <label for="review-comment"
                               class="mb-2 block text-sm font-semibold text-gray-700">
                            Your Comment
                        </label>

                        <textarea
                            id="review-comment"
                            name="comment"
                            rows="4"
                            maxlength="1000"
                            placeholder="Tell us what you think about this product..."
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comment') }}</textarea>

                        @error('comment')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700">

                        Submit Review

                    </button>

                </form>

            </div>

        @endif

    @endauth

</section>

@endsection
