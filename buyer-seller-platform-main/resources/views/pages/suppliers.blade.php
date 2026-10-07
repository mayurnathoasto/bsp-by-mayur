@extends('layouts.app')

@section('content')

    <style>
        /* ── Base ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .sp-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        /* ── Hero Banner ── */
        .sp-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 55%, #1e5a8e 100%);
            padding: 48px 20px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .sp-hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            top: -200px;
            right: -100px;
        }

        .sp-hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            bottom: -120px;
            left: -60px;
        }

        .sp-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #93c5fd;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .sp-hero h1 {
            font-size: 32px;
            font-weight: 900;
            color: #fff;
            margin: 0 0 10px;
            letter-spacing: -0.5px;
        }

        .sp-hero p {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            font-weight: 500;
        }

        .sp-hero-stats {
            display: flex;
            justify-content: center;
            gap: 36px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .sp-hero-stat {
            color: #fff;
            text-align: center;
        }

        .sp-hero-stat-num {
            font-size: 22px;
            font-weight: 900;
        }

        .sp-hero-stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ── Filter Bar ── */
        .sp-filter-bar {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-top: none;
            padding: 16px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .sp-filter-label {
            font-size: 12px;
            font-weight: 800;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-right: 4px;
        }

        .sp-select {
            height: 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            background: #f9fafb;
            outline: none;
            cursor: pointer;
            transition: border-color .2s;
        }

        .sp-select:focus {
            border-color: #0b2a4a;
            background: #fff;
        }

        .sp-search-input {
            flex: 1;
            min-width: 200px;
            height: 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            background: #f9fafb;
            outline: none;
            transition: border-color .2s;
        }

        .sp-search-input:focus {
            border-color: #0b2a4a;
            background: #fff;
        }

        .sp-filter-btn {
            height: 38px;
            padding: 0 20px;
            background: #0b2a4a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
        }

        .sp-filter-btn:hover {
            background: #1a4b7c;
        }

        .sp-reset-link {
            font-size: 13px;
            font-weight: 600;
            color: #9ca3af;
            text-decoration: none;
            margin-left: 4px;
        }

        .sp-reset-link:hover {
            color: #ef4444;
        }

        /* ── Result count ── */
        .sp-result-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0 10px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .sp-result-count {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
        }

        .sp-result-count span {
            color: #0b2a4a;
        }

        .sp-sort-select {
            height: 34px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            background: #f9fafb;
            outline: none;
            cursor: pointer;
        }

        /* ── Grid ── */
        .sp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
            margin-top: 6px;
        }

        /* ── Supplier Card ── */
        .sp-card {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: all .25s;
            text-decoration: none;
            color: inherit;
        }

        .sp-card:hover {
            border-color: #0b2a4a;
            box-shadow: 0 8px 32px rgba(11, 42, 74, 0.10);
            transform: translateY(-2px);
        }

        /* Card Top Row */
        .sp-card-top {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .sp-avatar {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0b2a4a, #1e5a8e);
            color: #fff;
            font-size: 22px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            letter-spacing: -1px;
        }

        .sp-card-info {
            flex: 1;
            min-width: 0;
        }

        .sp-card-name {
            font-size: 15px;
            font-weight: 800;
            color: #0b2a4a;
            margin: 0 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sp-card-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            font-size: 12px;
        }

        .sp-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .sp-location {
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
        }

        /* Card divider */
        .sp-divider {
            border: none;
            border-top: 1px solid #f3f4f6;
            margin: 0;
        }

        /* Card Details */
        .sp-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .sp-detail-label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 2px;
        }

        .sp-detail-value {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Card Tags */
        .sp-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .sp-tag {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        /* Card Footer */
        .sp-card-footer {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .sp-btn-inquiry {
            flex: 1;
            height: 36px;
            background: #0b2a4a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: background .2s;
        }

        .sp-btn-inquiry:hover {
            background: #1a4b7c;
        }

        .sp-btn-view {
            height: 36px;
            padding: 0 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all .2s;
        }

        .sp-btn-view:hover {
            border-color: #0b2a4a;
            color: #0b2a4a;
        }

        /* ── Empty State ── */
        .sp-empty {
            text-align: center;
            padding: 80px 20px;
            background: #f9fafb;
            border-radius: 14px;
            border: 2px dashed #e5e7eb;
            grid-column: 1/-1;
        }

        .sp-empty-icon {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .sp-empty h3 {
            font-size: 18px;
            font-weight: 800;
            color: #374151;
            margin: 0 0 6px;
        }

        .sp-empty p {
            font-size: 14px;
            color: #9ca3af;
            margin: 0;
        }

        /* ── Pagination ── */
        .sp-pagination {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }

        /* ── Active filter pill ── */
        .sp-active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 0 4px;
        }

        .sp-active-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .sp-active-pill a {
            color: #1d4ed8;
            text-decoration: none;
            font-size: 14px;
            line-height: 1;
        }

        .sp-active-pill a:hover {
            color: #ef4444;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .sp-hero {
                padding: 36px 16px 30px;
            }

            .sp-hero h1 {
                font-size: 22px;
            }

            .sp-hero-stats {
                gap: 20px;
            }

            .sp-filter-bar {
                padding: 12px 16px;
            }

            .sp-wrap {
                padding: 0 12px 48px;
            }

            .sp-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .sp-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    {{-- ═══════════════════ HERO BANNER ═══════════════════ --}}
    <div class="sp-hero">
        <div class="sp-hero-badge">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
            Verified B2B Suppliers
        </div>
        <h1>Explore All Suppliers</h1>
        <p>Connect with trusted manufacturers, traders &amp; exporters verified on the platform.</p>
        <div class="sp-hero-stats">
            <div class="sp-hero-stat">
                <div class="sp-hero-stat-num">{{ $suppliers->total() }}+</div>
                <div class="sp-hero-stat-lbl">Suppliers</div>
            </div>
            <div class="sp-hero-stat">
                <div class="sp-hero-stat-num">50+</div>
                <div class="sp-hero-stat-lbl">Industries</div>
            </div>
            <div class="sp-hero-stat">
                <div class="sp-hero-stat-num">10+</div>
                <div class="sp-hero-stat-lbl">Countries</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════ FILTER BAR (sticky) ═══════════════════ --}}
    <form method="GET" action="{{ url('/suppliers') }}">
        <div class="sp-filter-bar">
            <span class="sp-filter-label">Filter:</span>

            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search supplier name…"
                class="sp-search-input">

            <select name="type" class="sp-select" onchange="this.form.submit()">
                <option value="">All Business Types</option>
                @foreach($ownershipTypes as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
                @endforeach
            </select>

            <select name="state" class="sp-select" onchange="this.form.submit()">
                <option value="">All Locations</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" @selected(request('state') === $state)>{{ $state }}</option>
                @endforeach
            </select>

            <select name="year" class="sp-select" onchange="this.form.submit()">
                <option value="">Est. Year</option>
                <option value="2020" @selected(request('year') === '2020')>2020 - Present</option>
                <option value="2015" @selected(request('year') === '2015')>2015 - 2019</option>
                <option value="2010" @selected(request('year') === '2010')>2010 - 2014</option>
                <option value="2000" @selected(request('year') === '2000')>2000 - 2009</option>
                <option value="before_2000" @selected(request('year') === 'before_2000')>Before 2000</option>
            </select>

            <button type="submit" class="sp-filter-btn">Apply Filters</button>
            @if(request()->hasAny(['q', 'type', 'state', 'year']))
                <a href="{{ url('/suppliers') }}" class="sp-reset-link">✕ Reset</a>
            @endif
        </div>
    </form>

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <div class="sp-wrap">

        {{-- Active filter pills --}}
        @if(request()->hasAny(['q', 'type', 'state', 'year']))
            <div class="sp-active-filters">
                @if(request('q'))
                    <span class="sp-active-pill">Search: "{{ request('q') }}" <a
                            href="{{ request()->fullUrlWithQuery(['q' => null]) }}">×</a></span>
                @endif
                @if(request('type'))
                    <span class="sp-active-pill">Type: {{ request('type') }} <a
                            href="{{ request()->fullUrlWithQuery(['type' => null]) }}">×</a></span>
                @endif
                @if(request('state'))
                    <span class="sp-active-pill">Location: {{ request('state') }} <a
                            href="{{ request()->fullUrlWithQuery(['state' => null]) }}">×</a></span>
                @endif
                @if(request('year'))
                    <span class="sp-active-pill">Est: {{ request('year') }} <a
                            href="{{ request()->fullUrlWithQuery(['year' => null]) }}">×</a></span>
                @endif
            </div>
        @endif

        {{-- Result bar --}}
        <div class="sp-result-bar">
            <div class="sp-result-count">
                Showing <span>{{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }}</span>
                of <span>{{ $suppliers->total() }}</span> verified suppliers
            </div>
            <form method="GET" action="{{ url('/suppliers') }}" style="display:flex;align-items:center;gap:8px;">
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sp-sort-select" onchange="this.form.submit()">
                    <option value="recent" @selected(request('sort') === 'recent')>Sort: Newest First</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Sort: Oldest First</option>
                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Sort: Name A–Z</option>
                    <option value="name_desc" @selected(request('sort') === 'name_desc')>Sort: Name Z–A</option>
                </select>
            </form>
        </div>

        {{-- ═══════════════════ GRID ═══════════════════ --}}
        <div class="sp-grid">
            @forelse($suppliers as $supplier)
                @php
                    $biz = $supplier->businessProfile;
                    $name = $supplier->company_name ?? $supplier->name ?? 'Verified Supplier';
                    $words = array_slice(explode(' ', trim($name)), 0, 2);
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0], $words)));
                    $location = $biz->city_name ?? ($supplier->city->name ?? ($biz->state_name ?? ($supplier->country_code ?? 'India')));
                    $bizType = $biz->ownership_type ?? null;
                    $year = $biz->year_established ?? date('Y', strtotime($supplier->created_at));
                    $prods = $supplier->products_count ?? 0;
                    $supplierCountryCode = strtoupper($biz->country_code ?? $supplier->country_code ?? session('country', 'IN'));
                @endphp

                <div class="sp-card">
                    {{-- Top --}}
                    <div class="sp-card-top">
                        <div class="sp-avatar">{{ $initials }}</div>
                        <div class="sp-card-info">
                            <a href="{{ route('seller.show', $supplier->id) }}" class="sp-card-name" title="{{ $name }}"
                                style="text-decoration:none; display:block;">{{ $name }}</a>
                            <div class="sp-card-meta">
                                <span class="sp-verified-badge">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verified
                                </span>
                                @if($location)
                                    <span class="sp-location">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="display:inline;vertical-align:middle;margin-right:2px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $location }}
                                    </span>
                                @endif
                                @include('components.country-badge', [
                                    'countryCode' => $supplierCountryCode,
                                    'showName' => false,
                                    'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                ])
                            </div>
                        </div>
                    </div>

                    <hr class="sp-divider">

                    {{-- Details grid --}}
                    <div class="sp-details">
                        <div>
                            <div class="sp-detail-label">Business Type</div>
                            <div class="sp-detail-value">{{ $bizType ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="sp-detail-label">Est. Year</div>
                            <div class="sp-detail-value">{{ $year }}</div>
                        </div>
                        <div>
                            <div class="sp-detail-label">Products</div>
                            <div class="sp-detail-value">{{ $prods }} Listed</div>
                        </div>
                        <div>
                            <div class="sp-detail-label">Country</div>
                            <div class="sp-detail-value">
                                @include('components.country-badge', [
                                    'countryCode' => $supplierCountryCode,
                                    'countryName' => config('countries.supported.' . $supplierCountryCode . '.name') ?? $supplierCountryCode,
                                    'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                ])
                            </div>
                        </div>
                    </div>

                    {{-- Footer CTA --}}
                    <div class="sp-card-footer">
                        <a href="{{ route('post-requirement') }}" class="sp-btn-inquiry">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Send Inquiry
                        </a>
                        @if($prods > 0)
                            <a href="{{ route('seller.show', $supplier->id) }}" class="sp-btn-view">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                View Profile
                            </a>
                        @endif
                    </div>
                </div>

            @empty
                <div class="sp-empty">
                    <div class="sp-empty-icon">
                        <svg width="32" height="32" fill="none" stroke="#60a5fa" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3>No Suppliers Found</h3>
                    <p>Try adjusting your filters or search term.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($suppliers->hasPages())
            <div class="sp-pagination">
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

@endsection
