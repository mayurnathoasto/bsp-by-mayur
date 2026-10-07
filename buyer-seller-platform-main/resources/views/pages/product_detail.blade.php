@extends('layouts.app')

@section('title', $product->name . ' | Buyer Seller Platform')

@section('content')
    @php
        $images = collect($product->images ?? [])->filter()->values();
        $primaryImage = $images->first();
        $biz = $product->user?->businessProfile;
        $sellerName = $biz?->company_name ?: ($product->user?->name ?: 'Verified Seller');
        $sellerLogo = $biz?->logo ?: $biz?->company_logo_path;
        $countryCode = strtoupper($product->country_code ?: ($biz?->country_code ?: session('country', 'IN')));
        $leadTimeText = $product->lead_time_display ?: 'Contact seller';
        $shippingText = $product->shipping_display ?: 'Discuss with seller';
        $paymentText = $product->payment_display ?: 'Discuss with seller';
        $warrantyText = $product->warranty ?: 'As per seller policy';
        $minOrderText = $product->min_order_quantity ? $product->min_order_quantity . ' Units' : '1 Unit';
        $specifications = $product->specifications ?? collect();
        $companyDescription = trim((string) ($biz?->company_description ?: 'Verified supplier profile available on Buyer Seller Platform.'));
        $locationText = collect([$biz?->city_name, $biz?->state_name])->filter()->implode(', ');
        $establishedYear = $biz?->year_established;
        $employeeCount = $biz?->employee_count;
        $hasVerifiedSeller = ($product->user?->seller_status ?? null) === \App\Models\User::SELLER_STATUS_APPROVED;
    @endphp

    <div x-data="{ tab: 'description', activeImage: @js($primaryImage) }" class="bg-[#f4f6f9] py-6 lg:py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-4 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <a href="{{ url('/') }}" class="hover:text-slate-600">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products') }}" class="hover:text-slate-600">Products</a>
                <span class="mx-2">/</span>
                <span class="text-slate-700">{{ $product->name }}</span>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        @if($primaryImage)
                            <img :src="activeImage || @js($primaryImage)" alt="{{ $product->name }}" class="h-[320px] w-full object-contain bg-white p-4 sm:h-[420px]">
                        @else
                            <div class="flex h-[320px] items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-300 sm:h-[420px]">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div class="mt-2 text-sm font-semibold">No product image</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($images->count() > 1)
                        <div class="mt-4 flex gap-3 overflow-x-auto pb-1">
                            @foreach($images as $image)
                                <button type="button" @click="activeImage = @js($image)"
                                    class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 transition hover:border-[#ff6a00]">
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full rounded-xl object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-green-700">Live Product</span>
                        @if($hasVerifiedSeller)
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700">Verified Seller</span>
                        @endif
                    </div>

                    <h1 class="mt-4 text-2xl font-extrabold leading-tight text-[#0b1f3a] sm:text-[30px]">{{ $product->name }}</h1>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        @include('components.country-badge', [
                            'countryCode' => $countryCode,
                            'showName' => true,
                            'classes' => 'inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-[12px] font-semibold text-slate-600',
                        ])
                        @if($locationText !== '')
                            <span>{{ $locationText }}</span>
                        @endif
                    </div>

                    <div class="mt-6 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 to-sky-50 p-5">
                        <div class="text-3xl font-extrabold text-blue-700">{{ $product->display_price_formatted ?: 'Price on Request' }}</div>
                        <div class="mt-3 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
                            <div><span class="font-semibold text-slate-800">Minimum Order:</span> {{ $minOrderText }}</div>
                            <div><span class="font-semibold text-slate-800">Lead Time:</span> {{ $leadTimeText }}</div>
                            <div><span class="font-semibold text-slate-800">Shipping:</span> {{ $shippingText }}</div>
                            <div><span class="font-semibold text-slate-800">Payment:</span> {{ $paymentText }}</div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                @if($sellerLogo)
                                    <img src="{{ $sellerLogo }}" alt="{{ $sellerName }}" class="h-full w-full object-contain bg-white p-2">
                                @else
                                    <span class="text-xl font-black text-[#0b2a4a]">{{ strtoupper(substr($sellerName, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('seller.show', $product->user_id) }}" class="text-lg font-extrabold text-[#0b2a4a] hover:text-[#ff6a00]">{{ $sellerName }}</a>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                    @if($locationText !== '')
                                        <span>{{ $locationText }}</span>
                                    @endif
                                    @if($hasVerifiedSeller)
                                        <span class="font-semibold text-green-600">Verified Supplier</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <a href="#inquiry-form" @click="tab = 'inquiry'" class="flex h-12 items-center justify-center rounded-2xl bg-[#0b2a4a] px-4 text-sm font-bold text-white hover:bg-[#12385f]">Send Inquiry</a>
                        <a href="{{ route('seller.show', $product->user_id) }}" class="flex h-12 items-center justify-center rounded-2xl border border-[#0b2a4a] px-4 text-sm font-bold text-[#0b2a4a] hover:bg-slate-50">View Store</a>
                    </div>
                </section>
            </div>

            <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex overflow-x-auto border-b border-slate-200">
                    <button type="button" @click="tab = 'description'" :class="tab === 'description' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'" class="px-5 py-4 text-sm font-bold">Product Description</button>
                    <button type="button" @click="tab = 'specs'" :class="tab === 'specs' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'" class="px-5 py-4 text-sm font-bold">Detailed Specifications</button>
                    <button type="button" @click="tab = 'company'" :class="tab === 'company' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'" class="px-5 py-4 text-sm font-bold">Company Profile</button>
                    <button type="button" @click="tab = 'inquiry'" :class="tab === 'inquiry' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'" class="px-5 py-4 text-sm font-bold">Send Inquiry</button>
                </div>

                <div class="p-5 sm:p-8">
                    <div x-show="tab === 'description'" x-cloak class="space-y-6">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#0b1f3a]">
                                @if($product->category)
                                    {{ $product->category->name }} -
                                @endif
                                {{ $product->name }}
                            </h2>
                            <p class="mt-4 text-[15px] leading-8 text-slate-600">{{ $product->description ?: 'Product details are available from the seller on request.' }}</p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div class="text-lg font-extrabold text-blue-600">Quality Assured</div>
                                <p class="mt-2 text-sm leading-7 text-slate-600">Every product is quality-checked and verified before dispatch to ensure premium standards.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div class="text-lg font-extrabold text-blue-600">Fast Fulfillment</div>
                                <p class="mt-2 text-sm leading-7 text-slate-600">Ready-to-ship inventory with global logistics capabilities for quick order processing.</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'specs'" x-cloak>
                        <div class="overflow-hidden rounded-2xl border border-slate-200">
                            <table class="min-w-full border-collapse text-sm">
                                <tbody>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="w-[34%] px-4 py-3 text-left font-bold text-slate-700 sm:w-[28%]">Category</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $product->category?->name ?: 'Not specified' }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Subcategory</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $product->subcategory?->name ?: 'Not specified' }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Price</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $product->display_price_formatted ?: 'Price on Request' }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Minimum Order Quantity</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $minOrderText }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Lead Time</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $leadTimeText }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Shipping Options</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $shippingText }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Payment Options</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $paymentText }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left font-bold text-slate-700">Warranty</th>
                                        <td class="px-4 py-3 text-slate-600">{{ $warrantyText }}</td>
                                    </tr>
                                    @foreach($specifications as $spec)
                                        <tr class="{{ $loop->odd ? 'bg-slate-50' : '' }} border-b border-slate-200 last:border-b-0">
                                            <th class="px-4 py-3 text-left font-bold text-slate-700">{{ $spec->spec_key }}</th>
                                            <td class="px-4 py-3 text-slate-600">{{ $spec->spec_value ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="tab === 'company'" x-cloak class="space-y-6">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#0b1f3a]">{{ $sellerName }}</h2>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                @if($locationText !== '')
                                    <span>{{ $locationText }}</span>
                                @endif
                                @if($hasVerifiedSeller)
                                    <span class="font-semibold text-green-600">Verified Supplier</span>
                                @endif
                            </div>
                            <p class="mt-4 text-[15px] leading-8 text-slate-600">{{ $companyDescription }}</p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Location</div>
                                <div class="mt-2 text-sm font-semibold text-slate-700">{{ $locationText ?: 'Not specified' }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Country</div>
                                <div class="mt-2 text-sm font-semibold text-slate-700">{{ config('countries.supported.' . $countryCode . '.name') ?: $countryCode }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Established</div>
                                <div class="mt-2 text-sm font-semibold text-slate-700">{{ $establishedYear ?: 'Not specified' }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Employees</div>
                                <div class="mt-2 text-sm font-semibold text-slate-700">{{ $employeeCount ?: 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'inquiry'" x-cloak id="inquiry-form">
                        @if(session('success'))
                            <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-5">
                            <h2 class="text-2xl font-extrabold text-[#0b1f3a]">Send Inquiry to {{ $sellerName }}</h2>
                            <p class="mt-2 text-sm text-slate-500">Share your quantity, customization needs and delivery details.</p>
                        </div>

                        <form method="POST" action="{{ route('products.inquiry.store', $product) }}" class="grid gap-4 md:grid-cols-2">
                            @csrf
                            @guest
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Name</label>
                                    <input name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Your name">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Your email">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Phone</label>
                                    <input name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Your phone number">
                                </div>
                            @endguest

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Message</label>
                                <textarea name="message" rows="6" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" placeholder="Describe your requirements, quantity and delivery expectations." required>{{ old('message') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <button type="submit" class="rounded-2xl bg-[#0b2a4a] px-5 py-3 text-sm font-bold text-white hover:bg-[#12385f]">Send Inquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            @if(($relatedProducts ?? collect())->isNotEmpty())
                <section x-data="{ scroll(direction) { this.$refs.track.scrollBy({ left: direction * 760, behavior: 'smooth' }); } }" class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-[20px] font-extrabold text-slate-800">More Products Like This</h2>
                            <p class="mt-1 text-sm text-slate-500">Similar products from the same category, shown across all countries.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="scroll(-1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&larr;</button>
                            <button type="button" @click="scroll(1)" class="h-9 w-9 rounded-full border border-slate-200 text-slate-500 hover:border-[#ff6a00] hover:text-[#ff6a00]">&rarr;</button>
                        </div>
                    </div>

                    <div x-ref="track" class="mt-5 flex gap-4 overflow-x-auto pb-2 scroll-smooth" style="scrollbar-width: thin;">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="min-w-[210px] max-w-[210px] shrink-0">
                                @include('components.public-product-card', [
                                    'product' => $relatedProduct,
                                    'variant' => 'slider',
                                ])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
