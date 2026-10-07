@extends('layouts.app')

@section('content')
    @include('layouts.sidebar_css')
    <div x-data="{ 
                                    sidebarOpen: false, 
                                    openSub: 'dashboard',
                                    activeTab: 'dashboard'
                                }"
        class="flex flex-row-reverse h-screen overflow-hidden bg-[#f8fafc]">
        @include('layouts.sidebar')

        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto">
            {{-- Content Area --}}
            <div class="p-8">
                <div class="max-w-4xl mx-auto space-y-6">

                    <div class="flex items-start justify-between">
                        <div>
                            <div
                                class="mb-4 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <a href="{{ route('user.inquiries') }}" class="hover:text-slate-600">Inquiries</a>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="text-slate-800">Inquiry Details</span>
                            </div>

                            <h1 class="text-2xl font-bold text-slate-800">Inquiry Details</h1>
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden p-8 space-y-8">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Product</h2>
                                <p class="text-lg font-bold text-slate-800">
                                    {{ $inquiry->product->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="space-y-1">
                                <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Submitted At</h2>
                                <p class="text-slate-600 font-medium">{{ $inquiry->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">Buyer Info</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Name</span>
                                    <span class="font-bold text-slate-700">{{ $inquiry->buyer->name ?? $inquiry->name ?? 'N/A' }}</span>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Email</span>
                                    <span class="font-bold text-slate-700">{{ $inquiry->buyer->email ?? $inquiry->email ?? 'N/A' }}</span>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Phone</span>
                                    <span class="font-bold text-slate-700">{{ $inquiry->buyer->phone ?? $inquiry->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Message</h2>
                            <div class="bg-slate-50 p-6 rounded-2xl text-slate-700 leading-relaxed whitespace-pre-line border border-slate-100 italic">
                                "{{ $inquiry->message }}"
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">Status Update</h2>

                            <form method="POST" action="{{ route('user.inquiries.updateStatus', $inquiry) }}"
                                class="flex gap-3 items-center">
                                @csrf
                                <select name="status"
                                    class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:border-[#ff6a00] outline-none">
                                    <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New Inquiry</option>
                                    <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Mark as Contacted</option>
                                    <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Move to Closed</option>
                                </select>

                                <button type="submit"
                                    class="bg-[#0b2a4a] text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#081d33] transition-all">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                            <a href="{{ route('user.inquiries') }}"
                                class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-800 font-bold text-xs uppercase tracking-widest transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to List
                            </a>
                            
                            @if($inquiry->product)
                            <a href="{{ route('products.show', $inquiry->product) }}" target="_blank"
                                class="text-[#ff6a00] font-bold text-xs uppercase tracking-widest hover:underline">
                                View Product Page →
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{-- Mobile Sidebar Backdrop --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
            class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-[2px] lg:hidden transition-opacity"></div>
    </div>
@endsection