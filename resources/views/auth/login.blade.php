
@extends('layouts.app')

@section('title', 'Login | Ali Marketplace')

@section('content')

<div class="mx-auto max-w-5xl py-8 md:py-12">

    <div class="grid overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-xl lg:grid-cols-2">

        {{-- Welcome Section --}}
        <div class="flex flex-col justify-center bg-gradient-to-br from-blue-700 to-blue-950 p-8 text-white md:p-12">

            <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl">
                🛍
            </div>

            <h1 class="mb-5 text-4xl font-bold leading-tight">
                Welcome Back!
            </h1>

            <p class="mb-10 max-w-sm leading-8 text-blue-100">
                Sign in to your Ali Marketplace account
                and continue your shopping journey.
            </p>

            <div class="space-y-5">

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Discover amazing products
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Manage and track your orders
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xl text-blue-200">✓</span>
                    <span class="text-sm text-blue-100">
                        Manage your store if you're a seller
                    </span>
                </div>

            </div>

        </div>


        {{-- Login Form Section --}}
        <div class="p-7 sm:p-10 md:p-12">

            <div class="mb-9">

                <h2 class="text-3xl font-bold text-gray-900">
                    Sign In
                </h2>

                <p class="mt-3 text-gray-500">
                    Enter your email and password to continue.
                </p>

            </div>


            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>

            @endif


            {{-- Status Message --}}
            @if (session('status'))

                <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                    {{ session('status') }}
                </div>

            @endif


            {{-- Error Message --}}
            @if (session('error'))

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>

            @endif


            <form action="{{ route('login.store') }}"
                  method="POST"
                  class="space-y-6">

                @csrf


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
                        autofocus
                        @class([
                            'w-full rounded-xl border bg-white px-4 py-3 transition focus:outline-none focus:ring-2',
                            'border-red-400 focus:ring-red-200' => $errors->has('email'),
                            'border-gray-300 focus:border-blue-500 focus:ring-blue-200' => !$errors->has('email'),
                        ])
                    >

                    @error('email')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

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
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                        @class([
                            'w-full rounded-xl border bg-white px-4 py-3 transition focus:outline-none focus:ring-2',
                            'border-red-400 focus:ring-red-200' => $errors->has('password'),
                            'border-gray-300 focus:border-blue-500 focus:ring-blue-200' => !$errors->has('password'),
                        ])
                    >

                    @error('password')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                {{-- Login Button --}}
                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-xl bg-blue-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                    Sign In →

                </button>

            </form>


            {{-- Register Link --}}
            @if (Route::has('register'))

                <div class="mt-8 border-t border-gray-200 pt-6 text-center">

                    <p class="text-sm text-gray-500">
                        Don't have an account?

                        <a href="{{ route('register') }}"
                           class="font-semibold text-blue-600 hover:text-blue-700">

                            Create Account

                        </a>
                    </p>

                </div>

            @endif


            {{-- Back To Products --}}
            <div class="mt-6 text-center">

                <a href="{{ route('products.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-blue-600">

                    ← Back to Shopping

                </a>

            </div>

        </div>

    </div>

</div>

@endsection
