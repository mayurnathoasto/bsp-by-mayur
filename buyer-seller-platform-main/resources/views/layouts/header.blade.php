<header class="bg-white">
    <!-- Top strip -->
    <div class="bg-[#0b2a4a] text-white text-[12px]">
        <div class="max-w-7xl mx-auto px-3 py-1.5 flex items-center justify-between gap-2 flex-wrap sm:flex-nowrap">
            <div class="flex items-center gap-3">
                <span class="font-semibold">Buyer Seller Platform</span>
                <span class="hidden sm:inline text-white/80"><i>Your own B2B Marketplace</i></span>
            </div>

            <div class="flex items-center gap-2 ml-auto" data-no-translate>
                @php
                    $supportedCountries = config('countries.supported', []);
                    $defaultCountry = config('countries.default_country', 'IN');

                    $currentCountry = strtoupper(session('country', $defaultCountry));
                    if (!isset($supportedCountries[$currentCountry])) {
                        $currentCountry = $defaultCountry;
                    }

                    $currentCurrency = strtoupper(
                        session(
                            'currency',
                            $supportedCountries[$currentCountry]['currency'] ?? config('countries.default_currency', 'INR')
                        )
                    );

                    $countries = collect($supportedCountries)
                        ->filter(fn($row) => !empty($row['live']))
                        ->map(fn($row, $code) => [
                            'code' => $code,
                            'name' => $row['name'],
                        ])
                        ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                        ->values();
                    $currencyOptions = collect($supportedCountries)
                        ->pluck('currency')
                        ->filter()
                        ->map(fn($currency) => strtoupper((string) $currency))
                        ->unique()
                        ->sort()
                        ->values();

                    $currentCountryOption = $countries->firstWhere('code', $currentCountry);
                    $currentCountryName = $currentCountryOption['name'] ?? ($supportedCountries[$currentCountry]['name'] ?? $currentCountry);

                    $navLocationCountry = $currentCountry;

                    if (!isset($supportedCountries[$navLocationCountry])) {
                        $navLocationCountry = $defaultCountry;
                    }

                    $navGoogleCountry = strtolower($supportedCountries[$navLocationCountry]['google_maps_code'] ?? $navLocationCountry);
                @endphp

                <span class="hidden sm:inline font-semibold">Country</span>

                <div class="relative" data-no-translate
                    x-data="{
                        open: false,
                        detecting: false,
                        search: '',
                        selectedCountry: @js($currentCountry),
                        countries: {{ Js::from($countries->values()->all()) }},
                        filteredCountries() {
                            const query = this.search.trim().toLowerCase();

                            if (!query) {
                                return this.countries;
                            }

                            return this.countries.filter((country) =>
                                (country.name || '').toLowerCase().includes(query)
                            );
                        },
                        toggleDropdown() {
                            this.open = !this.open;

                            if (this.open) {
                                this.search = '';
                                this.$nextTick(() => this.$refs.countrySearch?.focus());
                            }
                        },
                        submitCountry(code) {
                            this.selectedCountry = code;
                            this.$refs.countryField.value = code;
                            this.$refs.countryForm.submit();
                        },
                        async detectLocation() {
                            if (this.detecting) return;

                            this.detecting = true;

                            try {
                                await window.detectAndApplyLocation();
                                this.open = false;
                            } catch (error) {
                                alert(error.message || 'Unable to detect your location right now.');
                            } finally {
                                this.detecting = false;
                            }
                        }
                    }">
                    <form method="POST" action="{{ route('set-country') }}" x-ref="countryForm" class="hidden">
                        @csrf
                        <input type="hidden" name="country" x-ref="countryField" value="{{ $currentCountry }}">
                    </form>

                    <button type="button" @click="toggleDropdown()" @keydown.escape.window="open = false"
                        class="h-7 min-w-[92px] sm:min-w-[120px] rounded bg-white text-[#0b2a4a] border border-white/20 px-2 sm:px-3 text-[12px] outline-none flex items-center justify-between gap-2">
                        <span class="truncate">{{ $currentCountryName }}</span>
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute right-0 top-full z-50 mt-2 w-[260px] rounded-xl border border-slate-200 bg-white p-2 text-[#0b2a4a] shadow-xl">
                        <button type="button" @click="detectLocation()"
                            class="flex w-full items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-left text-[12px] font-semibold transition-colors hover:border-[#0b2a4a] hover:bg-slate-100">
                            <span x-text="detecting ? 'Detecting location...' : 'Detect My Location'"></span>
                            <svg class="h-4 w-4 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m10-10h-4M6 12H2m15.07-7.07l-2.83 2.83M9.76 14.24l-2.83 2.83m0-12.14l2.83 2.83m4.48 6.48l2.83 2.83M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>

                        <div class="mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input type="text" x-ref="countrySearch" x-model="search"
                                placeholder="Search country..."
                                class="w-full bg-transparent text-[12px] text-slate-700 outline-none placeholder:text-slate-400">
                        </div>

                        <div class="mt-2 max-h-64 overflow-y-auto rounded-lg border border-slate-100">
                            <template x-for="country in filteredCountries()" :key="country.code">
                                <button type="button" @click="submitCountry(country.code)"
                                    class="flex w-full items-center justify-between px-3 py-2 text-left text-[12px] transition-colors hover:bg-slate-50"
                                    :class="selectedCountry === country.code ? 'bg-slate-50 font-semibold text-[#0b2a4a]' : 'text-slate-600'">
                                    <span x-text="country.name"></span>
                                    <template x-if="selectedCountry === country.code">
                                        <svg class="h-4 w-4 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </button>
                            </template>

                            <div x-show="filteredCountries().length === 0"
                                class="px-3 py-4 text-[12px] text-slate-500">
                                No matching country found.
                            </div>
                        </div>
                    </div>
                </div>

                <span class="hidden sm:inline font-semibold ml-2">Currency</span>

                <form method="POST" action="{{ route('set-currency') }}" data-no-translate>
                    @csrf
                    <select name="currency" onchange="this.form.submit()"
                        class="h-7 min-w-[72px] sm:min-w-[84px] rounded bg-white text-[#0b2a4a] border border-white/20 px-2 text-[12px] outline-none">
                        @foreach($currencyOptions as $currencyOption)
                            <option value="{{ $currencyOption }}" @selected($currentCurrency === $currencyOption)>
                                {{ $currencyOption }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Main header -->
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-3 py-2.5 lg:py-3">
            @php $cc = strtolower(session('country', 'IN')); @endphp
            <style>
    .sp-main-logo-link {
        display: inline-flex;
        align-items: center;
        transition: opacity .2s ease;
    }

    .sp-main-logo-link:hover {
        opacity: .92;
    }

    .sp-main-logo {
        height: 52px;
        width: auto;
        object-fit: contain;
        image-rendering: auto;
    }

    .sp-search-select,
    .sp-search-input,
    .sp-location-input,
    .sp-account-chip {
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .sp-search-select:focus,
    .sp-search-input:focus,
    .sp-location-input:focus {
        outline: none;
        border-color: rgba(255, 106, 0, 0.38);
        box-shadow: 0 0 0 4px rgba(255, 106, 0, 0.10);
    }

    @media (min-width: 1024px) {
        .sp-main-logo {
            height: 60px;
        }
    }
</style>

@php
    $activeCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
    $countryConfig = config("countries.supported.$activeCountry", []);
    $showCitySelector = (bool) ($countryConfig['city_selector'] ?? false);
    $cityName = session('city_name', 'Select City');
    $cityPlaceholder = $cityName ?: 'Select City';
    $authUser = auth()->user();
    $userName = $authUser?->name ?? 'User';
    $initial = strtoupper(substr($userName, 0, 1));
    $notificationCount = $authUser
        ? $authUser->notificationRecipients()->whereNull('read_at')->count()
        : 0;
    $recentNotifications = $authUser
        ? $authUser->notificationRecipients()
            ->with('notification')
            ->latest('id')
            ->take(5)
            ->get()
        : collect();
@endphp

<div class="flex flex-col gap-4 md:flex-row md:items-center md:gap-4">
    <div class="hidden items-center justify-between gap-3 md:w-auto md:shrink-0 md:flex">
        <a href="{{ $cc === 'in' ? url('/') : url('/' . $cc) }}"
            class="sp-main-logo-link relative z-20 block shrink-0 pointer-events-auto"
            data-no-translate
            aria-label="Go to homepage"
            onclick="window.location.href='{{ $cc === 'in' ? url('/') : url('/' . $cc) }}'">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="sp-main-logo" />
        </a>

        @auth
            <div class="relative md:hidden" x-data="{ open: false }">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false" data-no-translate
                    class="sp-account-chip flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-[17px] font-black text-[#0b2a4a] shadow-sm">
                    {{ $initial }}
                </button>

                <div x-show="open" x-cloak @click.outside="open = false"
                    class="absolute right-0 top-full mt-2 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl z-50"
                    style="z-index: 999999 !important;">
                    <div class="border-b border-slate-100 px-4 py-4">
                        <div class="text-[14px] font-extrabold text-[#0b2a4a]">Welcome!</div>
                        <div class="mt-1 text-[12px] text-slate-500">{{ $userName }}</div>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Dashboard</a>
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Profile</a>
                    <a href="{{ route('user.notifications') }}" class="flex items-center justify-between gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">
                        <span>Notifications</span>
                        @if($notificationCount > 0)
                            <span class="rounded-full bg-[#ff6a00] px-2 py-0.5 text-[11px] font-bold text-white">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.support.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Support &amp; Reports</a>
                    <a href="{{ route('user.inquiries') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Inquiries</a>
                    <a href="{{ route('user.products.create') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] font-bold text-[#0b2a4a] hover:bg-slate-50">+ Add Product</a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 text-left text-[13px] font-semibold text-red-600 hover:bg-slate-50">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}"
                class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-bold text-[#0b2a4a] shadow-sm md:hidden">
                Sign In
            </a>
        @endauth
    </div>

    <div class="hidden min-w-0 flex-1 md:flex md:items-center md:justify-center md:gap-3">
        <form method="GET" action="{{ route('search') }}" class="flex min-w-0 items-center rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden lg:w-[550px] mx-auto">
            <select name="type"
                class="sp-search-select h-10 border-0 border-r border-slate-200 bg-slate-50 px-1 text-[12px] font-semibold text-[#0b2a4a] shrink-0 w-[82px]">
                <option value="Products" @selected(request('type') === 'Products')>Products</option>
                <option value="Suppliers" @selected(request('type') === 'Suppliers')>Suppliers</option>
            </select>

            <input type="text" name="q" value="{{ request('q') }}"
                class="sp-search-input h-10 min-w-0 flex-1 border-0 bg-white px-4 text-[14px] font-medium text-slate-700 placeholder:text-slate-400"
                placeholder="Search products / suppliers" required />

            <button type="submit"
                class="inline-flex h-10 shrink-0 items-center justify-center bg-[#0b2a4a] px-4 text-[13px] font-bold text-white transition hover:bg-[#12385f] lg:min-w-[78px]">
                Search
            </button>
        </form>
    </div>

        @if($showCitySelector)
            <form method="POST" action="{{ route('set-city') }}" id="navCityForm"
                class="hidden shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 shadow-sm md:flex">
                @csrf
                <svg class="h-4 w-4 text-[#0b2a4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-[13px] font-semibold text-slate-500">Location:</span>
                <input type="text" id="nav_city_input" placeholder="{{ $cityPlaceholder }}"
                    value="{{ session('city_name', '') }}"
                    class="sp-location-input h-7 w-[110px] border-0 bg-transparent px-0 text-[14px] font-semibold text-slate-700 placeholder:text-slate-400 lg:w-[132px]" />
                <input type="hidden" name="city_name" id="nav_city_name" value="{{ session('city_name', '') }}">
                <input type="hidden" name="pincode" id="nav_city_pincode" value="{{ session('city_pincode', '') }}">
                <input type="hidden" name="state_name" id="nav_city_state" value="{{ session('city_state_name', '') }}">
                <input type="hidden" name="country_code" id="nav_city_country" value="{{ session('city_country_code', $currentCountry) }}">
                <button type="submit" class="hidden" id="navCitySubmit"></button>
            </form>
        @endif

        @auth
            <div class="relative hidden shrink-0 md:block" x-data="{ open: false }">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-[#0b2a4a] shadow-sm hover:border-slate-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                    @if($notificationCount > 0)
                        <span class="absolute -right-1 -top-1 inline-flex min-w-[18px] items-center justify-center rounded-full bg-[#ff6a00] px-1.5 text-[10px] font-bold text-white">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </button>

                <div x-show="open" x-cloak @click.outside="open = false"
                    class="absolute right-0 top-full mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl z-50"
                    style="z-index: 999999 !important;">
                    <div class="border-b border-slate-100 px-4 py-4">
                        <div class="text-[14px] font-extrabold text-[#0b2a4a]">Notifications</div>
                        <div class="mt-1 text-[12px] text-slate-500">Latest updates for your account</div>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        @forelse($recentNotifications as $recipient)
                            <a href="{{ $recipient->notification?->action_url ?: route('user.notifications') }}"
                                class="block border-b border-slate-50 px-4 py-3 hover:bg-slate-50">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-bold text-slate-800">{{ $recipient->notification?->title }}</div>
                                        <div class="mt-1 line-clamp-2 text-[12px] text-slate-500">{{ $recipient->notification?->message }}</div>
                                        <div class="mt-2 text-[11px] text-slate-400">{{ optional($recipient->created_at)->diffForHumans() }}</div>
                                    </div>
                                    @if(!$recipient->read_at)
                                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-[#ff6a00]"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-[13px] text-slate-500">No notifications yet.</div>
                        @endforelse
                    </div>

                    <div class="border-t border-slate-100 px-4 py-3">
                        <a href="{{ route('user.notifications') }}" class="text-[13px] font-semibold text-[#0b2a4a] hover:text-[#ff6a00]">
                            View all notifications
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative hidden shrink-0 md:block" x-data="{ open: false }">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false" data-no-translate
                    class="sp-account-chip flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[13px] font-semibold text-[#0b2a4a] shadow-sm hover:border-slate-300">
                    <div class="text-right leading-tight hidden lg:block">
                        <div class="text-[14px] font-black">Hi! {{ $userName }}</div>
                        <div class="text-[11px] font-semibold text-slate-400">Free Member</div>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-[16px] font-black">{{ $initial }}</span>
                    <svg class="w-4 h-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.outside="open = false"
                    class="absolute right-0 top-full mt-2 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl z-50"
                    style="z-index: 999999 !important;">
                    <div class="border-b border-slate-100 px-4 py-4">
                        <div class="text-[14px] font-extrabold text-[#0b2a4a]">Welcome!</div>
                        <div class="mt-1 text-[12px] text-slate-500">{{ $userName }}</div>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Dashboard</a>
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Profile</a>
                    <a href="{{ route('user.notifications') }}" class="flex items-center justify-between gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">
                        <span>Notifications</span>
                        @if($notificationCount > 0)
                            <span class="rounded-full bg-[#ff6a00] px-2 py-0.5 text-[11px] font-bold text-white">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.support.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Support &amp; Reports</a>
                    <a href="{{ route('user.inquiries') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Inquiries</a>
                    <a href="{{ route('user.products.create') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] font-bold text-[#0b2a4a] hover:bg-slate-50">+ Add Product</a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 text-left text-[13px] font-semibold text-red-600 hover:bg-slate-50">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}"
                class="hidden h-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-bold text-[#0b2a4a] shadow-sm hover:border-slate-300 md:inline-flex">
                Sign In
            </a>
        @endauth

    <div class="space-y-3 md:hidden">
        <div class="flex items-center gap-3">
            <a href="{{ $cc === 'in' ? url('/') : url('/' . $cc) }}"
                class="sp-main-logo-link relative z-20 block shrink-0 pointer-events-auto"
                data-no-translate
                aria-label="Go to homepage"
                onclick="window.location.href='{{ $cc === 'in' ? url('/') : url('/' . $cc) }}'">
                <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="sp-main-logo h-[42px]" />
            </a>

            @if($showCitySelector)
                <form method="POST" action="{{ route('set-city') }}" id="navCityFormMobileTop"
                    class="flex min-w-0 flex-1 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                    @csrf
                    <span class="shrink-0 text-[12px] font-semibold text-slate-500">Location:</span>
                    <input type="text" id="nav_city_input_mobile"
                        placeholder="{{ $cityPlaceholder }}" value="{{ session('city_name', '') }}"
                        class="sp-location-input h-6 w-full min-w-0 border-0 bg-transparent px-0 text-[13px] font-semibold text-slate-700 placeholder:text-slate-400" />
                    <input type="hidden" name="city_name" id="nav_city_mobile_name" value="{{ session('city_name', '') }}">
                    <input type="hidden" name="pincode" id="nav_city_mobile_pincode" value="{{ session('city_pincode', '') }}">
                    <input type="hidden" name="state_name" id="nav_city_mobile_state" value="{{ session('city_state_name', '') }}">
                    <input type="hidden" name="country_code" id="nav_city_mobile_country" value="{{ session('city_country_code', $currentCountry) }}">
                </form>
            @endif

            @auth
                <a href="{{ route('user.notifications') }}"
                    class="relative inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-[#0b2a4a] shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                    @if($notificationCount > 0)
                        <span class="absolute -right-1 -top-1 inline-flex min-w-[18px] items-center justify-center rounded-full bg-[#ff6a00] px-1.5 text-[10px] font-bold text-white">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </a>



                <div class="relative shrink-0" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @keydown.escape.window="open = false" data-no-translate
                        class="sp-account-chip flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-[17px] font-black text-[#0b2a4a] shadow-sm">
                        {{ $initial }}
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute right-0 top-full mt-2 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl z-50"
                        style="z-index: 999999 !important;">
                        <div class="border-b border-slate-100 px-4 py-4">
                            <div class="text-[14px] font-extrabold text-[#0b2a4a]">Welcome!</div>
                            <div class="mt-1 text-[12px] text-slate-500">{{ $userName }}</div>
                        </div>
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Dashboard</a>
                        <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Profile</a>
                        <a href="{{ route('user.notifications') }}" class="flex items-center justify-between gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">
                            <span>Notifications</span>
                            @if($notificationCount > 0)
                                <span class="rounded-full bg-[#ff6a00] px-2 py-0.5 text-[11px] font-bold text-white">{{ $notificationCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('user.support.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Support &amp; Reports</a>
                        <a href="{{ route('user.inquiries') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] hover:bg-slate-50">Inquiries</a>
                        <a href="{{ route('user.products.create') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] font-bold text-[#0b2a4a] hover:bg-slate-50">+ Add Product</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 text-left text-[13px] font-semibold text-red-600 hover:bg-slate-50">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="hidden h-9 shrink-0 items-center justify-center text-[13px] font-bold text-[#0b2a4a]">
                    Sign In
                </a>
            @endauth
        </div>

        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('search') }}" class="flex-1 flex items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <input type="hidden" name="type" value="{{ request('type', 'Products') }}">
                <input type="text" name="q" value="{{ request('q') }}"
                    class="sp-search-input h-10 min-w-0 flex-1 border-0 bg-white px-3 text-[13px] font-medium text-slate-700 placeholder:text-slate-400"
                    placeholder="Search..." required />
                <button type="submit"
                    class="inline-flex h-10 shrink-0 items-center justify-center bg-[#0b2a4a] px-4 text-[13px] font-bold text-white transition hover:bg-[#12385f]">
                    Go
                </button>
            </form>
            @if(request()->routeIs('user.*'))
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
                class="h-10 w-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-700 shadow-sm active:scale-95 transition-all hover:bg-slate-50 focus:outline-none shrink-0"
                aria-label="Toggle Menu">
                <svg class="w-6 h-6 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            @endif
        </div>
    </div>
</div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if(!empty(config('services.google_maps.api_key')))
            <script>
                window.initNavCityPlaces = function () {
                    if (!window.google || !google.maps?.places) return;

                    const initOne = (inputId, formId, ids) => {
                        const input = document.getElementById(inputId);
                        if (!input) return;

                        const ac = new google.maps.places.Autocomplete(input, {
                            fields: ['place_id', 'address_components', 'formatted_address'],
                            types: ['(cities)'],
                            componentRestrictions: { country: @json($navGoogleCountry) }
                        });

                        const getComp = (place, type) =>
                            (place.address_components || []).find(c => (c.types || []).includes(type)) || null;

                        const setVal = (id, val) => {
                            const el = document.getElementById(id);
                            if (el) el.value = val ?? '';
                        };

                        ac.addListener('place_changed', () => {
                            const place = ac.getPlace();
                            if (!place) return;

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
                                input.value = cityName;
                                setVal(ids.name, cityName);
                            }

                            if (state?.long_name) setVal(ids.state, state.long_name);
                            if (country?.short_name) setVal(ids.country, country.short_name);
                            if (postal?.long_name) setVal(ids.pincode, postal.long_name);

                            document.getElementById(formId)?.submit();
                        });

                        // manual typed fallback
                        input.addEventListener('blur', () => {
                            const typed = (input.value || '').trim();
                            if (typed) {
                                setVal(ids.name, typed);
                                setVal(ids.country, @json($navLocationCountry));
                            }
                        });
                    };

                    // Desktop
                    initOne('nav_city_input', 'navCityForm', {
                        name: 'nav_city_name',
                        state: 'nav_city_state',
                        country: 'nav_city_country',
                        pincode: 'nav_city_pincode'
                    });

                    // Mobile (top row)
                    initOne('nav_city_input_mobile', 'navCityFormMobileTop', {
                        name: 'nav_city_mobile_name',
                        state: 'nav_city_mobile_state',
                        country: 'nav_city_mobile_country',
                        pincode: 'nav_city_mobile_pincode'
                    });

                    // ✅ Trigger Business Profile init if exists
                    if (typeof window.initBspPlaces === 'function') {
                        window.initBspPlaces();
                    }
                };
            </script>
        @endif

        <script>
            function setHeaderLocationFields(payload = {}) {
                const cityName = payload.city_name ?? '';
                const stateName = payload.state_name ?? '';
                const pincode = payload.pincode ?? '';
                const countryCode = payload.country_code ?? '';

                const applyValue = (id, value) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.value = value;
                    }
                };

                applyValue('nav_city_input', cityName);
                applyValue('nav_city_name', cityName);
                applyValue('nav_city_state', stateName);
                applyValue('nav_city_pincode', pincode);
                applyValue('nav_city_country', countryCode);

                applyValue('nav_city_input_mobile', cityName);
                applyValue('nav_city_mobile_name', cityName);
                applyValue('nav_city_mobile_state', stateName);
                applyValue('nav_city_mobile_pincode', pincode);
                applyValue('nav_city_mobile_country', countryCode);
            }

            function geolocationErrorMessage(error) {
                if (!error || typeof error.code === 'undefined') {
                    return 'Unable to detect your location right now.';
                }

                if (error.code === error.PERMISSION_DENIED) {
                    return 'Location access was denied. Please allow location access in your browser.';
                }

                if (error.code === error.POSITION_UNAVAILABLE) {
                    return 'Your location is currently unavailable.';
                }

                if (error.code === error.TIMEOUT) {
                    return 'Location detection timed out. Please try again.';
                }

                return 'Unable to detect your location right now.';
            }

            function detectBrowserPosition() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Location detection is not supported in this browser.'));
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        resolve,
                        (error) => reject(new Error(geolocationErrorMessage(error))),
                        {
                            enableHighAccuracy: true,
                            timeout: 12000,
                            maximumAge: 300000,
                        }
                    );
                });
            }

            function waitForGoogleMaps(timeoutMs = 5000) {
                return new Promise((resolve, reject) => {
                    if (window.google && google.maps?.Geocoder) {
                        resolve();
                        return;
                    }

                    const startedAt = Date.now();
                    const timer = window.setInterval(() => {
                        if (window.google && google.maps?.Geocoder) {
                            window.clearInterval(timer);
                            resolve();
                            return;
                        }

                        if (Date.now() - startedAt >= timeoutMs) {
                            window.clearInterval(timer);
                            reject(new Error('Location detection is not configured right now.'));
                        }
                    }, 150);
                });
            }

            async function reverseGeocodePosition(lat, lng) {
                await waitForGoogleMaps();

                return new Promise((resolve, reject) => {
                    const geocoder = new google.maps.Geocoder();

                    geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                        if (status !== 'OK' || !Array.isArray(results) || !results.length) {
                            reject(new Error('We could not identify your city from the detected location.'));
                            return;
                        }

                        resolve(results);
                    });
                });
            }

            function parseDetectedAddress(results) {
                const primary = results.find((result) =>
                    Array.isArray(result.types) && (
                        result.types.includes('locality') ||
                        result.types.includes('postal_town') ||
                        result.types.includes('administrative_area_level_2')
                    )
                ) || results[0];

                const allAddressComponents = results.reduce((carry, result) => {
                    return carry.concat(result.address_components || []);
                }, []);

                const getComp = (type) =>
                    (primary.address_components || []).find((component) => (component.types || []).includes(type)) ||
                    allAddressComponents.find((component) => (component.types || []).includes(type)) ||
                    null;

                const locality = getComp('locality');
                const postalTown = getComp('postal_town');
                const adminArea2 = getComp('administrative_area_level_2');
                const sublocality = getComp('sublocality_level_1');
                const state = getComp('administrative_area_level_1');
                const country = getComp('country');
                const postal = getComp('postal_code');

                let cityName = locality?.long_name || postalTown?.long_name || adminArea2?.long_name || sublocality?.long_name || '';

                if (!cityName && primary.formatted_address) {
                    cityName = primary.formatted_address.split(',')[0].trim();
                }

                if (!cityName || !country?.short_name) {
                    throw new Error('We could not identify your city and country from the detected location.');
                }

                return {
                    city_name: cityName,
                    state_name: state?.long_name || '',
                    pincode: postal?.long_name || '',
                    country_code: country.short_name,
                };
            }

            window.detectAndApplyLocation = async function () {
                const position = await detectBrowserPosition();
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const geocodeResults = await reverseGeocodePosition(latitude, longitude);
                const locationPayload = parseDetectedAddress(geocodeResults);

                const response = await fetch('{{ route('detect-location') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ...locationPayload,
                        lat: latitude,
                        lng: longitude,
                    }),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to apply detected location.');
                }

                setHeaderLocationFields({
                    city_name: data.city_name || locationPayload.city_name,
                    state_name: locationPayload.state_name,
                    pincode: locationPayload.pincode,
                    country_code: data.country || locationPayload.country_code,
                });

                window.location.assign(data.redirect || window.location.href);
            };
        </script>

        @if(!empty(config('services.google_maps.api_key')))
            <script
                src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initNavCityPlaces"
                async defer></script>
        @endif
    @endpush
</header>
