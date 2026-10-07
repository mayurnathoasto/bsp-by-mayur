@extends('layouts.app')

@section('title', $title ?? 'Seller Profile')

@section('content')
<style>
    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       SELLER PROFILE â€” PREMIUM REDESIGN
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .sp-page {
        background: #f0f2f5;
        min-height: 100vh;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        padding-bottom: 60px;
    }

    /* â”€â”€ Hero Banner â”€â”€ */
    .sp-hero {
        position: relative;
        background: linear-gradient(135deg, #0b2a4a 0%, #163d67 50%, #1e5480 100%);
        overflow: hidden;
    }
    .sp-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 70% 50%, rgba(255,106,0,0.12) 0%, transparent 60%),
                          radial-gradient(circle at 20% 80%, rgba(37,99,235,0.15) 0%, transparent 50%);
    }
    .sp-hero-inner {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 28px 32px;
        display: flex;
        align-items: flex-end;
        gap: 28px;
        z-index: 2;
    }
    .sp-logo-wrap {
        position: relative;
        flex-shrink: 0;
    }
    .sp-logo-box {
        width: 100px;
        height: 100px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 900;
        color: #0b2a4a;
        text-transform: uppercase;
        overflow: hidden;
        border: 3px solid rgba(255,255,255,0.9);
    }
    .sp-logo-box img { width: 100%; height: 100%; object-fit: cover; }
    .sp-logo-badge {
        position: absolute;
        bottom: -6px;
        right: -6px;
        background: #16a34a;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-hero-text { flex: 1; color: #fff; }
    .sp-hero-title {
        font-size: 28px;
        font-weight: 900;
        margin: 0 0 10px;
        line-height: 1.15;
        letter-spacing: -0.3px;
    }
    .sp-hero-tags {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .sp-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1.5px solid rgba(255,255,255,0.25);
        color: rgba(255,255,255,0.9);
        backdrop-filter: blur(4px);
        background: rgba(255,255,255,0.08);
    }
    .sp-tag.verified {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    /* â”€â”€ Stats Strip â”€â”€ */
    .sp-stats-strip {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .sp-stats-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 28px;
        display: flex;
        align-items: stretch;
    }
    .sp-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 28px 16px 0;
        margin-right: 28px;
        border-right: 1px solid #f3f4f6;
    }
    .sp-stat-item:last-child { border-right: none; }
    .sp-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .sp-stat-icon.orange { background: #fff7f0; color: #ff6a00; }
    .sp-stat-icon.blue   { background: #eff6ff; color: #2563eb; }
    .sp-stat-icon.green  { background: #f0fdf4; color: #16a34a; }
    .sp-stat-text {}
    .sp-stat-num {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        line-height: 1;
    }
    .sp-stat-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 600;
        margin-top: 2px;
    }

    /* â”€â”€ Main Layout â”€â”€ */
    .sp-wrap {
        max-width: 1200px;
        margin: 28px auto 0;
        padding: 0 28px;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* â”€â”€ Sidebar Cards â”€â”€ */
    .sp-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }
    .sp-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
        font-weight: 800;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }
    .sp-card-header svg { color: #ff6a00; }
    .sp-collapse-btn {
        margin-left: auto;
        display: none;
        color: #94a3b8;
        transition: transform 0.3s;
    }
    .sp-collapse-btn.rot { transform: rotate(180deg); }

    @media (max-width: 768px) {
        .sp-collapse-btn { display: block; }
        .sp-card-header { cursor: pointer; }
    }
    .sp-card-body { padding: 16px 18px; }

    .sp-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f9fafb;
        font-size: 13px;
    }
    .sp-info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .sp-info-key { color: #6b7280; font-weight: 500; }
    .sp-info-val { color: #111827; font-weight: 700; text-align: right; max-width: 55%; }

    .sp-response-bar {
        background: #f3f4f6;
        border-radius: 999px;
        height: 6px;
        overflow: hidden;
        margin-top: 6px;
    }
    .sp-response-fill {
        height: 100%;
        background: linear-gradient(90deg, #16a34a, #4ade80);
        border-radius: 999px;
    }

    .sp-contact-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: linear-gradient(135deg, #ff6a00, #ff8533);
        color: #fff;
        text-align: center;
        padding: 13px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(255,106,0,0.3);
    }
    .sp-contact-btn:hover {
        background: linear-gradient(135deg, #e65c00, #ff6a00);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255,106,0,0.4);
    }

    /* Trust badges */
    .sp-trust-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .sp-trust-item {
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 10px;
        padding: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
    }
    .sp-trust-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-trust-label { font-size: 10px; font-weight: 700; color: #6b7280; }
    .sp-trust-val { font-size: 12px; font-weight: 800; color: #111827; }

    /* â”€â”€ Product Section â”€â”€ */
    .sp-products-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .sp-products-title {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }
    .sp-products-count {
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        background: #ff6a00;
        padding: 4px 12px;
        border-radius: 20px;
    }

    /* â”€â”€ Product Grid â”€â”€ */
    .pl-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .pl-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s, border-color .2s;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .pl-card:hover {
        box-shadow: 0 10px 32px rgba(0,0,0,.1);
        transform: translateY(-3px);
        border-color: #ff6a00;
    }
    .pl-card-img {
        position: relative;
        height: 170px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid #f3f4f6;
    }
    .pl-card-img img {
        max-width: 100%;
        max-height: 170px;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
        transition: transform 0.3s;
    }
    .pl-card:hover .pl-card-img img { transform: scale(1.05); }
    .pl-no-img {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        color: #cbd5e1;
    }
    .pl-no-img-text { font-size: 11px; font-weight: 600; color: #94a3b8; }
    .pl-card-body {
        padding: 14px 14px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .pl-cat-badge {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #2563eb;
        background: #eff6ff;
        border-radius: 4px;
        padding: 2px 8px;
        display: inline-block;
        margin-bottom: 8px;
    }
    .pl-card-name {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    .pl-card-price {
        font-size: 17px;
        font-weight: 900;
        color: #0b2a4a;
        line-height: 1;
    }
    .pl-card-price-unit {
        font-size: 11px;
        font-weight: 500;
        color: #9ca3af;
        margin-left: 2px;
    }
    .pl-card-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 12px;
        background: #0b2a4a;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 9px;
        border-radius: 8px;
        text-align: center;
        transition: background .15s;
        letter-spacing: 0.2px;
    }
    .pl-card:hover .pl-card-cta { background: #ff6a00; }

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

    /* List View Styles */
    .pl-grid.pl-list-view {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .pl-grid.pl-list-view .pl-card {
        flex-direction: row;
        height: 180px;
    }
    .pl-grid.pl-list-view .pl-card-img {
        width: 240px;
        height: 180px;
        border-bottom: none;
        border-right: 1px solid #f3f4f6;
    }
    .pl-grid.pl-list-view .pl-card-body {
        padding: 20px;
    }
    .pl-grid.pl-list-view .pl-card-name {
        font-size: 16px;
        margin-bottom: 8px;
    }
    .pl-grid.pl-list-view .pl-card-price {
        font-size: 20px;
    }
    .pl-grid.pl-list-view .pl-card-cta {
        width: fit-content;
        padding: 10px 24px;
        margin-top: auto;
    }

    /* Tabs Menu */
    .sp-tabs-nav {
        display: flex;
        gap: 8px;
        background: #fff;
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .sp-tabs-nav::-webkit-scrollbar { display: none; }
    .sp-tab-btn {
        background: transparent;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 700;
        color: #6b7280;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
        white-space: nowrap;
        outline: none;
    }
    .sp-tab-btn:hover { background: #f3f4f6; color: #111827; }
    .sp-tab-btn.active { background: #ff6a00; color: #fff; }

    /* â”€â”€ Responsive â”€â”€ */
    @media (max-width: 960px) {
        .sp-wrap { grid-template-columns: 1fr; margin-top: 20px; }
        .sp-hero-inner { padding-top: 36px; }
        .sp-logo-box { width: 80px; height: 80px; font-size: 30px; }
        .sp-hero-title { font-size: 22px; }
        .sp-stats-inner { gap: 0; overflow-x: auto; }
        .sp-stat-item { min-width: 140px; }
        .pl-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 550px) {
        .pl-grid { grid-template-columns: 1fr; }
        .sp-hero-inner { flex-direction: column; align-items: flex-start; gap: 14px; padding-bottom: 24px; }
        .sp-trust-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $biz = $seller->businessProfile;
    $companyName   = $biz?->company_name ?? $seller->name;
    $initial       = strtoupper(substr($companyName, 0, 1));
    // Prioritize logo from Additional Details tab, fall back to company_logo_path
    $logoPath      = $biz?->logo ?? $biz?->company_logo_path ?? null;
    $totalProducts = $products->total();
    $yearsInBusiness = $biz?->year_established
        ? (date('Y') - (int)$biz->year_established) . '+ Years'
        : date('Y', strtotime($seller->created_at)) . '+';

    // Additional Details data
    $companyDesc   = $biz?->company_description ?? null;
    $companyPhotos = $biz?->company_photos ?? [];
    $videoUrl      = $biz?->video_url ?? null;
    $videoTitle    = $biz?->video_title ?? null;
    $paymentModes  = $biz?->payment_modes ?? [];
    $workFrom      = $biz?->working_days_from ?? null;
    $workTo        = $biz?->working_days_to ?? null;
    $timeFrom      = $biz?->working_time_from ?? null;
    $timeTo        = $biz?->working_time_to ?? null;
    // Convert 24-hour time to 12-hour AM/PM format for display
    $timeFromFmt = $timeFrom ? date('g:i A', strtotime($timeFrom)) : null;
    $timeToFmt   = $timeTo   ? date('g:i A', strtotime($timeTo))   : null;
    $preferredCities   = $biz?->preferred_cities ?? null;
    $preferredStates   = $biz?->preferred_states ?? null;
    $excludedCities    = $biz?->excluded_cities ?? null;

    // Certification & Award data
    $stdCert         = $biz?->standard_certification ?? null;
    $membershipAff   = $biz?->membership_affiliations ?? null;
    $awardRecog      = $biz?->award_recognition ?? null;
    $certImages      = $biz?->certifications ?? [];
    $awardImages     = $biz?->awards ?? [];

    // Detect embedded YouTube/video
    $isYoutube = $videoUrl && (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be'));
    if ($isYoutube && preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $ym)) {
        $videoEmbed = 'https://www.youtube.com/embed/' . $ym[1];
    } else {
        $videoEmbed = null;
    }
@endphp

<div class="sp-page">

    {{-- â•â• HERO BANNER â•â• --}}
    <div class="sp-hero">
        <div class="sp-hero-inner">
            <div class="sp-logo-wrap">
                <div class="sp-logo-box">
                    @if($logoPath)
                        <img src="{{ $logoPath }}" alt="{{ $companyName }}">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <div class="sp-logo-badge">
                    <svg width="13" height="13" fill="none" stroke="#fff" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <div class="sp-hero-text">
                <h1 class="sp-hero-title">{{ $companyName }}</h1>
                <div class="sp-hero-tags">
                    <span class="sp-tag verified">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Verified Supplier
                    </span>

                    @if(optional($seller->city)->name)
                        <span class="sp-tag">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $seller->city->name }}@if($biz->state_name ?? null), {{ $biz->state_name }}@endif
                        </span>
                    @endif

                    @include('components.country-badge', [
                        'countryCode' => strtoupper($biz?->country_code ?? $seller->country_code ?? session('country', 'IN')),
                        'showName' => false,
                        'classes' => 'sp-tag',
                    ])

                    <span class="sp-tag">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Member since {{ $seller->created_at->format('Y') }}
                    </span>

                    @if($biz->ownership_type)
                        <span class="sp-tag">{{ $biz->ownership_type }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- â•â• STATS STRIP â•â• --}}
    <div class="sp-stats-strip">
        <div class="sp-stats-inner">
            <div class="sp-stat-item">
                <div class="sp-stat-icon orange">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="sp-stat-text">
                    <div class="sp-stat-num">{{ $totalProducts }}</div>
                    <div class="sp-stat-label">Products Listed</div>
                </div>
            </div>

            @if($biz->year_established)
                <div class="sp-stat-item">
                    <div class="sp-stat-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="sp-stat-text">
                        <div class="sp-stat-num">{{ date('Y') - (int)$biz->year_established }}+</div>
                        <div class="sp-stat-label">Years in Business</div>
                    </div>
                </div>
            @endif

            @if($biz->no_of_employees)
                <div class="sp-stat-item">
                    <div class="sp-stat-icon green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="sp-stat-text">
                        <div class="sp-stat-num">{{ $biz->no_of_employees }}</div>
                        <div class="sp-stat-label">Employees</div>
                    </div>
                </div>
            @endif

            <div class="sp-stat-item">
                <div class="sp-stat-icon green">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="sp-stat-text">
                    <div class="sp-stat-num" style="color:#16a34a;">98%</div>
                    <div class="sp-stat-label">Response Rate</div>
                </div>
            </div>
        </div>
    </div>

    {{-- â•â• MAIN CONTENT â•â• --}}
    <div class="sp-wrap">

        {{-- LEFT SIDEBAR --}}
        <aside>

            {{-- Company Overview --}}
            <div class="sp-card">
                <div class="sp-card-header">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Company Overview
                </div>
                <div class="sp-card-body" style="padding-bottom:0;">
                    @if($biz->business_type)
                        <div class="sp-info-row">
                            <span class="sp-info-key">Business Type</span>
                            <span class="sp-info-val">{{ is_array($biz->business_type) ? implode(', ', $biz->business_type) : $biz->business_type }}</span>
                        </div>
                    @endif
                    @if($biz->ownership_type)
                        <div class="sp-info-row">
                            <span class="sp-info-key">Ownership</span>
                            <span class="sp-info-val">{{ $biz->ownership_type }}</span>
                        </div>
                    @endif
                    @if($biz->year_established)
                        <div class="sp-info-row">
                            <span class="sp-info-key">Est. Year</span>
                            <span class="sp-info-val">{{ $biz->year_established }}</span>
                        </div>
                    @endif
                    @if($biz->annual_turnover)
                        <div class="sp-info-row">
                            <span class="sp-info-key">Annual Turnover</span>
                            <span class="sp-info-val">{{ $biz->annual_turnover }}</span>
                        </div>
                    @endif

                    <!-- Response Rate with bar -->
                    <div class="sp-info-row" style="flex-direction: column; align-items: flex-start; gap: 6px;">
                        <div style="display:flex; justify-content:space-between; width:100%;">
                            <span class="sp-info-key">Response Rate</span>
                            <span style="font-size:12px; font-weight:800; color:#16a34a;">Excellent (98%)</span>
                        </div>
                        <div class="sp-response-bar" style="width:100%;">
                            <div class="sp-response-fill" style="width:98%;"></div>
                        </div>
                    </div>
                </div>
                <div style="padding: 16px 18px;">
                    <a href="{{ route('post-requirement') }}?seller={{ $seller->id }}" class="sp-contact-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Send Inquiry
                    </a>
                    @auth
                        <a href="{{ route('user.support.index', ['type' => 'report', 'target_user_id' => $seller->id, 'context_url' => url()->current()]) }}"
                            style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; margin-top:10px; border:1px solid #fecaca; color:#dc2626; text-align:center; padding:12px; border-radius:10px; font-size:13px; font-weight:800; text-decoration:none; background:#fff5f5;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.938 19h12.124c1.54 0 2.502-1.667 1.732-3L13.732 5c-.77-1.333-2.694-1.333-3.464 0L4.206 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Report Seller
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Trust Badges --}}
            <div class="sp-card" x-data="{ openTrust: window.innerWidth > 768 }">
                <div class="sp-card-header" @click="if(window.innerWidth <= 768) openTrust = !openTrust">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Trust & Compliance
                    <span class="sp-collapse-btn" :class="openTrust ? 'rot' : ''">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
                <div class="sp-card-body" x-show="openTrust" x-collapse>
                    <div class="sp-trust-grid">
                        <div class="sp-trust-item">
                            <div class="sp-trust-icon" style="background:#f0fdf4; color:#16a34a;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="sp-trust-label">Identity</div>
                            <div class="sp-trust-val" style="color:#16a34a;">Verified</div>
                        </div>
                        <div class="sp-trust-item">
                            <div class="sp-trust-icon" style="background:#eff6ff; color:#2563eb;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="sp-trust-label">Secure</div>
                            <div class="sp-trust-val">Payments</div>
                        </div>
                        <div class="sp-trust-item">
                            <div class="sp-trust-icon" style="background:#fff7f0; color:#ff6a00;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                            <div class="sp-trust-label">On-Time</div>
                            <div class="sp-trust-val">Delivery</div>
                        </div>
                        <div class="sp-trust-item">
                            <div class="sp-trust-icon" style="background:#f0fdf4; color:#16a34a;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <div class="sp-trust-label">Rating</div>
                            <div class="sp-trust-val" style="color:#f59e0b;">4.9 ★</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- About Company --}}
            @if($companyDesc)
                <div class="sp-card">
                    <div class="sp-card-header">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        About Company
                    </div>
                    <div class="sp-card-body">
                        <p style="font-size:13px; color:#4b5563; line-height:1.7; margin:0;">{!! nl2br(e($companyDesc)) !!}</p>
                    </div>
                </div>
            @endif

            {{-- Working Hours --}}
            @if($workFrom || $timeFrom)
                <div class="sp-card">
                    <div class="sp-card-header">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Working Hours
                    </div>
                    <div class="sp-card-body">
                        @if($workFrom && $workTo)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Working Days</span>
                                <span class="sp-info-val">{{ $workFrom }} - {{ $workTo }}</span>
                            </div>
                        @endif
                        @if($timeFrom && $timeTo)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Timings</span>
                                <span class="sp-info-val">{{ $timeFromFmt }} - {{ $timeToFmt }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Payment Modes --}}
            @if(!empty($paymentModes))
                <div class="sp-card">
                    <div class="sp-card-header">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payment Modes
                    </div>
                    <div class="sp-card-body" style="display:flex; flex-wrap:wrap; gap:6px;">
                        @foreach((array)$paymentModes as $mode)
                            <span style="font-size:11px; font-weight:700; background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; border-radius:20px; padding:4px 10px;">{{ $mode }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Service Areas --}}
            @if($preferredCities || $preferredStates)
                <div class="sp-card">
                    <div class="sp-card-header">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Service Areas
                    </div>
                    <div class="sp-card-body">
                        @if($preferredStates)
                            <div class="sp-info-row">
                                <span class="sp-info-key">States</span>
                                <span class="sp-info-val">{{ $preferredStates }}</span>
                            </div>
                        @endif
                        @if($preferredCities)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Cities</span>
                                <span class="sp-info-val">{{ $preferredCities }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Certification Info --}}
            @if($stdCert || $membershipAff || $awardRecog)
                <div class="sp-card">
                    <div class="sp-card-header">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Certifications & Awards
                    </div>
                    <div class="sp-card-body">
                        @if($stdCert)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Certification</span>
                                <span class="sp-info-val">{{ $stdCert }}</span>
                            </div>
                        @endif
                        @if($membershipAff)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Membership</span>
                                <span class="sp-info-val">{{ $membershipAff }}</span>
                            </div>
                        @endif
                        @if($awardRecog)
                            <div class="sp-info-row">
                                <span class="sp-info-key">Awards</span>
                                <span class="sp-info-val">{{ $awardRecog }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </aside>

        {{-- RIGHT: Products and Media --}}
        <main x-data="{ activeTab: 'products' }">

            {{-- ── TAB NAVIGATION ── --}}
            <div class="sp-tabs-nav">
                <button class="sp-tab-btn" :class="activeTab === 'products' ? 'active' : ''" @click="activeTab = 'products'">
                    Products
                </button>
                @if(!empty($companyPhotos))
                    <button class="sp-tab-btn" :class="activeTab === 'photos' ? 'active' : ''" @click="activeTab = 'photos'">
                        Company Photos
                    </button>
                @endif
                @if($videoUrl)
                    <button class="sp-tab-btn" :class="activeTab === 'video' ? 'active' : ''" @click="activeTab = 'video'">
                        Videos
                    </button>
                @endif
                @if(!empty($certImages))
                    <button class="sp-tab-btn" :class="activeTab === 'certs' ? 'active' : ''" @click="activeTab = 'certs'">
                        Certifications
                    </button>
                @endif
                @if(!empty($awardImages))
                    <button class="sp-tab-btn" :class="activeTab === 'awards' ? 'active' : ''" @click="activeTab = 'awards'">
                        Awards & Recognition
                    </button>
                @endif
            </div>

            {{-- Company Photos --}}
            @if(!empty($companyPhotos))
                <div x-show="activeTab === 'photos'" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); margin-bottom:24px; padding:18px; display:none;" :style="activeTab === 'photos' ? 'display:block' : 'display:none'">
                    <h3 style="font-size:15px; font-weight:800; color:#111827; margin:0 0 14px; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="#ff6a00" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Company Photos
                    </h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(130px,1fr)); gap:10px;">
                        @foreach((array)$companyPhotos as $photo)
                            <a href="{{ $photo }}" target="_blank" style="display:block; border-radius:10px; overflow:hidden; border:1px solid #e5e7eb; aspect-ratio:1; background:#f8fafc;">
                                <img src="{{ $photo }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy" alt="Company Photo">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Company Video --}}
            @if($videoUrl)
                <div x-show="activeTab === 'video'" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); margin-bottom:24px; padding:18px; display:none;" :style="activeTab === 'video' ? 'display:block' : 'display:none'">
                    <h3 style="font-size:15px; font-weight:800; color:#111827; margin:0 0 14px; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="#ff6a00" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                        {{ $videoTitle ?? 'Company Video' }}
                    </h3>
                    @if($videoEmbed)
                        <div style="position:relative; padding-bottom:56.25%; height:0; border-radius:10px; overflow:hidden;">
                            <iframe src="{{ $videoEmbed }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
                        </div>
                    @else
                        <video src="{{ $videoUrl }}" controls style="width:100%; border-radius:10px; background:#000; max-height:360px;"></video>
                    @endif
                </div>
            @endif

            {{-- Certification Images --}}
            @if(!empty($certImages))
                <div x-show="activeTab === 'certs'" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); margin-bottom:24px; padding:18px; display:none;" :style="activeTab === 'certs' ? 'display:block' : 'display:none'">
                    <h3 style="font-size:15px; font-weight:800; color:#111827; margin:0 0 14px; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="#ff6a00" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        Certifications
                    </h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(130px,1fr)); gap:10px;">
                        @foreach((array)$certImages as $cert)
                            <a href="{{ $cert }}" target="_blank" style="display:block; border-radius:10px; overflow:hidden; border:1px solid #e5e7eb; aspect-ratio:1; background:#f8fafc;">
                                <img src="{{ $cert }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy" alt="Certification">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Award Images --}}
            @if(!empty($awardImages))
                <div x-show="activeTab === 'awards'" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); margin-bottom:24px; padding:18px; display:none;" :style="activeTab === 'awards' ? 'display:block' : 'display:none'">
                    <h3 style="font-size:15px; font-weight:800; color:#111827; margin:0 0 14px; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="#ff6a00" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Awards & Recognition
                    </h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(130px,1fr)); gap:10px;">
                        @foreach((array)$awardImages as $award)
                            <a href="{{ $award }}" target="_blank" style="display:block; border-radius:10px; overflow:hidden; border:1px solid #e5e7eb; aspect-ratio:1; background:#f8fafc;">
                                <img src="{{ $award }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy" alt="Award">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Product Catalog --}}
            <div x-show="activeTab === 'products'" style="display:none;" :style="activeTab === 'products' ? 'display:block' : 'display:none'">
                <div class="sp-products-header">
                    <h2 class="sp-products-title">Product Catalog</h2>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span class="sp-products-count">{{ $totalProducts }} Products</span>
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

                @if($products->isNotEmpty())
                <div class="pl-grid">
                    @foreach($products as $p)
                        @php
                            $imgs = is_array($p->images) ? $p->images : json_decode($p->images ?? '[]', true);
                            $img = $imgs[0] ?? null;
                            $productCountryCode = strtoupper($p->country_code ?? $biz?->country_code ?? session('country', 'IN'));
                        @endphp
                        <a href="{{ route('products.show', $p->id) }}" class="pl-card">
                            <div class="pl-card-img">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $p->name }}" loading="lazy">
                                @else
                                    <div class="pl-no-img">
                                        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="pl-no-img-text">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="pl-card-body">
                                @if(optional($p->category)->name)
                                    <span class="pl-cat-badge">{{ $p->category->name }}</span>
                                @endif
                                <div style="margin-bottom:10px;">
                                    @include('components.country-badge', [
                                        'countryCode' => $productCountryCode,
                                        'showName' => false,
                                        'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                    ])
                                </div>
                                <div class="pl-card-name" title="{{ $p->name }}">{{ $p->name }}</div>

                                <div class="pl-card-price">
                                    @if($p->price)
                                        {{ $p->display_price_formatted }}
                                        <span class="pl-card-price-unit">/ Piece</span>
                                    @else
                                        <span style="font-size:13px;color:#6b7280;font-weight:600;">Price on Request</span>
                                    @endif
                                </div>

                                <div class="pl-card-cta">Contact Seller</div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
                @else
                    <div style="text-align:center; padding:70px 20px; background:#fff; border-radius:14px; border:1px solid #e5e7eb;">
                        <svg width="52" height="52" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24" style="margin:0 auto 16px; display:block;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 style="font-size:16px; font-weight:800; color:#374151; margin:0 0 8px;">No Products Yet</h3>
                        <p style="font-size:13px; color:#9ca3af;">This supplier hasn't added any products to their catalog yet.</p>
                    </div>
                @endif
            </div>

        </main>

    </div>
</div>
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
