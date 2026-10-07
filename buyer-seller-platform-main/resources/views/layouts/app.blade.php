<!DOCTYPE html>
<html lang="{{ session('language', 'en') }}" dir="{{ session('language_direction', 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Buyer Seller Platform' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Global Font: Public Sans --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >
</head>

<body class="bg-gray-50 text-gray-900 pb-[100px] lg:pb-0">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Page Content --}}
    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    @php
        $languagePrompt = session('language_prompt');
    @endphp

    @if(!empty($languagePrompt))
        <div id="languageConsentPrompt" class="fixed inset-0 z-[1000] flex items-center justify-center bg-slate-950/55 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" data-no-translate>
                <div class="text-lg font-extrabold text-slate-900">Change Website Language?</div>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Do you want to change the website language to {{ $languagePrompt['language_name'] ?? 'the selected language' }}
                    for {{ $languagePrompt['country_name'] ?? 'this country' }}?
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" id="languageConsentAccept"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-xl bg-[#0b2a4a] px-4 text-sm font-bold text-white">
                        Yes, change language
                    </button>
                    <button type="button" id="languageConsentDecline"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700">
                        Keep English
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Alpine --}}
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    @if(!empty($languagePrompt))
        <script>
            (() => {
                const prompt = document.getElementById('languageConsentPrompt');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const country = @json($languagePrompt['country'] ?? null);

                if (!prompt || !country) {
                    return;
                }

                const submitPreference = async (accept) => {
                    const response = await fetch(@json(route('language.preference')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ country, accept }),
                    });

                    if (!response.ok) {
                        throw new Error('Unable to update language preference.');
                    }

                    window.location.reload();
                };

                document.getElementById('languageConsentAccept')?.addEventListener('click', () => {
                    submitPreference(true).catch((error) => alert(error.message));
                });

                document.getElementById('languageConsentDecline')?.addEventListener('click', () => {
                    submitPreference(false).catch((error) => alert(error.message));
                });
            })();
        </script>
    @endif

    <script>
        (() => {
            const translationConfig = {
                active: @json(session('language', 'en') !== 'en'),
                target: @json(session('language', 'en')),
                route: @json(route('language.translate')),
            };

            if (!translationConfig.active || !translationConfig.target) {
                return;
            }

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const skipTags = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'CODE', 'PRE', 'TEXTAREA', 'INPUT', 'SELECT', 'OPTION']);

            const shouldSkipText = (text) => {
                const trimmed = (text || '').trim();

                if (!trimmed) return true;
                if (/^[\d\s.,:%/()\-+]+$/.test(trimmed)) return true;
                if (/^[A-Z0-9]{1,6}$/.test(trimmed)) return true;

                return false;
            };

            const collectEntries = () => {
                const entries = [];
                const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                    acceptNode(node) {
                        const parent = node.parentElement;

                        if (!parent || skipTags.has(parent.tagName) || parent.closest('[data-no-translate]')) {
                            return NodeFilter.FILTER_REJECT;
                        }

                        return shouldSkipText(node.textContent) ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT;
                    },
                });

                let currentNode;
                while ((currentNode = walker.nextNode())) {
                    entries.push({
                        type: 'text',
                        node: currentNode,
                        text: currentNode.textContent.trim(),
                    });
                }

                document.querySelectorAll('input[placeholder], textarea[placeholder]').forEach((element) => {
                    if (element.closest('[data-no-translate]')) {
                        return;
                    }

                    const text = (element.getAttribute('placeholder') || '').trim();

                    if (shouldSkipText(text)) {
                        return;
                    }

                    entries.push({
                        type: 'placeholder',
                        node: element,
                        text,
                    });
                });

                const titleText = (document.title || '').trim();
                if (!shouldSkipText(titleText)) {
                    entries.push({
                        type: 'title',
                        node: document,
                        text: titleText,
                    });
                }

                return entries;
            };

            const applyTranslations = async () => {
                const entries = collectEntries();
                const uniqueTexts = [...new Set(entries.map((entry) => entry.text))];

                if (!uniqueTexts.length) {
                    return;
                }

                const translatedMap = new Map();

                for (let i = 0; i < uniqueTexts.length; i += 50) {
                    const batch = uniqueTexts.slice(i, i + 50);
                    const response = await fetch(translationConfig.route, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            target: translationConfig.target,
                            texts: batch,
                        }),
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    const translations = Array.isArray(payload.translations) ? payload.translations : batch;

                    batch.forEach((text, index) => {
                        translatedMap.set(text, translations[index] || text);
                    });
                }

                entries.forEach((entry) => {
                    const translated = translatedMap.get(entry.text);

                    if (!translated || translated === entry.text) {
                        return;
                    }

                    if (entry.type === 'text') {
                        entry.node.textContent = entry.node.textContent.replace(entry.text, translated);
                        return;
                    }

                    if (entry.type === 'placeholder') {
                        entry.node.setAttribute('placeholder', translated);
                        return;
                    }

                    if (entry.type === 'title') {
                        document.title = translated;
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyTranslations, { once: true });
                return;
            }

            applyTranslations();
        })();
    </script>

    @include('layouts.mobile_nav')
</body>
</html>
