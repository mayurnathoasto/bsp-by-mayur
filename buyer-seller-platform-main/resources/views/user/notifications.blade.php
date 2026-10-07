@extends('layouts.app')

@section('content')
    @include('layouts.sidebar_css')

    <div x-data="{ sidebarOpen: false, openSub: 'dashboard', activeTab: 'notifications' }"
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
                <div class="max-w-[1200px] mx-auto space-y-6">
                    <div class="flex items-center justify-between gap-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="mb-3 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-slate-800">Notifications</span>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
                            <p class="mt-1 text-sm text-slate-500">Track approval updates and announcements from the admin team.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('user.notifications.read-all') }}">
                                @csrf
                                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Mark All Read
                                </button>
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

                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                        @forelse($notifications as $recipient)
                            @php $item = $recipient->notification; @endphp
                            <div class="border-b border-slate-100 p-5 last:border-b-0">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div class="space-y-2">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h2 class="text-lg font-bold text-slate-800">{{ $item->title }}</h2>
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide {{ $recipient->read_at ? 'bg-slate-100 text-slate-500' : 'bg-orange-50 text-[#ff6a00]' }}">
                                                {{ $recipient->read_at ? 'Read' : 'Unread' }}
                                            </span>
                                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-blue-700">
                                                {{ str_replace('_', ' ', $item->type) }}
                                            </span>
                                        </div>
                                        <p class="text-sm leading-6 text-slate-600">{{ $item->message }}</p>
                                        <div class="text-xs text-slate-400">
                                            {{ optional($item->created_at)->format('d M Y, h:i A') }}
                                            @if($item->creator)
                                                | Sent by {{ $item->creator->name }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 shrink-0">
                                        @if($item->action_url)
                                            <a href="{{ $item->action_url }}" class="rounded-xl bg-[#0b2a4a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#12385f]">
                                                Open
                                            </a>
                                        @endif

                                        @if(!$recipient->read_at)
                                            <form method="POST" action="{{ route('user.notifications.read', $recipient) }}">
                                                @csrf
                                                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Mark Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-500">No notifications available right now.</div>
                        @endforelse
                    </div>

                    <div>{{ $notifications->links() }}</div>
                </div>
            </div>
        </main>
    </div>
@endsection
