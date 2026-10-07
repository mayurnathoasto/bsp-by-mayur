@extends('layouts.app')

@section('content')

    {{-- Import Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .stat-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .stat-card-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        /* Fix for oversized images in reference */
        .icon-bg-blue {
            background: #3b82f6;
        }

        .icon-bg-orange {
            background: #f97316;
        }

        .icon-bg-yellow {
            background: #f59e0b;
        }

        .icon-bg-red {
            background: #ef4444;
        }

        /* Analytics vertical marker */
        .analytics-marker {
            border-left: 3px solid #ff6a00;
            padding-left: 12px;
        }

        .submenu-item.active

        /* Profile Specific Styles */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .section-header {
            background: #8fa1b2;
            /* Grayish blue from reference */
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-input-container {
            position: relative;
        }

        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            padding-top: 24px;
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
        }

        .form-label {
            position: absolute;
            top: 6px;
            left: 12px;
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
        }

        .edit-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6a00;
            cursor: pointer;
        }

        .completeness-circle {
            position: relative;
            width: 64px;
            height: 64px;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .submenu-item.active

        /* Business Profile Specific */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .nav-tabs-container {
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .nav-tab {
            padding: 12px 24px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .nav-tab.active {
            color: #ff6a00;
            border-bottom-color: #ff6a00;
        }

        .section-header {
            background: #8fa1b2;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-input-container {
            position: relative;
        }

        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            padding-top: 24px;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            background-color: #f8fafc;
        }

        .form-label {
            position: absolute;
            top: 6px;
            left: 12px;
            font-size: 11px;
            color: #64748b;
            font-weight: 700;
        }

        .upload-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            background: white;
            transition: all 0.2s;
        }

        .upload-btn:hover {
            border-color: #ff6a00;
            color: #ff6a00;
        }

        .action-btn-view {
            color: #64748b;
            padding: 4px;
        }

        .action-btn-view:hover {
            color: #ff6a00;
        }

        /* Bank Details Specific */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .section-header {
            background: #8fa1b2;
            /* Grayish blue from reference */
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Manage Users Specific */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .add-user-btn {
            background: #3b4c5e;
            /* Darker blue-gray */
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }

        .user-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .user-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .user-table td {
            padding: 16px;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #f8fafc;
        }

        .status-badge {
            background: #f0fdf4;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
    </style>
    <!-- Shared Sidebar CSS -->
    @include('layouts.sidebar_css')

    <div x-data="{ 
                                                                                                                                                                                                                                                                                            sidebarOpen: false, 
                                                                                                                                                                                                                                                                                            openSub: '{{ request()->routeIs('user.profile', 'user.business-profile', 'user.bank-details', 'user.manage-users') ? 'profile' : (request()->routeIs('user.products.*') ? 'products' : 'dashboard') }}',
                                                                                                                                                                                                                                                                                        activeTab: '{{ request()->routeIs('user.profile') ? 'profile' : (request()->routeIs('user.business-profile') ? 'business' : (request()->routeIs('user.bank-details') ? 'bank' : (request()->routeIs('user.manage-users') ? 'manage_users' : 'dashboard'))) }}'
                                                                                                                                                                                                                                                                                    }"
        class="flex flex-row-reverse min-h-screen bg-[#f8fafc] relative">
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

        {{-- Main Content Column --}}
        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto pb-[100px] lg:pb-0">
            @php
                $pageTitle = 'Home';
                if (request()->routeIs('user.profile'))
                    $pageTitle = 'User Profile';
                elseif (request()->routeIs('user.business-profile'))
                    $pageTitle = 'Business Profile';
                elseif (request()->routeIs('user.bank-details'))
                    $pageTitle = 'Bank Details';
                elseif (request()->routeIs('user.manage-users'))
                    $pageTitle = 'Manage Users';
            @endphp
            @php
                $businessProfile = $user->businessProfile;
                $bankDetail = $user->bankDetail;
                $businessAdditionalInfo = is_array($businessProfile?->additional_info) ? $businessProfile->additional_info : [];

                $resolveBusinessProfileValue = function (string $column, ?string $additionalKey = null, $default = null) use ($businessProfile, $businessAdditionalInfo) {
                    $value = $businessProfile?->{$column} ?? null;

                    if ($value !== null && $value !== '') {
                        return $value;
                    }

                    return $businessAdditionalInfo[$additionalKey ?? $column] ?? $default;
                };

                $normalizeListValue = function ($value): array {
                    if ($value === null || $value === '') {
                        return [];
                    }

                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        $value = json_last_error() === JSON_ERROR_NONE ? $decoded : [$value];
                    }

                    if (!is_array($value)) {
                        $value = [$value];
                    }

                    return collect($value)
                        ->filter(fn($item) => filled($item))
                        ->values()
                        ->all();
                };

                $uploadedLogo = $resolveBusinessProfileValue('logo', 'logo', '');
                $uploadedCompanyPhotos = $normalizeListValue($resolveBusinessProfileValue('company_photos', 'photos', []));
                $uploadedCertifications = $normalizeListValue($resolveBusinessProfileValue('certifications', 'certifications', []));
                $uploadedAwards = $normalizeListValue($resolveBusinessProfileValue('awards', 'awards', []));
                $uploadedPaymentModes = $normalizeListValue($resolveBusinessProfileValue('payment_modes', 'payment_modes', []));
                $uploadedBusinessDocuments = is_array($businessProfile?->documents_json) ? $businessProfile->documents_json : [];

                $uploadedFileLabel = function (?string $value): string {
                    if (blank($value)) {
                        return '';
                    }

                    $path = parse_url($value, PHP_URL_PATH) ?: $value;
                    $filename = basename($path);

                    return $filename ? urldecode($filename) : 'Document Uploaded';
                };

                $profileCountryCode = strtoupper(
                    $user->businessProfile->country_code
                    ?? $user->country_code
                    ?? session('country', config('profile_localization.default_country', 'IN'))
                );

                $profileLocalization = config('profile_localization.countries', []);
                $profileUiConfig = $profileLocalization[$profileCountryCode] ?? ($profileLocalization['DEFAULT_INTL'] ?? []);
                $dialCode = $profileUiConfig['dial_code'] ?? '+00';

                $bankUi = $profileUiConfig['bank'] ?? [];
                $bankAccountTypeLabel = $bankUi['account_type_label'] ?? 'Account Type';
                $bankAccountTypeOptions = $bankUi['account_type_options'] ?? ['Personal', 'Business'];
                $bankAccountHolderLabel = $bankUi['account_holder_label'] ?? 'Account Holder Name';
                $bankAccountNumberLabel = $bankUi['account_number_label'] ?? 'Account Number';
                $bankAccountConfirmLabel = $bankUi['account_number_confirm_label'] ?? 'Confirm Account Number';
                $bankCodeLabel = $bankUi['bank_code_label'] ?? 'Bank Code';
                $bankCodePlaceholder = $bankUi['bank_code_placeholder'] ?? 'Bank Code';
                $bankLinkedMobileLabel = $bankUi['linked_mobile_label'] ?? 'Registered Mobile';

                $turnoverCurrencies = collect(config('countries.supported', []))
                    ->pluck('currency')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $resolvedTurnoverCurrency = old(
                    'annual_turnover_currency',
                    $user->businessProfile->annual_turnover_currency
                    ?? (config('countries.supported.' . $profileCountryCode . '.currency') ?? config('countries.default_currency', 'INR'))
                );

                $turnoverUnits = $profileUiConfig['turnover_units'] ?? ['Thousand', 'Million', 'Billion'];
            @endphp

            <div class="p-6 sm:p-10">
                <div x-show="activeTab === 'dashboard'" x-transition.opacity.duration.300ms>

                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="text-sm font-medium text-slate-500 mb-2">Dashboard</div>
                            <h2 class="text-[22px] font-bold text-[#1e293b]">Welcome Back,
                                {{ $user->name ?? 'User' }}!
                            </h2>
                        </div>
                        <!-- Sidebar Toggle Icon (Mobile Only) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -mr-2 -mt-2 text-slate-500 hover:text-[#ff6a00] transition-colors" aria-label="Toggle Sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                        <div class="lg:col-span-3 space-y-6">


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="stat-card-icon icon-bg-blue">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm2 3h8v2H8V9zm0 4h5v2H8v-2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Total Inquiries Received</p>
                                            <h3 class="text-2xl font-bold text-slate-900">
                                                {{ $stats['inquiries_count'] ?? 0 }}
                                            </h3>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.inquiries') }}"
                                        class="text-xs font-bold text-[#ff6a00] hover:underline flex items-center gap-1">View
                                        All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg></a>
                                </div>

                                <div
                                    class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="stat-card-icon icon-bg-orange">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM4 4h16v12H5.17L4 17.17V4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Contacted Inquiries</p>
                                            <h3 class="text-2xl font-bold text-slate-900">
                                                {{ $stats['contacted_inquiries_count'] ?? 0 }}
                                            </h3>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.inquiries', ['status' => 'contacted']) }}"
                                        class="text-xs font-bold text-[#ff6a00] hover:underline flex items-center gap-1">View
                                        All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg></a>
                                </div>

                                <div
                                    class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="stat-card-icon icon-bg-yellow">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Unread Inquiries</p>
                                            <h3 class="text-2xl font-bold text-slate-900">
                                                {{ $stats['new_inquiries_count'] ?? 0 }}
                                            </h3>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.inquiries', ['status' => 'new']) }}"
                                        class="text-xs font-bold text-[#ff6a00] hover:underline flex items-center gap-1">View
                                        All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg></a>
                                </div>

                                <div
                                    class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="stat-card-icon icon-bg-red">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Recent Buy Trade Leads</p>
                                            <h3 class="text-2xl font-bold text-slate-900">0</h3>
                                        </div>
                                    </div>
                                    <a href="#"
                                        class="text-xs font-bold text-[#ff6a00] hover:underline flex items-center gap-1">View
                                        All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg></a>
                                </div>
                            </div>


                            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                                <div
                                    class="px-6 py-4 border-b border-slate-50 flex items-center justify-between gap-4 flex-wrap">
                                    <h3 class="text-lg font-bold text-slate-800 analytics-marker">Analytics</h3>

                                    <form method="GET" action="{{ route('user.dashboard') }}"
                                        class="flex items-center gap-3 flex-wrap">
                                        <select name="period"
                                            class="h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none">
                                            <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Day</option>
                                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Week</option>
                                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Month</option>
                                        </select>

                                        <select name="year"
                                            class="h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none">
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                            class="h-11 px-5 rounded-xl bg-[#24618e] hover:bg-[#1a4b6e] text-white text-sm font-semibold transition-colors">
                                            Apply
                                        </button>
                                    </form>
                                </div>

                                <div class="p-6">
                                    <div class="mb-4 flex items-center justify-between gap-3 flex-wrap">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">
                                                {{ $chart['title'] ?? 'Inquiry Analytics' }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">Direct product inquiries received by your
                                                account.</p>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-xs text-slate-500">Total in selected range</div>
                                            <div class="text-2xl font-bold text-slate-900">
                                                {{ array_sum($chart['series'] ?? []) }}</div>
                                        </div>
                                    </div>

                                    <div id="sellerInquiryChart" class="w-full" style="min-height: 320px;"></div>
                                </div>
                            </div>
                        </div>


                        <div class="space-y-6">

                            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                                <div class="flex items-center justify-between mb-8">
                                    <h3 class="text-lg font-bold text-slate-800 analytics-marker">Products Status</h3>
                                    <a href="{{ route('user.products.create') }}"
                                        class="text-xs font-bold text-[#ff6a00] hover:underline flex items-center gap-1">+
                                        Add Product</a>
                                </div>

                                <div class="flex flex-col items-center py-4">
                                    <div class="relative w-40 h-40">
                                        <svg class="w-full h-full transform -rotate-90">
                                            <circle cx="50%" cy="50%" r="40%" stroke="#e2e8f0" stroke-width="15%"
                                                fill="none" />
                                            <circle cx="50%" cy="50%" r="40%" stroke="#ff6a00" stroke-width="15%"
                                                fill="none" stroke-dasharray="250" stroke-dashoffset="200"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-xs font-medium text-slate-400">Total</span>
                                            <span
                                                class="text-2xl font-bold text-slate-900">{{ $stats['products_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-8 grid grid-cols-3 gap-4 w-full">
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div><span
                                                    class="text-[10px] font-bold text-slate-400 uppercase">Live</span>
                                            </div>
                                            <span
                                                class="text-sm font-bold text-slate-800">{{ $stats['live_products_count'] ?? 0 }}</span>
                                        </div>
                                        <div class="flex flex-col items-center border-x border-slate-100">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <div class="w-2 h-2 rounded-full bg-orange-400"></div><span
                                                    class="text-[10px] font-bold text-slate-400 uppercase">Pending</span>
                                            </div>
                                            <span
                                                class="text-sm font-bold text-slate-800">{{ $stats['pending_products_count'] ?? 0 }}</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <div class="w-2 h-2 rounded-full bg-orange-600"></div><span
                                                    class="text-[10px] font-bold text-slate-400 uppercase">Closed</span>
                                            </div>
                                            <span
                                                class="text-sm font-bold text-slate-800">{{ $stats['closed_inquiries_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'profile'" x-cloak x-transition.opacity.duration.300ms>

                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-medium text-slate-500 mb-2">
                                <a href="#" @click.prevent="activeTab = 'dashboard'" class="hover:text-slate-700">Dashboard</a>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="hover:text-slate-700 cursor-pointer">Profile</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="text-slate-700">User Profile</span>
                            </div>
                            <h2 class="text-[22px] font-bold text-[#1e293b]">Profile Details</h2>
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-6">

                        <div class="flex-1 min-w-0">
                            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">

                                <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                                    <div class="relative">
                                        <div id="profileImageContainer"
                                            class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center text-3xl font-normal text-[#64748b]">
                                            @if($user->profile_photo_path)
                                                <img src="{{ $user->profile_photo_path }}"
                                                    class="w-full h-full rounded-full object-cover" alt="Profile Photo">
                                            @else
                                                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div id="uploadProfileBtn" title="Change Profile Picture"
                                            class="absolute bottom-0 right-0 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-[#ff6a00] cursor-pointer shadow-md hover:bg-slate-50 transition-colors z-10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center pl-2">
                                        <h3 class="text-[20px] font-semibold text-[#1e293b] leading-tight mb-1">
                                            {{ $user->name ?? 'User' }}
                                        </h3>
                                        <div class="flex items-center gap-1.5 text-[13px] font-medium text-slate-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            @if(!empty($displayCountry))
                                                @include('components.country-badge', [
                                                    'countryCode' => $displayCountry,
                                                    'showName' => false,
                                                    'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600',
                                                ])
                                            @endif
                                            <span>{{ $displayCity ?: 'Location Not Set' }}
                                                @if(!empty($displayState))
                                                    , {{ $displayState }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="p-6">
                                    <div class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded font-semibold text-sm mb-6">
                                        Personal Details
                                    </div>

                                    <form id="personalDetailsForm" class="space-y-6" method="POST"
                                        action="{{ route('user.profile.personal.update') }}">
                                        @csrf
                                        <input type="hidden" name="profile_photo_path" id="profilePhotoPath"
                                            value="{{ old('profile_photo_path', $user->profile_photo_path ?? '') }}">
                                        <input type="file" id="profilePhotoFileInput" accept="image/*" class="hidden">

                                        @php
                                            $parts = preg_split('/\s+/', trim($user->name ?? ''));
                                            $first = $parts[0] ?? '';
                                            $last = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
                                        @endphp

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                    Login Number (ID)
                                                </label>
                                                <input type="text"
                                                    value="{{ $user->phone ? 'P:' . $user->phone : ($user->email ? 'E:' . $user->email : '') }}"
                                                    readonly
                                                    class="w-full border border-slate-200 rounded-md px-4 py-2.5 text-[14px] text-slate-500 font-bold bg-slate-50 cursor-not-allowed focus:outline-none">
                                            </div>

                                            <div class="relative hidden md:block">
                                                {{-- Spacer for grid alignment --}}
                                            </div>

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                    First Name<span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="first_name" value="{{ old('first_name', $first) }}"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] @error('first_name') border-red-500 @enderror">
                                                @error('first_name') <p class="text-[10px] text-red-500 mt-1">{{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                    Last Name<span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="last_name" value="{{ old('last_name', $last) }}"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] @error('last_name') border-red-500 @enderror">
                                                @error('last_name') <p class="text-[10px] text-red-500 mt-1">{{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                    Email<span class="text-red-500">*</span>
                                                </label>
                                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 pr-10 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] @error('email') border-red-500 @enderror">
                                                @error('email') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                @enderror
                                                <button type="button"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#ff6a00]">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="relative">
                                                <input type="email" name="alternate_email"
                                                    value="{{ old('alternate_email', $user->alternate_email) }}"
                                                    placeholder="Alternate Email"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] placeholder:text-slate-400">
                                            </div>

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500 z-10">
                                                    Office Contact<span class="text-red-500">*</span>
                                                </label>
                                                <div class="flex">
                                                    <div
                                                        class="px-3 py-2.5 border border-r-0 border-slate-300 rounded-l-md bg-slate-50 text-[14px] text-slate-600 font-medium">
                                                        {{ $dialCode }}
                                                    </div>
                                                    <div class="relative flex-1">
                                                        <input type="text" name="office_contact"
                                                            value="{{ old('office_contact', $user->office_contact) }}"
                                                            class="w-full border border-l-0 border-slate-300 rounded-r-md px-3 py-2.5 pr-10 text-[14px] text-slate-800 focus:outline-none @error('office_contact') border-red-500 @enderror">
                                                        @error('office_contact') <p class="text-[10px] text-red-500 mt-1">
                                                            {{ $message }}
                                                        </p> @enderror
                                                        <button type="button"
                                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#ff6a00]">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="alternate_mobile"
                                                    value="{{ old('alternate_mobile', $user->alternate_mobile) }}"
                                                    placeholder="Alternate Mobile No."
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] placeholder:text-slate-400">
                                            </div>

                                            <div class="relative">
                                                <label
                                                    class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500 z-10">
                                                    Whatsapp Number
                                                </label>
                                                <div class="flex items-center">
                                                    <div
                                                        class="px-3 py-2.5 border border-r-0 border-slate-300 rounded-l-md bg-slate-50 text-[14px] flex items-center gap-1 text-green-600 font-medium">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.482 5.236 3.483 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.656zm6.304-3.693c1.551.92 3.511 1.408 5.539 1.409 5.457 0 9.897-4.438 9.9-9.895 0-2.645-1.03-5.131-2.902-6.999-1.872-1.868-4.358-2.898-7.004-2.899-5.456 0-9.897 4.438-9.9 9.895-.001 2.124.549 4.191 1.595 5.997l-1.059 3.866 3.963-1.041z" />
                                                        </svg>
                                                        {{ $dialCode }}
                                                    </div>
                                                    <div class="relative flex-1">
                                                        <input type="text" name="whatsapp_number"
                                                            value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                                                            class="w-full border border-l-0 border-slate-300 rounded-r-md px-3 py-2.5 pr-10 text-[14px] text-slate-800 focus:outline-none">
                                                        <button type="button"
                                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#ff6a00]">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="designation"
                                                    value="{{ old('designation', $user->designation) }}"
                                                    placeholder="Designation / Job Title*"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 focus:outline-none focus:border-[#ff6a00] placeholder:text-slate-500 placeholder:font-medium">
                                            </div>
                                        </div>

                                        <div class="flex justify-end pt-4">
                                            <button type="submit"
                                                class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-8 py-2.5 rounded text-[14px] font-semibold uppercase transition-colors">
                                                Save Details
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="w-full lg:w-[320px] xl:w-[360px] space-y-6 flex-shrink-0">

                            <div class="flex gap-4">

                                <div
                                    class="flex-1 bg-gradient-to-r from-[#ea580c] to-[#f97316] rounded-xl p-3 text-white flex items-center gap-3">
                                    <div
                                        class="w-[50px] h-[50px] bg-white rounded-full flex items-center justify-center text-[#ea580c] font-bold text-sm border-4 border-orange-200 flex-shrink-0">
                                        60%
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[13px] leading-tight mb-0.5">My Profile Completeness</h4>
                                        <p class="text-[10px] leading-tight">Please add your Designation / Job Title</p>
                                    </div>
                                </div>


                        </div>
                    </div>
                </div>
            </div>

                <div x-show="activeTab === 'business'" x-cloak x-transition.opacity.duration.300ms
                    x-data="{ subTab: 'business' }">

                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-medium text-slate-500 mb-2">
                                <a href="#" @click.prevent="activeTab = 'dashboard'" class="hover:text-slate-700">Dashboard</a>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="hover:text-slate-700 cursor-pointer">Profile</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="text-slate-700">Business Profile</span>
                            </div>
                            <h2 class="text-[22px] font-bold text-[#1e293b]">Business Details</h2>
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-6">

                        <div class="flex-1 min-w-0">
                            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">

                                <div class="p-6 pb-4 border-b border-slate-100 flex items-center gap-4">
                                    <div class="relative">
                                        <div id="businessLogoContainer"
                                            class="w-16 h-16 bg-[#e0f0fe] rounded-full flex items-center justify-center text-3xl font-normal text-[#3b82f6]">
                                            @if($uploadedLogo || ($user->businessProfile->company_logo_path ?? false))
                                                <img src="{{ $uploadedLogo ?: $user->businessProfile->company_logo_path }}"
                                                    class="w-full h-full rounded-full object-cover" alt="Company Logo">
                                            @else
                                                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div id="uploadBusinessLogoBtn" title="Change Business Logo"
                                            class="absolute bottom-0 right-0 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-[#ff6a00] cursor-pointer shadow-md hover:bg-slate-50 transition-colors z-10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center pl-2">
                                        <h3 class="text-[20px] font-semibold text-[#1e293b] leading-tight mb-1">
                                            {{ $user->name ?? 'User' }}
                                        </h3>
                                        <div class="flex items-center gap-1.5 text-[13px] font-medium text-slate-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>{{ $displayCity ?: 'Location Not Set' }}
                                                @if(!empty($displayState))
                                                    , {{ $displayState }}
                                                @endif
                                                @if(!empty($displayCountry))
                                                    , {{ $displayCountry }}
                                                @endif</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="px-6 border-b border-slate-100 flex gap-8">
                                    <button @click="subTab = 'business'"
                                        :class="subTab === 'business' ? 'py-3 px-1 text-[13px] font-semibold text-[#ff6a00] border-b-2 border-[#ff6a00]' : 'py-3 px-1 text-[13px] font-medium text-slate-500 hover:text-slate-700'">Business
                                        Details</button>
                                    <button @click="subTab = 'additional'"
                                        :class="subTab === 'additional' ? 'py-3 px-1 text-[13px] font-semibold text-[#ff6a00] border-b-2 border-[#ff6a00]' : 'py-3 px-1 text-[13px] font-medium text-slate-500 hover:text-slate-700'">Additional
                                        Details</button>
                                    <button @click="subTab = 'certification'"
                                        :class="subTab === 'certification' ? 'py-3 px-1 text-[13px] font-semibold text-[#ff6a00] border-b-2 border-[#ff6a00]' : 'py-3 px-1 text-[13px] font-medium text-slate-500 hover:text-slate-700'">Certification
                                        &
                                        Award</button>
                                </div>


                                <div x-show="subTab === 'business'" class="px-6 py-6 pb-10">
                                    <form id="businessProfileForm" class="space-y-8" method="POST"
                                        action="{{ route('user.business-profile.update') }}">
                                        @csrf
                                        <input type="hidden" name="company_logo_path" id="businessLogoPath"
                                            value="{{ old('company_logo_path', $user->businessProfile->company_logo_path ?? '') }}">
                                        <input type="file" id="businessLogoFileInput" accept="image/*" class="hidden">



                                        @php
                                            $businessCountryCode = strtoupper(old('country_code', $user->businessProfile->country_code ?? $user->country_code ?? session('country', 'IN')));
                                            $businessProfileConfigs = config('business_profile.countries', []);
                                            $businessProfileConfig = $businessProfileConfigs[$businessCountryCode] ?? ($businessProfileConfigs['DEFAULT_INTL'] ?? []);
                                            $addressConfig = $businessProfileConfig['address'] ?? [];
                                            $taxFieldConfig = $businessProfileConfig['tax_fields'] ?? [];
                                            $documentFieldConfig = $businessProfileConfig['document_fields'] ?? [];

                                            $existingTaxJson = is_array($businessProfile?->tax_ids_json) ? $businessProfile->tax_ids_json : [];
                                            $existingDocsJson = $uploadedBusinessDocuments;

                                            $taxInputMap = [
                                                'tax_id_type' => 'tax_id_type',
                                                'tax_id_number' => 'tax_id_number',
                                                'tax_aadhaar' => 'tax_aadhaar',
                                                'tax_pan' => 'tax_pan',
                                                'tax_iec' => 'tax_iec',
                                                'tax_tan' => 'tax_tan',
                                                'tax_vat' => 'tax_vat',
                                            ];

                                            $taxJsonMap = [
                                                'tax_id_type' => 'tax_id_type',
                                                'tax_id_number' => 'tax_id_number',
                                                'tax_aadhaar' => 'aadhaar',
                                                'tax_pan' => 'pan',
                                                'tax_iec' => 'iec',
                                                'tax_tan' => 'tan',
                                                'tax_vat' => 'vat',
                                            ];
                                        @endphp


                                        {{-- Google Places (future) hidden fields - DB ready now --}}
                                        <input type="hidden" name="place_id" id="bp_place_id"
                                            value="{{ old('place_id', $user->businessProfile->place_id ?? '') }}">
                                        <input type="hidden" name="formatted_address" id="bp_formatted_address"
                                            value="{{ old('formatted_address', $user->businessProfile->formatted_address ?? '') }}">
                                        <input type="hidden" name="lat" id="bp_lat"
                                            value="{{ old('lat', $user->businessProfile->lat ?? '') }}">
                                        <input type="hidden" name="lng" id="bp_lng"
                                            value="{{ old('lng', $user->businessProfile->lng ?? '') }}">
                                        <input type="hidden" name="city_name" id="bp_city_name"
                                            value="{{ old('city_name', $user->businessProfile->city_name ?? '') }}">

                                        {{-- ===================== Company Details ===================== --}}
                                        <div x-data="{ openCompany: true }">
                                            <div @click="openCompany = !openCompany"
                                                class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded font-semibold text-[13px] mb-5 flex justify-between items-center cursor-pointer transition-colors hover:bg-[#7a92a1]">
                                                <span>Company Details</span>
                                                <svg :class="openCompany ? '' : 'rotate-180'"
                                                    class="w-4 h-4 transition-transform duration-200" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7">
                                                    </path>
                                                </svg>
                                            </div>

                                            <div x-show="openCompany" x-collapse
                                                class="grid grid-cols-1 md:grid-cols-2 gap-5 pl-1">
                                                <div class="relative md:col-span-2">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">Company
                                                        Name<span class="text-red-500">*</span></label>
                                                    <input type="text" name="company_name"
                                                        value="{{ old('company_name', $user->company_name) }}"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white @error('company_name') border-red-500 @enderror">
                                                    @error('company_name') <p class="text-[10px] text-red-500 mt-1">
                                                        {{ $message }}
                                                    </p> @enderror
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500 z-10">Office
                                                        Contact<span class="text-red-500">*</span></label>
                                                    <div class="flex">
                                                        <div
                                                            class="px-3 py-2.5 border border-r-0 border-slate-300 rounded-l-md bg-slate-50 text-[14px] text-slate-600 font-medium">
                                                            {{ $dialCode }}
                                                        </div>
                                                        <input type="text" name="company_phone"
                                                            value="{{ old('company_phone', $user->company_phone) }}"
                                                            class="flex-1 w-full border border-l-0 border-slate-300 rounded-r-md px-3 py-2.5 text-[14px] bg-white @error('company_phone') border-red-500 @enderror">
                                                        @error('company_phone') <p class="text-[10px] text-red-500 mt-1">
                                                            {{ $message }}
                                                        </p> @enderror
                                                    </div>
                                                </div>

                                                <div class="relative">
                                                    <input type="text" name="fax_number"
                                                        value="{{ old('fax_number', $user->businessProfile->fax_number ?? '') }}"
                                                        placeholder="FAX Number"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 bg-white @error('fax_number') border-red-500 @enderror">
                                                    @error('fax_number') <p class="text-[10px] text-red-500 mt-1">
                                                        {{ $message }}
                                                    </p> @enderror
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500 z-10">Ownership
                                                        Type<span class="text-red-500">*</span></label>
                                                    <select name="ownership_type"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 appearance-none focus:outline-none focus:border-slate-400 bg-white @error('ownership_type') border-red-500 @enderror">
                                                        <option value="">Select Type</option>
                                                        @foreach (['Manufacturer', 'Trader', 'Wholesaler', 'Distributor', 'Importer', 'Exporter'] as $opt)
                                                            <option value="{{ $opt }}" @selected(old('ownership_type', $user->businessProfile->ownership_type ?? '') === $opt)>{{ $opt }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('ownership_type') <p class="text-[10px] text-red-500 mt-1">
                                                        {{ $message }}
                                                    </p> @enderror
                                                    <div
                                                        class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[11px] font-semibold text-slate-500 mb-2 ml-1">Annual
                                                        Turnover*</label>
                                                    <div class="flex gap-2">
                                                        <div class="relative w-1/3">
                                                            <select name="annual_turnover_currency"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-[14px] text-slate-800 appearance-none bg-white">
                                                                @php $cur = $resolvedTurnoverCurrency; @endphp
                                                                @foreach($turnoverCurrencies as $currencyCode)
                                                                    <option value="{{ $currencyCode }}"
                                                                        @selected($cur === $currencyCode)>{{ $currencyCode }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#ff6a00]">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <div class="relative w-1/3">
                                                            <input type="text" name="annual_turnover_amount"
                                                                value="{{ old('annual_turnover_amount', $user->businessProfile->annual_turnover_amount ?? '') }}"
                                                                placeholder="Amount"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 @error('annual_turnover_amount') border-red-500 @enderror">
                                                            @error('annual_turnover_amount') <p
                                                                class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="relative w-1/3">
                                                            <select name="annual_turnover_unit"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-[14px] text-slate-800 appearance-none bg-white">
                                                                @php $unit = old('annual_turnover_unit', $user->businessProfile->annual_turnover_unit ?? ($turnoverUnits[0] ?? 'Million')); @endphp
                                                                @foreach ($turnoverUnits as $opt)
                                                                    <option value="{{ $opt }}" @selected($unit === $opt)>
                                                                        {{ $opt }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#ff6a00]">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">Year
                                                        of Establishment<span class="text-red-500">*</span></label>
                                                    <input type="text" name="year_established"
                                                        value="{{ old('year_established', $user->businessProfile->year_established ?? '') }}"
                                                        placeholder="e.g. 2015"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 bg-white @error('year_established') border-red-500 @enderror">
                                                    @error('year_established') <p class="text-[10px] text-red-500 mt-1">
                                                        {{ $message }}
                                                    </p> @enderror
                                                </div>
                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">Number
                                                        of Employees<span class="text-red-500">*</span></label>
                                                    <input type="text" name="employee_count"
                                                        value="{{ old('employee_count', $user->businessProfile->employee_count ?? '') }}"
                                                        placeholder="e.g. 50"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 bg-white @error('employee_count') border-red-500 @enderror">
                                                    @error('employee_count') <p class="text-[10px] text-red-500 mt-1">
                                                        {{ $message }}
                                                    </p> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ===================== Address Details ===================== --}}
                                        <div x-data="{ openAddress: true }">
                                            <div @click="openAddress = !openAddress"
                                                class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded font-semibold text-[13px] mb-5 flex justify-between items-center cursor-pointer transition-colors hover:bg-[#7a92a1]">
                                                <span>Address Details</span>
                                                <svg :class="openAddress ? '' : 'rotate-180'"
                                                    class="w-4 h-4 transition-transform duration-200" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7">
                                                    </path>
                                                </svg>
                                            </div>

                                            <div x-show="openAddress" x-transition.opacity.duration.300ms
                                                class="grid grid-cols-1 md:grid-cols-2 gap-5 pl-1">
                                                <div class="relative md:col-span-2">
                                                    <input type="text" name="company_address"
                                                        value="{{ old('company_address', $user->company_address) }}"
                                                        id="bp_company_address" placeholder="Address*"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 bg-white @error('company_address') border-red-500 @enderror">
                                                    @error('company_address')
                                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                        {{ $addressConfig['postal_label'] ?? 'Postal Code' }}<span
                                                            class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="pincode"
                                                        value="{{ old('pincode', $user->pincode) }}" id="bp_pincode"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white @error('pincode') border-red-500 @enderror">
                                                    @error('pincode')
                                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                        {{ $addressConfig['city_label'] ?? 'City' }}<span
                                                            class="text-red-500">*</span>
                                                    </label>

                                                    <input type="text" id="bp_city_input"
                                                        placeholder="Start typing {{ strtolower($addressConfig['city_label'] ?? 'city') }}..."
                                                        value="{{ old('city_name', $user->businessProfile->city_name ?? '') }}"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 placeholder:text-slate-400 bg-white @error('city_name') border-red-500 @enderror">
                                                    @error('city_name')
                                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror

                                                    <input type="hidden" name="city_id" id="bp_city_id"
                                                        value="{{ old('city_id', $user->city_id) }}">
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                        {{ $addressConfig['state_label'] ?? 'State / Region' }}
                                                    </label>
                                                    <input type="text" name="state_name" id="bp_state_name"
                                                        value="{{ old('state_name', $user->businessProfile->state_name ?? '') }}"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white">
                                                </div>

                                                <div class="relative">
                                                    <label
                                                        class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                        {{ $addressConfig['country_label'] ?? 'Country' }}
                                                    </label>
                                                    <input type="text" value="{{ $businessCountryCode }}"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-slate-50"
                                                        readonly>
                                                    <input type="hidden" name="country_code" id="bp_country_code"
                                                        value="{{ old('country_code', $businessCountryCode) }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ===================== Taxation Details ===================== --}}
                                        <div x-data="{ openTax: true }">
                                            <div @click="openTax = !openTax"
                                                class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded font-semibold text-[13px] mb-5 flex justify-between items-center cursor-pointer transition-colors hover:bg-[#7a92a1]">
                                                <span>Taxation Details</span>
                                                <svg :class="openTax ? '' : 'rotate-180'"
                                                    class="w-4 h-4 transition-transform duration-200" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7">
                                                    </path>
                                                </svg>
                                            </div>

                                            <div x-show="openTax" x-transition.opacity.duration.300ms
                                                class="grid grid-cols-1 md:grid-cols-2 gap-5 pl-1">

                                                @foreach($taxFieldConfig as $field)
                                                    @php
                                                        $fieldKey = $field['key'];
                                                        $inputName = $taxInputMap[$fieldKey] ?? $fieldKey;
                                                        $jsonKey = $taxJsonMap[$fieldKey] ?? $fieldKey;
                                                        $fieldValue = old($inputName, $existingTaxJson[$jsonKey] ?? '');
                                                        $isTypeSelect = $fieldKey === 'tax_id_type';
                                                    @endphp

                                                    <div class="relative">
                                                        @if($isTypeSelect)
                                                            <label
                                                                class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">
                                                                {{ $field['label'] }}
                                                            </label>
                                                            <input type="text" name="{{ $inputName }}" value="{{ $fieldValue }}"
                                                                placeholder="{{ $field['placeholder'] ?? $field['label'] }}"
                                                                class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white @error($inputName) border-red-500 @enderror">
                                                        @else
                                                            <input type="text" name="{{ $inputName }}" value="{{ $fieldValue }}"
                                                                placeholder="{{ $field['placeholder'] ?? $field['label'] }}"
                                                                class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-[#f8fafc] @error($inputName) border-red-500 @enderror">
                                                        @endif

                                                        @error($inputName)
                                                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                @endforeach

                                                @foreach($documentFieldConfig as $doc)
                                                    @php
                                                        $docKey = $doc['key'];
                                                        $docValue = old('doc_' . $docKey, $existingDocsJson[$docKey] ?? '');
                                                    @endphp

                                                    <div class="relative min-w-0 flex items-center gap-2">
                                                        <input type="hidden" name="doc_{{ $docKey }}"
                                                            id="doc_{{ $docKey }}_hidden" value="{{ $docValue }}">
                                                        <input type="text" id="doc_{{ $docKey }}_display"
                                                            placeholder="{{ $doc['label'] }}"
                                                            value="{{ !empty($docValue) ? $uploadedFileLabel($docValue) : '' }}"
                                                            title="{{ !empty($docValue) ? $uploadedFileLabel($docValue) : '' }}"
                                                            class="min-w-0 flex-1 border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-500 bg-[#f8fafc] cursor-default pointer-events-none"
                                                            readonly>
                                                        <button type="button" onclick="viewDocument('{{ $docKey }}')"
                                                            class="w-[42px] shrink-0 border border-slate-300 rounded-md flex items-center justify-center text-slate-400 hover:text-slate-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button type="button" onclick="openDocumentUpload('{{ $docKey }}')"
                                                            class="w-[42px] shrink-0 border border-slate-300 rounded-md flex items-center justify-center text-slate-400 hover:text-[#ff6a00]">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex justify-end pt-2">
                                            <button type="submit"
                                                class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-8 py-2.5 rounded text-[14px] font-semibold uppercase transition-colors">
                                                Save Details
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Additional Details Tab --}}
                                <div x-show="subTab === 'additional'" x-cloak class="px-6 py-6 pb-10"
                                    x-data="{
                                        additionalInfo: {
                                            video_url: {{ Js::from($resolveBusinessProfileValue('video_url', 'video_url', '')) }},
                                            video_title: {{ Js::from($resolveBusinessProfileValue('video_title', 'video_title', '')) }},
                                            video_category: {{ Js::from($resolveBusinessProfileValue('video_category', 'video_category', '')) }},
                                            company_description: {{ Js::from($resolveBusinessProfileValue('company_description', 'company_description', '')) }},
                                            excluded_cities: {{ Js::from($resolveBusinessProfileValue('excluded_cities', 'excluded_cities', '')) }},
                                            preferred_cities: {{ Js::from($resolveBusinessProfileValue('preferred_cities', 'preferred_cities', '')) }},
                                            preferred_states: {{ Js::from($resolveBusinessProfileValue('preferred_states', 'preferred_states', '')) }},
                                            working_days_from: {{ Js::from($resolveBusinessProfileValue('working_days_from', 'working_days_from', '')) }},
                                            working_days_to: {{ Js::from($resolveBusinessProfileValue('working_days_to', 'working_days_to', '')) }},
                                            working_time_from: {{ Js::from($resolveBusinessProfileValue('working_time_from', 'working_time_from', '')) }},
                                            working_time_to: {{ Js::from($resolveBusinessProfileValue('working_time_to', 'working_time_to', '')) }},
                                            payment_modes: {{ Js::from($uploadedPaymentModes) }},
                                        },
                                        logo: {{ Js::from($uploadedLogo) }},
                                        photos: {{ Js::from($uploadedCompanyPhotos) }},
                                        certifications: {{ Js::from($uploadedCertifications) }},
                                        awards: {{ Js::from($uploadedAwards) }},

                                        openLogoWidget: async function () {
                                            try {
                                                const result = await uploadSingleFromPicker({
                                                    folder: 'business-logos',
                                                    accept: 'image/png,image/jpeg,image/webp',
                                                    maxBytes: 5 * 1024 * 1024,
                                                });

                                                if (result) this.logo = result.url;
                                            } catch (error) {
                                                console.error('Logo Upload Error:', error);
                                                alert(error.message || 'Logo upload failed.');
                                            }
                                        },
                                        openPhotosWidget: async function () {
                                            const remaining = Math.max(0, 6 - this.photos.length);
                                            if (remaining === 0) return;

                                            try {
                                                const uploadedUrls = await uploadMultipleFromPicker({
                                                    folder: 'company-photos',
                                                    accept: 'image/png,image/jpeg,image/webp',
                                                    maxFiles: remaining,
                                                    maxBytes: 5 * 1024 * 1024,
                                                });

                                                if (uploadedUrls.length) this.photos.push(...uploadedUrls);
                                            } catch (error) {
                                                console.error('Photo Upload Error:', error);
                                                alert(error.message || 'Photo upload failed.');
                                            }
                                        },
                                        openCertWidget: async function () {
                                            const remaining = Math.max(0, 6 - this.certifications.length);
                                            if (remaining === 0) return;

                                            try {
                                                const uploadedUrls = await uploadMultipleFromPicker({
                                                    folder: 'certifications',
                                                    accept: 'image/png,image/jpeg,image/webp,application/pdf',
                                                    maxFiles: remaining,
                                                    maxBytes: 10 * 1024 * 1024,
                                                });

                                                if (uploadedUrls.length) this.certifications.push(...uploadedUrls);
                                            } catch (error) {
                                                console.error('Certification Upload Error:', error);
                                                alert(error.message || 'Certification upload failed.');
                                            }
                                        },
                                        openAwardWidget: async function () {
                                            const remaining = Math.max(0, 6 - this.awards.length);
                                            if (remaining === 0) return;

                                            try {
                                                const uploadedUrls = await uploadMultipleFromPicker({
                                                    folder: 'awards',
                                                    accept: 'image/png,image/jpeg,image/webp,application/pdf',
                                                    maxFiles: remaining,
                                                    maxBytes: 10 * 1024 * 1024,
                                                });

                                                if (uploadedUrls.length) this.awards.push(...uploadedUrls);
                                            } catch (error) {
                                                console.error('Awards Upload Error:', error);
                                                alert(error.message || 'Awards upload failed.');
                                            }
                                        },
                                        openVideoUpload: async function () {
                                            try {
                                                const result = await uploadSingleFromPicker({
                                                    folder: 'company-videos',
                                                    accept: 'video/mp4,video/webm,video/quicktime',
                                                    maxBytes: 50 * 1024 * 1024,
                                                });

                                                if (result) this.additionalInfo.video_url = result.url;
                                            } catch (error) {
                                                console.error('Video Upload Error:', error);
                                                alert(error.message || 'Video upload failed.');
                                            }
                                        }
                                    }">
                                    <form class="space-y-8" method="POST"
                                        action="{{ route('user.business-profile.update') }}">
                                        @csrf
                                        <input type="hidden" name="form_type" value="additional">

                                        <!-- Hidden Inputs for JS Array Data -->
                                        <input type="hidden" name="additional_info[logo]" :value="logo">
                                        <template x-for="p in photos">
                                            <input type="hidden" name="additional_info[photos][]" :value="p">
                                        </template>

                                        <!-- Company Logo -->
                                        <div>
                                            <h4 class="text-[14px] font-semibold text-[#1e293b] mb-3">Company Logo</h4>
                                            <div class="flex items-center gap-6">
                                                <div
                                                    class="w-24 h-24 border border-dashed border-slate-300 rounded-lg flex items-center justify-center bg-slate-50 overflow-hidden relative group">
                                                    <template x-if="logo">
                                                        <img :src="logo" class="w-full h-full object-contain">
                                                    </template>
                                                    <template x-if="!logo">
                                                        <svg class="w-8 h-8 text-slate-300" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </template>
                                                    <button type="button" @click="openLogoWidget()"
                                                        class="absolute inset-0 bg-black/40 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center text-[10px] font-bold uppercase transition-opacity">Change</button>
                                                </div>
                                                <div class="text-xs text-slate-500 space-y-1">
                                                    <p class="font-bold text-[#ff6a00]">Add your company logo.</p>
                                                    <p>200KB max. JPEG or PNG format only.</p>
                                                    <button type="button" @click="openLogoWidget()"
                                                        class="text-blue-600 font-bold hover:underline">Upload Logo</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company Photo -->
                                        <div>
                                            <h4 class="text-[14px] font-semibold text-[#1e293b] mb-3">Company Photo</h4>
                                            <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-4">
                                                <template x-for="(p, index) in photos" :key="index">
                                                    <div
                                                        class="aspect-square border border-slate-200 rounded-lg overflow-hidden relative group">
                                                        <img :src="p" class="w-full h-full object-cover">
                                                        <button type="button" @click="photos.splice(index, 1)"
                                                            class="absolute top-1 right-1 bg-white/80 text-red-500 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="photos.length < 6" @click="openPhotosWidget()"
                                                    class="aspect-square border border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-[#ff6a00] transition-colors bg-white">
                                                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="text-[10px] font-medium">Add Photo</span>
                                                </button>
                                            </div>
                                            <p class="text-[11px] text-[#ff6a00] font-medium mt-3">Add up to 6 images to
                                                enhance your company photo.</p>
                                            <p class="text-[11px] text-slate-500 mt-1">200KB max. JPEG or PNG format only.
                                                Suggested photo width and height: 270*270px</p>
                                        </div>

                                        <!-- Company Video -->
                                        <div>
                                            <h4 class="text-[14px] font-semibold text-[#1e293b] mb-3">Company Video</h4>
                                            <div class="flex gap-5 flex-col md:flex-row">
                                                <div
                                                    class="w-full md:w-1/2 bg-slate-100 h-48 rounded-lg overflow-hidden relative flex items-center justify-center group">
                                                    <template x-if="additionalInfo.video_url">
                                                        <video class="w-full h-full object-cover" controls>
                                                            <source :src="additionalInfo.video_url" type="video/mp4">
                                                        </video>
                                                    </template>
                                                    <template x-if="!additionalInfo.video_url">
                                                        <div class="text-center p-4">
                                                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <p class="text-xs text-slate-400">No Video Uploaded</p>
                                                        </div>
                                                    </template>
                                                    <div
                                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <button type="button"
                                                            @click="openVideoUpload()"
                                                            class="bg-white text-[#ff6a00] px-4 py-2 rounded text-[12px] font-bold uppercase">Upload
                                                            Video</button>
                                                    </div>
                                                    <input type="hidden" name="additional_info[video_url]"
                                                        :value="additionalInfo.video_url">
                                                </div>
                                                <div class="w-full md:w-1/2 flex flex-col gap-4">
                                                    <div class="relative">
                                                        <label
                                                            class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">Video
                                                            Title</label>
                                                        <input type="text" name="additional_info[video_title]"
                                                            x-model="additionalInfo.video_title"
                                                            placeholder="Enter Video Title"
                                                            class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white text-slate-800 placeholder:text-slate-400">
                                                    </div>
                                                    <div class="relative">
                                                        <label
                                                            class="absolute -top-2 left-3 bg-white px-1 text-[11px] font-semibold text-slate-500">Video
                                                            Category</label>
                                                        <select name="additional_info[video_category]"
                                                            x-model="additionalInfo.video_category"
                                                            class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] text-slate-800 appearance-none bg-white">
                                                            <option value="">Select Category</option>
                                                            <option value="company_profile">Company Profile</option>
                                                            <option value="manufacturing_unit">Manufacturing Unit</option>
                                                            <option value="product_demo">Product Demo</option>
                                                        </select>
                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-[#3b82f6]">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company Description -->
                                        <div>
                                            <div
                                                class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded-t font-semibold text-[13px]">
                                                Company Description
                                            </div>
                                            <div class="border border-t-0 border-slate-300 rounded-b bg-white">
                                                <textarea name="additional_info[company_description]" rows="6"
                                                    x-model="additionalInfo.company_description"
                                                    class="w-full p-4 text-[14px] text-slate-800 focus:outline-none resize-none bg-white"></textarea>
                                                <div
                                                    class="bg-[#f8fafc] border-t border-slate-300 p-2.5 flex items-center gap-5 text-slate-600 h-10">
                                                    <span
                                                        class="text-[13px] font-medium border-r border-slate-300 pr-5">Normal</span>
                                                    <button type="button"
                                                        class="hover:text-black font-bold text-[14px]">B</button>
                                                    <button type="button"
                                                        class="hover:text-black italic font-serif text-[14px]">I</button>
                                                    <button type="button"
                                                        class="hover:text-black underline text-[14px]">U</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Collapsible Dropdowns -->
                                        <div class="space-y-4">
                                            {{-- Payment Mode --}}
                                            <div x-data="{ openPay: false, modes: additionalInfo.payment_modes || [] }"
                                                class="border border-slate-300 rounded-md bg-white">
                                                <button type="button" @click="openPay = !openPay"
                                                    class="w-full flex items-center justify-between px-4 py-3 text-[14px] text-slate-600 rounded-md hover:bg-slate-50 transition-colors">
                                                    Mode of Payment
                                                    <svg :class="openPay ? 'rotate-180' : ''"
                                                        class="w-4 h-4 text-[#3b82f6] transition-transform" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="openPay" x-transition.opacity.duration.300ms
                                                    class="p-4 border-t border-slate-200">
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                        @foreach(['Cash', 'Cheque', 'Credit Card', 'Bank Transfer', 'Online Payment', 'UPI', 'Letter of Credit', 'Western Union'] as $mode)
                                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                                <input type="checkbox" name="additional_info[payment_modes][]"
                                                                    value="{{ $mode }}" {{ in_array($mode, $uploadedPaymentModes) ? 'checked' : '' }}
                                                                    class="w-4 h-4 rounded border-slate-300 text-[#ff6a00] focus:ring-[#ff6a00]">
                                                                <span
                                                                    class="text-[13px] text-slate-600 group-hover:text-slate-900">{{ $mode }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Working Days & Time --}}
                                            <div class="border border-slate-300 rounded-md p-5 bg-white space-y-5">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-24 text-[13px] text-[#3b82f6] font-medium">Working Days
                                                    </div>
                                                    <div class="flex-1 flex items-center gap-4">
                                                        <div class="relative flex-1">
                                                            <select name="additional_info[working_days_from]"
                                                                x-model="additionalInfo.working_days_from"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-[14px] text-slate-600 appearance-none bg-white">
                                                                <option value="">Select</option>
                                                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                                    <option value="{{ $day }}">{{ $day }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#3b82f6]">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <span class="text-[13px] font-bold text-black">To</span>
                                                        <div class="relative flex-1">
                                                            <select name="additional_info[working_days_to]"
                                                                x-model="additionalInfo.working_days_to"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-[14px] text-slate-600 appearance-none bg-white">
                                                                <option value="">Select</option>
                                                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                                    <option value="{{ $day }}">{{ $day }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#3b82f6]">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <div class="w-24 text-[13px] text-[#3b82f6] font-medium">Working Time
                                                    </div>
                                                    <div class="flex-1 flex items-center gap-4">
                                                        <div class="relative flex-1">
                                                            <input type="time" name="additional_info[working_time_from]"
                                                                x-model="additionalInfo.working_time_from"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-[14px] text-slate-600 bg-white">
                                                        </div>
                                                        <span class="text-[13px] font-bold text-black">To</span>
                                                        <div class="relative flex-1">
                                                            <input type="time" name="additional_info[working_time_to]"
                                                                x-model="additionalInfo.working_time_to"
                                                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-[14px] text-slate-600 bg-white">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Preferred States --}}
                                            <div x-data="{ openStates: false }"
                                                class="border border-slate-300 rounded-md bg-white">
                                                <button type="button" @click="openStates = !openStates"
                                                    class="w-full flex items-center justify-between px-4 py-3 text-[14px] text-slate-500 rounded-md hover:bg-slate-50 transition-colors">
                                                    Add Your Preferred Business States
                                                    <svg :class="openStates ? 'rotate-180' : ''"
                                                        class="w-4 h-4 text-[#3b82f6] transition-transform" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="openStates" x-transition.opacity.duration.300ms
                                                    class="p-4 border-t border-slate-200">
                                                    <input type="text" name="additional_info[preferred_states]"
                                                        x-model="additionalInfo.preferred_states"
                                                        placeholder="e.g. Maharashtra, Gujarat, Delhi (Comma separated)"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white text-slate-800 placeholder:text-slate-400">
                                                </div>
                                            </div>

                                            <div x-data="{ openCitiesEx: false }"
                                                class="border border-slate-300 rounded-md bg-white">
                                                <button type="button" @click="openCitiesEx = !openCitiesEx"
                                                    class="w-full flex items-center justify-between px-4 py-3 text-[14px] text-slate-500 rounded-md hover:bg-slate-50 transition-colors">
                                                    Add Cities to Exclude (Non-Business)
                                                    <svg :class="openCitiesEx ? 'rotate-180' : ''"
                                                        class="w-4 h-4 text-[#3b82f6] transition-transform" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="openCitiesEx" x-transition.opacity.duration.300ms
                                                    class="p-4 border-t border-slate-200">
                                                    <input type="text" name="additional_info[excluded_cities]"
                                                        x-model="additionalInfo.excluded_cities"
                                                        placeholder="e.g. Mumbai, Pune (Comma separated)"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white text-slate-800 placeholder:text-slate-400">
                                                </div>
                                            </div>

                                            <div x-data="{ openCitiesPref: false }"
                                                class="border border-slate-300 rounded-md bg-white">
                                                <button type="button" @click="openCitiesPref = !openCitiesPref"
                                                    class="w-full flex items-center justify-between px-4 py-3 text-[14px] text-slate-500 rounded-md hover:bg-slate-50 transition-colors">
                                                    Add Your Preferred Business Cities
                                                    <svg :class="openCitiesPref ? 'rotate-180' : ''"
                                                        class="w-4 h-4 text-[#3b82f6] transition-transform" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="openCitiesPref" x-transition.opacity.duration.300ms
                                                    class="p-4 border-t border-slate-200">
                                                    <input type="text" name="additional_info[preferred_cities]"
                                                        x-model="additionalInfo.preferred_cities"
                                                        placeholder="e.g. Surat, Indore (Comma separated)"
                                                        class="w-full border border-slate-300 rounded-md px-4 py-2.5 text-[14px] bg-white text-slate-800 placeholder:text-slate-400">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end pt-4">
                                            <button type="submit"
                                                class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-8 py-2.5 rounded text-[14px] font-semibold uppercase transition-colors">
                                                Save Details
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Certification Tab --}}
                                <div x-show="subTab === 'certification'" x-cloak class="px-6 py-6 pb-10"
                                    x-data="{
                                        additionalInfo: {
                                            standard_certification: {{ Js::from($resolveBusinessProfileValue('standard_certification', 'standard_certification', '')) }},
                                            membership_affiliations: {{ Js::from($resolveBusinessProfileValue('membership_affiliations', 'membership_affiliations', '')) }},
                                            award_recognition: {{ Js::from($resolveBusinessProfileValue('award_recognition', 'award_recognition', '')) }},
                                        },
                                        certifications: {{ Js::from($uploadedCertifications) }},
                                        awards: {{ Js::from($uploadedAwards) }},
                                        openCertWidget: async function () {
                                            const remaining = Math.max(0, 6 - this.certifications.length);
                                            if (remaining === 0) return;

                                            try {
                                                const uploadedUrls = await uploadMultipleFromPicker({
                                                    folder: 'certifications',
                                                    accept: 'image/png,image/jpeg,image/webp,application/pdf',
                                                    maxFiles: remaining,
                                                    maxBytes: 10 * 1024 * 1024,
                                                });

                                                if (uploadedUrls.length) this.certifications.push(...uploadedUrls);
                                            } catch (error) {
                                                console.error('Certification Upload Error:', error);
                                                alert(error.message || 'Certification upload failed.');
                                            }
                                        },
                                        openAwardWidget: async function () {
                                            const remaining = Math.max(0, 6 - this.awards.length);
                                            if (remaining === 0) return;

                                            try {
                                                const uploadedUrls = await uploadMultipleFromPicker({
                                                    folder: 'awards',
                                                    accept: 'image/png,image/jpeg,image/webp,application/pdf',
                                                    maxFiles: remaining,
                                                    maxBytes: 10 * 1024 * 1024,
                                                });

                                                if (uploadedUrls.length) this.awards.push(...uploadedUrls);
                                            } catch (error) {
                                                console.error('Awards Upload Error:', error);
                                                alert(error.message || 'Awards upload failed.');
                                            }
                                        }
                                    }">
                                    <form class="space-y-6" method="POST"
                                        action="{{ route('user.business-profile.update') }}">
                                        @csrf
                                        <input type="hidden" name="form_type" value="certification">

                                        <!-- Hidden inputs for images -->
                                        <template x-for="c in certifications">
                                            <input type="hidden" name="additional_info[certifications][]" :value="c">
                                        </template>
                                        <template x-for="a in awards">
                                            <input type="hidden" name="additional_info[awards][]" :value="a">
                                        </template>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <input type="text" name="additional_info[standard_certification]"
                                                    x-model="additionalInfo.standard_certification"
                                                    placeholder="Standard Certification"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-600 bg-white placeholder:text-slate-500">
                                            </div>
                                            <div>
                                                <input type="text" name="additional_info[membership_affiliations]"
                                                    x-model="additionalInfo.membership_affiliations"
                                                    placeholder="Membership & Affiliations"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-600 bg-white placeholder:text-slate-500">
                                            </div>
                                            <div class="md:col-span-2">
                                                <input type="text" name="additional_info[award_recognition]"
                                                    x-model="additionalInfo.award_recognition"
                                                    placeholder="Award & Recognition"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-600 bg-white placeholder:text-slate-500">
                                            </div>
                                        </div>

                                        <div class="pt-2">
                                            <h4 class="text-[14px] font-semibold text-[#1e293b] mb-3">Upload Certification
                                            </h4>
                                            <div class="flex flex-wrap gap-4 mb-4">
                                                <template x-for="(c, index) in certifications" :key="index">
                                                    <div
                                                        class="w-[120px] h-[120px] border border-slate-200 rounded-xl overflow-hidden relative group">
                                                        <template x-if="isPreviewableImage(c)">
                                                            <img :src="c" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!isPreviewableImage(c)">
                                                            <a :href="c" target="_blank"
                                                                class="flex h-full w-full flex-col items-center justify-center gap-2 bg-slate-50 px-3 text-center text-slate-600">
                                                                <svg class="h-7 w-7 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v5h5"></path>
                                                                </svg>
                                                                <span class="line-clamp-2 text-[11px] font-medium" x-text="fileLabel(c)"></span>
                                                                <span class="text-[10px] uppercase tracking-wide text-slate-400" x-text="isPdfFile(c) ? 'PDF' : 'File'"></span>
                                                            </a>
                                                        </template>
                                                        <button type="button" @click="certifications.splice(index, 1)"
                                                            class="absolute top-2 right-2 bg-white/80 text-red-500 rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="certifications.length < 6"
                                                    @click="openCertWidget()"
                                                    class="w-[120px] h-[120px] border border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-[#ff6a00] transition-colors bg-white">
                                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="text-[13px] font-medium">Add Photo</span>
                                                </button>
                                            </div>
                                            <p class="text-[12px] text-[#ff6a00] font-medium mt-3 mb-0.5">Add up to 6
                                                certification in your business details.</p>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Supports JPEG, PNG,
                                                WEBP, or PDF uploads. Suggested image size: 270*270px</p>
                                        </div>

                                        <div class="pt-2">
                                            <h4 class="text-[14px] font-semibold text-[#1e293b] mb-3">Upload Award Image
                                            </h4>
                                            <div class="flex flex-wrap gap-4 mb-4">
                                                <template x-for="(a, index) in awards" :key="index">
                                                    <div
                                                        class="w-[120px] h-[120px] border border-slate-200 rounded-xl overflow-hidden relative group">
                                                        <template x-if="isPreviewableImage(a)">
                                                            <img :src="a" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!isPreviewableImage(a)">
                                                            <a :href="a" target="_blank"
                                                                class="flex h-full w-full flex-col items-center justify-center gap-2 bg-slate-50 px-3 text-center text-slate-600">
                                                                <svg class="h-7 w-7 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v5h5"></path>
                                                                </svg>
                                                                <span class="line-clamp-2 text-[11px] font-medium" x-text="fileLabel(a)"></span>
                                                                <span class="text-[10px] uppercase tracking-wide text-slate-400" x-text="isPdfFile(a) ? 'PDF' : 'File'"></span>
                                                            </a>
                                                        </template>
                                                        <button type="button" @click="awards.splice(index, 1)"
                                                            class="absolute top-2 right-2 bg-white/80 text-red-500 rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="awards.length < 6" @click="openAwardWidget()"
                                                    class="w-[120px] h-[120px] border border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-[#ff6a00] transition-colors bg-white">
                                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="text-[13px] font-medium">Add Photo</span>
                                                </button>
                                            </div>
                                            <p class="text-[12px] text-[#ff6a00] font-medium mt-3 mb-0.5">Add up to 6 Award
                                                image in your business details.</p>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Supports JPEG, PNG,
                                                WEBP, or PDF uploads. Suggested image size: 270*270px</p>
                                        </div>

                                        <div class="flex justify-end pt-4">
                                            <button type="submit"
                                                class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-8 py-2.5 rounded text-[14px] font-semibold uppercase transition-colors shadow-sm">
                                                Save Details
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="w-full lg:w-[320px] xl:w-[360px] space-y-6 flex-shrink-0">

                            <div class="flex gap-4">

                                <div
                                    class="flex-1 bg-gradient-to-r from-[#ea580c] to-[#f97316] rounded-xl p-3 text-white flex items-center gap-3">
                                    <div
                                        class="w-[50px] h-[50px] bg-white rounded-full flex items-center justify-center text-[#ea580c] font-bold text-sm border-4 border-orange-200 flex-shrink-0">
                                        {{ $user->profile_completeness }}%
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[13px] leading-tight mb-0.5">Business Profile Completeness
                                        </h4>
                                        <p class="text-[10px] leading-tight">Please add your Designation / Job Title</p>
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>

                </div>

                <div x-show="activeTab === 'bank'" x-cloak x-transition.opacity.duration.300ms>

                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-medium text-slate-500 mb-2">
                                <a href="#" @click.prevent="activeTab = 'dashboard'" class="hover:text-slate-700">Dashboard</a>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="hover:text-slate-700 cursor-pointer">Profile</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="text-slate-700">Bank Details</span>
                            </div>
                            <h2 class="text-[22px] font-bold text-[#1e293b]">Bank Details</h2>
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-6">

                        <div class="flex-1 min-w-0">
                            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm p-6 pb-8">
                                <form class="space-y-6 mt-2" method="POST" action="{{ route('user.bank-details.update') }}">
                                    @csrf

                                    <div x-data="{ openAccount: true }">
                                        <div @click="openAccount = !openAccount"
                                            class="bg-[#8ba3b2] text-white px-4 py-2.5 rounded font-semibold text-[13px] mb-5 flex justify-between items-center cursor-pointer transition-colors hover:bg-[#7a92a1]">
                                            <span>Account Details</span>
                                            <svg :class="openAccount ? 'rotate-180' : ''"
                                                class="w-4 h-4 transition-transform duration-200" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </div>

                                        <div x-show="openAccount" x-transition.opacity.duration.300ms
                                            class="grid grid-cols-1 md:grid-cols-2 gap-5 pl-1">
                                            <div class="relative md:col-span-2">
                                                <select name="account_type"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-500 appearance-none focus:outline-none focus:border-[#3b82f6] bg-white @error('account_type') border-red-500 @enderror">
                                                    <option value="">{{ $bankAccountTypeLabel }}</option>
                                                    @foreach ($bankAccountTypeOptions as $opt)
                                                        <option value="{{ $opt }}" @selected(old('account_type', $bankDetail->account_type ?? '') === $opt)>
                                                            {{ $opt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('account_type') <p class="text-[10px] text-red-500 mt-1">
                                                    {{ $message }}
                                                </p> @enderror
                                                <div
                                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#3b82f6]">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="account_holder_name"
                                                    value="{{ old('account_holder_name', $bankDetail->account_holder_name ?? '') }}"
                                                    placeholder="{{ $bankAccountHolderLabel }}*"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-800 placeholder:text-slate-400 @error('account_holder_name') border-red-500 @enderror">
                                                @error('account_holder_name') <p class="text-[10px] text-red-500 mt-1">
                                                    {{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="account_number"
                                                    inputmode="numeric"
                                                    maxlength="18"
                                                    pattern="[0-9]{9,18}"
                                                    value="{{ old('account_number', $bankDetail->account_number ?? '') }}"
                                                    placeholder="{{ $bankAccountNumberLabel }}* (9-18 digits)"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-800 placeholder:text-slate-400 @error('account_number') border-red-500 @enderror">
                                                @error('account_number') <p class="text-[10px] text-red-500 mt-1">
                                                    {{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="confirm_account_number"
                                                    inputmode="numeric"
                                                    maxlength="18"
                                                    pattern="[0-9]{9,18}"
                                                    value="{{ old('confirm_account_number', $bankDetail->account_number ?? '') }}"
                                                    placeholder="{{ $bankAccountConfirmLabel }}*"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-800 placeholder:text-slate-400 @error('confirm_account_number') border-red-500 @enderror">
                                                @error('confirm_account_number') <p class="text-[10px] text-red-500 mt-1">
                                                    {{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="ifsc_code"
                                                    maxlength="11"
                                                    style="text-transform:uppercase;"
                                                    oninput="this.value=this.value.toUpperCase()"
                                                    value="{{ old('ifsc_code', $bankDetail->ifsc_code ?? '') }}"
                                                    placeholder="{{ $bankCodeLabel }}* (e.g. SBIN0001234)"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-800 placeholder:text-slate-400 @error('ifsc_code') border-red-500 @enderror">
                                                @error('ifsc_code') <p class="text-[10px] text-red-500 mt-1">{{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div class="relative">
                                                <input type="text" name="linked_mobile"
                                                    inputmode="numeric"
                                                    maxlength="10"
                                                    pattern="[0-9]{10}"
                                                    value="{{ old('linked_mobile', $bankDetail->linked_mobile ?? '') }}"
                                                    placeholder="{{ $bankLinkedMobileLabel }}* (10 digits)"
                                                    class="w-full border border-slate-300 rounded-md px-4 py-3 text-[14px] text-slate-800 placeholder:text-slate-400 @error('linked_mobile') border-red-500 @enderror">
                                                @error('linked_mobile') <p class="text-[10px] text-red-500 mt-1">
                                                    {{ $message }}
                                                </p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-4 pr-1">
                                        <button type="submit"
                                            class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-8 py-2.5 rounded text-[14px] font-semibold uppercase transition-colors">
                                            Save Details
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="w-full lg:w-[320px] xl:w-[360px] space-y-6 flex-shrink-0">

                            <div class="flex gap-4">

                                <div
                                    class="flex-1 bg-gradient-to-r from-[#ea580c] to-[#f97316] rounded-xl p-3 text-white flex items-center gap-4 pl-4">
                                    <div
                                        class="w-[50px] h-[50px] bg-white rounded-full flex items-center justify-center text-[#ea580c] font-bold text-sm border-4 border-orange-200 flex-shrink-0">
                                        {{ $user->profile_completeness }}%
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[14px] leading-tight mb-0.5">Profile Completeness</h4>
                                    </div>
                                </div>



                            </div>
                        </div>






                    </div>
                </div>
            </div>

    <div x-show="activeTab === 'manage_users'" x-cloak x-transition.opacity.duration.300ms>

        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-500 mb-2">
                    <a href="#" @click.prevent="activeTab = 'dashboard'" class="hover:text-slate-700">Dashboard</a>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                    <span class="hover:text-slate-700 cursor-pointer">Profile</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                    <span class="text-slate-700">Manage User</span>
                </div>
                <h2 class="text-[22px] font-bold text-[#1e293b]">Manage Users</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row-reverse gap-6">

            <div class="flex-1 min-w-0">
                <div
                    class="bg-white rounded-lg border border-slate-200 shadow-sm p-12 flex flex-col items-center justify-center text-center min-h-[460px]">

                    <div class="mb-6">
                        <svg width="120" height="80" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Illustration kept same -->
                            <circle cx="40" cy="35" r="10" fill="#fcd34d" />
                            <path d="M25 65 c 0 -15, 30 -15, 30 0 v 15 h -30 v -15 z" fill="#8b5cf6" />
                            <path d="M35 55 h 10 v 25 h -10 z" fill="#7c3aed" />
                            <circle cx="80" cy="35" r="10" fill="#fcd34d" />
                            <path d="M65 65 c 0 -15, 30 -15, 30 0 v 15 h -30 v -15 z" fill="#3b82f6" />
                            <path d="M75 55 h 10 v 25 h -10 z" fill="#2563eb" />
                            <circle cx="60" cy="25" r="12" fill="#fcd34d" />
                            <path d="M40 70 c 0 -20, 40 -20, 40 0 v 10 h -40 v -10 z" fill="#f43f5e" />
                            <path d="M57 50 h 6 v 30 h -6 z" fill="#1e293b" />
                        </svg>
                    </div>

                    <h3 class="text-[17px] font-bold text-[#1e293b] mb-4">No User Available</h3>
                    <p class="text-[13px] text-slate-500 max-w-sm mb-8 leading-relaxed">
                        Add multiple users and turn your master account into multiple sub-accounts.<br><br>
                        Multiple sub-accounts allow you to control user roles, grant permissions to selected
                        accounts to manage
                        a task, and allow unique logins.
                    </p>

                    <button
                        class="bg-[#24618e] hover:bg-[#1a4b6e] text-white px-6 py-2.5 rounded font-semibold text-[14px] flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New User
                    </button>
                </div>
            </div>


            <div class="w-full lg:w-[320px] xl:w-[360px] space-y-6 flex-shrink-0">

                <div class="flex gap-4">

                    <div
                        class="flex-1 bg-gradient-to-r from-[#ea580c] to-[#f97316] rounded-xl p-3 text-white flex items-center gap-4 pl-4">
                        <div
                            class="w-[50px] h-[50px] bg-white rounded-full flex items-center justify-center text-[#ea580c] font-bold text-sm border-4 border-orange-200 flex-shrink-0">
                            {{ $user->profile_completeness }}%
                        </div>
                        <div>
                            <h4 class="font-bold text-[14px] leading-tight mb-0.5">Profile Completeness</h4>
                        </div>
                    </div>



                </div>
            </div>






        </div>
    </div>
</div>
    </main>

    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-[2px] lg:hidden transition-opacity"></div>
    </div>

    @push('scripts')
        @if(config('services.google_maps.api_key'))
            <script>
                window.initBspPlaces = function () {
                    const cityInput = document.getElementById('bp_city_input');
                    if (!cityInput || !window.google || !google.maps?.places) return;

                    const activeCountryEl = document.getElementById('bp_country_code');
                    const activeCountry = (activeCountryEl?.value || '{{ strtolower($addressConfig['google_restrict_country'] ?? '') }}' || '').toLowerCase();

                    const autocompleteOptions = {
                        fields: ['place_id', 'formatted_address', 'geometry', 'address_components'],
                        types: ['(cities)'],
                    };

                    @if(($addressConfig['restrict_google_country'] ?? false) === true)
                        if (activeCountry) {
                            autocompleteOptions.componentRestrictions = { country: activeCountry };
                        }
                    @endif

                                                                                                                        const ac = new google.maps.places.Autocomplete(cityInput, autocompleteOptions);

                    const getComp = (place, type) =>
                        (place.address_components || []).find(c => (c.types || []).includes(type)) || null;

                    const setVal = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.value = val ?? '';
                    };

                    ac.addListener('place_changed', () => {
                        const place = ac.getPlace();
                        if (!place || !place.place_id) return;

                        setVal('bp_place_id', place.place_id || '');
                        setVal('bp_formatted_address', place.formatted_address || '');
                        setVal('bp_lat', place.geometry?.location?.lat?.() ?? '');
                        setVal('bp_lng', place.geometry?.location?.lng?.() ?? '');

                        const state = getComp(place, 'administrative_area_level_1');
                        const country = getComp(place, 'country');
                        const postal = getComp(place, 'postal_code');
                        const locality = getComp(place, 'locality');
                        const postalTown = getComp(place, 'postal_town');

                        let cityName = '';
                        if (locality?.long_name) cityName = locality.long_name;
                        else if (postalTown?.long_name) cityName = postalTown.long_name;
                        else if (place.formatted_address) cityName = place.formatted_address.split(',')[0].trim();

                        if (cityName) {
                            cityInput.value = cityName;
                            setVal('bp_city_name', cityName);
                        }
                        if (state?.long_name) setVal('bp_state_name', state.long_name);
                        if (country?.short_name) setVal('bp_country_code', country.short_name);
                        if (postal?.long_name) setVal('bp_pincode', postal.long_name);
                    });

                    cityInput.addEventListener('blur', () => {
                        const typed = (cityInput.value || '').trim();
                        if (typed) setVal('bp_city_name', typed);
                    });
                };

                if (window.google && window.google.maps && window.google.maps.places) {
                    window.initBspPlaces();
                }
            </script>
        @endif

        <script>
            async function uploadToSpaces(file, folder) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('folder', folder);

                const response = await fetch('{{ route('user.upload.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok || !data.url) {
                    throw new Error(data.message || 'Upload failed');
                }

                return data;
            }

            function openFilePicker(options = {}) {
                return new Promise((resolve) => {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = options.accept || '*/*';
                    input.multiple = Boolean(options.multiple);

                    input.onchange = function (event) {
                        resolve(Array.from(event.target.files || []));
                    };

                    input.click();
                });
            }

            function ensureFileSize(file, maxBytes) {
                if (!maxBytes || file.size <= maxBytes) {
                    return;
                }

                const limitMb = Math.floor(maxBytes / (1024 * 1024));
                throw new Error('File is too large. Maximum allowed size is ' + limitMb + ' MB.');
            }

            async function uploadSingleFromPicker(options = {}) {
                const files = await openFilePicker({ accept: options.accept, multiple: false });
                if (!files.length) return null;

                const file = files[0];
                ensureFileSize(file, options.maxBytes);

                return uploadToSpaces(file, options.folder);
            }

            async function uploadMultipleFromPicker(options = {}) {
                const files = await openFilePicker({ accept: options.accept, multiple: true });
                if (!files.length) return [];

                const limitedFiles = files.slice(0, Math.max(0, options.maxFiles || files.length));
                const uploaded = [];

                for (const file of limitedFiles) {
                    ensureFileSize(file, options.maxBytes);
                    const result = await uploadToSpaces(file, options.folder);
                    uploaded.push(result.url);
                }

                return uploaded;
            }

            function isPreviewableImage(url) {
                return /\.(png|jpe?g|webp|gif|bmp|svg)(?:$|[?#])/i.test(url || '');
            }

            function isPdfFile(url) {
                return /\.pdf(?:$|[?#])/i.test(url || '');
            }

            function fileLabel(url) {
                if (!url) {
                    return 'Uploaded file';
                }

                try {
                    const pathname = new URL(url, window.location.origin).pathname || '';
                    const filename = pathname.split('/').pop();

                    return decodeURIComponent(filename || 'Uploaded file');
                } catch (error) {
                    const fallback = String(url).split('/').pop();
                    return fallback ? decodeURIComponent(fallback) : 'Uploaded file';
                }
            }

            function viewDocument(docType) {
                const hidden = document.getElementById('doc_' + docType + '_hidden');
                if (hidden && hidden.value) {
                    window.open(hidden.value, '_blank');
                } else {
                    alert('No document uploaded yet.');
                }
            }

            async function openDocumentUpload(docType) {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.png,.jpg,.jpeg,.webp,.pdf';

                input.onchange = async function (event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    try {
                        const result = await uploadToSpaces(file, 'business-documents');

                        const hiddenField = document.getElementById('doc_' + docType + '_hidden');
                        const displayField = document.getElementById('doc_' + docType + '_display');

                        if (hiddenField) hiddenField.value = result.url;
                        if (displayField) displayField.value = result.original_name || 'Document Uploaded';
                    } catch (error) {
                        console.error('Document Upload Error:', error);
                        alert(error.message || 'Document upload failed.');
                    }
                };

                input.click();
            }

            document.addEventListener('DOMContentLoaded', function () {
                const uploadProfileBtn = document.getElementById('uploadProfileBtn');
                const profilePhotoFileInput = document.getElementById('profilePhotoFileInput');

                if (uploadProfileBtn && profilePhotoFileInput) {
                    uploadProfileBtn.addEventListener('click', function () {
                        profilePhotoFileInput.click();
                    });

                    profilePhotoFileInput.addEventListener('change', async function (event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        try {
                            const result = await uploadToSpaces(file, 'profile-photos');

                            const hiddenField = document.getElementById('profilePhotoPath');
                            const container = document.getElementById('profileImageContainer');

                            if (hiddenField) hiddenField.value = result.url;

                            if (container) {
                                container.innerHTML = '<img src="' + result.url + '" class="w-full h-full rounded-full object-cover" alt="Profile Photo">';
                            }
                        } catch (error) {
                            console.error('Profile Photo Upload Error:', error);
                            alert(error.message || 'Profile photo upload failed.');
                        } finally {
                            profilePhotoFileInput.value = '';
                        }
                    });
                }

                const uploadBusinessBtn = document.getElementById('uploadBusinessLogoBtn');
                const businessLogoFileInput = document.getElementById('businessLogoFileInput');

                if (uploadBusinessBtn && businessLogoFileInput) {
                    uploadBusinessBtn.addEventListener('click', function () {
                        businessLogoFileInput.click();
                    });

                    businessLogoFileInput.addEventListener('change', async function (event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        try {
                            const result = await uploadToSpaces(file, 'business-logos');

                            const hiddenField = document.getElementById('businessLogoPath');
                            const container = document.getElementById('businessLogoContainer');

                            if (hiddenField) hiddenField.value = result.url;

                            if (container) {
                                container.innerHTML = '<img src="' + result.url + '" class="w-full h-full rounded-full object-cover" alt="Company Logo">';
                            }
                        } catch (error) {
                            console.error('Business Logo Upload Error:', error);
                            alert(error.message || 'Business logo upload failed.');
                        } finally {
                            businessLogoFileInput.value = '';
                        }
                    });
                }
            });
        </script>
    @endpush


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartEl = document.querySelector('#sellerInquiryChart');
            if (!chartEl) return;

            const options = {
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Inquiries',
                    data: @json($chart['series'] ?? [])
                }],
                xaxis: {
                    categories: @json($chart['labels'] ?? []),
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#24618e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.30,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                grid: {
                    borderColor: '#e2e8f0',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light'
                }
            };

            const chart = new ApexCharts(chartEl, options);
            chart.render();
        });
    </script>


@endsection
