@extends('layouts.app')

@section('content')
    {{-- Import Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .submenu-item.active {}
    </style>
    <!-- Shared Sidebar CSS -->
    @include('layouts.sidebar_css')

    <div x-data="{ 
                            sidebarOpen: false, 
                            openSub: 'dashboard',
                            activeTab: 'my_enquiries'
                        }" class="flex flex-row-reverse h-screen overflow-hidden bg-[#f8fafc] relative">
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

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto">
            {{-- Content Area --}}
            <div class="p-8">
                <div class="max-w-[1400px] mx-auto space-y-4">
                    {{-- Breadcrumbs --}}
                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-6">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-slate-800">Inquiries</span>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-xl font-bold text-slate-800">Inquiries</h1>

                        <div class="flex items-center gap-3">
                            <form method="GET" class="flex gap-2">
                                <select name="status" class="border rounded-md px-3 py-2 bg-white text-sm font-medium">
                                    <option value="">All</option>
                                    <option value="new" @selected(request('status') === 'new')>New</option>
                                    <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
                                    <option value="closed" @selected(request('status') === 'closed')>Closed</option>
                                </select>
                                <button
                                    class="bg-white border rounded-md px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">Filter</button>
                            </form>
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 shadow-sm rounded-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600 border-b border-slate-100">
                                    <tr>
                                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider">Product
                                        </th>
                                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider">From</th>
                                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider">Message
                                        </th>
                                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider">Status
                                        </th>
                                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider">Date</th>
                                        <th class="text-right px-6 py-4 font-bold text-xs uppercase tracking-wider">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inquiries as $inq)
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                @if($inq->product)
                                                    <a class="text-[#3b7ea1] font-semibold hover:underline"
                                                        href="{{ url('/products/' . $inq->product->id) }}">
                                                        {{ $inq->product->name }}
                                                    </a>
                                                @else
                                                    <span class="text-slate-400 font-medium italic">Product deleted</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-800">{{ $inq->name ?? 'Buyer' }}</div>
                                                <div class="text-[11px] text-slate-500 mt-1 space-y-0.5">
                                                    @if($inq->phone)
                                                        <div>
                                                            <span
                                                                class="font-semibold text-slate-400 uppercase tracking-wider">Tel:</span>
                                                            {{ $inq->phone }}
                                                        </div>
                                                    @endif

                                                    @if($inq->email)
                                                        <div>
                                                            <span
                                                                class="font-semibold text-slate-400 uppercase tracking-wider">E:</span>
                                                            {{ $inq->email }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 max-w-md">
                                                <div class="line-clamp-2 text-slate-700 font-medium">{{ $inq->message }}</div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $inq->status === 'new' ? 'bg-orange-50 text-[#ff6a00] border-orange-100' : ($inq->status === 'contacted' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-slate-100 text-slate-500 border-slate-200') }}">
                                                    {{ ucfirst($inq->status) }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                                {{ $inq->created_at->format('d M Y, h:i A') }}
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('user.inquiries.show', $inq) }}"
                                                    class="text-blue-600 hover:underline text-sm font-semibold">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">
                                                No inquiries yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6">
                        {{ $inquiries->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
