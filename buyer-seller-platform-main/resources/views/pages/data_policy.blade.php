@extends('layouts.app')

@section('content')

    <style>
        .dp-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 60%, #24618e 100%);
            padding: 56px 0 40px;
            text-align: center;
            color: #fff;
        }

        .dp-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .dp-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        .dp-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .dp-section {
            margin-bottom: 38px;
        }

        .dp-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0b2a4a;
            border-left: 4px solid #ff6a00;
            padding-left: 12px;
            margin-bottom: 14px;
        }

        .dp-section p {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.9;
            margin-bottom: 10px;
        }

        .dp-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 8px;
        }

        .dp-section ul li {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dp-section ul li::before {
            content: '';
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff6a00;
            flex-shrink: 0;
        }

        .dp-highlight {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 14px;
            color: #0369a1;
            font-weight: 600;
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dp-contact-box {
            background: linear-gradient(135deg, #ff6a00, #f97316);
            color: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dp-contact-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .dp-contact-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .dp-contact-box a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }
    </style>

    {{-- Hero --}}
    <div class="dp-hero">
        <div class="max-w-3xl mx-auto px-4">
            <h1>Data Usage &amp; Retention Policy</h1>
            <p>Buyer Seller Platform — Your Global B2B Marketplace</p>
        </div>
    </div>

    <div class="dp-wrap">

        {{-- Section 1 --}}
        <div class="dp-section">
            <h2>1. Data Storage</h2>
            <p>User data is stored on secure, encrypted cloud infrastructure with industry-standard access controls to
                ensure it remains safe and protected at all times.</p>
        </div>

        {{-- Section 2 --}}
        <div class="dp-section">
            <h2>2. Data Retention</h2>
            <p>We retain your data under the following conditions:</p>
            <ul>
                <li>As long as your account remains active</li>
                <li>As required by applicable law</li>
            </ul>
            <p style="margin-top:14px;">Once data is no longer needed, it is securely deleted or anonymized in accordance
                with our internal data lifecycle policy.</p>
        </div>

        {{-- Section 3 --}}
        <div class="dp-section">
            <h2>3. Data Sharing</h2>
            <p>We may share your information only in the following limited circumstances:</p>
            <ul>
                <li>Legal authorities (if required by law)</li>
                <li>Payment gateways</li>
                <li>Fraud prevention services</li>
            </ul>

            <div class="dp-highlight">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                We do <strong>&nbsp;NOT&nbsp;</strong> sell user data to any third parties — ever.
            </div>
        </div>

        {{-- Contact Box --}}
        <div class="dp-contact-box">
            <svg class="w-10 h-10 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div>
                <h3>Questions About Your Data?</h3>
                <p>Contact our support team for any queries about how your data is stored or used.</p>
                <p style="margin-top:6px;">Email: <a
                        href="mailto:support@buyer-seller-platform.com">support@buyer-seller-platform.com</a></p>
            </div>
        </div>

    </div>

@endsection