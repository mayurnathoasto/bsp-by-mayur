<header
    class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 sm:px-10 sticky top-0 z-30 shadow-sm flex-shrink-0">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:bg-slate-50 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
        {{-- Logo: visible on mobile only (sidebar hidden on mobile) --}}
        <a href="{{ url('/') }}" class="lg:hidden">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="h-[32px]" />
        </a>
        <div class="text-sm font-semibold tracking-tight text-slate-800 hidden sm:block">
            {{ $pageTitle ?? 'Dashboard' }}
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div
            class="w-10 h-10 bg-[#ff6a00]/10 border border-[#ff6a00]/20 text-[#ff6a00] rounded-xl flex items-center justify-center font-bold text-lg cursor-pointer hover:bg-[#ff6a00]/20 transition-colors">
            {{ strtoupper(substr(auth()->user()->name ?? 'User', 0, 1)) }}
        </div>
        <div class="hidden md:block">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none mb-1">Hi!</p>
            <div class="flex items-center gap-1.5 cursor-pointer group">
                <span
                    class="text-sm font-bold text-slate-800 group-hover:text-[#ff6a00] transition-colors">{{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>
    </div>
</header>