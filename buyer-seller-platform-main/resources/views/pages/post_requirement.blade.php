@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:py-12 lg:px-8 border-t border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="side-by-side-grid lg:gap-20 items-start">

                <!-- Left Side: Informative steps -->
                <div class="mb-12 lg:mb-0 lg:pr-8">
                    <div
                        class="inline-block px-4 py-1 border border-[#0b2a4a]/30 text-[#0b2a4a] rounded text-xs font-semibold uppercase tracking-widest mb-6">
                        Post Bulk Requirement
                    </div>
                    <h1 class="text-3xl font-bold text-[#0b2a4a] tracking-tight sm:text-4xl leading-tight">
                        Post Your Buying Requirement
                    </h1>
                    <p class="mt-4 text-base text-slate-500 max-w-lg leading-relaxed">
                        Submit your requirement once and get multiple quotes from verified suppliers. Free and easy.
                    </p>

                    <div class="mt-12 space-y-12 relative">
                        <!-- Vertical Line connecting steps -->
                        <div class="absolute left-[23px] top-4 bottom-4 w-px bg-slate-200 hidden sm:block"></div>

                        <!-- Step 1 -->
                        <div class="relative flex items-start group">
                            <div
                                class="flex-shrink-0 w-9 h-9 flex items-center justify-center bg-[#0b2a4a] text-white rounded-full font-semibold text-base z-10">
                                1
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-[#0b2a4a]">Describe your need</h3>
                                <p class="mt-2 text-slate-500 text-[15px] leading-relaxed">
                                    Fill out the quick form with details like product name, specifications, and volume.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative flex items-start group">
                            <div
                                class="flex-shrink-0 w-9 h-9 flex items-center justify-center bg-white text-[#0b2a4a] border-2 border-slate-200 rounded-full font-semibold text-base z-10 group-hover:bg-[#0b2a4a] group-hover:text-white transition-all duration-300">
                                2
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-[#0b2a4a]">Expert Verification</h3>
                                <p class="mt-2 text-slate-500 text-[15px] leading-relaxed">
                                    Our team validates your request to ensure only genuine interest reaches the suppliers.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative flex items-start group">
                            <div
                                class="flex-shrink-0 w-9 h-9 flex items-center justify-center bg-white text-[#0b2a4a] border-2 border-slate-200 rounded-full font-semibold text-base z-10 group-hover:bg-[#0b2a4a] group-hover:text-white transition-all duration-300">
                                3
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-[#0b2a4a]">Compare Offers</h3>
                                <p class="mt-2 text-slate-500 text-[15px] leading-relaxed">
                                    Verified sellers will reach out with competitive quotes for your requirement.
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative flex items-start group">
                            <div
                                class="flex-shrink-0 w-9 h-9 flex items-center justify-center bg-white text-[#0b2a4a] border-2 border-slate-200 rounded-full font-semibold text-base z-10 group-hover:bg-[#0b2a4a] group-hover:text-white transition-all duration-300">
                                4
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-[#0b2a4a]">Transact Safely</h3>
                                <p class="mt-2 text-slate-500 text-[15px] leading-relaxed">
                                    Pick the best quote, communicate securely, and finalize your purchase with confidence.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badge -->
                    <div class="mt-12 p-5 bg-[#0b2a4a] rounded-xl flex items-center gap-5">
                        <div class="w-11 h-11 bg-white/10 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-white">Trusted by 50,000+ Buyers</div>
                            <div class="text-sm text-slate-300 mt-0.5">Verified suppliers across India and worldwide.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Interaction Form -->
                <div class="relative bg-white border border-slate-100 rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] p-6 sm:p-10 lg:sticky lg:top-12"
                    x-data="{ 
                            recordingStatus: 'idle', 
                            financing: 'no', 
                            seconds: 0,
                            timer: null,
                            startRecording() {
                                this.recordingStatus = 'recording';
                                this.seconds = 0;
                                this.timer = setInterval(() => { this.seconds++ }, 1000);
                            },
                            stopRecording() {
                                clearInterval(this.timer);
                                this.recordingStatus = 'saved';
                            },
                            deleteRecording() {
                                this.recordingStatus = 'idle';
                                this.seconds = 0;
                            },
                            formatTime(s) {
                                return (s < 10 ? '0' + s : s);
                            }
                        }">

                    <!-- Branding Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-[#0b2a4a]">Send Your Requirement</h2>
                        <p class="text-slate-400 text-sm mt-1">Fill the form and verified suppliers will contact you.</p>
                    </div>

                    <!-- Form Progress -->
                    <div class="mb-8 px-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">
                            <span>Your Request Details</span>
                            
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#0b2a4a] rounded-full"></div>
                        </div>
                    </div>

                    <form action="{{ route('post-requirement.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Explain what product or service do you need? <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="product_name" required
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:bg-white focus:border-[#0b2a4a] focus:ring-1 focus:ring-[#0b2a4a] outline-none transition-all text-base placeholder:text-slate-400"
                                    placeholder="e.g. Industrial Pumps, Cotton Fabric...">
                                <div
                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#ff6a00] transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Personalization Block -->
                        <div class="bg-[#fcfdfe] rounded-[2rem] p-6 border-2 border-dashed border-slate-100">
                            <label class="block text-sm font-bold text-[#0b2a4a] mb-1 ml-1 text-center sm:text-left">
                                Personalize your inquiry
                            </label>
                            <p class="text-[13px] text-slate-400 mb-6 font-medium text-center sm:text-left">
                                Record a voice message to explain your requirements better.
                            </p>

                            <div
                                class="bg-white border border-slate-100 rounded-2xl p-5 shadow-[0_8px_20px_-8px_rgba(0,0,0,0.05)] overflow-hidden min-h-[120px] flex items-center justify-center">

                                <!-- Idle State -->
                                <div x-show="recordingStatus === 'idle'"
                                    class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 group cursor-pointer"
                                    @click="startRecording()">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0 5 5 0 01-10 0 1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="text-center sm:text-left">
                                            <div class="font-bold text-[#0b2a4a]">Record Voice</div>
                                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Tap
                                                to start</div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-end gap-1 h-8 opacity-20 group-hover:opacity-100 transition-opacity">
                                        @for ($i = 0; $i < 12; $i++)
                                            <div class="w-1 bg-slate-200 rounded-full" style="height: {{ rand(20, 80) }}%">
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Recording State -->
                                <div x-show="recordingStatus === 'recording'"
                                    class="w-full flex items-center justify-between animate-in fade-in zoom-in duration-300"
                                    x-cloak>
                                    <div class="flex flex-col gap-1">
                                        <div
                                            class="flex items-center gap-2 text-red-600 font-black text-xs uppercase tracking-widest animate-pulse">
                                            <span
                                                class="w-2.5 h-2.5 bg-red-600 rounded-full shadow-[0_0_8px_rgba(220,38,38,0.5)]"></span>
                                            LIVE RECORDING
                                        </div>
                                        <div class="text-2xl font-black text-[#0b2a4a]"
                                            x-text="'00 : ' + formatTime(seconds)">00 : 00</div>
                                    </div>
                                    <button type="button" @click="stopRecording()"
                                        class="bg-[#0b2a4a] text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all outline-none">
                                        Save Record
                                    </button>
                                </div>

                                <!-- Saved State -->
                                <div x-show="recordingStatus === 'saved'"
                                    class="w-full flex items-center justify-between animate-in slide-in-from-bottom duration-300"
                                    x-cloak>
                                    <div class="flex items-center gap-5">
                                        <div
                                            class="w-14 h-14 rounded-full bg-gradient-to-br from-[#ff6a00] to-[#ff9e5e] flex items-center justify-center text-white shadow-xl cursor-pointer hover:scale-105 active:scale-95 transition-all">
                                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M4.5 3a.5.5 0 00-.5.5v13a.5.5 0 00.81.39l10-6.5a.5.5 0 000-.78l-10-6.5a.5.5 0 00-.31-.11z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#0b2a4a] text-lg">Recorded</div>
                                            <div class="text-xs text-slate-400 font-bold"
                                                x-text="'00:00 / 00:' + formatTime(seconds)">00:00 / 00:00</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="deleteRecording()"
                                        class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Financing Section -->
                        <div class="space-y-8 px-1">
                            <div>
                                <label class="block text-sm font-bold text-[#0b2a4a] mb-5">
                                    Would you like a loan to finance this order?
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label
                                        :class="financing === 'yes' ? 'border-[#ff6a00] bg-[#ff6a00]/5' : 'border-slate-100 bg-slate-50'"
                                        class="flex items-center justify-center py-4 px-6 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-100/50">
                                        <input type="radio" name="financing" value="yes" x-model="financing" class="hidden">
                                        <span :class="financing === 'yes' ? 'text-[#ff6a00]' : 'text-slate-500'"
                                            class="font-bold">Yes</span>
                                    </label>
                                    <label
                                        :class="financing === 'no' ? 'border-[#ff6a00] bg-[#ff6a00]/5' : 'border-slate-100 bg-slate-50'"
                                        class="flex items-center justify-center py-4 px-6 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-100/50">
                                        <input type="radio" name="financing" value="no" x-model="financing" class="hidden">
                                        <span :class="financing === 'no' ? 'text-[#ff6a00]' : 'text-slate-500'"
                                            class="font-bold">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Conditional GST Field -->
                            <div x-show="financing === 'yes'" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="pt-2">
                                <label class="block text-sm font-bold text-[#0b2a4a] mb-5">
                                    Do you have GST Number?
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label
                                        class="relative flex items-center justify-center py-4 px-6 border-2 border-slate-100 bg-slate-50 rounded-2xl cursor-pointer group hover:bg-slate-100/50">
                                        <input type="radio" name="gst_status" value="yes"
                                            class="w-5 h-5 text-[#ff6a00] border-slate-300 focus:ring-[#ff6a00]">
                                        <span class="ml-3 font-bold text-slate-500 group-hover:text-[#0b2a4a]">Yes</span>
                                    </label>
                                    <label
                                        class="relative flex items-center justify-center py-4 px-6 border-2 border-slate-100 bg-slate-50 rounded-2xl cursor-pointer group hover:bg-slate-100/50">
                                        <input type="radio" name="gst_status" value="no"
                                            class="w-5 h-5 text-[#ff6a00] border-slate-300 focus:ring-[#ff6a00]">
                                        <span class="ml-3 font-bold text-slate-500 group-hover:text-[#0b2a4a]">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#0b2a4a] hover:bg-[#07192d] text-white font-black py-6 px-6 rounded-3xl transition-all shadow-[0_20px_40px_-10px_rgba(11,42,74,0.3)] hover:shadow-[0_25px_50px_-12px_rgba(11,42,74,0.4)] active:scale-[0.98] flex items-center justify-center group tracking-wider text-lg">
                            Submit Requirement
                            <svg class="w-6 h-6 ml-3 group-hover:translate-x-1.5 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>

                    <p class="mt-10 text-center text-xs text-slate-400 font-bold tracking-tight px-4 leading-relaxed">
                        SECURE 256-BIT ENCRYPTION. BY CONTINUING, YOU AGREE TO OUR<br>
                        <a href="#" class="text-[#0b2a4a] hover:text-[#ff6a00] transition-colors">TERMS OF SERVICE</a> &
                        <a href="{{ route('privacy-policy') }}"
                            class="text-[#0b2a4a] hover:text-[#ff6a00] transition-colors">PRIVACY POLICY</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Force Side-by-Side Layout on Desktop */
        @media (min-width: 1024px) {
            .side-by-side-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        /* Ensure stacking on small screens */
        @media (max-width: 1023px) {
            .side-by-side-grid {
                display: block !important;
            }
        }

        @keyframes wave {
            0% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(1.5);
            }

            100% {
                transform: scaleY(1);
            }
        }

        .animate-in {
            animation-delay: 0.1s;
        }
    </style>
@endsection