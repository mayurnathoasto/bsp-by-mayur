@extends('layouts.app')

@section('content')
<div class="bg-gray-50 font-sans">
    <section class="relative overflow-hidden bg-slate-900 border-b border-slate-800 py-20 sm:py-32">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0b2a4a] to-slate-900/90"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-amber-400 font-semibold tracking-wider uppercase text-sm mb-4 block">Discover Our Journey</span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                Empowering Global <br class="hidden sm:block" /> B2B Trade
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
                We are a premier international marketplace connecting verified suppliers with global buyers. Our platform makes cross-border trade seamless, secure, and highly efficient.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-10 shadow-xl border border-gray-100 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full transition-transform duration-500 group-hover:scale-125 z-0"></div>
                <div class="relative z-10 text-[#0b2a4a] mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="relative z-10 text-2xl font-bold text-slate-900 mb-4">Our Mission</h3>
                <p class="relative z-10 text-slate-600 leading-relaxed text-[15px]">
                    To build a trust-driven, comprehensive B2B ecosystem that removes international trade barriers, giving businesses of all sizes the resources they need to expand globally with confidence and ease.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-10 shadow-xl border border-gray-100 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-full transition-transform duration-500 group-hover:scale-125 z-0"></div>
                <div class="relative z-10 text-amber-500 mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="relative z-10 text-2xl font-bold text-slate-900 mb-4">Our Vision</h3>
                <p class="relative z-10 text-slate-600 leading-relaxed text-[15px]">
                    To be the world's most reliable digital stage for manufacturers and wholesalers to showcase their products, fostering a community of sustainable commerce and enduring partnerships.
                </p>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
                <div class="p-4">
                    <div class="text-4xl font-extrabold text-[#0b2a4a] mb-2">150+</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Countries</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-extrabold text-[#0b2a4a] mb-2">50k+</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Active Buyers</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-extrabold text-[#0b2a4a] mb-2">20k+</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Verified Sellers</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-extrabold text-[#0b2a4a] mb-2">2M+</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Products Listed</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Why Choose Buyer Seller Platform?</h2>
                <p class="text-slate-600 text-[15px] leading-relaxed">
                    We provide top-notch features to enhance your trading experience. Whether you are sourcing for your next project or looking to expand your sales pipeline, we have you covered.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-[#0b2a4a] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Verified Suppliers</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Every supplier goes through a rigorous verification process to ensure authenticity and quality for buyers.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Secure Transactions</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Our platform promotes secure engagement and provides transparency throughout the whole trade cycle.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Seamless Communication</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Instant messaging and inquiry management systems that allow buyers and sellers to communicate effectively.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[#0b2a4a] opacity-80 mix-blend-multiply"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to scale your business globally?</h2>
            <p class="text-slate-300 mb-8 max-w-2xl mx-auto text-lg">
                Join thousands of businesses that are already sourcing and selling via Buyer Seller Platform.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold rounded-lg shadow-lg hover:shadow-xl transition-all w-full sm:w-auto">
                    Join as a Buyer
                </a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-transparent border-2 border-white text-white hover:bg-white hover:text-slate-900 font-bold rounded-lg transition-all w-full sm:w-auto">
                    Become a Supplier
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
