@extends('layouts.app')

@section('content')
  @php $country = session('country', 'IN'); @endphp

  <div class="min-h-[85vh] bg-[#f8fafc] flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div
      class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row border border-slate-100">

      {{-- Left: Branding Section --}}
      <div
        class="w-full md:w-5/12 bg-gradient-to-br from-[#0b2a4a] to-[#1a3f66] p-10 lg:p-14 text-white flex flex-col justify-center relative overflow-hidden">
        {{-- Background Grid Pattern --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
          <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
          </svg>
        </div>

        <div class="relative z-10 flex flex-col h-full justify-between">
          <div>
            <div class="mb-10">
              <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform"
                class="h-14 w-auto object-contain filter brightness-0 invert" />
            </div>

            <h1 class="text-3xl lg:text-[40px] font-extrabold leading-tight mb-5 text-white tracking-tight">
              Grow Your <br><span class="text-[#ff6a00]">B2B Business</span> Globally
            </h1>

            <p class="text-white/80 text-[15px] max-w-sm font-medium leading-relaxed mb-8">
              Connect with thousands of verified wholesale buyers and global suppliers in India's fastest growing B2B
              marketplace.
            </p>
          </div>

          {{-- Feature Badges --}}
          <div class="hidden md:flex flex-col gap-6 mt-16">
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center backdrop-blur-sm shadow-inner shrink-0">
                <svg class="w-6 h-6 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                  </path>
                </svg>
              </div>
              <div>
                <h4 class="text-[14px] font-bold text-white tracking-wide">100% Verified</h4>
                <p class="text-[12px] text-white/60 font-medium">Genuine Buyers & Sellers</p>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center backdrop-blur-sm shadow-inner shrink-0">
                <svg class="w-6 h-6 text-[#ff6a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                  </path>
                </svg>
              </div>
              <div>
                <h4 class="text-[14px] font-bold text-white tracking-wide">Fast Networking</h4>
                <p class="text-[12px] text-white/60 font-medium">Instant leads and interactions</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Orange Glow Effect --}}
        <div
          class="absolute -bottom-24 -right-24 w-80 h-80 bg-[#ff6a00] rounded-full blur-[120px] opacity-30 pointer-events-none">
        </div>
      </div>

      {{-- Right: Login Form --}}
      <div class="w-full md:w-7/12 p-8 sm:p-12 lg:p-16 flex items-center justify-center relative">

        {{-- Mobile Decor --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#0b2a4a]/5 rounded-bl-[100px] -z-10 md:hidden"></div>

        <div class="w-full max-w-sm">
          <div class="mb-10">
            <h2 class="text-3xl font-extrabold text-[#1e293b] tracking-tight">Welcome Back</h2>
            <p class="text-[14px] text-slate-500 font-medium mt-2">
              @if($country === 'IN')
                Sign in with your registered mobile number
              @else
                Sign in with your registered email
              @endif
            </p>
          </div>

          @if($errors->has('login'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[13px] font-medium text-red-700">
              {{ $errors->first('login') }}
            </div>
          @endif

          <form method="POST" action="{{ route('login.requestOtp') }}" class="space-y-6">
            @csrf

            @if($country === 'IN')
              <div class="relative group">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Mobile
                  Number<span class="text-red-500 ml-0.5">*</span></label>
                <div class="relative flex items-center">
                  <span
                    class="absolute left-4 font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded select-none border border-slate-200">+91</span>
                  <input name="phone" type="tel" maxlength="10"
                    class="w-full pl-[4.5rem] pr-4 py-3.5 bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-xl text-[15px] font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#ff6a00]/10 focus:border-[#ff6a00] transition-all"
                    placeholder="Enter 10 digit number" required autofocus>
                </div>
                @error('phone')
                  <div class="text-red-500 text-[12px] mt-2 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                  </div>
                @enderror
              </div>
            @else
              <div class="relative group">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Email
                  Address<span class="text-red-500 ml-0.5">*</span></label>
                <div class="relative flex items-center">
                  <span class="absolute left-4 text-slate-400 bg-slate-100 p-1.5 rounded border border-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                      </path>
                    </svg>
                  </span>
                  <input name="email" type="email"
                    class="w-full pl-14 pr-4 py-3.5 bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-xl text-[15px] font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#ff6a00]/10 focus:border-[#ff6a00] transition-all"
                    placeholder="yourname@company.com" required autofocus>
                </div>
                @error('email')
                  <div class="text-red-500 text-[12px] mt-2 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                  </div>
                @enderror
              </div>
            @endif

            <div class="pt-4">
              <button type="submit"
                class="relative overflow-hidden w-full h-12 bg-gradient-to-r from-[#ff6a00] to-[#ea580c] hover:from-[#ea580c] hover:to-[#c2410c] text-white rounded-xl font-bold text-[15px] tracking-wide shadow-lg shadow-orange-500/25 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                <span>Request OTP</span>
                <svg class="w-5 h-5 opacity-80 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                  </path>
                </svg>
              </button>
            </div>
          </form>

          <div class="my-6 flex items-center gap-4 text-[11px] font-bold uppercase tracking-[0.28em] text-slate-300">
            <span class="h-px flex-1 bg-slate-200"></span>
            <span>Or continue with</span>
            <span class="h-px flex-1 bg-slate-200"></span>
          </div>

          <a href="{{ route('login.google.redirect') }}"
            class="flex h-12 w-full items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white text-[15px] font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="#EA4335"
                d="M12 10.2v3.9h5.4c-.24 1.25-.96 2.3-2.04 3.01l3.3 2.56c1.92-1.77 3.03-4.38 3.03-7.49 0-.71-.06-1.4-.18-2.06H12z" />
              <path fill="#34A853"
                d="M12 22c2.7 0 4.96-.89 6.61-2.41l-3.3-2.56c-.91.61-2.08.97-3.31.97-2.55 0-4.72-1.72-5.5-4.03H3.09v2.63A9.98 9.98 0 0 0 12 22z" />
              <path fill="#4A90E2"
                d="M6.5 13.97A6 6 0 0 1 6.19 12c0-.68.12-1.34.31-1.97V7.4H3.09A9.98 9.98 0 0 0 2 12c0 1.61.39 3.13 1.09 4.4l3.41-2.43z" />
              <path fill="#FBBC05"
                d="M12 5.98c1.47 0 2.79.51 3.83 1.5l2.87-2.87C16.95 2.98 14.7 2 12 2A9.98 9.98 0 0 0 3.09 7.4l3.41 2.63c.78-2.31 2.95-4.05 5.5-4.05z" />
            </svg>
            <span>Continue with Google</span>
          </a>


         

        </div>
      </div>
    </div>
  </div>

@endsection
