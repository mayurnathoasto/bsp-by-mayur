@extends('layouts.app')

@section('content')
    {{-- Import Public Sans Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .sidebar-item-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            margin-bottom: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-item-card.active {
            background: #fff9f5;
            border-color: #ff6a00;
            box-shadow: 0 4px 6px -1px rgba(255, 106, 0, 0.1);
        }

        .submenu-container {
            position: relative;
            margin-left: 18px;
            padding-left: 14px;
            border-left: 1.5px dashed #fed7aa;
            margin-bottom: 12px;
            margin-top: -4px;
        }

        .submenu-item {
            position: relative;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .submenu-dot {
            position: absolute;
            left: -19.5px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            background: #e2e8f0;
            border: 2px solid white;
            border-radius: 50%;
            z-index: 10;
        }

        .submenu-item.active .submenu-dot {
            background-color: #ff6a00;
        }

        /* Profile Specific Styles */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .section-header {
            background: #8fa1b2;
            /* Grayish blue from reference */
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-input-container {
            position: relative;
        }

        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            padding-top: 24px;
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
        }

        .form-label {
            position: absolute;
            top: 6px;
            left: 12px;
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
        }

        .edit-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6a00;
            cursor: pointer;
        }

        .completeness-circle {
            position: relative;
            width: 64px;
            height: 64px;
        }
    </style>

    <div x-data="{ sidebarOpen: false, openSub: 'profile' }" class="flex h-screen overflow-hidden bg-[#f8fafc]">

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 w-[280px] bg-white border-r border-slate-200 transition-transform lg:static lg:translate-x-0 flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#ff6a00] rounded-lg flex items-center justify-center font-black text-white">B
                    </div>
                    <span class="font-bold text-sm uppercase text-slate-800 tracking-wider">Seller Panel</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-8 space-y-2 bg-[#fcfdfe]">
                {{-- Dashboard Group --}}
                <div>
                    <button @click="openSub = (openSub === 'dashboard' ? '' : 'dashboard')"
                        :class="openSub === 'dashboard' ? 'sidebar-item-card active' : 'sidebar-item-card'"
                        class="w-full flex items-center justify-between px-4 py-3.5 group">
                        <div class="flex items-center gap-3">
                            <svg style="width: 22px; height: 22px;" class="text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span class="text-[15px] font-semibold text-slate-700">Dashboard</span>
                        </div>
                    </button>
                </div>

                {{-- Profile Group --}}
                <div>
                    <button @click="openSub = (openSub === 'profile' ? '' : 'profile')"
                        :class="openSub === 'profile' ? 'sidebar-item-card active' : 'sidebar-item-card'"
                        class="w-full flex items-center justify-between px-4 py-3.5 group">
                        <div class="flex items-center gap-3">
                            <svg style="width: 22px; height: 22px;" class="text-[#ff6a00]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-[15px] font-semibold text-slate-900">Profile</span>
                        </div>
                        <svg class="w-4 h-4 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                    <div x-show="openSub === 'profile'" x-collapse class="submenu-container">
                        <a href="{{ route('user.profile') }}"
                            class="submenu-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <div class="submenu-dot"></div>
                            <span
                                class="text-[14px] {{ request()->routeIs('user.profile') ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500' }}">User
                                Profile</span>
                        </a>
                        <a href="{{ route('user.business-profile') }}"
                            class="submenu-item {{ request()->routeIs('user.business-profile') ? 'active' : '' }}">
                            <div class="submenu-dot"></div>
                            <span
                                class="text-[14px] {{ request()->routeIs('user.business-profile') ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500' }}">Business
                                Profile</span>
                        </a>
                        <a href="{{ route('user.bank-details') }}"
                            class="submenu-item {{ request()->routeIs('user.bank-details') ? 'active' : '' }}">
                            <div class="submenu-dot"></div>
                            <span
                                class="text-[14px] {{ request()->routeIs('user.bank-details') ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500' }}">Bank
                                Details</span>
                        </a>
                        <a href="{{ route('user.manage-users') }}"
                            class="submenu-item {{ request()->routeIs('user.manage-users') ? 'active' : '' }}">
                            <div class="submenu-dot"></div>
                            <span
                                class="text-[14px] {{ request()->routeIs('user.manage-users') ? 'font-semibold text-[#ff6a00]' : 'font-medium text-slate-500' }}">Manage
                                Users</span>
                        </a>
                        <a href="#" class="submenu-item">
                            <div class="submenu-dot"></div><span
                                class="text-[14px] font-medium text-slate-500">Reward</span>
                        </a>
                    </div>
                </div>

                {{-- Manage Product --}}
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
                            <a href="{{ $sub === 'Add New Product' ? route('user.products.create') : ($sub === 'My Product' ? route('user.products.index') : ($sub === 'My Catalog' ? route('user.products.catalog') : '#')) }}"
                                class="submenu-item group {{ (request()->routeIs('user.products.create') && $sub === 'Add New Product') || (request()->routeIs('user.products.index') && $sub === 'My Product') || (request()->routeIs('user.products.catalog') && $sub === 'My Catalog') ? 'active' : '' }}">
                                <div class="submenu-dot"></div>
                                <span
                                    class="text-[14px] {{ (request()->routeIs('user.products.create') && $sub === 'Add New Product') || (request()->routeIs('user.products.index') && $sub === 'My Product') || (request()->routeIs('user.products.catalog') && $sub === 'My Catalog') ? 'font-bold text-[#ff6a00]' : 'font-medium text-slate-500' }} group-hover:text-[#ff6a00] transition-colors">{{ $sub }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Settings --}}
                <div
                    class="sidebar-item-card w-full flex items-center justify-between px-4 py-3.5 group cursor-pointer hover:bg-white transition-all">
                    <div class="flex items-center gap-3">
                        <svg style="width: 22px; height: 22px;" class="text-slate-400 group-hover:text-[#ff6a00]"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        <span class="text-[15px] font-semibold text-slate-700">Settings</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content Column --}}
        <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc] overflow-y-auto">
            {{-- Content Area --}}
            <div class="p-8">
                <div class="max-w-[1400px] mx-auto space-y-4">
                    {{-- Breadcrumbs --}}
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="hover:text-slate-600 cursor-pointer">Profile</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-slate-800">User Profile</span>
                    </div>

                    <div class="flex items-start justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-800">Profile Details</h2>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        {{-- Main Details Form (Left) --}}
                        <div class="lg:col-span-8 space-y-6">
                            <div class="profile-card p-10 bg-white">
                                {{-- Profile Header info --}}
                                <div class="flex items-center gap-6 mb-10 pb-8 border-b border-slate-50">
                                    <div class="relative">
                                        <div
                                            class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-3xl font-bold text-slate-600 border-2 border-slate-50">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div
                                            class="absolute bottom-0 right-0 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-[#ff6a00]/60 cursor-pointer shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-2xl font-extrabold text-[#0b2a4a] tracking-tight leading-none">
                                            {{ $user->name }}
                                        </h3>
                                        <p
                                            class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $user->city_name ?? 'Location Not Set' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Fields Grid --}}
                                <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-8">
                                    @csrf
                                    <div class="section-header mb-6">Personal Details</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                        {{-- New Login Number Field --}}
                                        <div class="form-input-container md:col-span-2 lg:col-span-1">
                                            <label class="form-label">Login Number (ID)</label>
                                            <input type="text"
                                                value="{{ $user->phone ? 'P:' . $user->phone : ($user->email ? 'E:' . $user->email : '') }}"
                                                class="form-input bg-slate-50 text-slate-500 font-bold" readonly>
                                        </div>
                                        <div class="hidden lg:block"></div> {{-- Spacer to keep it on its own line if
                                        desired, or remove to flow --}}

                                        <div class="form-input-container">
                                            <label class="form-label">First Name<span class="text-red-500">*</span></label>
                                            <input type="text" name="name" value="{{ explode(' ', $user->name)[0] }}"
                                                class="form-input bg-slate-50/50">
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Last Name<span class="text-red-500">*</span></label>
                                            <input type="text"
                                                value="{{ count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name)[1] : '' }}"
                                                class="form-input bg-slate-50/50">
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Email<span class="text-red-500">*</span></label>
                                            <input type="email" value="{{ $user->email }}" class="form-input bg-white"
                                                readonly>
                                            <div class="edit-icon">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Alternate Email</label>
                                            <input type="email" class="form-input bg-white" placeholder="Alternate Email">
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Office Contact<span
                                                    class="text-red-500">*</span></label>
                                            <div class="flex">
                                                <div
                                                    class="w-16 border border-r-0 border-slate-300 rounded-l-lg flex items-center justify-center bg-slate-50 text-sm font-bold text-slate-500">
                                                    +91</div>
                                                <input type="text" value="{{ $user->phone }}"
                                                    class="form-input rounded-l-none border-l-0" readonly>
                                            </div>
                                            <div class="edit-icon">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Alternate Mobile No.</label>
                                            <select class="form-input bg-white appearance-none pr-10">
                                                <option>Alternate Mobile No.</option>
                                            </select>
                                            <div class="absolute right-3 top-[65%] -translate-y-1/2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Whatsapp Number</label>
                                            <div class="flex items-center gap-2 absolute left-3 top-[65%] -translate-y-1/2">
                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.482 5.236 3.483 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.656zm6.304-3.693c1.551.92 3.511 1.408 5.539 1.409 5.457 0 9.897-4.438 9.9-9.895 0-2.645-1.03-5.131-2.902-6.999-1.872-1.868-4.358-2.898-7.004-2.899-5.456 0-9.897 4.438-9.9 9.895-.001 2.124.549 4.191 1.595 5.997l-1.059 3.866 3.963-1.041z" />
                                                </svg>
                                                <span class="text-sm font-bold text-slate-500">+91</span>
                                            </div>
                                            <input type="text" class="form-input pl-14" placeholder="">
                                            <div class="edit-icon">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="form-input-container">
                                            <label class="form-label">Designation / Job Title<span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" class="form-input bg-white"
                                                placeholder="Designation / Job Title">
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-8">
                                        <button type="submit"
                                            class="bg-[#3b7ea1] hover:bg-[#2b607c] text-white px-10 py-3 rounded-lg font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-500/10 active:scale-95 transition-all">
                                            SAVE DETAILS
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Right Sidebar Widgets --}}
                        <div class="lg:col-span-4 space-y-6">
                            {{-- Completeness widget --}}
                            <div
                                class="bg-[#f97316] rounded-xl p-6 text-white overflow-hidden relative group cursor-pointer hover:shadow-lg transition-all shadow-orange-500/20 shadow-md flex items-center gap-6 h-[120px]">
                                <div class="flex-shrink-0 relative">
                                    <svg class="w-20 h-20 transform -rotate-90">
                                        <circle cx="40" cy="40" r="32" stroke="rgba(255,255,255,0.2)" stroke-width="6"
                                            fill="none" />
                                        <circle cx="40" cy="40" r="32" stroke="white" stroke-width="6" fill="none"
                                            stroke-dasharray="200" stroke-dashoffset="80" stroke-linecap="round" />
                                    </svg>
                                    <span
                                        class="absolute inset-0 flex items-center justify-center font-bold text-lg">60%</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg leading-tight mb-1">My Profile Completeness</h4>
                                    <p class="text-[11px] font-medium opacity-90 leading-tight">Please add your Designation
                                        / Job Title</p>
                                </div>
                            </div>




                        </div>

                    </div>


                    {{-- Footer Text --}}
                    <div class="pt-10 pb-6 text-center">
                        <p class="text-xs font-semibold text-slate-400">India's Largest B2B Marketplace, Connecting Buyers
                            and Sellers.</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tight">Privacy Policy - Terms
                            of Use © 1999-2026 Infocom Network Private Limited. All rights reserved.</p>
                    </div>
                </div>

            </div>
        </main>
        {{-- Mobile Sidebar Backdrop --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
            class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-[2px] lg:hidden transition-opacity"></div>
    </div>
@endsection
