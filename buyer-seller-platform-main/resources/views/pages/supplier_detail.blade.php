@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="text-sm text-gray-500">Suppliers / {{ $slug }}</div>

    <div class="mt-4 bg-white border rounded-lg p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold">{{ ucwords(str_replace('-', ' ', $slug)) }}</h1>
                <div class="text-sm text-gray-600">Verified Supplier • Response Rate: High</div>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded bg-orange-500 text-white font-semibold">Contact Supplier</button>
                <button class="px-4 py-2 rounded border font-semibold">Send Enquiry</button>
            </div>
        </div>

        <div class="mt-6">
            <div class="font-semibold">Products from this supplier</div>
            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @for ($i = 1; $i <= 12; $i++)
                <a href="/products/{{ $slug }}-product-{{ $i }}" class="border rounded-lg p-3 hover:bg-gray-50">
                    <div class="h-20 rounded bg-gray-100"></div>
                    <div class="mt-2 text-sm font-semibold">Product {{ $i }}</div>
                    <div class="text-xs text-gray-500">Get Best Price</div>
                </a>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
