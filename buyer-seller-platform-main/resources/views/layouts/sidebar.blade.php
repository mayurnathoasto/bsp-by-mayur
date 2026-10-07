<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col shadow-sm">
            <div class="h-16 flex items-center px-4 bg-white border-b border-slate-100 flex-shrink-0">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="h-[36px]" />
                    </a>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-8 space-y-2 bg-[#fcfdfe]">
                <div>
                    @php $isDashboardRoute = request()->routeIs('user.dashboard', 'user.profile', 'user.business-profile', 'user.bank-details', 'user.manage-users'); @endphp
                    <button @click="openSub = (openSub === 'dashboard' ? '' : 'dashboard')"
                        :class="openSub === 'dashboard' ? 'sidebar-item-card active' : 'sidebar-item-card'"
                        class="w-full flex items-center justify-between px-4 py-3.5 group">
                        <div class="flex items-center gap-3">
                            <svg style="width: 22px; height: 22px;" class="transition-colors flex-shrink-0"
                                :class="openSub === 'dashboard' ? 'text-[#ff6a00]' : 'text-slate-400 group-hover:text-[#ff6a00]'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span class="text-[15px] font-semibold text-slate-700"
                                :class="openSub === 'dashboard' && 'text-slate-900'">Dashboard</span>
                        </div>
                        <svg :class="openSub === 'dashboard' ? 'rotate-0 text-[#ff6a00]' : 'rotate-180 text-slate-400'"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                    <div x-show="openSub === 'dashboard'" x-collapse x-cloak class="submenu-container">
                        <a href="{{ route('user.dashboard') }}"
                            @click="sidebarOpen = false"
                            @if($isDashboardRoute) @click.prevent="activeTab = 'dashboard'; window.history.pushState({}, '', '{{ route('user.dashboard') }}')" @endif
                            class="submenu-item" :class="activeTab === 'dashboard' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'dashboard' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">Home</span>
                        </a>
                        <a href="{{ route('user.inquiries') }}"
                            @click="sidebarOpen = false"
                            class="submenu-item" :class="activeTab === 'my_enquiries' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'my_enquiries' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">My Enquiries</span>
                        </a>
                        <a href="{{ route('user.notifications') }}"
                            @click="sidebarOpen = false"
                            class="submenu-item" :class="activeTab === 'notifications' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'notifications' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">Notifications</span>
                        </a>
                        <a href="{{ route('user.support.index') }}"
                            @click="sidebarOpen = false"
                            class="submenu-item" :class="activeTab === 'support' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'support' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">Support &amp; Reports</span>
                        </a>
                    </div>
                </div>

                <div>
                    <button @click="openSub = (openSub === 'profile' ? '' : 'profile')"
                        :class="openSub === 'profile' ? 'sidebar-item-card active' : 'sidebar-item-card'"
                        class="w-full flex items-center justify-between px-4 py-3.5 group">
                        <div class="flex items-center gap-3">
                            <svg style="width: 22px; height: 22px;" class="transition-colors flex-shrink-0"
                                :class="openSub === 'profile' ? 'text-[#ff6a00]' : 'text-slate-400 group-hover:text-[#ff6a00]'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-[15px] font-semibold text-slate-700"
                                :class="openSub === 'profile' && 'text-slate-900'">Profile</span>
                        </div>
                        <svg :class="openSub === 'profile' ? 'rotate-0 text-[#ff6a00]' : 'rotate-180 text-slate-400'"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                    <div x-show="openSub === 'profile'" x-collapse x-cloak class="submenu-container">
                        <a href="{{ route('user.profile') }}"
                            @click="sidebarOpen = false"
                            @if($isDashboardRoute) @click.prevent="activeTab = 'profile'; window.history.pushState({}, '', '{{ route('user.profile') }}')" @endif
                            class="submenu-item" :class="activeTab === 'profile' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'profile' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">User
                                Profile</span>
                        </a>
                        <a href="{{ route('user.business-profile') }}"
                            @click="sidebarOpen = false"
                            @if($isDashboardRoute) @click.prevent="activeTab = 'business'; window.history.pushState({}, '', '{{ route('user.business-profile') }}')" @endif
                            class="submenu-item" :class="activeTab === 'business' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'business' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">Business
                                Profile</span>
                        </a>
                        <a href="{{ route('user.bank-details') }}"
                            @click="sidebarOpen = false"
                            @if($isDashboardRoute) @click.prevent="activeTab = 'bank'; window.history.pushState({}, '', '{{ route('user.bank-details') }}')" @endif
                            class="submenu-item" :class="activeTab === 'bank' ? 'active group' : 'group'">
                            <div class="submenu-dot"></div>
                            <span class="text-[14px] group-hover:text-[#ff6a00]"
                                :class="activeTab === 'bank' ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500'">Bank
                                Details</span>
                        </a>

                    </div>
                </div>

                <div>
                    <button @click="openSub = (openSub === 'products' ? '' : 'products')"
                        :class="openSub === 'products' ? 'sidebar-item-card active' : 'sidebar-item-card'"
                        class="w-full flex items-center justify-between px-4 py-3.5 group">
                        <div class="flex items-center gap-3">
                            <svg style="width: 22px; height: 22px;" class="transition-colors flex-shrink-0"
                                :class="openSub === 'products' ? 'text-[#ff6a00]' : 'text-slate-400 group-hover:text-[#ff6a00]'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                            </svg>
                            <span class="text-[15px] font-semibold text-slate-700"
                                :class="openSub === 'products' && 'text-slate-900'">Manage Product</span>
                        </div>
                        <svg :class="openSub === 'products' ? 'rotate-0 text-[#ff6a00]' : 'rotate-180 text-slate-400'"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                    <div x-show="openSub === 'products'" x-collapse x-cloak class="submenu-container">
                        @foreach(['Add New Product', 'My Product', 'My Catalog', 'Message on Catalog'] as $sub)
                            <a href="{{ $sub === 'Add New Product' ? route('user.products.create') : ($sub === 'My Product' ? route('user.products.index') : ($sub === 'My Catalog' ? route('user.products.catalog') : ($sub === 'Message on Catalog' ? route('user.inquiries') : '#'))) }}"
                                @click="sidebarOpen = false"
                                class="submenu-item group {{ (request()->routeIs('user.products.create') && $sub === 'Add New Product') || (request()->routeIs('user.products.index') && $sub === 'My Product') || (request()->routeIs('user.products.catalog') && $sub === 'My Catalog') ? 'active' : '' }}">
                                <div class="submenu-dot"></div>
                                <span
                                    class="text-[14px] {{ (request()->routeIs('user.products.create') && $sub === 'Add New Product') || (request()->routeIs('user.products.index') && $sub === 'My Product') || (request()->routeIs('user.products.catalog') && $sub === 'My Catalog') ? 'font-bold text-[#ff6a00]' : 'font-medium text-slate-500' }} group-hover:text-[#ff6a00]">{{ $sub }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('user.profile') }}"
                    class="sidebar-item-card w-full flex items-center justify-between px-4 py-3.5 group cursor-pointer hover:bg-white transition-all">
                    <div class="flex items-center gap-3">
                        <svg style="width: 22px; height: 22px;"
                            class="text-slate-400 group-hover:text-[#ff6a00] flex-shrink-0 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        <span class="text-[15px] font-semibold text-slate-700">Settings</span>
                    </div>
                    <svg class="rotate-180 text-slate-400 w-4 h-4 group-hover:text-[#ff6a00]" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </a>

                <div class="pt-6 space-y-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full h-12 flex items-center justify-start gap-3 px-5 text-slate-500 hover:bg-slate-50 rounded-xl transition-all font-bold text-sm border border-transparent hover:border-slate-100 group">
                            <svg style="width: 22px; height: 22px;" class="flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Logout Session</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('user.account.destroy') }}" onsubmit="return confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full h-12 flex items-center justify-start gap-3 px-5 text-red-500 hover:bg-red-50 rounded-xl transition-all font-bold text-sm border border-transparent hover:border-red-100 group">
                            <svg style="width: 22px; height: 22px;" class="flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Delete Account</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Global Mobile Sidebar Trigger --}}
            <div @toggle-sidebar.window="sidebarOpen = true" class="hidden"></div>
        </aside>


