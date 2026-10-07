@extends('layouts.app')

@section('content')
    @php
        $hero = [
            'AE' => [
                'title' => 'Source Products from UAE & Global Suppliers',
                'subtitle' => 'Connect with verified suppliers across UAE, GCC and international markets.',
                'usp' => 'Get quotations | Compare suppliers | Fast delivery',
            ],
            'US' => [
                'title' => 'Find Trusted US Manufacturers & Exporters',
                'subtitle' => 'Discover quality products from verified suppliers across the United States.',
                'usp' => 'Verified sellers | Competitive pricing | Reliable shipping',
            ],
            'UK' => [
                'title' => 'Buy & Sell with Verified UK Suppliers',
                'subtitle' => 'Access a wide range of products from trusted UK manufacturers and traders.',
                'usp' => 'Trusted suppliers | Transparent pricing | Fast response',
            ],
            'CA' => [
                'title' => 'Discover Reliable Canadian Suppliers',
                'subtitle' => 'Source products from verified suppliers across Canada and North America.',
                'usp' => 'Quality products | Verified sellers | Competitive offers',
            ],
            'AU' => [
                'title' => 'Connect with Leading Australian Suppliers',
                'subtitle' => 'Explore products from verified suppliers across Australia and nearby regions.',
                'usp' => 'Trusted sellers | Best prices | Quick quotes',
            ],
            'CN' => [
                'title' => 'Discover Verified Chinese Manufacturers & Exporters',
                'subtitle' => 'Find products from trusted suppliers across China with country-focused listings.',
                'usp' => 'Verified suppliers | Better sourcing | Faster comparison',
            ],
        ];

        $data = $hero[$country] ?? [
            'title' => 'Explore Products from ' . $countryName,
            'subtitle' => 'Connect with verified suppliers and discover country-specific marketplace listings.',
            'usp' => 'Verified sellers | Country-focused products | Fast inquiry',
        ];
    @endphp

    <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-3 py-4">
            <div class="bg-white border p-4">
                <div class="text-[12px] text-gray-500">Home / {{ $countryName }}</div>

                <div class="mt-3 min-h-[260px] bg-gradient-to-r from-[#0b2a4a] to-[#163d67] p-6 text-white">
                    <div class="text-[26px] font-extrabold leading-8">{{ $data['title'] }}</div>
                    <div class="mt-2 text-[14px] text-white/90">{{ $data['subtitle'] }}</div>
                    <div class="mt-3 text-[13px] text-white/80">{{ $data['usp'] }}</div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('products', ['country' => $country]) }}" class="px-4 py-2 bg-[#ff6a00] font-bold text-[13px]">
                            Explore {{ $countryName }} Products
                        </a>
                        <a href="{{ route('post-requirement') }}" class="px-4 py-2 bg-white text-[#0b2a4a] font-bold text-[13px]">
                            Post Buy Requirement
                        </a>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 text-[13px] md:grid-cols-3">
                    <div class="border bg-gray-50 p-3">
                        <div class="font-extrabold text-[#0b2a4a]">Popular Categories</div>
                        <div class="mt-1 text-[12px] text-gray-600">{{ $popularCategories->count() }} active categories available for {{ $countryName }}.</div>
                    </div>
                    <div class="border bg-gray-50 p-3">
                        <div class="font-extrabold text-[#0b2a4a]">Local Products</div>
                        <div class="mt-1 text-[12px] text-gray-600">{{ $products->count() }} products currently available from {{ $countryName }}.</div>
                    </div>
                    <div class="border bg-gray-50 p-3">
                        <div class="font-extrabold text-[#0b2a4a]">Local Sellers</div>
                        <div class="mt-1 text-[12px] text-gray-600">{{ $suppliers->count() }} verified sellers currently available from {{ $countryName }}.</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-[18px] font-extrabold text-[#0b2a4a]">Popular Categories in {{ $countryName }}</h2>
                    </div>

                    @if($popularCategories->isEmpty())
                        <div class="mt-3 border bg-gray-50 p-4 text-[13px] text-gray-600">
                            No country-specific categories found yet for {{ $countryName }}.
                        </div>
                    @else
                        <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-4">
                            @foreach($popularCategories as $category)
                                <a href="{{ route('products', ['category' => $category->id]) }}"
                                    class="block border bg-white p-3 transition hover:shadow-sm">
                                    <div class="flex h-28 w-full items-center justify-center overflow-hidden bg-gray-100">
                                        @if($category->image)
                                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                                        @elseif($category->icon)
                                            <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="h-14 w-14 object-contain">
                                        @else
                                            <div class="text-[12px] text-gray-400">No Image</div>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-[13px] font-bold text-gray-800">{{ $category->name }}</div>
                                    <div class="mt-1 text-[11px] text-gray-500">{{ $category->country_products_count ?? 0 }} products</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mt-8 space-y-8">
                    <section x-data="{ scroll(direction) { this.$refs.track.scrollBy({ left: direction * 900, behavior: 'smooth' }); } }">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h2 class="text-[18px] font-extrabold text-[#0b2a4a]">Products from {{ $countryName }}</h2>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="scroll(-1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&larr;</button>
                                <button type="button" @click="scroll(1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&rarr;</button>
                            </div>
                        </div>

                        @if($products->isEmpty())
                            <div class="border bg-gray-50 p-4 text-[13px] text-gray-600">
                                Products from {{ $countryName }} are currently unavailable.
                            </div>
                        @else
                            <div x-ref="track" class="flex gap-4 overflow-x-auto pb-2 scroll-smooth" style="scrollbar-width: thin;">
                                @foreach($products as $product)
                                    <div class="min-w-[220px] max-w-[220px] shrink-0">
                                        @include('components.public-product-card', ['product' => $product, 'variant' => 'slider'])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section x-data="{ scroll(direction) { this.$refs.track.scrollBy({ left: direction * 860, behavior: 'smooth' }); } }">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h2 class="text-[18px] font-extrabold text-[#0b2a4a]">Sellers from {{ $countryName }}</h2>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="scroll(-1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&larr;</button>
                                <button type="button" @click="scroll(1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&rarr;</button>
                            </div>
                        </div>

                        @if($suppliers->isEmpty())
                            <div class="border bg-gray-50 p-4 text-[13px] text-gray-600">
                                Sellers from {{ $countryName }} are currently unavailable.
                            </div>
                        @else
                            <div x-ref="track" class="flex gap-4 overflow-x-auto pb-2 scroll-smooth" style="scrollbar-width: thin;">
                                @foreach($suppliers as $supplier)
                                    <div class="min-w-[320px] max-w-[320px] shrink-0">
                                        @include('components.public-supplier-card', ['supplier' => $supplier])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section x-data="{ scroll(direction) { this.$refs.track.scrollBy({ left: direction * 900, behavior: 'smooth' }); } }">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h2 class="text-[18px] font-extrabold text-[#0b2a4a]">Products from Other International Markets</h2>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="scroll(-1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&larr;</button>
                                <button type="button" @click="scroll(1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&rarr;</button>
                            </div>
                        </div>

                        @if(($otherInternationalProducts ?? collect())->isEmpty())
                            <div class="border bg-gray-50 p-4 text-[13px] text-gray-600">
                                No products from other international markets are visible right now.
                            </div>
                        @else
                            <div x-ref="track" class="flex gap-4 overflow-x-auto pb-2 scroll-smooth" style="scrollbar-width: thin;">
                                @foreach($otherInternationalProducts as $product)
                                    <div class="min-w-[220px] max-w-[220px] shrink-0">
                                        @include('components.public-product-card', ['product' => $product, 'variant' => 'slider'])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section x-data="{ scroll(direction) { this.$refs.track.scrollBy({ left: direction * 860, behavior: 'smooth' }); } }">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h2 class="text-[18px] font-extrabold text-[#0b2a4a]">Sellers from Other International Markets</h2>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="scroll(-1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&larr;</button>
                                <button type="button" @click="scroll(1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&rarr;</button>
                            </div>
                        </div>

                        @if(($otherInternationalSuppliers ?? collect())->isEmpty())
                            <div class="border bg-gray-50 p-4 text-[13px] text-gray-600">
                                No sellers from other international markets are visible right now.
                            </div>
                        @else
                            <div x-ref="track" class="flex gap-4 overflow-x-auto pb-2 scroll-smooth" style="scrollbar-width: thin;">
                                @foreach($otherInternationalSuppliers as $supplier)
                                    <div class="min-w-[320px] max-w-[320px] shrink-0">
                                        @include('components.public-supplier-card', ['supplier' => $supplier])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <div class="mt-6 text-[13px] text-gray-600">
                    You are viewing the <b>{{ $countryName }}</b> marketplace. Local results are shown first, followed by other international markets.
                </div>
            </div>
        </div>
    </div>
@endsection
