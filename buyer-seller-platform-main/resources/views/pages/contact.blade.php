@extends('layouts.app')

@section('content')
    <div class="bg-gray-50">
        <section class="relative overflow-hidden bg-white border-b border-gray-200">
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">Contact Us</h1>
                <p class="mt-3 text-slate-600 text-[15px] max-w-2xl leading-relaxed">
                    Have questions, sales queries, or need support? Send us a message and we'll get back to you.
                </p>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-5 flex">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden w-full">
                        <div class="p-8 bg-slate-50 h-full">
                            <h2 class="text-xl font-bold text-slate-900">Get in touch</h2>
                            <p class="mt-2 text-[15px] text-slate-600 leading-relaxed">
                                We support buyers and suppliers across multiple countries. If you are facing any issue or want
                                to know more about listings, inquiries, or seller verification, contact us.
                            </p>

                            <div class="mt-6 space-y-4 text-sm">
                                <div class="flex gap-3">
                                    <div class="mt-0.5 w-9 h-9 rounded-full bg-[#0b2a4a] text-white flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.5 4.5a1 1 0 01-.502 1.21l-2.257 1.128a11.042 11.042 0 005.516 5.516l1.128-2.257a1 1 0 011.21-.502l4.5 1.5A1 1 0 0121 16.72V20a2 2 0 01-2 2h-1C9.163 22 2 14.837 2 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">Phone</div>
                                        <div class="text-slate-600">+91-9810090914</div>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <div class="mt-0.5 w-9 h-9 rounded-full bg-[#0b2a4a] text-white flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">Email</div>
                                        <div class="text-slate-600">support@example.com</div>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <div class="mt-0.5 w-9 h-9 shrink-0 rounded-full bg-[#0b2a4a] text-white flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-slate-900">Indian Corporate Office</div>
                                        <div class="text-slate-600 mt-1 leading-relaxed">
                                            C 48, Sector 33,<br>
                                            Noida 20301 (UP) INDIA
                                        </div>
                                        <div class="text-slate-600 mt-2 space-y-1">
                                            <div><span class="font-semibold text-slate-800">Phone:</span> 9810090914</div>
                                            <div>
                                                <span class="font-semibold text-slate-800">Email:</span>
                                                <a href="mailto:India1@buyersellerplatform.com" class="text-[#0b2a4a] hover:underline break-all">India1@buyersellerplatform.com</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 my-5"></div>

                                <div>
                                    <h3 class="text-[16px] font-bold text-slate-900 mb-5">International Offices</h3>

                                    <div class="space-y-6">
                                        <div class="flex gap-3">
                                            <div class="mt-1.5 w-2 h-2 shrink-0 rounded-full bg-amber-400"></div>
                                            <div class="flex-1">
                                                <div class="font-bold text-slate-900">USA</div>
                                                <div class="text-slate-600 mt-1 leading-relaxed">
                                                    5247, Ligurian CT,<br>
                                                    San Jose, California.<br>
                                                    <div class="mt-1">
                                                        <span class="font-medium text-slate-800">Email:</span>
                                                        <a href="mailto:usa1@buyersellerplatform.com" class="text-[#0b2a4a] hover:underline break-all">usa1@buyersellerplatform.com</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <div class="mt-1.5 w-2 h-2 shrink-0 rounded-full bg-amber-400"></div>
                                            <div class="flex-1">
                                                <div class="font-bold text-slate-900">UK</div>
                                                <div class="text-slate-600 mt-1 leading-relaxed">
                                                    7 Hafod, Emmer Green,<br>
                                                    Reading RG48LZ, England,<br>
                                                    United Kingdom<br>
                                                    <div class="mt-1">
                                                        <span class="font-medium text-slate-800">Email:</span>
                                                        <a href="mailto:uk1@buyersellerplatform.com" class="text-[#0b2a4a] hover:underline break-all">uk1@buyersellerplatform.com</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <div class="mt-1.5 w-2 h-2 shrink-0 rounded-full bg-amber-400"></div>
                                            <div class="flex-1">
                                                <div class="font-bold text-slate-900">Ireland</div>
                                                <div class="text-slate-600 mt-1 leading-relaxed">
                                                    19, Silken Park Avenue, Kingswood Cross,<br>
                                                    City West, Dublin D22CF72, Ireland.<br>
                                                    <div class="mt-1">
                                                        <span class="font-medium text-slate-800">Email:</span>
                                                        <a href="mailto:Ireland1@buyersellerplatform.com" class="text-[#0b2a4a] hover:underline break-all">Ireland1@buyersellerplatform.com</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <div class="mt-1.5 w-2 h-2 shrink-0 rounded-full bg-amber-400"></div>
                                            <div class="flex-1">
                                                <div class="font-bold text-slate-900">Australia</div>
                                                <div class="text-slate-600 mt-1 leading-relaxed">
                                                    6, Russell Street, Denistone East,<br>
                                                    NSW, Australia<br>
                                                    <div class="mt-1">
                                                        <span class="font-medium text-slate-800">Email:</span>
                                                        <a href="mailto:australia1@buyersellerplatform.com" class="text-[#0b2a4a] hover:underline break-all">australia1@buyersellerplatform.com</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 flex">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm w-full">
                        <div class="p-8 h-full">
                            <h2 class="text-xl font-bold text-slate-900">Send a message</h2>
                            <p class="mt-2 text-[15px] text-slate-600">
                                Fill the form and we'll respond as soon as possible.
                            </p>

                            <form action="{{ route('contact.submit') }}" method="POST" class="mt-6 space-y-5">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="text-sm font-medium text-slate-800">Full Name</label>
                                        <input name="name" value="{{ old('name') }}" type="text"
                                            class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-[#0b2a4a] focus:outline-none focus:ring-1 focus:ring-[#0b2a4a]"
                                            placeholder="Your name">
                                        @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-slate-800">Email</label>
                                        <input name="email" value="{{ old('email') }}" type="email"
                                            class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-[#0b2a4a] focus:outline-none focus:ring-1 focus:ring-[#0b2a4a]"
                                            placeholder="you@example.com">
                                        @error('email') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-slate-800">Phone</label>
                                        <input name="phone" value="{{ old('phone') }}" type="text"
                                            class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-[#0b2a4a] focus:outline-none focus:ring-1 focus:ring-[#0b2a4a]"
                                            placeholder="Phone number">
                                        @error('phone') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-slate-800">Issue Type</label>
                                        <select name="issue_type"
                                            class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-[#0b2a4a] focus:outline-none focus:ring-1 focus:ring-[#0b2a4a]">
                                            <option value="">Select</option>
                                            <option value="sales" @selected(old('issue_type') === 'sales')>Sales</option>
                                            <option value="support" @selected(old('issue_type') === 'support')>Support</option>
                                            <option value="feedback" @selected(old('issue_type') === 'feedback')>Feedback</option>
                                            <option value="other" @selected(old('issue_type') === 'other')>Other</option>
                                        </select>
                                        @error('issue_type') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-slate-800">Message</label>
                                    <textarea name="message" rows="5"
                                        class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-slate-900 shadow-sm resize-none focus:border-[#0b2a4a] focus:outline-none focus:ring-1 focus:ring-[#0b2a4a]"
                                        placeholder="Write your message...">{{ old('message') }}</textarea>
                                    @error('message') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 mt-4">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-md px-8 py-3 text-sm font-semibold text-white transition-colors"
                                        style="background-color: #0b2a4a;">
                                        Submit Message
                                    </button>
                                </div>
                            </form>

                            <p class="mt-5 text-xs text-slate-500">
                                By submitting, you agree that we may contact you back regarding your query.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
