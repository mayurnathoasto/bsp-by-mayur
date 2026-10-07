@extends('layouts.app')

@section('content')
    @include('layouts.sidebar_css')

    <div x-data="{ sidebarOpen: false, openSub: 'dashboard', activeTab: 'support' }"
        class="flex flex-row-reverse h-screen overflow-hidden bg-[#f8fafc] relative">
        <style>
            @media (min-width: 1024px) {
                aside {
                    border-right: none !important;
                    border-left: 1px solid #e2e8f0 !important;
                }
            }
        </style>

        @include('layouts.sidebar')

        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto">
            <div class="p-8">
                <div class="max-w-[1400px] mx-auto grid gap-6 xl:grid-cols-[1.05fr_1.3fr]">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="mb-4 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="text-slate-800">Support & Reports</span>
                                </div>

                                <h1 class="text-2xl font-bold text-slate-800">Raise a Ticket or Report</h1>
                            </div>
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Use tickets for order or account support. Use reports to flag a seller or another marketplace user.</p>

                        @if(session('success'))
                            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.support.store') }}" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Request Type</label>
                                <select name="type" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700">
                                    <option value="ticket" @selected(old('type', $prefillType) === 'ticket')>Support Ticket</option>
                                    <option value="report" @selected(old('type', $prefillType) === 'report')>Report User / Seller</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Briefly describe the issue" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700" required>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Order Reference</label>
                                    <input type="text" name="order_reference" value="{{ old('order_reference') }}" placeholder="Optional order ID / reference" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700">
                                </div>

                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Reported User ID</label>
                                    <input type="number" name="target_user_id" value="{{ old('target_user_id', $targetUser?->id) }}" placeholder="Optional user ID" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700">
                                    @if($targetUser)
                                        <div class="mt-2 text-xs text-slate-500">Prefilled user: <span class="font-semibold text-slate-700">{{ $targetUser->company_name ?? $targetUser->name }}</span></div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Context URL</label>
                                <input type="text" name="context_url" value="{{ old('context_url', $contextUrl) }}" placeholder="Optional page URL for reference" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700">
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Details</label>
                                <textarea name="message" rows="6" placeholder="Share the issue, timeline, and what outcome you need." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700" required>{{ old('message') }}</textarea>
                            </div>

                            @if($errors->any())
                                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
                            @endif

                            <button class="rounded-xl bg-[#0b2a4a] px-5 py-3 text-sm font-semibold text-white hover:bg-[#12385f]">Submit Request</button>
                        </form>
                    </section>

                    <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-4">
                            <h2 class="text-lg font-bold text-slate-800">My Tickets & Reports</h2>
                            <p class="mt-1 text-sm text-slate-500">Track your submitted cases and admin replies.</p>
                        </div>

                        @forelse($tickets as $ticket)
                            <div class="border-b border-slate-100 px-6 py-5 last:border-b-0">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-base font-bold text-slate-800">{{ $ticket->subject }}</h3>
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-blue-700">{{ strtoupper($ticket->type) }}</span>
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ in_array($ticket->status, ['resolved', 'closed'], true) ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-[#ff6a00]' }}">{{ str_replace('_', ' ', $ticket->status) }}</span>
                                    </div>
                                    <p class="text-sm leading-6 text-slate-600">{{ $ticket->message }}</p>
                                    <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                                        <span>Submitted: {{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                                        @if($ticket->order_reference)
                                            <span>Order Ref: {{ $ticket->order_reference }}</span>
                                        @endif
                                        @if($ticket->targetUser)
                                            <span>Target: {{ $ticket->targetUser->company_name ?? $ticket->targetUser->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($ticket->admin_reply)
                                    <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                                        <div class="text-xs font-bold uppercase tracking-wide text-green-700">Admin Reply</div>
                                        <div class="mt-2 text-sm leading-6 text-green-800">{{ $ticket->admin_reply }}</div>
                                        <div class="mt-2 text-xs text-green-700">
                                            {{ optional($ticket->adminRepliedBy)->name ?: 'Admin Team' }} | {{ optional($ticket->admin_replied_at)->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-500">No support cases submitted yet.</div>
                        @endforelse

                        <div class="px-6 py-4">{{ $tickets->links() }}</div>
                    </section>
                </div>
            </div>
        </main>
    </div>
@endsection
