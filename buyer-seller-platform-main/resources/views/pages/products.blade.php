@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' Products' : 'All Products') . ' | Buyer Seller Platform')

@section('content')
<style>
    /* -
       PRODUCTS LISTING PAGE — PREMIUM UI
    - */
    .pl-page {
        background: #f4f6f9;
        min-height: 100vh;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    /* - Breadcrumb - */
    .pl-breadcrumb {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 0;
    }
    .pl-breadcrumb-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .pl-breadcrumb-inner a {
        color: #6b7280;
        text-decoration: none;
    }
    .pl-breadcrumb-inner a:hover { color: #2563eb; }
    .pl-breadcrumb-inner span { color: #111827; font-weight: 600; }

    /* - Main layout - */
    .pl-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        display: grid;
        grid-template-columns: 264px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* -
       LEFT SIDEBAR — FILTERS
    - */
    .pl-sidebar {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .pl-sidebar-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid #f3f4f6;
    }
    .pl-sidebar-head-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .pl-sidebar-head-title svg { color: #7c3aed; }
    .pl-clear {
        font-size: 12px;
        color: #7c3aed;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }
    .pl-clear:hover { text-decoration: underline; }

    /* Filter section */
    .pl-filter-section {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
    }
    .pl-filter-section:last-of-type { border-bottom: none; }

    .pl-filter-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 800;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 12px;
        cursor: pointer;
    }
    .pl-filter-label svg { color: #9ca3af; }

    /* Verified toggle */
    .pl-verified-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pl-verified-row.active {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .pl-verified-row span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        transition: color 0.2s;
    }
    .pl-verified-row.active span {
        color: #15803d;
    }
    .pl-toggle {
        position: relative;
        width: 36px;
        height: 20px;
    }
    .pl-toggle input { opacity: 0; width: 0; height: 0; }
    .pl-toggle-slider {
        position: absolute;
        inset: 0;
        background: #d1d5db;
        border-radius: 20px;
        cursor: pointer;
        transition: .2s;
    }
    .pl-toggle-slider:before {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background: #fff;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: .2s;
    }
    .pl-toggle input:checked + .pl-toggle-slider { background: #16a34a; }
    .pl-toggle input:checked + .pl-toggle-slider:before { transform: translateX(16px); }

    /* Price range */
    .pl-price-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .pl-price-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 13px;
        color: #111827;
        outline: none;
        width: 100%;
    }
    .pl-price-input:focus { border-color: #7c3aed; }
    .pl-price-go {
        width: 32px;
        height: 32px;
        background: #7c3aed;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        flex-shrink: 0;
    }
    .pl-price-go svg { color: #fff; }
    input[type=range].pl-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 4px;
        outline: none;
    }
    input[type=range].pl-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: #7c3aed;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Checkbox list */
    .pl-checkbox-list { display: flex; flex-direction: column; gap: 8px; }
    .pl-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #374151;
        cursor: pointer;
    }
    .pl-checkbox-item input[type=checkbox] {
        width: 15px;
        height: 15px;
        accent-color: #7c3aed;
        cursor: pointer;
    }

    /* Sidebar promo box */
    .pl-sidebar-promo {
        margin: 14px;
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        border-radius: 12px;
        padding: 18px;
        color: #fff;
        text-align: center;
    }
    .pl-sidebar-promo h4 {
        font-size: 13px;
        font-weight: 800;
        margin: 8px 0 6px;
        line-height: 1.4;
    }
    .pl-sidebar-promo p { font-size: 11px; opacity: .85; margin-bottom: 12px; }
    .pl-sidebar-promo a {
        display: block;
        background: #fff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        padding: 8px;
        border-radius: 8px;
        text-decoration: none;
    }
    .pl-sidebar-promo a:hover { background: #f0f4ff; }

    /* -
       MAIN CONTENT
    - */
    .pl-main { min-width: 0; }

    .pl-heading-bar {
        background: transparent;
        padding: 0 0 16px;
        margin-bottom: 16px;
    }
    .pl-heading-bar h1 {
        font-size: 22px;
        font-weight: 900;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 0 0 4px;
    }
    .pl-showing {
        font-size: 13px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .pl-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
    }
    .pl-sort {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
    }
    .pl-sort select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 13px;
        color: #374151;
        outline: none;
    }
    .pl-view-btns { display: flex; gap: 4px; }
    .pl-view-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: #fff;
        color: #6b7280;
        transition: background .15s;
    }
    .pl-view-btn.active, .pl-view-btn:hover {
        background: #7c3aed;
        border-color: #7c3aed;
        color: #fff;
    }

    /* Product grid */
    .pl-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    /* List View Styles */
    .pl-grid.pl-list-view {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .pl-grid.pl-list-view .pl-card {
        flex-direction: row;
        align-items: center;
        padding: 16px;
        gap: 20px;
        min-height: 180px;
    }

    .pl-grid.pl-list-view .pl-card-img {
        width: 160px;
        height: 160px;
        flex-shrink: 0;
        border-radius: 10px;
    }

    .pl-grid.pl-list-view .pl-card-body {
        padding: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .pl-grid.pl-list-view .pl-card-name {
        font-size: 18px;
        margin-bottom: 8px;
        -webkit-line-clamp: 2;
    }

    .pl-grid.pl-list-view .pl-card-price {
        font-size: 20px;
        margin-bottom: 12px;
    }

    .pl-grid.pl-list-view .pl-card-cta {
        margin-top: 0;
        width: fit-content;
        padding: 8px 24px;
    }

    /* Product Card */
    .pl-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }
    .pl-card:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,.12);
        transform: translateY(-2px);
    }

    /* Card image */
    .pl-card-img {
        position: relative;
        height: 200px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .pl-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .pl-card-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pl-verified-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(22,163,74,.9);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        letter-spacing: .8px;
        display: flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        backdrop-filter: blur(4px);
    }

    /* Card body */
    .pl-card-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; }

    .pl-card-stars {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 6px;
        font-size: 12px;
        color: #f59e0b;
    }
    .pl-card-stars span { color: #9ca3af; font-size: 11px; }

    .pl-card-name {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pl-card-price {
        font-size: 20px;
        font-weight: 900;
        color: #111827;
        margin-bottom: 2px;
        letter-spacing: -.3px;
    }
    .pl-card-price-unit {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
    }
    .pl-card-moq {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .pl-card-supplier-loc {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #374151;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .pl-card-supplier-loc span.loc {
        color: #9ca3af;
        font-weight: 400;
        text-transform: uppercase;
        font-size: 11px;
    }

    .pl-card-actions { margin-top: auto; display: flex; flex-direction: column; gap: 8px; }
    .pl-card-contact {
        display: block;
        background: #2563eb;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 10px;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        transition: background .15s;
    }
    .pl-card-contact:hover { background: #1d4ed8; color: #fff; }
    .pl-card-price-link {
        display: block;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
    }
    .pl-card-price-link:hover { text-decoration: underline; }

    /* Empty state */
    .pl-empty {
        background: #fff;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }
    .pl-empty h3 { font-size: 18px; font-weight: 700; color: #374151; margin: 16px 0 8px; }
    .pl-empty p { font-size: 14px; color: #9ca3af; }

    /* Pagination */
    .pl-pagination { margin-top: 28px; }
    .pl-pagination nav { display: flex; justify-content: center; }

    /* Recommended categories */
    .pl-rec-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-top: 28px;
    }
    .pl-rec-title {
        font-size: 12px;
        font-weight: 800;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 16px;
    }
    .pl-rec-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    .pl-rec-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        background: #f9fafb;
        transition: background .15s, border-color .15s;
    }
    .pl-rec-item:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }
    .pl-rec-item::before { content: '•'; color: #7c3aed; font-size: 14px; }

    /* CTA Banner */
    .pl-cta {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 28px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    .pl-cta-left { display: flex; align-items: center; gap: 16px; }
    .pl-cta-icon {
        width: 44px;
        height: 44px;
        background: #eff6ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #2563eb;
    }
    .pl-cta h4 { font-size: 15px; font-weight: 800; color: #111827; margin: 0 0 4px; }
    .pl-cta p { font-size: 13px; color: #6b7280; margin: 0; }
    .pl-cta-btn {
        background: #2563eb;
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        flex-shrink: 0;
        transition: background .15s;
    }
    .pl-cta-btn:hover { background: #1d4ed8; color: #fff; }

    /* - Tablet - */
    @media (max-width: 1100px) {
        .pl-wrap { grid-template-columns: 220px 1fr; gap: 16px; }
        .pl-grid { grid-template-columns: repeat(2, 1fr); }
        .pl-rec-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* -
       MOBILE (≤768px)
    - */
    @media (max-width: 768px) {
        /* - hide desktop-only elements - */
        .pl-breadcrumb, .pl-sidebar { display: none !important; }

        /* - mobile sticky header - */
        .mob-header {
            position: sticky;
            top: 0;
            z-index: 200;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 16px 8px;
        }
        .mob-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .mob-logo {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .mob-logo img {
            height: 36px;
            width: auto;
            object-fit: contain;
        }
        .mob-header-icons { display: flex; gap: 14px; }
        .mob-header-icons a {
            color: #374151;
            text-decoration: none;
            position: relative;
        }
        .mob-header-icons .badge {
            position: absolute;
            top: -6px; right: -6px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .mob-search-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mob-search-box {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f3f4f6;
            border-radius: 24px;
            padding: 8px 14px;
        }
        .mob-search-box input {
            border: none;
            background: transparent;
            font-size: 13px;
            color: #374151;
            width: 100%;
            outline: none;
        }
        .mob-search-box input::placeholder { color: #9ca3af; }
        .mob-filter-icon {
            width: 36px; height: 36px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #374151;
            border: none;
            cursor: pointer;
        }
        .mob-chips {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            overflow-x: auto;
            padding: 8px 16px 8px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }
        .mob-chips::-webkit-scrollbar { display: none; }
        .mob-chip {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            color: #374151;
            background: #fff;
            cursor: pointer;
        }
        .mob-chip.active {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #2563eb;
        }

        /* - page layout - */
        .pl-page { padding-top: 0; }
        .pl-wrap {
            grid-template-columns: 1fr;
            padding: 12px 16px 140px;
            gap: 0;
        }

        /* - heading - */
        .pl-heading-bar {
            padding: 14px 0 10px;
            margin-bottom: 0;
        }
        .pl-heading-bar h1 {
            font-size: 18px;
            margin-bottom: 2px;
        }
        .pl-showing { font-size: 12px; }
        .pl-controls { display: none; }

        /* - product grid → vertical list - */
        .pl-grid {
            display: flex !important;
            flex-direction: column;
            gap: 0;
        }
        .pl-card {
            flex-direction: row !important;
            border-radius: 0;
            border-left: none;
            border-right: none;
            border-top: none;
            border-bottom: 1px solid #f3f4f6;
            box-shadow: none !important;
            transform: none !important;
            padding: 12px 0;
            background: #fff;
        }
        .pl-card:hover { box-shadow: none !important; transform: none !important; }

        /* image */
        .pl-card-img {
            width: 90px !important;
            height: 90px !important;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
            margin-right: 12px;
        }
        .pl-card-img img { width: 100%; height: 100%; object-fit: cover; }

        /* body */
        .pl-card-body {
            padding: 0;
            flex: 1;
            min-width: 0;
        }
        .pl-card-stars { font-size: 11px; margin-bottom: 3px; }
        .pl-card-name {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
            -webkit-line-clamp: 2;
        }
        .pl-card-price {
            font-size: 15px;
            color: #16a34a;
            margin-bottom: 2px;
            font-weight: 800;
            letter-spacing: -.2px;
        }
        .pl-card-price-unit { font-size: 11px; }
        .pl-card-moq { font-size: 11px; margin-bottom: 6px; }
        .pl-card-supplier-loc { font-size: 11px; margin-bottom: 8px; }

        /* actions: send inquiry pill */
        .pl-card-actions {
            flex-direction: row !important;
            gap: 8px;
            align-items: center;
        }
        .pl-card-contact {
            padding: 7px 14px;
            font-size: 12px;
            border-radius: 20px;
            flex: none;
        }
        .pl-card-price-link { display: none; }

        /* hide rec section on mobile */
        .pl-rec-section { display: none; }

        /* CTA */
        .pl-cta { flex-direction: column; align-items: flex-start; padding: 16px; }
        .pl-rec-grid { grid-template-columns: repeat(2, 1fr); }

        /* - floating Filter & Sort pill - */
        .mob-filter-fab {
            position: fixed;
            bottom: 76px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 300;
            background: #4f3ff0;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 13px 28px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(79,63,240,.45);
            cursor: pointer;
            white-space: nowrap;
        }

        /* - bottom nav bar - */
        .mob-bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #fff;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 64px;
            z-index: 400;
            padding: 0 8px;
        }
        .mob-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            color: #9ca3af;
            font-weight: 500;
            text-decoration: none;
            flex: 1;
            padding: 6px 2px;
            position: relative;
        }
        .mob-nav-item.active { color: #4f3ff0; }
        .mob-nav-item .mob-badge {
            position: absolute;
            top: 4px;
            right: calc(50% - 20px);
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            min-width: 15px;
            height: 15px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px;
        }
    }

    /* hide mobile elements on desktop (≥769px) */
    @media (min-width: 769px) {
        .mob-header, .mob-chips, .mob-filter-fab, .mob-bottom-nav { display: none !important; }
    }
    /* show mobile elements on mobile */
    @media (max-width: 768px) {
        .mob-header  { display: block !important; }
        .mob-chips   { display: flex !important; }
        .mob-filter-fab  { display: flex !important; }
        .mob-bottom-nav  { display: flex !important; }
        /* hide the site-wide desktop header on mobile */
        header.bg-white, header.site-header, .topbar, nav.navbar,
        .header-main, #site-header, .main-header { display: none !important; }
    }
</style>

{{-- - MOBILE STICKY HEADER (mobile only) - --}}
<header class="mob-header">
    <div class="mob-header-top">
        <a href="{{ url('/') }}" class="mob-logo">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="h-[36px]" />
        </a>
        <div class="mob-header-icons">
            <a href="{{ route('user.inquiries') }}">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </a>
        </div>
    </div>
    <div class="mob-search-row">
        <div class="mob-search-box">
            <svg width="15" height="15" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Search industrial components..."
                value="{{ request('search') }}"
                onkeydown="if(event.key==='Enter'){window.location='{{ route('products') }}?search='+this.value}">
        </div>
        <button class="mob-filter-icon" onclick="document.getElementById('mobFilterSheet').classList.toggle('open')">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h2M9 16h6"/>
            </svg>
        </button>
    </div>
</header>

{{-- MOBILE FILTER CHIPS --}}
@php
    $isVerified = (bool) request('verified');
    $currentSort = request('sort', 'newest');
    $isPriceAsc  = $currentSort === 'price_asc';
    $isNewest    = $currentSort === 'newest';

    // Build toggle URLs — remove param when active, add when inactive
    $verifiedOnUrl  = request()->fullUrlWithQuery(['verified' => 1]);
    $verifiedOffUrl = url()->current() . '?' . http_build_query(request()->except('verified'));
    $priceAscOnUrl  = request()->fullUrlWithQuery(['sort' => 'price_asc']);
    $priceAscOffUrl = url()->current() . '?' . http_build_query(request()->except('sort'));
    $newestOnUrl    = request()->fullUrlWithQuery(['sort' => 'newest']);
    $newestOffUrl   = url()->current() . '?' . http_build_query(request()->except('sort'));
@endphp
<div class="mob-chips">
    {{-- Verified --}}
    <a href="{{ $isVerified ? $verifiedOffUrl : $verifiedOnUrl }}"
       class="mob-chip {{ $isVerified ? 'active' : '' }}">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Verified
    </a>
    {{-- Price: Low to High --}}
    <a href="{{ $isPriceAsc ? $priceAscOffUrl : $priceAscOnUrl }}"
       class="mob-chip {{ $isPriceAsc ? 'active' : '' }}">
        Price: Low to High
    </a>
    {{-- Newest --}}
    <a href="{{ $isNewest ? $newestOffUrl : $newestOnUrl }}"
       class="mob-chip {{ $isNewest ? 'active' : '' }}">
        Newest
    </a>
    {{-- High to Low --}}
    @php
        $isPriceDesc   = $currentSort === 'price_desc';
        $priceDescOnUrl  = request()->fullUrlWithQuery(['sort' => 'price_desc']);
        $priceDescOffUrl = url()->current() . '?' . http_build_query(request()->except('sort'));
    @endphp
    <a href="{{ $isPriceDesc ? $priceDescOffUrl : $priceDescOnUrl }}"
       class="mob-chip {{ $isPriceDesc ? 'active' : '' }}">
        Price: High to Low
    </a>
</div>

<div class="pl-page">


    {{-- Breadcrumb --}}
    <div class="pl-breadcrumb desk-only">
        <div class="pl-breadcrumb-inner">
            <a href="{{ url('/') }}">Home</a>
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('products') }}">Products</a>
            @if($selectedCategory)
                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('products', ['category' => $selectedCategory->id]) }}">{{ $selectedCategory->name }}</a>
            @endif
            @if(isset($selectedSubcategory) && $selectedSubcategory)
                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>{{ $selectedSubcategory->name }}</span>
            @endif
        </div>
    </div>

    <div class="pl-wrap">

        {{-- - LEFT SIDEBAR — FILTERS - --}}
        <aside class="pl-sidebar">
            <form method="GET" action="{{ route('products') }}" id="filterForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <input type="hidden" name="sort" id="sortHidden" value="{{ request('sort', 'newest') }}">

                <div class="pl-sidebar-head">
                    <div class="pl-sidebar-head-title">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h2M9 16h6"/>
                        </svg>
                        Filter Results
                    </div>
                    <a href="{{ route('products', array_filter(['category' => request('category'), 'search' => request('search')])) }}" class="pl-clear">Clear All</a>
                </div>

                {{-- VERIFIED SUPPLIER --}}
                <div class="pl-filter-section">
                    <div class="pl-filter-label">
                        Verified Supplier
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <label class="pl-verified-row {{ request('verified') ? 'active' : '' }}">
                        <span>
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Verified Only
                        </span>
                        <div class="pl-toggle">
                            <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()">
                            <span class="pl-toggle-slider"></span>
                        </div>
                    </label>
                </div>

                {{-- PRICE RANGE --}}
                <div class="pl-filter-section">
                    <div class="pl-filter-label">
                        Price Range
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="pl-price-row">
                        <input type="number" id="minPriceInput" name="min_price" placeholder="Min" class="pl-price-input" value="{{ request('min_price') }}">
                        <input type="number" id="maxPriceInput" name="max_price" placeholder="Max" class="pl-price-input" value="{{ request('max_price') }}">
                        <button type="submit" class="pl-price-go">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <input type="range" class="pl-slider" min="0" max="{{ $dbMaxPrice }}" step="1"
                        value="{{ request('max_price', $dbMaxPrice) }}"
                        oninput="document.getElementById('maxPriceInput').value = this.value">
                </div>

                {{-- MIN ORDER QUANTITY --}}
                <div class="pl-filter-section">
                    <div class="pl-filter-label">
                        Min. Order Quantity
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="pl-checkbox-list">
                        @foreach([
                            ['label' => 'No Minimum', 'val' => ''],
                            ['label' => 'Up to 10',   'val' => 'moq_10'],
                            ['label' => '10 - 100',   'val' => 'moq_100'],
                            ['label' => '100 - 500',  'val' => 'moq_500'],
                            ['label' => '500+',       'val' => 'moq_plus'],
                        ] as $moq)
                        <label class="pl-checkbox-item">
                            <input type="checkbox" name="moq[]" value="{{ $moq['val'] }}"
                                {{ in_array($moq['val'], (array)request('moq', [])) ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()">
                            {{ $moq['label'] }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- SELLER LOCATION --}}
                <div class="pl-filter-section">
                    <div class="pl-filter-label">
                        Seller Location
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="pl-checkbox-list">
                        @foreach(['Gujarat','Maharashtra','Karnataka','Tamil Nadu','Delhi NCR'] as $loc)
                        <label class="pl-checkbox-item">
                            <input type="checkbox" name="location[]" value="{{ $loc }}"
                                {{ in_array($loc, (array)request('location', [])) ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()">
                            {{ $loc }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- BUSINESS TYPE --}}
                <div class="pl-filter-section">
                    <div class="pl-filter-label">
                        Business Type
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="pl-checkbox-list">
                        @foreach(['Manufacturer','Wholesaler','Export House','Service Provider'] as $bt)
                        <label class="pl-checkbox-item">
                            <input type="checkbox" name="btype[]" value="{{ $bt }}"
                                {{ in_array($bt, (array)request('btype', [])) ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()">
                            {{ $bt }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- CLEAR ALL FILTERS --}}
                <div style="padding:12px 18px 16px;">
                    <a href="{{ route('products', array_filter(['category' => request('category'), 'search' => request('search')])) }}" class="pl-clear">Clear All Filters</a>
                </div>

            </form>

            {{-- Promo box --}}
            <div class="pl-sidebar-promo">
                <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24" style="opacity:.9;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                </svg>
                <h4>Start Sourcing with Pro-Trust Membership</h4>
                <p>Get exclusive access to pre-vetted top-tier manufacturers globally.</p>
                <a href="{{ route('post-requirement') }}">Upgrade Now</a>
            </div>
        </aside>

        {{-- - MAIN CONTENT - --}}
        <div class="pl-main">

            {{-- Heading bar --}}
            <div class="pl-heading-bar">
                <h1>
                    @if(isset($selectedSubcategory) && $selectedSubcategory)
                        {{ $selectedSubcategory->name }}
                    @elseif($selectedCategory)
                        {{ $selectedCategory->name }}
                    @else
                        All Products
                    @endif
                </h1>
                <div class="pl-showing">Showing {{ $products->total() }} Products</div>
                <div class="pl-controls">
                    <div class="pl-sort">
                        <span style="font-size:11px;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.8px;">SORT BY:</span>
                        <select onchange="document.getElementById('sortHidden').value=this.value; document.getElementById('filterForm').submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
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

            {{-- Product Grid --}}
            @if($products->isNotEmpty())
                <div class="pl-grid">
                    @foreach($products as $p)
                        @php
                            $img = is_array($p->images) && count($p->images) ? $p->images[0] : null;
                            $bizProfile = $p->user?->businessProfile;
                            $bizName = $bizProfile?->business_name ?? ($p->user?->name ?? 'Supplier');
                            $city = $bizProfile?->city_name ?? '';
                            $productCountryCode = strtoupper($p->country_code ?? $bizProfile?->country_code ?? session('country', 'IN'));
                        @endphp
                        <div class="pl-card">
                            {{-- Image --}}
                            <div class="pl-card-img">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $p->name }}" loading="lazy">
                                @else
                                    <div class="pl-card-img-placeholder">
                                        <svg width="48" height="48" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                                <span class="pl-verified-badge">
                                    <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4"/>
                                    </svg>
                                    Verified
                                </span>
                            </div>

                            {{-- Body --}}
                            <div class="pl-card-body">
                                {{-- Stars (static for now) --}}
                                <div class="pl-card-stars">
                                    ★ ★ ★ ★ ☆
                                    <span>(24 Reviews)</span>
                                </div>

                                <div class="pl-card-name">{{ $p->name }}</div>

                                @if($p->price)
                                    <div class="pl-card-price">
                                        {{ $p->display_price_formatted }}
                                        <span class="pl-card-price-unit">/ Piece</span>
                                    </div>
                                @else
                                    <div class="pl-card-price">Get Best Price</div>
                                @endif

                                <div class="pl-card-moq" style="margin-bottom:10px;">MOQ: 1 Pieces</div>

                                <div style="margin-bottom:10px;">
                                    @include('components.country-badge', [
                                        'countryCode' => $productCountryCode,
                                        'showName' => false,
                                        'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                    ])
                                </div>

                                <div class="pl-card-supplier-loc">
                                    <svg width="13" height="13" fill="none" stroke="#374151" viewBox="0 0 24 24" style="flex-shrink:0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <a href="{{ route('seller.show', $p->user_id) }}" class="hover:text-amber-600 transition-colors" title="View Profile">{{ Str::limit($bizName, 28) }}</a>
                                    @if($city)
                                        <svg width="10" height="10" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="flex-shrink:0;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="loc">{{ strtoupper($city) }}</span>
                                    @endif
                                </div>

                                <div class="pl-card-actions">
                                    <a href="{{ route('products.show', $p->id) }}" class="pl-card-contact">Contact Supplier</a>
                                    <a href="{{ route('products.show', $p->id) }}" class="pl-card-price-link">Get Latest Price</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pl-pagination">
                    {{ $products->links() }}
                </div>

            @else
                <div class="pl-empty">
                    <svg width="56" height="56" fill="none" stroke="#d1d5db" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters or browse other categories.</p>
                </div>
            @endif

            {{-- Recommended Product Categories --}}
            @if($allCategories->isNotEmpty())
            <div class="pl-rec-section">
                <div class="pl-rec-title">Recommended Product Categories</div>
                <div class="pl-rec-grid">
                    @foreach($allCategories->take(8) as $cat)
                        <a href="{{ route('products', ['category' => $cat->id]) }}"
                           class="pl-rec-item">{{ Str::limit($cat->name, 30) }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- CTA Banner --}}
            <div class="pl-cta">
                <div class="pl-cta-left">
                    <div class="pl-cta-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Can't find what you are looking for?</h4>
                        <p>Post a Buying Request and let verified suppliers come to you with their best quotes.</p>
                    </div>
                </div>
                <a href="{{ route('post-requirement') }}" class="pl-cta-btn">Post Buying Request</a>
            </div>

        </div>{{-- /pl-main --}}
    </div>{{-- /pl-wrap --}}
</div>{{-- /pl-page --}}

{{-- - MOBILE FLOATING FILTER BUTTON - --}}
<button class="mob-filter-fab" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M9 16h6"/>
    </svg>
    Filter &amp; Sort
</button>

{{-- - MOBILE BOTTOM NAV - --}}
<nav class="mob-bottom-nav">
    <a href="{{ url('/') }}" class="mob-nav-item">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Home
    </a>
    <a href="{{ route('categories.all') }}" class="mob-nav-item active">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        Categories
    </a>
    <a href="{{ route('user.inquiries') }}" class="mob-nav-item">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        Inquiries
    </a>
    <a href="{{ route('user.dashboard') }}" class="mob-nav-item">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Account
    </a>
</nav>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridBtn = document.querySelector('.pl-view-btn[title="Grid View"]');
    const listBtn = document.querySelector('.pl-view-btn[title="List View"]');
    const container = document.querySelector('.pl-grid');

    if (!gridBtn || !listBtn || !container) return;

    // Load saved view
    const savedView = localStorage.getItem('product-view') || 'grid';
    if (savedView === 'list') {
        enableListView();
    }

    gridBtn.addEventListener('click', function() {
        enableGridView();
    });

    listBtn.addEventListener('click', function() {
        enableListView();
    });

    function enableGridView() {
        container.classList.remove('pl-list-view');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        localStorage.setItem('product-view', 'grid');
    }

    function enableListView() {
        container.classList.add('pl-list-view');
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
        localStorage.setItem('product-view', 'list');
    }
});
</script>
@endpush
