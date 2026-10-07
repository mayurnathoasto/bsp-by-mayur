@extends('layouts.app')

@section('content')

    <style>
        .rp-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 60%, #24618e 100%);
            padding: 56px 0 40px;
            text-align: center;
            color: #fff;
        }

        .rp-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .rp-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        .rp-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .rp-section {
            margin-bottom: 32px;
        }

        .rp-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0b2a4a;
            border-left: 4px solid #ff6a00;
            padding-left: 12px;
            margin-bottom: 14px;
        }

        .rp-section p {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.9;
            margin-bottom: 10px;
        }

        .rp-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
            margin-top: 8px;
        }

        .rp-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 18px;
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .rp-card-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ff6a00;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .rp-note {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 16px 20px;
            font-size: 14px;
            color: #92400e;
            margin-bottom: 32px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.7;
        }

        .rp-contact-box {
            background: linear-gradient(135deg, #ff6a00, #f97316);
            color: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .rp-contact-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .rp-contact-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .rp-contact-box a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }
    </style>

    {{-- Hero --}}
    <div class="rp-hero">
        <div class="max-w-3xl mx-auto px-4">
            <h1>Refund Policy</h1>
            <p>Buyer Seller Platform — Your Global B2B Marketplace</p>
        </div>
    </div>

    <div class="rp-wrap">

        {{-- Intro note --}}
        <div class="rp-note">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd" />
            </svg>
            <span><strong>Buyer Seller Platform</strong> is a B2B listing platform. We do not directly handle product
                transactions unless explicitly stated in the relevant service plan.</span>
        </div>

        <div class="rp-cards" style="margin-bottom: 32px;">
            <div class="rp-card">
                <div class="rp-card-dot"></div>
                <div>Subscription payments (if applicable) are <strong>non-refundable</strong>.</div>
            </div>
            <div class="rp-card">
                <div class="rp-card-dot"></div>
                <div>Refund eligibility depends on the purchased service plan terms.</div>
            </div>
            <div class="rp-card">
                <div class="rp-card-dot"></div>
                <div>We do not directly handle product transactions unless stated.</div>
            </div>
        </div>

        {{-- Contact Box --}}
        <div class="rp-contact-box">
            <svg class="w-10 h-10 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div>
                <h3>Refund Related Queries?</h3>
                <p>Contact our support team and we'll assist you based on your service plan.</p>
                <p style="margin-top:6px;">Email: <a
                        href="mailto:support@buyer-seller-platform.com">support@buyer-seller-platform.com</a></p>
            </div>
        </div>

    </div>

@endsection