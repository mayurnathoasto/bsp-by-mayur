@extends('layouts.app')

@section('content')
  <div class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans">

    {{-- Left Side: Branding & Value Proposition --}}
    <div
      class="hidden md:flex md:w-5/12 lg:w-[45%] bg-gradient-to-br from-[#0a2540] via-[#113054] to-[#1f4a7c] text-white p-12 flex-col relative overflow-hidden">
      {{-- Decorative Elements --}}
      <div
        class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3">
      </div>
      <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#ff6a00]/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4">
      </div>

      <div class="relative z-10 flex flex-col h-full">
        {{-- Logo Area --}}
        <div class="mb-16">
          <a href="{{ url('/') }}" class="inline-flex items-center">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="h-12 w-auto object-contain filter brightness-0 invert" />
          </a>
        </div>

        {{-- Main Copy --}}
        <div class="mt-auto mb-20 space-y-6">
          <h1 class="text-4xl lg:text-5xl font-black tracking-tight leading-[1.1]">
            Verify<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-[#ff6a00]">Your
              Identity.</span>
          </h1>
          <p class="text-lg text-slate-300 font-medium leading-relaxed max-w-sm">
            Enter the code sent to your device to access your dashboard and manage operations.
          </p>
        </div>

        {{-- Trust Badges --}}
        <div class="flex items-center gap-6 text-sm font-semibold text-slate-400">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
              </path>
            </svg>
            Secure Login
          </div>
          <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
              </path>
            </svg>
            Verified Platform
          </div>
        </div>
      </div>
    </div>

    {{-- Right Side: OTP Form --}}
    <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 py-12 relative">
      <div class="absolute inset-0 bg-slate-50"></div>

      <div class="w-full max-w-md mx-auto relative z-10">
        {{-- Mobile Logo --}}
        <div class="md:hidden mb-8 text-center flex justify-center">
          <a href="{{ url('/') }}">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Buyer Seller Platform" class="h-10 w-auto object-contain" />
          </a>
        </div>

        {{-- Form Card --}}
        <div
          class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10">
          <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Enter OTP</h2>
            <p class="text-slate-500 font-medium mt-2 flex items-center gap-2">
              We sent a code to <span
                class="text-slate-800 font-bold px-2 py-0.5 bg-slate-100 rounded-md">{{ $identifier }}</span>
            </p>
          </div>

          @if(!empty($devOtp))
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
              <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <p class="text-sm font-semibold text-amber-800">Development OTP</p>
                <p class="text-sm text-amber-900 mt-1 font-mono font-bold tracking-wider text-lg">{{ $devOtp }}</p>
              </div>
            </div>
          @endif

          <form method="POST" action="{{ route('login.verifyOtp') }}" class="space-y-6">
            @csrf

            {{-- OTP Input --}}
            <div>
              <div class="relative group">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">6-Digit
                  OTP<span class="text-red-500 ml-0.5">*</span></label>
                <div class="relative flex items-center">
                  <span
                    class="absolute left-4 text-slate-400 bg-slate-100 p-1.5 rounded border border-slate-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </span>

                  <input name="otp" type="text" maxlength="6" required autofocus autocomplete="one-time-code"
                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-xl text-[15px] font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#ff6a00]/10 focus:border-[#ff6a00] transition-all tracking-widest"
                    placeholder="Enter 6-digit OTP">
                </div>
              </div>
              @error('otp')
                <div class="mt-2 text-[12px] text-red-500 font-medium flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ $message }}
                </div>
              @enderror
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
              <button type="submit"
                class="relative overflow-hidden w-full h-12 bg-gradient-to-r from-[#ff6a00] to-[#ea580c] hover:from-[#ea580c] hover:to-[#c2410c] text-white rounded-xl font-bold text-[15px] tracking-wide shadow-lg shadow-orange-500/25 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                <span>Verify & Continue</span>
                <svg class="w-5 h-5 opacity-80 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                  </path>
                </svg>
              </button>
            </div>

            {{-- Change Login Link --}}
            <div class="text-center mt-6">
              <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#0a2540] transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                  </path>
                </svg>
                Change login method
              </a>
            </div>
          </form>
        </div>

        {{-- Footer Text --}}
        <div class="mt-8 text-center sm:hidden">
          <p class="text-xs text-slate-500 font-medium">Securely verified by Buyer Seller Platform</p>
        </div>
      </div>
    </div>
  </div>
@endsection