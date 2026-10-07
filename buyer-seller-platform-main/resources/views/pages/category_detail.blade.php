@extends('layouts.app')

@section('content')
    <style>
        /* ─── HERO BANNER ─── */
        .cd-hero {
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1d4ed8 100%);
            color: #fff;
            overflow: hidden;
        }

        .cd-hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 56px 24px 48px;
            position: relative;
            z-index: 2;
        }

        .cd-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 11px;
            letter-spacing: 1px;
            font-weight: 700;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 20px;
        }

        .cd-hero-title {
            font-size: clamp(28px, 4vw, 52px);
            font-weight: 900;
            line-height: 1.1;
            margin: 0 0 16px;
            max-width: 640px;
        }

        .cd-hero-title span {
            color: #60a5fa;
        }

        .cd-hero-desc {
            font-size: 15px;
            color: #bfdbfe;
            max-width: 520px;
            line-height: 1.7;
            margin: 0 0 28px;
        }

        .cd-hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .cd-hero-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #1e3a5f;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
        }

        .cd-hero-btn-primary:hover {
            background: #eff6ff;
            transform: translateY(-1px);
        }

        .cd-hero-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
        }

        .cd-hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .cd-hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
        }

        .cd-hero-stat-num {
            font-size: 24px;
            font-weight: 900;
            color: #fff;
        }

        .cd-hero-stat-lbl {
            font-size: 12px;
            color: #93c5fd;
            margin-top: 2px;
        }

        .cd-hero-deco {
            position: absolute;
            right: -60px;
            top: -60px;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
        }

        .cd-hero-deco2 {
            position: absolute;
            left: 30%;
            bottom: -80px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
        }

        /* ─── TRUST BAR ─── */
        .cd-trust-bar {
            background: #1e293b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .cd-trust-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            gap: 24px;
            align-items: center;
            overflow-x: auto;
            scrollbar-width: none;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
        }

        .cd-trust-inner::-webkit-scrollbar {
            display: none;
        }

        .cd-trust-item {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .cd-trust-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ─── BREADCRUMB ─── */
        .cd-breadcrumb {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .cd-breadcrumb-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
        }

        .cd-breadcrumb-inner a {
            color: #6b7280;
            text-decoration: none;
        }

        .cd-breadcrumb-inner a:hover {
            color: #2563eb;
        }

        /* ─── LAYOUT ─── */
        .cd-layout-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px 64px;
            display: flex;
            gap: 32px;
            align-items: flex-start;
        }

        /* ─── SIDEBAR ─── */
        .cd-sidebar {
            width: 220px;
            flex-shrink: 0;
        }

        .cd-sidebar-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .cd-sidebar-head {
            padding: 14px 16px 10px;
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cd-sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 13px;
            color: #374151;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s;
        }

        .cd-sidebar-link:hover {
            background: #f0f9ff;
            color: #1d4ed8;
        }

        .cd-sidebar-link.active {
            background: #eff6ff;
            border-left-color: #3b82f6;
            color: #1d4ed8;
            font-weight: 700;
        }

        .cd-sidebar-promo {
            background: linear-gradient(135deg, #1d4ed8, #7c3aed);
            border-radius: 12px;
            padding: 18px;
            color: #fff;
        }

        .cd-sidebar-promo h4 {
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .cd-sidebar-promo p {
            font-size: 12px;
            color: #c7d2fe;
            margin: 0 0 14px;
            line-height: 1.5;
        }

        .cd-sidebar-promo a {
            display: block;
            text-align: center;
            background: #fff;
            color: #1d4ed8;
            padding: 9px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        /* ─── MAIN CONTENT ─── */
        .cd-main {
            flex: 1;
            min-width: 0;
        }

        /* ─── SUBCATEGORY SECTION ─── */
        .cd-section {
            margin-bottom: 40px;
        }

        .cd-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
        }

        .cd-section-title {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cd-section-title-dot {
            width: 4px;
            height: 20px;
            background: #3b82f6;
            border-radius: 2px;
        }

        .cd-section-viewall {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            transition: gap .15s;
        }

        .cd-section-viewall:hover {
            gap: 8px;
            color: #1d4ed8;
        }

        /* Product horizontal scroll row */
        .cd-products-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .cd-product-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .cd-product-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.10);
            transform: translateY(-2px);
        }

        .cd-product-img {
            height: 140px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .cd-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cd-product-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .cd-product-body {
            padding: 12px;
        }

        .cd-product-name {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cd-product-supplier {
            font-size: 11px;
            color: #9ca3af;
            margin: 0 0 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cd-product-price {
            font-size: 13px;
            font-weight: 800;
            color: #1d4ed8;
        }

        .cd-product-units {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }

        /* ─── PROMO BANNER ─── */
        .cd-promo-banner {
            background: linear-gradient(135deg, #1d4ed8, #7c3aed);
            border-radius: 16px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin: 40px 0;
            color: #fff;
        }

        .cd-promo-content h3 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .cd-promo-content p {
            font-size: 13px;
            color: #c7d2fe;
            margin: 0;
            line-height: 1.6;
        }

        .cd-promo-btn {
            flex-shrink: 0;
            background: #fff;
            color: #1d4ed8;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s;
        }

        .cd-promo-btn:hover {
            background: #eff6ff;
        }

        /* ─── EMPTY STATE ─── */
        .cd-empty {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .cd-empty svg {
            margin-bottom: 16px;
        }

        .cd-empty h3 {
            font-size: 18px;
            font-weight: 700;
            color: #374151;
            margin: 0 0 8px;
        }

        .cd-empty p {
            font-size: 14px;
            margin: 0 0 20px;
        }

        .cd-empty a {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
        }


        /* ─── SUBCATEGORY CARD GRID (image cards style) ─── */
        .cd-subcat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .cd-subcat-card {
            display: block;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: #111827;
            transition: box-shadow .2s, transform .2s;
        }

        .cd-subcat-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.13);
            transform: translateY(-3px);
        }

        /* Image area */
        .cd-subcat-img {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            background: linear-gradient(135deg, #e0e7ef 0%, #c7d2e3 100%);
            overflow: hidden;
        }

        .cd-subcat-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Placeholder icon when no image */
        .cd-subcat-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f4ff 0%, #dbe4ff 100%);
        }

        /* Badge */
        .cd-subcat-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: .4px;
        }

        /* Text area */
        .cd-subcat-body {
            padding: 12px 14px 14px;
        }

        .cd-subcat-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .cd-subcat-count {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Hidden on desktop, shown only on mobile via media query */
        .cd-subcat-btn {
            display: none;
        }


        /* ─── TABLET (901-1024px) ─── */
        @media (max-width: 1024px) and (min-width: 769px) {
            .cd-layout-wrap {
                padding: 24px 20px 48px;
                gap: 24px;
            }

            .cd-sidebar {
                width: 200px;
            }

            .cd-products-row {
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
            }

            .cd-hero-inner {
                padding: 40px 20px 36px;
            }
        }

        /* ─── MOBILE ONLY ELEMENTS ─── */
        .mob-only {
            display: none;
        }

        .desk-only {
            display: block;
        }

        .desk-flex {
            display: flex;
        }

        /* ─── FULL MOBILE (<= 768px) ─── */
        @media (max-width: 768px) {

            /* Hide desktop-specific elements */
            .desk-only {
                display: none !important;
            }

            .desk-flex {
                display: none !important;
            }

            .mob-only {
                display: block;
            }

            /* Hide desktop hero/trust bar sections — replaced by compact header */
            .cd-hero,
            .cd-trust-bar,
            .cd-breadcrumb {
                display: none;
            }

            /* ── Mobile top header bar ── */
            .mob-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                background: #fff;
                border-bottom: 1px solid #e5e7eb;
                position: sticky;
                top: 0;
                z-index: 50;
            }

            .mob-header-back {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #f3f4f6;
                color: #374151;
                text-decoration: none;
                flex-shrink: 0;
            }

            .mob-header-title {
                font-size: 16px;
                font-weight: 800;
                color: #111827;
                text-align: center;
                flex: 1;
                margin: 0 12px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mob-header-search {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #f3f4f6;
                color: #374151;
                text-decoration: none;
                flex-shrink: 0;
            }

            /* ── Filter chips ── */
            .mob-chips-wrap {
                padding: 12px 16px 8px;
                background: #fff;
                border-bottom: 1px solid #f3f4f6;
                display: flex;
                gap: 8px;
                overflow-x: auto;
                scrollbar-width: none;
            }

            .mob-chips-wrap::-webkit-scrollbar {
                display: none;
            }

            .mob-chip {
                flex-shrink: 0;
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 600;
                white-space: nowrap;
                cursor: pointer;
                border: 1.5px solid #e5e7eb;
                background: #fff;
                color: #374151;
                text-decoration: none;
            }

            .mob-chip.active {
                background: #7c3aed;
                border-color: #7c3aed;
                color: #fff;
            }

            /* ── Layout: no sidebar, full width content ── */
            .cd-layout-wrap {
                display: block;
                padding: 0 0 80px;
            }

            .cd-sidebar {
                display: none;
            }

            .cd-main {
                padding: 0;
            }

            /* ── Mobile section (per subcategory) ── */
            .cd-section {
                margin-bottom: 0;
                padding: 20px 16px 16px;
                border-bottom: 8px solid #f3f4f6;
            }

            .cd-section-header {
                margin-bottom: 14px;
                padding-bottom: 0;
                border-bottom: none;
            }

            .cd-section-title {
                font-size: 16px;
                font-weight: 800;
                color: #111827;
            }

            .cd-section-title-dot {
                display: none;
            }

            .cd-section-viewall {
                font-size: 13px;
                font-weight: 600;
                color: #7c3aed;
            }

            .cd-section-viewall:hover {
                color: #6d28d9;
            }

            /* ── 2-column product image grid ── */
            .cd-products-row {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .cd-product-card {
                border-radius: 10px;
            }

            .cd-product-img {
                height: 130px;
                border-radius: 10px 10px 0 0;
            }

            .cd-product-body {
                padding: 10px;
            }

            .cd-product-name {
                font-size: 12px;
                margin-bottom: 3px;
            }

            .cd-product-price {
                font-size: 12px;
            }

            .cd-product-supplier {
                font-size: 10px;
            }

            /* ── Mobile promo banner ── */
            .cd-promo-banner {
                margin: 20px 16px;
                padding: 20px;
                border-radius: 14px;
                flex-direction: row;
                align-items: center;
                gap: 16px;
                background: linear-gradient(135deg, #7c3aed, #4f46e5);
            }

            .cd-promo-content h3 {
                font-size: 15px;
            }

            .cd-promo-content p {
                font-size: 12px;
            }

            .cd-promo-btn {
                padding: 10px 18px;
                font-size: 12px;
                border-radius: 8px;
            }

            /* ── Empty state ── */
            .cd-empty {
                padding: 40px 20px;
            }

            /* ── Mobile subcategory: vertical list view ── */
            .cd-subcat-grid {
                display: flex;
                flex-direction: column;
                gap: 0;
            }

            .cd-subcat-card {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 14px;
                border-radius: 0;
                border-bottom: 1px solid #f3f4f6;
                padding: 14px 16px;
                background: #fff;
            }

            .cd-subcat-card:first-child {
                border-top: 1px solid #f3f4f6;
            }

            .cd-subcat-card:hover {
                transform: none;
                box-shadow: none;
                background: #f9fafb;
            }

            /* Left image area — small square */
            .cd-subcat-img {
                width: 88px;
                height: 88px;
                flex-shrink: 0;
                border-radius: 10px;
                aspect-ratio: 1/1;
            }

            .cd-subcat-img-placeholder {
                border-radius: 10px;
            }

            /* Badge repositioned inside image */
            .cd-subcat-badge {
                top: 6px;
                left: 6px;
                font-size: 9px;
                padding: 2px 7px;
            }

            /* Right body */
            .cd-subcat-body {
                flex: 1;
                min-width: 0;
                padding: 0;
            }

            .cd-subcat-name {
                font-size: 15px;
                font-weight: 700;
                margin-bottom: 4px;
                white-space: normal;
            }

            .cd-subcat-count {
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 10px;
            }

            /* "Send Inquiry" button */
            .cd-subcat-btn {
                display: inline-block;
                background: #2563eb;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                padding: 7px 14px;
                border-radius: 8px;
                text-decoration: none;
                white-space: nowrap;
            }

            /* ── Mobile bottom nav ── */
            .mob-bottom-nav {
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

            .mob-nav-item {
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

            .mob-nav-item svg {
                width: 22px;
                height: 22px;
            }

            .mob-nav-item.active {
                color: #7c3aed;
            }

            /* ── Mobile list-style section (for categories without subcategory products) ── */
            .mob-list-section {
                padding: 20px 16px 16px;
            }

            .mob-list-item {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 14px 0;
                border-bottom: 1px solid #f3f4f6;
                text-decoration: none;
                color: inherit;
            }

            .mob-list-item:last-child {
                border-bottom: none;
            }

            .mob-list-icon {
                width: 44px;
                height: 44px;
                background: #f3f4f6;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .mob-list-info {
                flex: 1;
                min-width: 0;
            }

            .mob-list-name {
                font-size: 14px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 2px;
            }

            .mob-list-desc {
                font-size: 12px;
                color: #9ca3af;
            }

            .mob-list-arrow {
                width: 28px;
                height: 28px;
                background: #f3f4f6;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
        }

        @media (max-width: 400px) {
            .cd-products-row {
                gap: 8px;
            }

            .cd-product-img {
                height: 110px;
            }
        }
    </style>


    {{-- ══════════════ MOBILE-ONLY ELEMENTS ══════════════ --}}

    {{-- Mobile sticky header --}}
    <div class="mob-only mob-header">
        <a href="{{ route('categories.all') }}" class="mob-header-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div class="mob-header-title">{{ Str::limit($category->name, 26) }}</div>
        <a href="{{ route('search') }}" class="mob-header-search">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
            </svg>
        </a>
    </div>

    {{-- Mobile filter chips (All + each subcategory) --}}
    <div class="mob-only mob-chips-wrap">
        <span class="mob-chip active">All {{ Str::words($category->name, 2, '') }}</span>
        @foreach($subcategories->take(6) as $sub)
            <a href="#section-{{ $sub->id }}" class="mob-chip"
                onclick="document.querySelectorAll('.mob-chip').forEach(c=>c.classList.remove('active'));this.classList.add('active');">
                {{ Str::limit($sub->name, 16) }}
            </a>
        @endforeach
    </div>

    {{-- ══════════════ DESKTOP: HERO BANNER ══════════════ --}}
    <section class="cd-hero">

        <div class="cd-hero-deco"></div>
        <div class="cd-hero-deco2"></div>
        <div class="cd-hero-inner">
            <div class="cd-hero-badge">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                Industrial Category
            </div>
            <h1 class="cd-hero-title">{{ $category->name }}</h1>
            <p class="cd-hero-desc">Discover verified suppliers and manufacturers across all sub-sectors. Trusted by 10,000+
                enterprises worldwide.</p>
            <div class="cd-hero-btns">
                <a href="{{ route('post-requirement') }}" class="cd-hero-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Talk to an Expert
                </a>
                <a href="{{ route('products', ['category' => $category->id]) }}" class="cd-hero-btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Download Catalog
                </a>
            </div>
            <div class="cd-hero-stats">
                <div>
                    <div class="cd-hero-stat-num">{{ $subcategories->count() }}+</div>
                    <div class="cd-hero-stat-lbl">Sub-categories</div>
                </div>
                <div>
                    <div class="cd-hero-stat-num">50K+</div>
                    <div class="cd-hero-stat-lbl">Verified Suppliers</div>
                </div>
                <div>
                    <div class="cd-hero-stat-num">120+</div>
                    <div class="cd-hero-stat-lbl">Countries Served</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── TRUST BAR ─── --}}
    <div class="cd-trust-bar">
        <div class="cd-trust-inner">
            <div class="cd-trust-item"><span class="cd-trust-dot"></span> MSME Verified Suppliers</div>
            <div class="cd-trust-item"><span class="cd-trust-dot"></span> ISO Certified Manufacturers</div>
            <div class="cd-trust-item"><span class="cd-trust-dot"></span> Trade Assurance Available</div>
            <div class="cd-trust-item"><span class="cd-trust-dot"></span> Instant Inquiry Response</div>
        </div>
    </div>

    {{-- ─── BREADCRUMB ─── --}}
    <div class="cd-breadcrumb">
        <div class="cd-breadcrumb-inner">
            <a href="{{ url('/') }}">Home</a>
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('categories.all') }}">All Categories</a>
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span style="color:#111827;font-weight:600;">{{ $category->name }}</span>
        </div>
    </div>

    {{-- ─── MAIN LAYOUT ─── --}}
    <div class="cd-layout-wrap">

        {{-- ─── SIDEBAR ─── --}}
        <aside class="cd-sidebar">
            {{-- Other categories --}}
            <div class="cd-sidebar-box">
                <div class="cd-sidebar-head">Other Categories</div>
                <nav>
                    @foreach($allCategories->take(8) as $cat)
                        <a href="{{ route('category.detail', $cat->id) }}" class="cd-sidebar-link">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                            </svg>
                            {{ Str::limit($cat->name, 24) }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Promo Box --}}
            <div class="cd-sidebar-promo">
                <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24"
                    style="margin-bottom:10px;opacity:.8;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h4>Procurement Pro</h4>
                <p>Get the best quotes from verified suppliers in your region.</p>
                <a href="{{ route('post-requirement') }}">Learn More</a>
            </div>

            {{-- Support box --}}
            <div style="margin-top:16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <svg width="16" height="16" fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span style="font-size:12px;font-weight:800;color:#15803d;">Support Center</span>
                </div>
                <p style="font-size:12px;color:#166534;margin:0 0 10px;line-height:1.5;">Contact specialized sourcing agents
                    for {{ Str::limit($category->name, 20) }} procurement.</p>
                <a href="{{ route('post-requirement') }}"
                    style="font-size:12px;font-weight:700;color:#16a34a;text-decoration:none;">Get Support →</a>
            </div>
        </aside>

        {{-- ─── MAIN CONTENT ─── --}}
        <main class="cd-main">

            {{-- Single section: Category name as title, subcategories as cards --}}
            <div class="cd-section">
                <div class="cd-section-header">
                    <div class="cd-section-title">
                        <div class="cd-section-title-dot"></div>
                        {{ $category->name }}
                    </div>
                    <a href="{{ route('products', ['category' => $category->id]) }}" class="cd-section-viewall">
                        View All Products
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                {{-- Subcategory image-cards grid --}}
                @if($subcategories->isNotEmpty())
                    <div class="cd-subcat-grid">
                        @foreach($subcategories as $sub)
                            @php
                                $subImage = $sub->image ?? null;
                            @endphp
                            <a href="{{ route('products', ['category' => $category->id, 'subcategory' => $sub->id]) }}"
                                class="cd-subcat-card">
                                {{-- Image area --}}
                                <div class="cd-subcat-img">
                                    @if($subImage)
                                        <img src="{{ $subImage }}" alt="{{ $sub->name }}" loading="lazy">
                                    @else
                                        <div class="cd-subcat-img-placeholder">
                                            <svg width="48" height="48" fill="none" stroke="#93c5fd" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if($loop->first)
                                        <span class="cd-subcat-badge">Best Seller</span>
                                    @endif
                                </div>
                                {{-- Body --}}
                                <div class="cd-subcat-body">
                                    <div class="cd-subcat-name">{{ $sub->name }}</div>
                                    <div class="cd-subcat-count">
                                        {{ $sub->products_count ?? 0 }} Models
                                    </div>
                                    <span class="cd-subcat-btn">Send Inquiry</span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                @else
                    <div class="cd-empty">
                        <svg width="56" height="56" fill="none" stroke="#d1d5db" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3>No Sub-categories Yet</h3>
                        <p>Sub-categories for <strong>{{ $category->name }}</strong> will appear here.</p>
                        <a href="{{ route('post-requirement') }}">Post Your Requirement</a>
                    </div>
                @endif
            </div>

            {{-- Promo banner --}}
            <div class="cd-promo-banner">
                <div class="cd-promo-content">
                    <h3>Scale Your Production with AI-Driven Logistics</h3>
                    <p>Ship directly to factories and save up to 40% on shipments.</p>
                </div>
                <a href="{{ route('post-requirement') }}" class="cd-promo-btn">Request Quote</a>
            </div>

        </main>
    </div>

    <script>
        function highlightSection(subId) {
            // smooth scroll + highlight active link (desktop sidebar)
            document.querySelectorAll('.cd-sidebar-link').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');
            const target = document.getElementById('section-' + subId);
            if (target) {
                const offset = 120; // account for mobile sticky header
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        }
    </script>

    {{-- ══════════════ MOBILE BOTTOM NAV ══════════════ --}}
    <nav class="mob-only mob-bottom-nav">
        <a href="{{ url('/') }}" class="mob-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Home
        </a>
        <a href="{{ route('search') }}" class="mob-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
            </svg>
            Search
        </a>
        <a href="{{ route('post-requirement') }}" class="mob-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
            </svg>
            Post
        </a>
        <a href="{{ route('user.inquiries') }}" class="mob-nav-item" style="position:relative;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span
                style="position:absolute;top:8px;right:22%;background:#ef4444;color:#fff;font-size:8px;font-weight:700;padding:1px 5px;border-radius:6px;">2</span>
            Messages
        </a>
        <a href="{{ auth()->check() ? url('/user/profile') : url('/login') }}" class="mob-nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>
    </nav>

@endsection