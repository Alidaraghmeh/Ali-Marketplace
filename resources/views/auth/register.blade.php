
@extends('layouts.app')

@section('title', 'Register | Ali Marketplace')

@section('content')

<div class="mx-auto max-w-5xl py-8 md:py-12">

    <div class="grid overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-xl lg:grid-cols-2">


        {{-- Register Form Section --}}
        <div class="p-7 sm:p-10 md:p-12">

            <div class="mb-9">

                <h1 class="text-3xl font-bold text-gray-900">
                    Create Account
                </h1>

                <p class="mt-3 text-gray-500">
                    Join Ali Marketplace and start shopping today.
                </p>

            </div>



            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>

            @endif


            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <p class="font-semibold text-red-700">
                        Please check the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form action="{{ route('register.store') }}"
                  method="POST"
                  class="space-y-5">

                @csrf


                {{-- Name --}}
                <div>

                    <label for="name"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        maxlength="255"
                        required
                        autofocus
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                </div>



                {{-- Email --}}
                <div>

                    <label for="email"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                </div>



                {{-- Password --}}
                <div>

                    <label for="password"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                </div>



                {{-- Confirm Password --}}
                <div>

                    <label for="password_confirmation"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Enter your password again"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                </div>



                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                    Create Account →

                </button>

            </form>



            {{-- Login Link --}}
            <div class="mt-8 border-t border-gray-200 pt-6 text-center">

                <p class="text-sm text-gray-500">

                    Already have an account?

                    <a href="{{ route('login') }}"
                       class="font-semibold text-blue-600 hover:text-blue-700">

                        Sign In

                    </a>

                </p>

            </div>


            <div class="mt-6 text-center">

                <a href="{{ route('products.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-blue-600">

                    ← Back to Shopping

                </a>

            </div>

        </div>



        {{-- Welcome Section --}}
        <div class="flex flex-col justify-center bg-gradient-to-br from-blue-700 to-indigo-950 p-8 text-white md:p-12">

            <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl">
                🎉
            </div>


            <h2 class="mb-5 text-4xl font-bold leading-tight">
                Join Our Marketplace!
            </h2>


            <p class="mb-9 max-w-sm leading-8 text-blue-100">
                Create your account and explore products
                from different stores in one place.
            </p>


            <div class="space-y-5">

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Shop from multiple sellers
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Track your purchases
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Apply to become a seller
                    </span>
                </div>

            </div>


            {{-- Seller Info --}}
            <div class="mt-10 rounded-2xl border border-white/20 bg-white/10 p-5">

                <h3 class="mb-2 text-lg font-bold">
                    🏪 Want to Become a Seller?
                </h3>

                <p class="text-sm leading-7 text-blue-100">
                    Create your account first.
                    Then you can apply to become a seller
                    and open your own store after approval.
                </p>

            </div>

        </div>


    </div>

</div>

@endsection
