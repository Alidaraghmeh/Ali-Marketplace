@extends('layouts.app')

@section('title', 'Seller Request History | Ali Marketplace')

@section('content')
<div class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Admin Panel</p>
    <h1 class="mt-2 text-3xl font-bold text-gray-900">Seller Request History 🗂️</h1>
    <p class="mt-2 text-gray-500">A record of seller applications and their review decisions.</p>
</div>

<div class="mb-7 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Application Records</h2>
            <p class="mt-1 text-sm text-gray-500">Who applied, the decision, and who reviewed it.</p>
        </div>
        <span class="self-start rounded-full bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">{{ $requests->count() }} Shown</span>
    </div>
</div>

<div class="grid gap-5 lg:grid-cols-2">
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
            <div class="flex items-center justify-between gap-3 border-b border-gray-100 bg-gray-50 p-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Seller Application</p>
                    <h3 class="mt-1 text-lg font-bold text-gray-900">#{{ $request->id }}</h3>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
            </div>

            <div class="space-y-5 p-5">
                <div>
                    <p class="text-sm text-gray-500">Applicant</p>
                    <p class="mt-1 font-semibold text-gray-900">👤 {{ $request->user?->name ?? 'User unavailable' }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Reviewed By</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $request->reviewer?->name ?? 'Not reviewed yet' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Reviewed At</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $request->reviewed_at ?? 'Not reviewed yet' }}</p>
                    </div>
                </div>
                @if ($request->rejection_reason)
                    <div class="rounded-xl border border-red-100 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-700">Rejection Reason</p>
                        <p class="mt-2 whitespace-pre-line leading-6 text-red-800">{{ $request->rejection_reason }}</p>
                    </div>
                @else
                    <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">No rejection reason recorded.</div>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm lg:col-span-2">
            <p class="text-5xl">📋</p>
            <h2 class="mt-4 text-xl font-bold text-gray-900">No Requests Found</h2>
            <p class="mt-2 text-gray-500">Seller application records will appear here.</p>
        </div>
    @endforelse
</div>

@if (method_exists($requests, 'links') && $requests->hasPages())
    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-4">{{ $requests->links() }}</div>
@endif
@endsection
