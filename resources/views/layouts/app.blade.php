
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Ali Marketplace')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans text-gray-900">

  
@auth
    @php
        $currentUser = auth()->user();

        $isBuyer = $currentUser->role === 'buyer';

        $needsStore = $currentUser->role === 'seller'
            && !$currentUser->store()->exists();
    @endphp
@endauth

<header class="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">

    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex min-h-18 items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('products.index') }}"
               class="shrink-0 text-lg font-bold text-blue-600">

                🛍 Ali Marketplace

            </a>


            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-4 xl:flex">

                <a href="{{ route('products.index') }}"
                   class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Products
                </a>


                @auth

                    <a href="{{ route('orders.index') }}"
                       class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        My Orders
                    </a>

                    <a href="{{ route('cart.index') }}"
   class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">

    My Cart

    <span id="cartCount"
          class="ml-2 rounded-full bg-blue-600 px-2 py-1 text-xs text-white">
        {{ auth()->user()?->cart?->items()->sum('quantity') ?? 0 }}
    </span>

</a>


                    {{-- Buyer: Apply as Seller --}}
                    @if ($isBuyer)

                        <a href="{{ route('seller.apply.create') }}"
                           class="rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50">

                            Become a Seller

                        </a>

                    @endif


                    {{-- Approved Seller Without Store --}}
                    @if ($needsStore)

                        <a href="{{ route('seller.store.create') }}"
                           class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">

                            Create Store

                        </a>

                    @endif


                    {{-- Seller With Store --}}
                    @can('manage-store')

                        <a href="{{ route('seller.dashboard') }}"
                           class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">

                            🏪 Seller Dashboard

                        </a>

                    @endcan


                    <span class="text-sm font-medium text-gray-500">
                        {{ $currentUser->name }}
                    </span>


                    {{-- Logout --}}
                    <form action="{{ route('logout') }}"
                          method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="cursor-pointer rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">

                            Logout

                        </button>

                    </form>

                @else

                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-blue-600 px-5 py-2 font-semibold text-white hover:bg-blue-700">

                        Login

                    </a>

                @endauth

            </div>



            {{-- Mobile & Tablet Navigation --}}
            <details class="relative xl:hidden">

                <summary class="cursor-pointer list-none rounded-lg border border-gray-200 px-4 py-2 text-gray-700">

                    ☰ Menu

                </summary>

                <div class="absolute right-0 top-full mt-3 w-64 space-y-2 rounded-xl border border-gray-200 bg-white p-4 shadow-xl">

                    <a href="{{ route('products.index') }}"
                       class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">

                        Products

                    </a>


                    @auth

                        <p class="border-b border-gray-100 px-4 py-3 text-sm font-semibold text-gray-900">
                            {{ $currentUser->name }}
                        </p>


                        <a href="{{ route('orders.index') }}"
                           class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">

                            My Orders

                        </a>

                        <a href="{{ route('cart.index') }}"
                           class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">

                            My Cart

                        </a>


                        @if ($isBuyer)

                            <a href="{{ route('seller.apply.create') }}"
                               class="block rounded-lg bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700">

                                Become a Seller

                            </a>

                        @endif


                        @if ($needsStore)

                            <a href="{{ route('seller.store.create') }}"
                               class="block rounded-lg bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">

                                Create Store

                            </a>

                        @endif


                        @can('manage-store')

                            <a href="{{ route('seller.dashboard') }}"
                               class="block rounded-lg bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700">

                                🏪 Seller Dashboard

                            </a>

                        @endcan


                        {{-- Logout --}}
                        <form action="{{ route('logout') }}"
                              method="POST">

                            @csrf

                            <button
                                type="submit"
                                class="w-full cursor-pointer rounded-lg bg-red-50 px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-100">

                                Logout

                            </button>

                        </form>

                    @else

                        <a href="{{ route('login') }}"
                           class="block rounded-lg bg-blue-600 px-4 py-3 text-center font-semibold text-white">

                            Login

                        </a>
<a href="{{ route('login') }}"
   class="rounded-lg px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">
    Login
</a>

@if (Route::has('register'))
    <a href="{{ route('register') }}"
       class="rounded-lg bg-blue-600 px-5 py-2 font-semibold text-white hover:bg-blue-700">
        Create Account
    </a>
@endif
                    @endauth

                </div>

            </details>

        </div>

    </nav>

</header>



    {{-- Main Page Content --}}
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        @yield('content')

    </main>

<footer class="mt-12 bg-slate-900 text-white">

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">

            {{-- Brand --}}
            <div>
                <h2 class="text-2xl font-bold">
                    Ali <span class="text-blue-400">Marketplace</span>
                </h2>

                <p class="mt-4 text-sm leading-7 text-gray-400">
                    Your favorite place to shop from trusted stores.
                </p>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="mb-4 font-semibold">Quick Links</h3>

                <div class="flex flex-col gap-3 text-sm text-gray-400">

                    <a href="{{ route('products.index') }}"
                       class="hover:text-blue-400">
                        Products
                    </a>

                    @auth
                        <a href="{{ route('orders.index') }}"
                           class="hover:text-blue-400">
                            My Orders
                        </a>

                        <a href="{{ route('cart.index') }}"
                           class="hover:text-blue-400">
                            My Cart
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hover:text-blue-400">
                            Login
                        </a>
                    @endauth

                </div>
            </div>

            {{-- Marketplace --}}
            <div>
                <h3 class="mb-4 font-semibold">Our Marketplace</h3>

                <p class="text-sm leading-7 text-gray-400">
                    Discover products from multiple stores,
                    explore great deals, and enjoy easy shopping.
                </p>
            </div>

        </div>

        <div class="mt-10 border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
            © {{ date('Y') }} Ali Marketplace. All rights reserved.
        </div>

    </div>

</footer>

</body>
</html>
