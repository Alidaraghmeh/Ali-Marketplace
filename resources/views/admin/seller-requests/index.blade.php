@extends('layouts.app')

@section('title', 'Seller Requests | Ali Marketplace')

@section('content')
<div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Admin Panel</p>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Seller Requests 🏪</h1>
        <p class="mt-2 text-gray-500">Review applications from buyers who want to become sellers.</p>
    </div>
    <span class="self-start rounded-xl bg-indigo-50 px-5 py-3 font-semibold text-indigo-700">{{ $requests->count() }} Shown</span>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
        <p class="font-semibold">Please check the following:</p>
        <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    @forelse ($requests as $request)
        @php
            $statusClass = match ($request->status) {
                'approved' => 'bg-green-100 text-green-700',
                'rejected' => 'bg-red-100 text-red-700',
                'pending' => 'bg-amber-100 text-amber-700',
                default => 'bg-gray-100 text-gray-700',
            };
        @endphp

        <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Seller Application #{{ $request->id }}</p>
                    <h2 class="mt-2 text-xl font-bold text-gray-900">👤 {{ $request->user?->name ?? 'User unavailable' }}</h2>
                    @if ($request->reviewed_at)
                        <p class="mt-2 text-xs text-gray-500">Reviewed at: {{ $request->reviewed_at }}</p>
                    @endif
                </div>
                <span class="self-start rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
            </div>

            <div class="p-5 sm:p-6">
                @if ($request->rejection_reason)
                    <div class="mb-5 rounded-xl border border-red-100 bg-red-50 p-4">
                        <h3 class="font-semibold text-red-700">Previous Rejection Reason</h3>
                        <p class="mt-2 whitespace-pre-line leading-6 text-red-800">{{ $request->rejection_reason }}</p>
                    </div>
                @endif

                @if ($request->status === 'pending')
                    <h3 class="mb-4 font-bold text-gray-900">Review Application</h3>
                    <div class="grid items-start gap-4 md:grid-cols-2">
                        <form action="{{ route('admin.seller-requests.approve', $request->id) }}" method="POST" class="rounded-xl border border-green-200 bg-green-50 p-5">
                            @csrf
                            @method('PATCH')
                            <p class="mb-4 text-sm leading-6 text-green-800">Approve this application to proceed with the existing seller approval workflow.</p>
                            <button type="submit" class="w-full cursor-pointer rounded-xl bg-green-600 px-5 py-3 font-semibold text-white transition hover:bg-green-700">✓ Approve Seller</button>
                        </form>

                        <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                            <details class="group" @if($errors->has('rejection_reason')) open @endif>
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-2 font-semibold text-red-700">
                                    <span>✕ Reject Application</span>
                                    <span class="text-xl transition group-open:rotate-180">⌄</span>
                                </summary>
                                <form action="{{ route('admin.seller-requests.reject', $request->id) }}" method="POST" class="mt-4 space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label for="reason-{{ $request->id }}" class="mb-2 block text-sm font-semibold text-gray-700">Rejection Reason</label>
                                        <textarea id="reason-{{ $request->id }}" name="rejection_reason" rows="4" required placeholder="Explain why the application is rejected..." class="w-full rounded-lg border border-red-200 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-200">{{ old('rejection_reason') }}</textarea>
                                        @error('rejection_reason') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <button type="submit" class="w-full cursor-pointer rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-700">Confirm Rejection</button>
                                </form>
                            </details>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl bg-gray-50 p-4 text-sm font-medium text-gray-600">This application has already been reviewed. No further action is available here.</div>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">
            <p class="text-5xl">📭</p>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">No Seller Requests</h2>
            <p class="mt-3 text-gray-500">Applications will appear here when customers apply to become sellers.</p>
        </div>
    @endforelse
</div>

@if (method_exists($requests, 'links') && $requests->hasPages())
    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">{{ $requests->links() }}</div>
@endif
@endsection
