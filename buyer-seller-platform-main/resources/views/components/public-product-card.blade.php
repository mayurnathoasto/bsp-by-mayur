@php
    $product = $product ?? null;
    $variant = $variant ?? 'grid';
    if ($product) {
        $images = is_array($product->images) ? $product->images : json_decode($product->images ?? '[]', true);
        $images = is_array($images) ? array_values(array_filter($images)) : [];
        $image = $images[0] ?? null;

        $productCountryCode = strtoupper($product->country_code ?? $product->user?->businessProfile?->country_code ?? session('country', 'IN'));
        $categoryText = trim(collect([
            $product->category->name ?? null,
            $product->subcategory->name ?? null,
        ])->filter()->implode(' / '));

        $sellerName = $product->user?->businessProfile?->company_name
            ?? $product->user?->name
            ?? 'Verified Seller';

        $sellerLabel = $variant === 'slider' ? 'Verified Seller' : ('Seller: ' . $sellerName);

        $cardClasses = $variant === 'slider'
            ? 'group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg'
            : 'group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md';

        $imageWrapClasses = $variant === 'slider'
            ? 'relative aspect-[4/3] overflow-hidden bg-slate-100'
            : 'relative aspect-[4/3] overflow-hidden rounded-xl bg-slate-100';

        $bodyClasses = 'flex flex-1 flex-col p-4';
    }
@endphp

@if($product)
    <a href="{{ route('products.show', $product) }}" class="{{ $cardClasses }}">
        <div class="{{ $imageWrapClasses }}">
            @if($image)
                <img src="{{ $image }}" alt="{{ $product->name }}"
                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-300">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="mt-2 text-[10px] font-bold uppercase tracking-[0.22em]">No Image</span>
                </div>
            @endif
        </div>

        <div class="{{ $bodyClasses }}">
            <div class="line-clamp-2 min-h-[3.25rem] text-[15px] font-extrabold leading-6 text-[#0b2a4a] transition-colors group-hover:text-[#ff6a00]">
                {{ $product->name }}
            </div>

            @if($categoryText !== '')
                <div class="mt-2 line-clamp-2 text-[12px] font-medium leading-5 text-slate-500">
                    {{ $categoryText }}
                </div>
            @endif

            <div class="mt-3 flex items-center gap-2">
                @include('components.country-badge', [
                    'countryCode' => $productCountryCode,
                    'showName' => false,
                    'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600',
                ])
                <span class="text-[12px] font-semibold text-slate-500">{{ $sellerLabel }}</span>
            </div>

            <div class="mt-auto flex items-end justify-between gap-3 pt-4">
                <div class="text-[18px] font-extrabold text-[#1a7f37]">
                    {{ $product->display_price_formatted ?? 'N/A' }}
                </div>

                @if($variant === 'slider')
                    <span class="text-[13px] font-bold text-[#0b2a4a]">Get Best Price</span>
                @endif
            </div>
        </div>
    </a>
@endif
