@extends('layouts.app')

@section('content')

    <style>
        /* ===================== DESKTOP LAYOUT ===================== */
        .cat-page-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .cat-layout {
            display: flex;
            gap: 32px;
            align-items: flex-start;
        }

        .cat-sidebar {
            width: 240px;
            flex-shrink: 0;
        }

        .cat-sidebar-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .cat-sidebar-title {
            padding: 16px 16px 8px;
            font-size: 15px;
            font-weight: 800;
            color: #111827;
        }

        .cat-sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            font-size: 13px;
            color: #374151;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background .15s;
        }

        .cat-sidebar-link:hover {
            background: #f9fafb;
        }

        .cat-sidebar-link.first {
            background: #eff6ff;
            border-left-color: #3b82f6;
            color: #1d4ed8;
            font-weight: 600;
        }

        .cat-bulk-box {
            margin-top: 20px;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            border-radius: 12px;
            padding: 20px;
            color: #fff;
        }

        .cat-bulk-title {
            font-weight: 800;
            font-size: 14px;
            margin: 0 0 8px;
        }

        .cat-bulk-desc {
            font-size: 12px;
            color: #bfdbfe;
            margin: 0 0 16px;
            line-height: 1.6;
        }

        .cat-bulk-btn {
            display: block;
            text-align: center;
            background: #fff;
            color: #1d4ed8;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background .15s;
        }

        .cat-bulk-btn:hover {
            background: #eff6ff;
        }

        /* Main desktop content */
        .cat-main {
            flex: 1;
            min-width: 0;
        }

        .cat-main-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .cat-main-title {
            font-size: 28px;
            font-weight: 900;
            color: #111827;
            margin: 0 0 6px;
        }

        .cat-main-sub {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .cat-sort-btns {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .cat-sort-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
        }

        /* Desktop grid cards */
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .cat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
        }

        .cat-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
            transform: translateY(-2px);
        }

        .cat-card-img {
            height: 160px;
            background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cat-card-body {
            padding: 18px;
        }

        .cat-card-title {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 12px;
            line-height: 1.3;
        }

        .cat-card-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px;
        }

        .cat-card-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 3px 0;
        }

        .cat-card-list li .dot {
            width: 5px;
            height: 5px;
            background: #93c5fd;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .cat-card-list li a {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
        }

        .cat-card-list li a:hover {
            color: #2563eb;
        }

        .cat-card-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }

        .cat-card-link:hover {
            color: #1d4ed8;
        }

        .cat-footer-section {
            margin-top: 48px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 32px;
        }

        .cat-footer-count {
            font-size: 13px;
            color: #9ca3af;
            margin: 0 0 16px;
        }

        .cat-load-more {
            padding: 12px 40px;
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            cursor: pointer;
            transition: border-color .15s;
        }

        .cat-load-more:hover {
            border-color: #9ca3af;
        }

        /* ===================== MOBILE LAYOUT ===================== */
        /* Mobile-only elements (hidden on desktop) */
        .mobile-only {
            display: none;
        }

        /* Desktop-only elements */
        .desktop-only {
            display: block;
        }

        .desktop-only-flex {
            display: flex;
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }

            .desktop-only-flex {
                display: none !important;
            }

            .mobile-only {
                display: block;
            }

            /* Mobile page wrapper */
            .cat-page-wrap {
                padding: 0;
            }

            .cat-layout {
                display: block;
                gap: 0;
            }

            /* Mobile breadcrumb override */
            .mobile-breadcrumb {
                padding: 10px 16px;
                font-size: 11px;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-weight: 600;
            }

            .mobile-breadcrumb span {
                color: #2563eb;
            }

            /* Mobile header */
            .mobile-page-header {
                padding: 0 16px 16px;
            }

            .mobile-page-header h1 {
                font-size: 22px;
                font-weight: 900;
                color: #111827;
                margin: 0 0 16px;
            }

            /* Filter chips */
            .mobile-chips {
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding-bottom: 4px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .mobile-chips::-webkit-scrollbar {
                display: none;
            }

            .mobile-chip {
                flex-shrink: 0;
                padding: 6px 14px;
                border-radius: 20px;
                border: 1px solid #e5e7eb;
                background: #fff;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                cursor: pointer;
                text-decoration: none;
                white-space: nowrap;
            }

            .mobile-chip.active {
                background: #111827;
                color: #fff;
                border-color: #111827;
            }

            /* Mobile category list */
            .mobile-cat-list {
                padding: 8px 16px;
            }

            .mobile-cat-item {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 14px 0;
                border-bottom: 1px solid #f3f4f6;
                text-decoration: none;
                color: inherit;
            }

            .mobile-cat-item:last-child {
                border-bottom: none;
            }

            .mobile-cat-thumb {
                width: 58px;
                height: 58px;
                border-radius: 10px;
                background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                overflow: hidden;
            }

            .mobile-cat-info {
                flex: 1;
                min-width: 0;
            }

            .mobile-cat-name {
                font-size: 15px;
                font-weight: 700;
                color: #111827;
                display: block;
                margin-bottom: 3px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-cat-count {
                font-size: 12px;
                color: #9ca3af;
            }

            .mobile-cat-arrow {
                width: 30px;
                height: 30px;
                background: #eff6ff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            /* Mobile RFQ box */
            .mobile-rfq {
                margin: 20px 16px;
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                border-radius: 16px;
                padding: 24px 20px;
                text-align: center;
            }

            .mobile-rfq h3 {
                font-size: 17px;
                font-weight: 800;
                color: #fff;
                margin: 0 0 8px;
            }

            .mobile-rfq p {
                font-size: 13px;
                color: #bfdbfe;
                margin: 0 0 20px;
                line-height: 1.6;
            }

            .mobile-rfq a {
                display: inline-block;
                background: #fff;
                color: #1d4ed8;
                padding: 12px 28px;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 700;
                text-decoration: none;
            }

            /* Mobile bottom nav */
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-top: 1px solid #e5e7eb;
                display: flex;
                z-index: 999;
                box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.08);
            }

            /* Extra bottom padding so content doesn't hide behind the nav */
            .cat-page-wrap {
                padding-bottom: 80px;
            }

            .mobile-nav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3px;
                padding: 10px 0;
                text-decoration: none;
                color: #9ca3af;
                font-size: 10px;
                font-weight: 600;
            }

            .mobile-nav-item.active {
                color: #2563eb;
            }

            .mobile-nav-item svg {
                width: 22px;
                height: 22px;
            }
        }
    </style>

    {{-- ===== BREADCRUMB (Desktop) ===== --}}
    <div class="desktop-only" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
        <div
            style="max-width:1280px;margin:0 auto;padding:10px 20px;display:flex;align-items:center;gap:8px;font-size:13px;color:#6b7280;">
            <a href="{{ url('/') }}" style="color:#6b7280;text-decoration:none;" onmouseover="this.style.color='#2563eb'"
                onmouseout="this.style.color='#6b7280'">
                Home
            </a>
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span style="color:#111827;font-weight:600;">All Categories</span>
        </div>
    </div>

    {{-- ===== MOBILE BREADCRUMB ===== --}}
    <div class="mobile-only mobile-breadcrumb">
        Marketplace &rsaquo; <span>Global Categories</span>
    </div>

    {{-- ===== MOBILE HEADER ===== --}}
    <div class="mobile-only mobile-page-header">
        <h1>Sourcing Categories</h1>
        {{-- Filter Chips --}}
        <div class="mobile-chips">
            <span class="mobile-chip active">All ({{ $categories->count() }})</span>
            <span class="mobile-chip">Trending</span>
            <span class="mobile-chip">New Arrivals</span>
            <span class="mobile-chip">Featured</span>
        </div>
    </div>

    {{-- ===== MAIN WRAPPER ===== --}}
    <div class="cat-page-wrap">
        <div class="cat-layout">

            {{-- ===== DESKTOP SIDEBAR ===== --}}
            <aside class="cat-sidebar desktop-only">
                {{-- Industries List --}}
                <div class="cat-sidebar-box">
                    <div class="cat-sidebar-title">Top Industries</div>
                    <nav>
                        @foreach($categories as $index => $cat)
                            <a href="{{ route('products', ['category' => $cat->id]) }}"
                                class="cat-sidebar-link {{ $index === 0 ? 'first' : '' }}">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="flex-shrink:0;color:#9ca3af;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                {{ Str::limit($cat->name, 28) }}
                            </a>
                        @endforeach
                    </nav>
                </div>
                {{-- Bulk Box --}}
                <div class="cat-bulk-box">
                    <div class="cat-bulk-title">Need Bulk Assistance?</div>
                    <p class="cat-bulk-desc">Connect with our dedicated sourcing agents for custom wholesale quotes.</p>
                    <a href="{{ route('post-requirement') }}" class="cat-bulk-btn">Request a Quote</a>
                </div>
            </aside>

            {{-- ===== DESKTOP MAIN ===== --}}
            <div class="cat-main desktop-only">
                <div class="cat-main-header">
                    <div>
                        <h1 class="cat-main-title">Explore All Categories</h1>
                        <p class="cat-main-sub">Browse over 50,000 verified Industrial suppliers across global regions.</p>
                    </div>
                    <div class="cat-sort-btns">
                        <button class="cat-sort-btn">
                            Sort by Relevance
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <button class="cat-sort-btn">
                            Filters (0)
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6h18M7 12h10m-3 6h3" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Desktop 3-col grid --}}
                <div class="cat-grid">
                    @foreach($categories as $cat)
                        <div class="cat-card">
                            <div class="cat-card-img">
                                @if($cat->image)
                                    <img src="{{ $cat->image }}" alt="{{ $cat->name }}"
                                        style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <svg width="48" height="48" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                @endif
                            </div>
                            <div class="cat-card-body">
                                <h2 class="cat-card-title">{{ $cat->name }}</h2>
                                <ul class="cat-card-list">
                                    @forelse($cat->subcategories as $sub)
                                        <li>
                                            <span class="dot"></span>
                                            <a href="{{ route('products', ['subcategory' => $sub->id]) }}">{{ $sub->name }}</a>
                                        </li>
                                    @empty
                                        <li style="color:#9ca3af;font-size:13px;font-style:italic;">Coming Soon</li>
                                    @endforelse
                                </ul>
                                <a href="{{ route('category.detail', ['id' => $cat->id]) }}" class="cat-card-link">
                                    View All Products
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cat-footer-section">
                    <p class="cat-footer-count">Showing {{ $categories->count() }} of 42 high-level categories</p>
                    <button class="cat-load-more">Load More Categories</button>
                </div>
            </div>

            {{-- ===== MOBILE LIST VIEW ===== --}}
            <div class="mobile-only" style="width:100%;">
                <div class="mobile-cat-list">
                    @foreach($categories as $cat)
                        <a href="{{ route('category.detail', ['id' => $cat->id]) }}" class="mobile-cat-item">
                            <div class="mobile-cat-thumb">
                                @if($cat->image)
                                    <img src="{{ $cat->image }}" alt="{{ $cat->name }}"
                                        style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                                @else
                                    <svg width="28" height="28" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                @endif
                            </div>
                            <div class="mobile-cat-info">
                                <span class="mobile-cat-name">{{ $cat->name }}</span>
                                <span class="mobile-cat-count">{{ $cat->subcategories_count ?? $cat->subcategories->count() }}
                                    Products</span>
                            </div>
                            <div class="mobile-cat-arrow">
                                <svg width="14" height="14" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Mobile RFQ Card --}}
                <div class="mobile-rfq">
                    <h3>Request for Quotation</h3>
                    <p>Can't find what you're looking for? Submit an RFQ and let suppliers find you.</p>
                    <a href="{{ route('post-requirement') }}">Post RFQ Now</a>
                </div>
            </div>

        </div>{{-- /.cat-layout --}}
    </div>{{-- /.cat-page-wrap --}}

    {{-- ===== MOBILE BOTTOM NAV ===== --}}
    <nav class="mobile-only mobile-bottom-nav">
        <a href="{{ url('/') }}" class="mobile-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Home
        </a>
        <a href="{{ route('categories.all') }}" class="mobile-nav-item active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Categories
        </a>
        <a href="{{ route('user.inquiries') }}" class="mobile-nav-item" style="position:relative;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            Messages
            <span
                style="position:absolute;top:8px;right:22px;width:16px;height:16px;background:#ef4444;border-radius:50%;font-size:9px;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">9</span>
        </a>
        <a href="#" class="mobile-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>
    </nav>

@endsection