@php
    $resolvedCode = strtoupper(trim((string) ($countryCode ?? '')));
    $flagCode = $resolvedCode === 'UK' ? 'GB' : $resolvedCode;
    $resolvedName = $countryName ?? (config("countries.supported.{$resolvedCode}.name") ?? $resolvedCode);
    $showName = $showName ?? true;

    $classes = $classes ?? 'inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-600';
    $flagClasses = $flagClasses ?? 'h-3.5 w-5 rounded-[2px] object-cover shrink-0 shadow-sm';
    $labelClasses = $labelClasses ?? '';
    $fallbackClasses = 'hidden min-w-[20px] items-center justify-center text-[10px] font-bold uppercase leading-none text-slate-500';

    $flagUrl = preg_match('/^[A-Z]{2}$/', $flagCode) === 1
        ? 'https://flagcdn.com/w40/' . strtolower($flagCode) . '.png'
        : null;
@endphp

<span class="{{ $classes }}" title="{{ $resolvedName }}">
    @if($flagUrl)
        <img
            src="{{ $flagUrl }}"
            alt="{{ $resolvedName }} flag"
            class="{{ $flagClasses }}"
            loading="lazy"
            decoding="async"
            referrerpolicy="no-referrer"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
        >
    @endif
    <span class="{{ $fallbackClasses }}" @if($flagUrl) aria-hidden="true" @endif>{{ $resolvedCode ?: 'GL' }}</span>
    @if($showName)
        <span class="{{ $labelClasses }}">{{ $resolvedName }}</span>
    @endif
</span>
