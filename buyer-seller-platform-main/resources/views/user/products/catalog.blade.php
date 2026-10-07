@extends('layouts.app')

@section('content')
    {{-- Import Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .submenu-item.active .product-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .learning-card {
            background: #fcfdfe;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            gap: 12px;
            transition: all 0.2s;
        }

        .learning-card:hover {
            border-color: #ff6a0033;
            background: white;
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        }
    </style>
    <!-- Shared Sidebar CSS -->
    @include('layouts.sidebar_css')

    <div x-data="{ 
                    sidebarOpen: false, 
                    openSub: '{{ request()->routeIs('user.profile', 'user.business-profile', 'user.bank-details', 'user.manage-users') ? 'profile' : (request()->routeIs('user.products.*') ? 'products' : 'dashboard') }}',
                    activeTab: '{{ request()->routeIs('user.profile') ? 'profile' : (request()->routeIs('user.business-profile') ? 'business' : (request()->routeIs('user.bank-details') ? 'bank' : (request()->routeIs('user.manage-users') ? 'manage_users' : 'dashboard'))) }}'
                }" class="flex flex-row-reverse h-screen overflow-hidden bg-[#f8fafc] relative">
        <style>
            @media (min-width: 1024px) {
                aside {
                    border-right: none !important;
                    border-left: 1px solid #e2e8f0 !important;
                }
            }
        </style>
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto">
            {{-- Content Area --}}
            <div class="p-8">
                <div class="max-w-[1400px] mx-auto space-y-4">
                    {{-- Breadcrumbs --}}
                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="#" class="hover:text-slate-600 transition-colors">Manage Product</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-slate-800">My Collection</span>
                    </div>

                    <div class="flex items-start justify-between mt-8 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">My Catalog</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="p-2 text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg></button>
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-8">
                        {{-- Main Content Column --}}
                        <div class="flex-1 min-w-0">

                            @if(session('success'))
                                <div
                                    class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($products->isEmpty())
                                <div
                                    class="product-card bg-white p-6 shadow-sm border border-slate-100 min-h-[600px] flex flex-col items-center justify-center">
                                    {{-- Empty State --}}
                                    <div class="relative w-56 h-56 mb-8">
                                        <div
                                            class="absolute inset-0 bg-orange-50 rounded-full scale-110 opacity-40 animate-pulse">
                                        </div>
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png"
                                            class="w-full h-full object-contain relative z-10 opacity-90" alt="No results">
                                        <div
                                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg z-20">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h4 class="text-2xl font-black text-slate-800 tracking-tight">Whooops! No Results Found</h4>
                                    <p class="text-slate-500 mt-2 font-medium">Add some products to your collection to see them
                                        here.</p>
                                    <a href="{{ route('user.products.create') }}"
                                        class="mt-6 bg-[#3b7ea1] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#2b607c] transition-all">Add
                                        Your First Product</a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    @foreach($products as $product)
                                        @php
                                            $productCountryCode = strtoupper($product->country_code ?? session('country', 'IN'));
                                        @endphp
                                        <div
                                            class="product-card bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full border border-slate-200">
                                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                                @php $img = !empty($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://placehold.co/400x400/f8fafc/64748b?text=No+Image'; @endphp
                                                <img src="{{ $img }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                    alt="{{ $product->name }}">
                                                <div class="absolute top-3 left-3">
                                                    <span
                                                        class="bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded uppercase tracking-wider shadow-sm">
                                                        {{ $product->category->name ?? 'Category' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-5 flex-1 flex flex-col">
                                                <h4
                                                    class="font-bold text-slate-800 text-[15px] mb-2 line-clamp-2 min-h-[40px] leading-snug">
                                                    {{ $product->name }}
                                                </h4>

                                                <div class="mb-4">
                                                    @include('components.country-badge', [
                                                        'countryCode' => $productCountryCode,
                                                        'showName' => false,
                                                        'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                                    ])
                                                </div>

                                                <div
                                                    class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                                                    <div>
                                                        <span
                                                            class="text-[11px] font-bold text-slate-400 uppercase block mb-0.5">Price</span>
                                                        <span class="text-[16px] font-black text-[#ff6a00]">
                                                            {{ $product->currency ?? 'INR' }}
                                                            {{ number_format($product->price ?? 0) }}
                                                        </span>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('products.show', $product) }}" target="_blank"
                                                            class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#ff6a00] hover:border-[#ff6a00] transition-colors"
                                                            title="View">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </a>

                                                        <a href="{{ route('user.products.edit', $product) }}"
                                                            class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#3b82f6] hover:border-[#3b82f6] transition-colors"
                                                            title="Edit">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </a>

                                                        <form method="POST" action="{{ route('user.products.destroy', $product) }}"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-500 transition-colors"
                                                                title="Delete">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-10">
                                    {{ $products->links() }}
                                </div>
                            @endif
                        </div>


                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
