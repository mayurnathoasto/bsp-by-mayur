<div class="lg:hidden fixed bottom-0 left-0 w-full z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] bg-white text-slate-500 font-sans pb-[env(safe-area-inset-bottom)]">
    <!-- Post Your Requirement Banner -->
    <a href="{{ route('post-requirement') }}" class="block w-full bg-[#2563eb] text-white text-center py-2.5 font-bold text-[14px]">
        Post Your Requirement
    </a>

    <!-- Nav Items -->
    <div class="flex justify-around items-center h-[60px] border-t border-slate-100 bg-white">
        <!-- Home -->
        <a href="{{ url('/') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 text-slate-400">
            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.592 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        <!-- Search -->
        <a href="{{ route('search') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 text-slate-400">
            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <span class="text-[10px] font-medium">Search</span>
        </a>

        <!-- Post -->
        <a href="{{ route('post-requirement') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 text-slate-400">
            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span class="text-[10px] font-medium">Post</span>
        </a>

        <!-- Messages -->
        @php
            $unreadCount = auth()->check() ? \App\Models\ProductInquiry::where('seller_user_id', auth()->id())->where('status', 'new')->count() : 0;
        @endphp
        <a href="{{ auth()->check() ? route('user.inquiries') : route('login') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 relative text-slate-400">
            <div class="relative">
                <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[16px] h-[16px] rounded-full bg-[#ef4444] text-white text-[9px] font-bold px-[4px] border-2 border-white leading-none">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </div>
            <span class="text-[10px] font-medium">Messages</span>
        </a>

        <!-- Profile -->
        <a href="{{ auth()->check() ? route('user.dashboard') : route('login') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 text-slate-400">
            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>
</div>
