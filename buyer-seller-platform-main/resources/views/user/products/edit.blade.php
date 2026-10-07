@extends('layouts.app')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        [x-cloak] {
            display: none !important;
        }

        .submenu-item.active .product-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-input-container {
            position: relative;
            width: 100%;
        }

        .form-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 8px;
            border: 1.5px solid #f1f5f9;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            background: #fbfcfe;
        }

        .form-input:focus {
            border-color: #ff6a00;
            box-shadow: 0 0 0 4px rgba(255, 106, 0, 0.05);
            outline: none;
            background: white;
        }

        .upload-area {
            border: 1.5px dashed #e2e8f0;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: white;
            transition: all 0.2s;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #ff6a00;
            background: #fffaf7;
        }

        .add-product-btn {
            background: #3b7ea1;
            color: white;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 10px 15px -3px rgba(59, 126, 161, 0.2);
            transition: all 0.2s;
        }

        .add-product-btn:hover {
            background: #2b607c;
            transform: translateY(-1px);
        }
    </style>

    @include('layouts.sidebar_css')

    <div x-data="{ 
                                      sidebarOpen: false, 
                                      openSub: '{{ request()->routeIs('user.profile', 'user.business-profile', 'user.bank-details', 'user.manage-users') ? 'profile' : (request()->routeIs('user.products.*') ? 'products' : 'dashboard') }}',
                                      activeTab: '{{ request()->routeIs('user.profile') ? 'profile' : (request()->routeIs('user.business-profile') ? 'business' : (request()->routeIs('user.bank-details') ? 'bank' : (request()->routeIs('user.manage-users') ? 'manage_users' : 'dashboard'))) }}'
                                    }" class="flex flex-row-reverse h-screen overflow-hidden bg-[#f8fafc] relative">

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
                <div class="max-w-[1400px] mx-auto space-y-4">
                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('user.products.index') }}" class="hover:text-slate-600 transition-colors">Manage
                            Product</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-slate-800">Edit Product</span>
                    </div>

                    <div class="flex items-start justify-between mt-8 mb-4">
                        <h2 class="text-xl font-bold text-slate-800">Edit Product</h2>
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 -mr-2 -mt-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-8">
                        <div class="flex-1 min-w-0">
                            <div class="product-card p-8 bg-white shadow-sm border border-slate-100" x-data="{ 
                categories: {{ Js::from($categories) }},
                selectedCategoryId: '{{ (string) old('category_id', $product->category_id) }}',
                selectedSubcategoryId: '{{ (string) old('subcategory_id', $product->subcategory_id) }}',
                subcategories: [],
                images: {{ Js::from(old('images', $product->images ?? [])) }},
                uploading: false,
                specifications: {{ Js::from($initialSpecifications ?? []) }},

                updateSubcategories() {
                    const cat = this.categories.find(c => String(c.id) === String(this.selectedCategoryId));
                    this.subcategories = cat && cat.subcategories ? cat.subcategories : [];

                    if (!this.subcategories.find(s => String(s.id) === String(this.selectedSubcategoryId))) {
                        this.selectedSubcategoryId = '';
                    }
                },

                openFilePicker() {
                    this.$refs.productImagesInput.click();
                },

                addSpecification() {
                    this.specifications.push({ key: '', value: '' });
                },

                removeSpecification(index) {
                    this.specifications.splice(index, 1);
                },

                async uploadImages(event) {
                    const files = Array.from(event.target.files || []);
                    if (!files.length) return;

                    const remaining = 6 - this.images.length;
                    const selectedFiles = files.slice(0, remaining);

                    if (!selectedFiles.length) return;

                    this.uploading = true;

                    try {
                        for (const file of selectedFiles) {
                            const formData = new FormData();
                            formData.append('file', file);
                            formData.append('folder', 'products');

                            const response = await fetch('{{ route('user.upload.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            });

                            const data = await response.json();

                            if (!response.ok || !data.url) {
                                throw new Error(data.message || 'Upload failed');
                            }

                            this.images.push(data.url);
                        }
                    } catch (error) {
                        console.error(error);
                        alert(error.message || 'Image upload failed.');
                    } finally {
                        this.uploading = false;
                        event.target.value = '';
                    }
                },

                removeImage(index) {
                    this.images.splice(index, 1);
                }
            }" x-init="$nextTick(() => updateSubcategories())">

                                @php
                                    $activeCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
                                    $countries = config('countries.supported', []);
                                    $resolvedCurrency = $product->currency ?? ($countries[$activeCountry]['currency'] ?? config('countries.default_currency', 'INR'));
                                @endphp

                                <form class="space-y-8" method="POST"
                                    action="{{ route('user.products.update', $product) }}">
                                    @csrf
                                    @method('PUT')

                                    @if(session('success'))
                                        <div class="bg-green-50 text-green-700 p-4 rounded-lg text-sm font-semibold">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm font-semibold">
                                            Please fix the errors below and try again.
                                            <ul class="list-disc pl-5 mt-2 text-xs font-normal">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-input-container">
                                        <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                            placeholder="Enter Product Name"
                                            class="form-input @error('name') border-red-500 @enderror" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4 relative text-left">
                                            <label class="form-label inline-block">Category <span
                                                    class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <select name="category_id" x-model="selectedCategoryId"
                                                    @change="updateSubcategories"
                                                    class="form-input appearance-none bg-white pr-10 @error('category_id') border-red-500 @enderror"
                                                    required>
                                                    <option value="">Select a Category</option>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}" {{ (string) old('category_id', $product->category_id) === (string) $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4 relative text-left">
                                            <label class="form-label inline-block">Subcategory</label>
                                            <div class="relative">
                                                <select name="subcategory_id" x-model="selectedSubcategoryId"
                                                    class="form-input appearance-none bg-white pr-10 @error('subcategory_id') border-red-500 @enderror">
                                                    <option value="">Select a Subcategory (Optional)</option>
                                                    <template x-for="sub in subcategories" :key="sub.id">
                                                        <option :value="String(sub.id)" x-text="sub.name"></option>
                                                    </template>
                                                </select>
                                                <div
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <label class="form-label">Price Range (Optional Max Price)</label>

                                            <div class="flex items-center gap-3 w-full">
                                                <div class="w-full">
                                                    <input type="number" step="0.01" name="price"
                                                        value="{{ old('price', $product->price) }}" placeholder="Min Price"
                                                        class="form-input w-full @error('price') border-red-500 @enderror">
                                                </div>

                                                <div class="w-full">
                                                    <input type="number" step="0.01" name="price_to"
                                                        value="{{ old('price_to', $product->price_to) }}" placeholder="Max Price (Optional)"
                                                        class="form-input w-full @error('price_to') border-red-500 @enderror">
                                                </div>

                                                <div class="relative w-28 shrink-0">
                                                    <input type="text" value="{{ $resolvedCurrency }}"
                                                        class="form-input bg-slate-100 text-center font-bold" readonly>
                                                    <input type="hidden" name="currency" value="{{ $resolvedCurrency }}">
                                                </div>
                                            </div>

                                            <p class="text-[11px] text-gray-500 mt-1">
                                                Currency remains based on your product country setup. For single price, leave Max Price empty.
                                            </p>
                                        </div>

                                        <div class="space-y-4">
                                            <label class="form-label">Minimum Order Quantity</label>
                                            <input type="number" min="1" name="moq"
                                                value="{{ old('moq', $product->min_order_quantity) }}" placeholder="1"
                                                class="form-input @error('moq') border-red-500 @enderror">
                                        </div>
                                    </div>

                                    <div class="form-input-container">
                                        <label class="form-label">Product Description</label>
                                        <textarea name="description" rows="5"
                                            class="form-input @error('description') border-red-500 @enderror"
                                            placeholder="Write a detailed description of your product...">{{ old('description', $product->description) }}</textarea>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <label class="form-label !mb-1">Product Specifications</label>
                                                <p class="text-xs text-slate-500">Add technical details like material, size,
                                                    grade, voltage, capacity, etc.</p>
                                            </div>

                                            <button type="button" @click="addSpecification()"
                                                class="px-4 py-2 rounded-lg border border-[#3b7ea1] text-[#3b7ea1] text-xs font-bold uppercase tracking-wider hover:bg-[#3b7ea1] hover:text-white transition-all">
                                                Add Specification
                                            </button>
                                        </div>

                                        <div class="space-y-3" x-show="specifications.length > 0" x-cloak>
                                            <template x-for="(spec, index) in specifications" :key="index">
                                                <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 items-start">
                                                    <div>
                                                        <input type="text" :name="`spec_keys[${index}]`" x-model="spec.key"
                                                            placeholder="Specification name (e.g. Material)"
                                                            class="form-input">
                                                    </div>

                                                    <div>
                                                        <input type="text" :name="`spec_values[${index}]`"
                                                            x-model="spec.value"
                                                            placeholder="Specification value (e.g. Stainless Steel)"
                                                            class="form-input">
                                                    </div>

                                                    <div>
                                                        <button type="button" @click="removeSpecification(index)"
                                                            class="h-[50px] px-4 rounded-lg border border-red-200 text-red-500 text-xs font-bold uppercase tracking-wider hover:bg-red-50 transition-all">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <div x-show="specifications.length === 0"
                                            class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-5 text-sm text-slate-500">
                                            No specifications added yet.
                                        </div>
                                    </div>


                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <label class="form-label">Lead Time</label>
                                            <div class="grid grid-cols-[1fr_180px] gap-3">
                                                <input type="number" min="1" max="365" name="lead_time_value"
                                                    value="{{ old('lead_time_value', $product->lead_time_value) }}"
                                                    placeholder="e.g. 7"
                                                    class="form-input @error('lead_time_value') border-red-500 @enderror">

                                                <select name="lead_time_unit"
                                                    class="form-input appearance-none bg-white @error('lead_time_unit') border-red-500 @enderror">
                                                    <option value="">Select unit</option>
                                                    @foreach($leadTimeUnits as $unit)
                                                        <option value="{{ $unit }}" {{ old('lead_time_unit', $product->lead_time_unit) === $unit ? 'selected' : '' }}>
                                                            {{ $unit }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <label class="form-label">MOQ for Sample</label>
                                            <input type="number" min="1" name="sample_moq"
                                                value="{{ old('sample_moq', $product->sample_moq) }}" placeholder="e.g. 1"
                                                class="form-input @error('sample_moq') border-red-500 @enderror">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <label class="form-label">Warranty</label>
                                            <input type="text" name="warranty"
                                                value="{{ old('warranty', $product->warranty) }}" placeholder="e.g. 1 Year"
                                                class="form-input @error('warranty') border-red-500 @enderror">
                                        </div>

                                        <div class="space-y-4">
                                            <label class="form-label">Support Type</label>
                                            <select name="support_option"
                                                class="form-input appearance-none bg-white @error('support_option') border-red-500 @enderror">
                                                <option value="">Select support type</option>
                                                @foreach($supportOptionsList as $option)
                                                    <option value="{{ $option }}" {{ old('support_option', $product->support_option) === $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="form-label">Shipping Options</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @php $selectedShipping = old('shipping_options', $product->shipping_options ?? []); @endphp
                                            @foreach($shippingOptionsList as $option)
                                                <label
                                                    class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 bg-white">
                                                    <input type="checkbox" name="shipping_options[]" value="{{ $option }}" {{ in_array($option, $selectedShipping, true) ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="form-label">Payment Options</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @php $selectedPayments = old('payment_options', $product->payment_options ?? []); @endphp
                                            @foreach($paymentOptionsList as $option)
                                                <label
                                                    class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 bg-white">
                                                    <input type="checkbox" name="payment_options[]" value="{{ $option }}" {{ in_array($option, $selectedPayments, true) ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>



                                    <div class="space-y-4">
                                        <label class="form-label">Product Images</label>

                                        <template x-for="(url, index) in images" :key="index">
                                            <input type="hidden" name="images[]" :value="url">
                                        </template>

                                        <input type="file" x-ref="productImagesInput" class="hidden" accept="image/*"
                                            multiple @change="uploadImages($event)">

                                        <div class="upload-area group border-2" @click="openFilePicker()"
                                            x-show="images.length < 6 && !uploading">
                                            <div
                                                class="w-12 h-12 bg-[#ff6a00]/5 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-[#ff6a00]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-5-8l-5-5m0 0l-5 5m5-5v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h5 class="font-bold text-slate-800 text-sm mb-1">Upload Photos</h5>
                                            <p class="text-[11px] font-bold text-[#ff9100]">Add up to 6 images to your
                                                product.</p>
                                        </div>

                                        <div class="upload-area group border-2 opacity-70 cursor-not-allowed"
                                            x-show="uploading" x-cloak>
                                            <div
                                                class="w-12 h-12 bg-[#ff6a00]/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-6 h-6 text-[#ff6a00] animate-spin" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                </svg>
                                            </div>
                                            <h5 class="font-bold text-slate-800 text-sm mb-1">Uploading...</h5>
                                            <p class="text-[11px] font-bold text-[#ff9100]">Please wait while images are
                                                uploaded.</p>
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 pt-4"
                                            x-show="images.length > 0" x-cloak>
                                            <template x-for="(url, index) in images" :key="index">
                                                <div
                                                    class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200">
                                                    <img :src="url" class="w-full h-full object-cover">
                                                    <div
                                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <button type="button" @click.stop="removeImage(index)"
                                                            class="bg-white text-red-500 p-2 rounded-full hover:bg-red-50 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-8 gap-3">
                                        <a href="{{ route('user.products.index') }}"
                                            class="px-6 py-3 rounded-lg border border-slate-300 text-slate-600 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">
                                            Cancel
                                        </a>

                                        <button type="submit"
                                            class="add-product-btn hover:bg-[#2b607c] shadow-lg shadow-[#3b7ea1]/20 active:scale-95 transition-all outline-none font-bold uppercase tracking-widest text-xs">
                                            Update Product
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
