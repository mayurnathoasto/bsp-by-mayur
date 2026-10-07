@php
    $supplier = $supplier ?? null;
    if ($supplier) {
        $displayName = $supplier->businessProfile?->company_name ?? $supplier->company_name ?? $supplier->name ?? 'Verified Seller';
        $initial = strtoupper(substr($displayName, 0, 1));
        $supplierCountryCode = strtoupper($supplier->businessProfile?->country_code ?? $supplier->country_code ?? session('country', 'IN'));
        $logoPath = $supplier->businessProfile?->logo
            ?? $supplier->businessProfile?->company_logo_path
            ?? $supplier->profile_photo_path
            ?? null;

        $location = $supplier->businessProfile?->city_name
            ?? $supplier->city?->name
            ?? ($supplier->businessProfile?->country_code ? config('countries.supported.' . strtoupper($supplier->businessProfile->country_code) . '.name') : null)
            ?? 'India';

        $ownershipType = $supplier->businessProfile?->ownership_type;
        $since = $supplier->businessProfile?->year_established ?? date('Y', strtotime($supplier->created_at));
    }
@endphp

@if($supplier)
    <a href="{{ route('seller.show', $supplier->id) }}"
        class="group flex h-full items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="{{ $displayName }}" class="h-full w-full object-contain bg-white p-2">
            @else
                <span class="text-[38px] font-black leading-none text-[#0b2a4a]">{{ $initial }}</span>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <div class="truncate text-[15px] font-extrabold text-[#0b2a4a] transition-colors group-hover:text-[#ff6a00]" title="{{ $displayName }}">
                {{ $displayName }}
            </div>

            <div class="mt-1 flex flex-wrap items-center gap-1 text-[13px] text-slate-500">
                <span class="font-bold text-[#16a34a]">Verified</span>
                <span>&bull;</span>
                <span>{{ $location }}</span>
            </div>

            <div class="mt-3 flex items-center gap-3">
                @include('components.country-badge', [
                    'countryCode' => $supplierCountryCode,
                    'showName' => false,
                    'classes' => 'inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600',
                ])

                @if($ownershipType)
                    <span class="truncate text-[12px] font-semibold text-slate-600">{{ $ownershipType }}</span>
                @endif
            </div>

            <div class="mt-2 text-[12px] font-medium text-slate-400">
                Since {{ $since }}
            </div>
        </div>
    </a>
@endif
