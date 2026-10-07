@extends('layouts.app')

@section('content')
    @php
        // Data is provided from routes/web.php (DB-driven). Keep safe fallbacks.
        $categories = $categories ?? collect();
        $popular = $categories->take(12);
        $indiaSuppliers = ($topSuppliers ?? collect())->filter(function ($supplier) {
            return strtoupper($supplier->businessProfile?->country_code ?? $supplier->country_code ?? 'IN') === 'IN';
        })->take(6)->values();
        $internationalSuppliers = ($topSuppliers ?? collect())->filter(function ($supplier) {
            return strtoupper($supplier->businessProfile?->country_code ?? $supplier->country_code ?? 'IN') !== 'IN';
        })->take(6)->values();
        $heroSlides = collect([
            [
                'kind' => 'default',
                'link' => route('products'),
                'alt' => 'Find Products and Verified Suppliers',
            ],
            [
                'kind' => 'image',
                'link' => route('products'),
                'alt' => 'India trusted B2B marketplace for buyers and sellers',
                'desktop' => asset('images/home-banners/hero-desktop-1.png'),
                'mobile' => asset('images/home-banners/hero-mobile-1.png'),
            ],
            [
                'kind' => 'image',
                'link' => route('products'),
                'alt' => 'Trusted by businesses across India',
                'desktop' => asset('images/home-banners/hero-desktop-2.png'),
                'mobile' => asset('images/home-banners/hero-mobile-2.png'),
            ],
        ])->concat(
            ($heroBanners ?? collect())->map(function ($banner) {
                return [
                    'kind' => 'image',
                    'link' => $banner->link_url ?? '#',
                    'alt' => $banner->title ?? 'Banner',
                    'desktop' => $banner->image_url,
                    'mobile' => $banner->mobile_image_url ?? $banner->image_url,
                ];
            })
        )->values();

        // Compact label for grid cards
        $shortLabel = function ($name) {
            $name = trim((string) $name);
            $name = preg_split('/&|,/', $name)[0] ?? $name;
            $name = preg_replace('/\s+/', ' ', trim($name));
            return mb_substr($name, 0, 22) . (mb_strlen($name) > 22 ? '...' : '');
        };
    @endphp

    <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-3 py-4">

            <!-- Top area -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

                <!-- Left: All Categories (scroll) -->
                <div class="lg:col-span-3">
                    <div class="bg-white border">
                        <div class="px-3 py-2 font-bold text-[13px] text-white bg-[#0b2a4a] flex items-center justify-between">
                            <span>All Categories</span>
                            <a href="{{ route('categories.all') }}" class="text-[11px] text-white/80 hover:text-white hover:underline font-normal uppercase">
                                View All
                            </a>
                        </div>

                        <div class="divide-y text-[13px] max-h-[320px] lg:max-h-[420px] overflow-auto flex-1">
                            @forelse ($categories as $cat)
                                <a href="{{ route('category.detail', $cat->id) }}" class="block px-3 py-2 hover:bg-gray-50">
                                    {{ $cat->name }}
                                </a>
                            @empty
                                <div class="px-3 py-3 text-gray-600">
                                    No categories found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Center: Banner -->
<div class="lg:col-span-6">
    <div class="bg-white border h-full flex flex-col">

        <div class="relative overflow-hidden border flex-1 min-h-[380px] sm:min-h-[440px] lg:min-h-[390px]"
            x-data="{
                i: 0,
                total: {{ $heroSlides->count() }},
                timer: null,
                start() {
                    if (this.total <= 1) return;
                    this.stop();
                    this.timer = setInterval(() => {
                        this.i = (this.i + 1) % this.total;
                    }, 3000);
                },
                stop() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                next() {
                    this.i = (this.i + 1) % this.total;
                },
                prev() {
                    this.i = (this.i - 1 + this.total) % this.total;
                }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()">
            <div class="relative h-full">
                @foreach($heroSlides as $idx => $slide)
                    <div x-show="i === {{ $idx }}" x-transition.opacity.duration.500ms x-cloak class="absolute inset-0 h-full">
                        @if($slide['kind'] === 'default')
                            <div class="h-full bg-gradient-to-r from-[#0b2a4a] to-[#163d67] text-white p-5 lg:p-8 flex flex-col justify-start">
                                <div class="pt-5 lg:pt-4">
                                    <div class="text-[26px] lg:text-[44px] font-extrabold leading-tight">
                                        Find Products &amp; Verified Suppliers
                                    </div>
                                    <div class="text-[13px] lg:text-[16px] text-white/90 mt-2">
                                        Get quotes &bull; Compare prices &bull; Grow your business
                                    </div>
                                </div>

                                <div class="mt-8 lg:mt-12 flex gap-3">
                                    <a href="{{ route('products') }}"
                                        class="px-5 py-3 bg-[#ff6a00] font-bold text-[13px] lg:text-[14px]">
                                        Explore Products
                                    </a>
                                    <a href="{{ route('post-requirement') }}"
                                        class="px-5 py-3 bg-white text-[#0b2a4a] font-bold text-[13px] lg:text-[14px]">
                                        Post Requirement
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ $slide['link'] }}" class="block h-full">
                                <picture>
                                    <source media="(min-width: 1024px)" srcset="{{ $slide['desktop'] }}">
                                    <img src="{{ $slide['mobile'] }}"
                                        alt="{{ $slide['alt'] }}"
                                        class="h-full w-full object-cover">
                                </picture>
                            </a>
                        @endif
                    </div>
                @endforeach

                @if($heroSlides->count() > 1)
                    <div class="absolute bottom-3 left-0 right-0 z-10 flex justify-center gap-2">
                        @foreach($heroSlides as $idx => $slide)
                            <button type="button"
                                @click="i = {{ $idx }}"
                                class="h-2.5 w-2.5 rounded-full border border-white/80"
                                :class="i === {{ $idx }} ? 'bg-white' : 'bg-white/40'"
                                aria-label="Go to banner {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- mini highlights -->
        <div class="grid grid-cols-3 gap-2 mt-3 text-[12px]">
            <div class="bg-gray-50 border p-2">
                <div class="font-bold text-[#0b2a4a]">Trusted Sellers</div>
                <div class="text-gray-600">Verified profiles</div>
            </div>
            <div class="bg-gray-50 border p-2">
                <div class="font-bold text-[#0b2a4a]">Quick Quotes</div>
                <div class="text-gray-600">Fast response</div>
            </div>
            <div class="bg-gray-50 border p-2">
                <div class="font-bold text-[#0b2a4a]">Best Prices</div>
                <div class="text-gray-600">Compare offers</div>
            </div>
        </div>

    </div>
</div>

                <!-- Right: Quick Actions -->
                <div class="lg:col-span-3">
                    <div class="bg-white border h-full flex flex-col">
                        <div class="px-3 py-2 font-bold text-[13px] text-white bg-[#0b2a4a]">
                            Quick Actions
                        </div>

                        <div class="p-3 text-[13px] space-y-3">
                            <div class="border p-3">
                                <div class="font-extrabold text-[#0b2a4a]">Get quotes from sellers</div>
                                <div class="text-[12px] text-gray-600 mt-1">Tell us what you need and get best price.</div>
                                <a href="{{ route('post-requirement') }}"
                                    class="inline-block mt-2 px-3 py-2 bg-[#ff6a00] text-white font-bold text-[13px]">Post
                                    Requirement</a>
                            </div>

                            <div class="border p-3">
                                <div class="font-extrabold text-[#0b2a4a]">Sell on platform</div>
                                <div class="text-[12px] text-gray-600 mt-1">List your products and get inquiries.</div>
                                <a href="{{ route('user.products.create') }}"
                                    class="inline-block mt-2 px-3 py-2 border font-bold text-[13px] hover:bg-gray-50">Add
                                    Product</a>
                            </div>

                            <div class="border p-3 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="font-extrabold text-[#0b2a4a]">Need help?</div>
                                    <div class="text-[12px] text-gray-600 mt-1">Call or WhatsApp support for marketplace assistance.</div>
                                </div>

                                <div class="pt-4">
                                    <a href="{{ route('contact') }}"
                                        class="inline-block px-3 py-2 border font-bold text-[13px] hover:bg-gray-50">
                                        Contact
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Popular Categories -->
            <div class="mt-5 bg-white border"
                x-data="{
                    scroll(direction) {
                        const track = this.$refs.track;
                        if (!track) return;

                        const card = track.querySelector('[data-slider-card]');
                        const cardWidth = card ? card.getBoundingClientRect().width + 12 : track.clientWidth * 0.8;
                        const visibleCards = window.innerWidth >= 1024 ? 4 : (window.innerWidth >= 768 ? 3 : 1);

                        track.scrollBy({ left: direction * cardWidth * visibleCards, behavior: 'smooth' });
                    }
                }">
                <div class="px-3 py-2 border-b flex items-center justify-between gap-3">
                    <div class="font-extrabold text-[#0b2a4a] text-[14px]">Popular Categories</div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="scroll(-1)"
                            class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 transition-colors hover:border-[#ff6a00] hover:text-[#ff6a00]"
                            aria-label="Previous popular categories">
                            <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" @click="scroll(1)"
                            class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 transition-colors hover:border-[#ff6a00] hover:text-[#ff6a00]"
                            aria-label="Next popular categories">
                            <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <a href="{{ route('categories.all') }}" class="text-[13px] text-[#0b2a4a] font-bold hover:underline">View All</a>
                    </div>
                </div>

                <div x-ref="track"
                    class="flex gap-3 overflow-x-auto p-3 text-[13px] scroll-smooth snap-x snap-mandatory"
                    style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
                    @forelse($popular as $c)
                        <a href="{{ route('category.detail', $c->id) }}" data-slider-card
                            class="min-w-[160px] md:min-w-[190px] lg:min-w-[200px] border bg-white hover:shadow-md flex flex-col group transition-shadow overflow-hidden rounded snap-start">
                            <div class="h-32 w-full bg-gray-100 flex items-center justify-center overflow-hidden">
                                @if($c->image ?? null)
                                    <img src="{{ $c->image }}" class="w-full h-full object-cover" alt="{{ $c->name }}">
                                @else
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                @endif
                            </div>
                            <div class="p-2 text-center">
                                <div class="font-bold text-[#0b2a4a] text-[12px] leading-snug group-hover:text-[#ff6a00] transition-colors">{{ $c->name }}</div>
                                <div class="text-[11px] text-gray-500 mt-1">Explore</div>
                            </div>
                        </a>
                    @empty
                        <div class="w-full p-4 text-gray-600">
                            No categories found.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Featured Products -->
            <div class="mt-5 bg-white border"
                x-data="{
                    scroll(direction) {
                        const track = this.$refs.track;
                        if (!track) return;

                        const card = track.querySelector('[data-slider-card]');
                        const cardWidth = card ? card.getBoundingClientRect().width + 12 : track.clientWidth * 0.85;
                        const visibleCards = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);

                        track.scrollBy({ left: direction * cardWidth * visibleCards, behavior: 'smooth' });
                    }
                }">
                <div class="px-3 py-2 border-b flex items-center justify-between gap-3">
                    <div class="font-extrabold text-[#0b2a4a] text-[14px]">Featured Products</div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="scroll(-1)"
                            class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 transition-colors hover:border-[#ff6a00] hover:text-[#ff6a00]"
                            aria-label="Previous featured products">
                            <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" @click="scroll(1)"
                            class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 transition-colors hover:border-[#ff6a00] hover:text-[#ff6a00]"
                            aria-label="Next featured products">
                            <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <a href="{{ route('products') }}" class="text-[13px] text-[#0b2a4a] font-bold hover:underline">View All</a>
                    </div>
                </div>

                <div x-ref="track"
                    class="flex gap-3 overflow-x-auto p-3 text-[13px] scroll-smooth snap-x snap-mandatory"
                    style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
                    @if(($featuredProducts ?? collect())->count())
                        @foreach($featuredProducts as $p)
                            <div data-slider-card class="min-w-[232px] md:min-w-[252px] lg:min-w-[272px] snap-start">
                                @include('components.public-product-card', [
                                    'product' => $p,
                                    'variant' => 'slider',
                                ])
                            </div>
                        @endforeach
                    @else
                        <div class="w-full">
                            <div
                                class="bg-gradient-to-r from-[#0b2a4a] to-[#163d67] text-white rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                                <div>
                                    <div class="text-xl font-extrabold">No products listed yet</div>
                                    <div class="text-sm text-white/90 mt-1">Be the first to post a product and start receiving
                                        inquiries.</div>
                                    <div class="mt-3 flex gap-2">
                                        <a href="{{ route('user.products.create') }}"
                                            class="px-4 py-2 bg-[#ff6a00] font-bold text-[13px] rounded">
                                            Add Product
                                        </a>
                                        <a href="{{ route('products') }}"
                                            class="px-4 py-2 bg-white text-[#0b2a4a] font-bold text-[13px] rounded">
                                            Browse Products
                                        </a>
                                    </div>
                                </div>
                                <div class="bg-white/10 border border-white/20 rounded-lg p-4 text-sm">
                                    <div class="font-bold">Tip</div>
                                    <div class="text-white/90">Upload 3-5 images for best response.</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mid Banners -->
            @if(($midBanners ?? collect())->count())
                <div class="mt-5 space-y-3">
                    @foreach($midBanners as $b)
                        <a href="{{ $b->link_url ?? '#' }}" class="block">
                            <img src="{{ $b->image_url }}" class="w-full border rounded-lg" alt="{{ $b->title ?? 'Banner' }}">
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-5 bg-white border">
                <div class="px-3 py-2 border-b flex items-center justify-between">
                    <div class="font-extrabold text-[#0b2a4a] text-[14px]">Seller Discovery</div>
                    <a href="{{ route('suppliers.all') }}" class="text-[13px] text-[#0b2a4a] font-bold hover:underline">View All</a>
                </div>

                <div class="p-3 space-y-6">
                    <div>
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-[15px] font-extrabold text-[#0b2a4a]">Sellers from India</h3>
                            <span class="text-[12px] font-semibold text-slate-400">{{ $indiaSuppliers->count() }} shown</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-[13px]">
                            @forelse($indiaSuppliers as $supplier)
                                @include('components.public-supplier-card', ['supplier' => $supplier])
                            @empty
                                <div class="col-span-full p-6 text-center text-gray-500 font-medium bg-gray-50 border border-dashed rounded">
                                    No Indian sellers are visible right now.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-[15px] font-extrabold text-[#0b2a4a]">Sellers from International Markets</h3>
                            <span class="text-[12px] font-semibold text-slate-400">{{ $internationalSuppliers->count() }} shown</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-[13px]">
                            @forelse($internationalSuppliers as $supplier)
                                @include('components.public-supplier-card', ['supplier' => $supplier])
                            @empty
                                <div class="col-span-full p-6 text-center text-gray-500 font-medium bg-gray-50 border border-dashed rounded">
                                    No international sellers are visible right now.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category wise Subcategories --}}
            @if(($categoryRows ?? collect())->count())
                @foreach($categoryRows as $categoryRow)
                    <div class="mt-5 bg-white border">
                        {{-- Section Header --}}
                        <div class="px-4 py-3 border-b flex items-center justify-between">
                            <div class="font-extrabold text-[#0b2a4a] text-[15px] tracking-tight">{{ $categoryRow->name }}</div>
                            <a href="{{ route('category.detail', $categoryRow->id) }}"
                               class="text-[12px] font-bold text-[#ff6a00] hover:underline flex items-center gap-1">
                                View All
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        <div class="p-4">
                            @if($categoryRow->subcategories->count())
                                {{-- Subcategory scroll row --}}
                                <div class="flex overflow-x-auto snap-x gap-4 pb-2" style="scrollbar-width:none; -webkit-overflow-scrolling:touch;">
                                    @foreach($categoryRow->subcategories->take(20) as $sub)
                                        <a href="{{ route('products', ['subcategory' => $sub->id]) }}"
                                           class="flex-none snap-start group w-[calc((100%-2rem)/3)] lg:w-[calc((100%-6rem)/7)]">
                                            <div class="relative rounded-xl overflow-hidden border border-gray-200 shadow-sm group-hover:shadow-lg group-hover:border-[#ff6a00] transition-all duration-300 bg-white flex flex-col h-full">

                                                {{-- Image area --}}
                                                <div class="relative h-40 bg-gray-50 flex flex-col items-center justify-center overflow-hidden">
                                                    @if($sub->image ?? null)
                                                        <img src="{{ $sub->image }}"
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-400"
                                                             alt="{{ $sub->name }}">
                                                    @else
                                                        {{-- Stylish empty state --}}
                                                        <div class="flex flex-col items-center gap-2">
                                                            <div class="w-20 h-20 bg-white shadow-sm flex items-center justify-center rounded-lg border border-gray-100">
                                                                <svg class="w-10 h-10 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Name footer --}}
                                                <div class="px-2 py-3 bg-white border-t border-gray-100 flex-grow flex flex-col justify-between">
                                                    <div class="font-bold text-[#0b2a4a] text-[12px] leading-snug line-clamp-2 group-hover:text-[#ff6a00] transition-colors text-center" title="{{ $sub->name }}">
                                                        {{ $sub->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                {{-- No subcategories: show a "Browse category" CTA --}}
                                <a href="{{ route('category.detail', $categoryRow->id) }}"
                                   class="inline-flex items-center gap-2 text-[13px] font-bold text-[#0b2a4a] hover:text-[#ff6a00] transition-colors py-2">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    Browse all products in this category
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Bottom Banners -->
            @if(($bottomBanners ?? collect())->count())
                <div class="mt-5 space-y-3">
                    @foreach($bottomBanners as $b)
                        <a href="{{ $b->link_url ?? '#' }}" class="block">
                            <img src="{{ $b->image_url }}" class="w-full border rounded-lg" alt="{{ $b->title ?? 'Banner' }}">
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
