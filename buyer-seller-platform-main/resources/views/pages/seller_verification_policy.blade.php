@extends('layouts.app')

@section('content')

    <style>
        .svp-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 60%, #24618e 100%);
            padding: 56px 0 40px;
            text-align: center;
            color: #fff;
        }

        .svp-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .svp-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        .svp-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .svp-intro {
            font-size: 15px;
            color: #374151;
            line-height: 1.9;
            margin-bottom: 32px;
        }

        .svp-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0b2a4a;
            border-left: 4px solid #ff6a00;
            padding-left: 12px;
            margin-bottom: 16px;
        }

        .svp-list {
            list-style: none;
            padding: 0;
            margin: 0 0 32px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 12px;
        }

        .svp-list li {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.6;
        }

        .svp-list li svg {
            flex-shrink: 0;
            margin-top: 1px;
            color: #ff6a00;
        }

        .svp-badge {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .svp-badge-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .svp-badge-text h3 {
            font-size: 15px;
            font-weight: 700;
            color: #166534;
            margin-bottom: 4px;
        }

        .svp-badge-text p {
            font-size: 13px;
            color: #4b5563;
            margin: 0;
            line-height: 1.6;
        }

        .svp-contact-box {
            background: linear-gradient(135deg, #ff6a00, #f97316);
            color: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .svp-contact-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .svp-contact-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .svp-contact-box a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }
    </style>

    {{-- Hero --}}
    <div class="svp-hero">
        <div class="max-w-3xl mx-auto px-4">
            <h1>Seller Verification Policy</h1>
            <p>Buyer Seller Platform — Your Global B2B Marketplace</p>
        </div>
    </div>

    <div class="svp-wrap">

        <p class="svp-intro">
            To maintain <strong>trust and transparency</strong> across the platform, all sellers are subject to the
            following verification process before they can operate as a Verified Seller.
        </p>

        <div class="svp-section" style="margin-bottom: 20px;">
            <h2>Verification Requirements</h2>
        </div>

        <ul class="svp-list">
            <li>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                Sellers may be required to submit business documents
            </li>
            <li>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                GST / VAT validation may be performed
            </li>
            <li>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                Manual approval process may apply
            </li>
        </ul>

        {{-- Verified Badge info --}}
        <div class="svp-badge">
            <div class="svp-badge-icon">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="svp-badge-text">
                <h3>✅ Verified Seller Badge</h3>
                <p>Sellers who successfully complete the verification process may receive a <strong>"Verified
                        Seller"</strong> badge on their profile, increasing buyer trust and visibility on the platform.</p>
            </div>
        </div>

        {{-- Contact Box --}}
        <div class="svp-contact-box">
            <svg class="w-10 h-10 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div>
                <h3>Questions About Seller Verification?</h3>
                <p>Contact our support team for help with the verification process.</p>
                <p style="margin-top:6px;">Email: <a
                        href="mailto:support@buyer-seller-platform.com">support@buyer-seller-platform.com</a></p>
            </div>
        </div>

    </div>

@endsection