@extends('layouts.app')

@section('content')
    @php
        $activeCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
        $activeCountryName = config('countries.supported.' . $activeCountry . '.name') ?? $activeCountry;

        $productItems = $products instanceof \Illuminate\Contracts\Pagination\Paginator ? collect($products->items()) : collect($products ?? []);
        $supplierItems = $suppliers instanceof \Illuminate\Contracts\Pagination\Paginator ? collect($suppliers->items()) : collect($suppliers ?? []);

        $productLocal = $productItems->filter(fn ($item) => strtoupper($item->country_code ?? $item->user?->businessProfile?->country_code ?? '') === $activeCountry)->values();
        $productInternational = $productItems->filter(fn ($item) => strtoupper($item->country_code ?? $item->user?->businessProfile?->country_code ?? '') !== $activeCountry)->values();
        $supplierLocal = $supplierItems->filter(fn ($item) => strtoupper($item->businessProfile?->country_code ?? $item->country_code ?? '') === $activeCountry)->values();
        $supplierInternational = $supplierItems->filter(fn ($item) => strtoupper($item->businessProfile?->country_code ?? $item->country_code ?? '') !== $activeCountry)->values();
    @endphp

    <main class="flex-1 min-w-0 bg-[#f8fafc]">
        <div class="mx-auto w-full max-w-[1400px] px-3 py-6 sm:px-6 sm:py-10 lg:px-10">
            <div class="mb-8">
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Search Results</h1>
                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Showing {{ strtolower($type) }} matching "<span class="text-[#ff6a00]">{{ $query }}</span>"
                </p>
            </div>

            <style>
                /* View Toggle Buttons */
                .pl-view-btns { display: flex; gap: 4px; }
                .pl-view-btn {
                    width: 32px;
                    height: 32px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #fff;
                    border: 1.5px solid #e5e7eb;
                    border-radius: 6px;
                    color: #6b7280;
                    cursor: pointer;
                    transition: all .2s;
                }
                .pl-view-btn.active, .pl-view-btn:hover {
                    background: #ff6a00;
                    border-color: #ff6a00;
                    color: #fff;
                }

                /* List View Overrides for Tailwind Grid */
                .pl-list-view {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 16px !important;
                }
                .pl-list-view > a.group {
                    flex-direction: row !important;
                    height: 180px !important;
                }
                .pl-list-view > a.group > div:first-child {
                    width: 240px !important;
                    height: 100% !important;
                    border-right: 1px solid #e2e8f0;
                    aspect-ratio: auto !important;
                }
                .pl-list-view > a.group > div:first-child img {
                    object-fit: contain !important;
                }
            </style>

            @if ($type === 'Products')
                @if ($products->isNotEmpty())
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-lg font-extrabold text-slate-800">Products from {{ $activeCountryName }}</h2>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $productLocal->count() }} on this page</span>
                                    <div class="pl-view-btns">
                                        <button class="pl-view-btn active" title="Grid View">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z"/>
                                            </svg>
                                        </button>
                                        <button class="pl-view-btn" title="List View">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if($productLocal->isEmpty())
                                <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500">No products from {{ $activeCountryName }} matched this search.</div>
                            @else
                                <div class="pl-grid grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    @foreach ($productLocal as $product)
                                        @include('components.public-product-card', ['product' => $product, 'variant' => 'grid'])
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <section class="border-t border-slate-200 pt-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-lg font-extrabold text-slate-800">Products from Other International Markets</h2>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $productInternational->count() }} on this page</span>
                                    <div class="pl-view-btns">
                                        <button class="pl-view-btn active" title="Grid View">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z"/>
                                            </svg>
                                        </button>
                                        <button class="pl-view-btn" title="List View">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if($productInternational->isEmpty())
                                <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500">No products from other international markets matched this search.</div>
                            @else
                                <div class="pl-grid grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    @foreach ($productInternational as $product)
                                        @include('components.public-product-card', ['product' => $product, 'variant' => 'grid'])
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <div class="mt-8 flex justify-center">{{ $products->links() }}</div>
                @else
                    <div class="rounded-xl border border-slate-100 bg-white p-12 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-1 text-lg font-bold text-slate-800">No products found</h3>
                        <p class="text-sm text-slate-500">We couldn't find any products matching your search query.</p>
                    </div>
                @endif
            @elseif ($type === 'Suppliers')
                @if ($suppliers->isNotEmpty())
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-lg font-extrabold text-slate-800">Sellers from {{ $activeCountryName }}</h2>
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $supplierLocal->count() }} on this page</span>
                            </div>
                            @if($supplierLocal->isEmpty())
                                <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500">No sellers from {{ $activeCountryName }} matched this search.</div>
                            @else
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($supplierLocal as $supplier)
                                        @include('components.public-supplier-card', ['supplier' => $supplier])
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <section class="border-t border-slate-200 pt-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-lg font-extrabold text-slate-800">Sellers from Other International Markets</h2>
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $supplierInternational->count() }} on this page</span>
                            </div>
                            @if($supplierInternational->isEmpty())
                                <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500">No sellers from other international markets matched this search.</div>
                            @else
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($supplierInternational as $supplier)
                                        @include('components.public-supplier-card', ['supplier' => $supplier])
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <div class="mt-8 flex justify-center">{{ $suppliers->links() }}</div>
                @else
                    <div class="rounded-xl border border-slate-100 bg-white p-12 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-1 text-lg font-bold text-slate-800">No suppliers found</h3>
                        <p class="text-sm text-slate-500">We couldn't find any suppliers matching your search query.</p>
                    </div>
                @endif
            @endif
        </div>
    </main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridBtns = document.querySelectorAll('.pl-view-btn[title="Grid View"]');
    const listBtns = document.querySelectorAll('.pl-view-btn[title="List View"]');
    const containers = document.querySelectorAll('.pl-grid');

    if (containers.length === 0) return;

    // Load saved view
    const savedView = localStorage.getItem('product-view') || 'grid';
    if (savedView === 'list') {
        enableListView();
    }

    gridBtns.forEach(btn => btn.addEventListener('click', enableGridView));
    listBtns.forEach(btn => btn.addEventListener('click', enableListView));

    function enableGridView() {
        containers.forEach(c => c.classList.remove('pl-list-view'));
        gridBtns.forEach(btn => btn.classList.add('active'));
        listBtns.forEach(btn => btn.classList.remove('active'));
        localStorage.setItem('product-view', 'grid');
    }

    function enableListView() {
        containers.forEach(c => c.classList.add('pl-list-view'));
        gridBtns.forEach(btn => btn.classList.remove('active'));
        listBtns.forEach(btn => btn.classList.add('active'));
        localStorage.setItem('product-view', 'list');
    }
});
</script>
@endpush
