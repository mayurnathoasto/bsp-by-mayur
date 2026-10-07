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

        .submenu-item.active

        /* My Products Specific Stylings */
        .status-tab {
            padding: 8px 32px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s;
            border: 1.5px solid #e2e8f0;
            background: #f1f5f9;
            color: #64748b;
        }

        .status-tab.active {
            background: #fff9f5;
            border-color: #ff6a00;
            color: #ff6a00;
            box-shadow: 0 4px 6px -1px rgba(255, 106, 0, 0.05);
        }

        .alert-bar {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-align: center;
            margin: 20px 0;
        }

        .add-more-btn {
            background: #3b7ea1;
            color: white;
            padding: 14px 0;
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 30px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-more-btn:hover {
            background: #2b607c;
            transform: translateY(-1px);
        }

        .learning-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            padding: 16px;
            display: flex;
            gap: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .learning-card:hover {
            border-color: #ff6a00;
            background: #fffaf7;
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
                        <span class="text-slate-800">My Products</span>
                    </div>

                    <div class="flex items-start justify-between mt-8 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">My Products</h2>
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
                            <div class="product-card bg-white p-6 shadow-sm border border-slate-100 min-h-[600px]">

                                @if(session('success'))
                                    <div
                                        class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                                        {{ session('success') }}
                                    </div>
                                @endif


                                {{-- Tabs --}}
                                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('user.products.index') }}"
                                            class="status-tab {{ empty($currentStatus) ? 'active' : '' }}">
                                            All
                                        </a>

                                        <a href="{{ route('user.products.index', ['status' => 'pending']) }}"
                                            class="status-tab {{ ($currentStatus ?? '') === 'pending' ? 'active' : '' }}">
                                            Under Review
                                        </a>

                                        <a href="{{ route('user.products.index', ['status' => 'live']) }}"
                                            class="status-tab {{ ($currentStatus ?? '') === 'live' ? 'active' : '' }}">
                                            Live
                                        </a>
                                    </div>

                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('user.products.export', array_filter(['status' => $currentStatus ?: null])) }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ff6a00] bg-[#fff7f2] px-4 py-2.5 text-[13px] font-bold text-[#ff6a00] transition-colors hover:bg-[#ff6a00] hover:text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
                                            </svg>
                                            Export Excel
                                        </a>

                                        {{-- Bulk Import Excel Form --}}
                                        <form method="POST" action="{{ route('user.products.import') }}" enctype="multipart/form-data" class="inline-flex items-center">
                                            @csrf
                                            <label class="cursor-pointer inline-flex items-center justify-center gap-2 rounded-lg border border-blue-600 bg-blue-50 px-4 py-2.5 text-[13px] font-bold text-blue-600 transition-colors hover:bg-blue-600 hover:text-white">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                <span>Import Excel</span>
                                                <input type="file" name="file" accept=".csv" class="hidden" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                    </div>
                                </div>

                                @if(session('success'))
                                    <div class="alert-bar">{{ session('success') }}</div>
                                @endif

                                <div class="mt-10">
                                    <div class="flex items-center justify-between border-b border-orange-100 pb-2 mb-6">
                                        <span
                                            class="text-[12px] font-black text-[#ff6a00] uppercase tracking-wider">Featured
                                            Products</span>
                                    </div>

                                    <a href="{{ route('user.products.create') }}" class="add-more-btn">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add More product
                                    </a>

                                    {{-- Dynamic Product List --}}
                                    @if($products->isEmpty())
                                        <div class="flex flex-col items-center justify-center py-20">
                                            <div class="relative w-56 h-56 mb-8">
                                                <div
                                                    class="absolute inset-0 bg-orange-50 rounded-full scale-110 opacity-40 animate-pulse">
                                                </div>
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png"
                                                    class="w-full h-full object-contain relative z-10 opacity-90"
                                                    alt="No results">
                                            </div>
                                            <h4 class="text-2xl font-black text-slate-800 tracking-tight">Whoops! No Results
                                                Found</h4>
                                            <p class="text-slate-500 mt-2 font-medium text-center">
                                                @if(($currentStatus ?? '') === 'pending')
                                                    You do not have any products under review right now.
                                                @elseif(($currentStatus ?? '') === 'live')
                                                    You do not have any live products right now.
                                                @else
                                                    You haven't added any products yet.
                                                @endif
                                            </p>
                                        </div>
                                    @else
                                        <div class="space-y-4 mb-8">
                                            @foreach($products as $product)
                                                                        <div
                                                                            class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-orange-200 transition-colors">
                                                                            <div
                                                                                class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-white border border-slate-200">
                                                                                @php $img = !empty($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://placehold.co/100x100/f8fafc/64748b?text=No+Image'; @endphp
                                                                                <img src="{{ $img }}" class="w-full h-full object-cover">
                                                                            </div>
                                                                            <div class="flex-1 min-w-0">
                                                                                <div class="flex items-center gap-3 mb-1">
                                                                                    <h5 class="font-bold text-slate-800 truncate">{{ $product->name }}</h5>

                                                                                    <span
                                                                                        class="px-2 py-1 text-[10px] font-bold rounded-full shrink-0
                                                                                                                                                            @if(($product->status ?? 'pending') === 'live')
                                                                                                                                                                bg-green-100 text-green-700
                                                                                                                                                            @elseif(($product->status ?? 'pending') === 'pending')
                                                                                                                                                                bg-yellow-100 text-yellow-700
                                                                                                                                                            @else
                                                                                                                                                                bg-slate-100 text-slate-600
                                                                                                                                                            @endif">
                                                                                        {{ ($product->status ?? 'pending') === 'live'
                                                ? 'LIVE'
                                                : (($product->status ?? 'pending') === 'pending' ? 'UNDER REVIEW' : 'DRAFT') }}
                                                                                    </span>
                                                                                </div>

                                                                                <div
                                                                                    class="flex items-center gap-3 text-[12px] font-bold text-slate-400 uppercase tracking-wider">
                                                                                    <span>{{ $product->category->name ?? 'Category' }}</span>
                                                                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                                                    @include('components.country-badge', [
                                                                                        'countryCode' => strtoupper($product->country_code ?? session('country', 'IN')),
                                                                                        'showName' => false,
                                                                                        'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600 normal-case tracking-normal',
                                                                                    ])
                                                                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                                                    <span class="text-[#ff6a00]">{{ $product->currency ?? 'INR' }}
                                                                                        {{ number_format($product->price ?? 0) }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex items-center gap-2">
                                                                                <a href="{{ route('products.show', $product) }}" target="_blank"
                                                                                    class="p-2 text-slate-400 hover:text-[#3b82f6] transition-colors"
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
                                                                                    class="p-2 text-slate-400 hover:text-[#ff6a00] transition-colors"
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
                                                                                        class="p-2 text-slate-400 hover:text-red-500 transition-colors"
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
                                            @endforeach
                                        </div>

                                        <div class="py-4">
                                            {{ $products->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Right Sidebar --}}

                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
