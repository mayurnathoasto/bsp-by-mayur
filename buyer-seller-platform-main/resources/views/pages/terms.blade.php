@extends('layouts.app')

@section('content')

    <style>
        .tc-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 60%, #24618e 100%);
            padding: 56px 0 40px;
            text-align: center;
            color: #fff;
        }

        .tc-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .tc-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        .tc-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .tc-section {
            margin-bottom: 38px;
        }

        .tc-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0b2a4a;
            border-left: 4px solid #ff6a00;
            padding-left: 12px;
            margin-bottom: 14px;
        }

        .tc-section p {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.9;
            margin-bottom: 10px;
        }

        .tc-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 8px;
        }

        .tc-section ul li {
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

        .tc-section ul li::before {
            content: '';
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff6a00;
            flex-shrink: 0;
        }

        .tc-alert {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 16px 20px;
            font-size: 14px;
            color: #92400e;
            margin-bottom: 38px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.7;
        }

        .tc-contact-box {
            background: linear-gradient(135deg, #ff6a00, #f97316);
            color: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .tc-contact-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .tc-contact-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .tc-contact-box a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }
    </style>

    {{-- Hero --}}
    <div class="tc-hero">
        <div class="max-w-3xl mx-auto px-4">
            <h1>Terms &amp; Conditions</h1>
            <p>Buyer Seller Platform — Your Global B2B Marketplace</p>
        </div>
    </div>

    <div class="tc-wrap">


        {{-- Section 1 --}}
        <div class="tc-section">
            <h2>1. Platform Nature</h2>
            <p><strong>Buyer Seller Platform</strong> is a B2B marketplace that connects buyers and sellers across multiple
                countries.</p>
            <ul>
                <li>We do not manufacture, sell, or own listed products</li>
                <li>We act solely as a platform facilitator</li>
            </ul>
        </div>

        {{-- Section 2 --}}
        <div class="tc-section">
            <h2>2. User Eligibility</h2>
            <p>To use this platform, users must:</p>
            <ul>
                <li>Be at least 18 years old</li>
                <li>Represent a legally registered business</li>
                <li>Provide accurate business details</li>
            </ul>
        </div>

        {{-- Section 3 --}}
        <div class="tc-section">
            <h2>3. Seller Responsibilities</h2>
            <p>Sellers using this platform must:</p>
            <ul>
                <li>Provide accurate product descriptions</li>
                <li>Deliver products as agreed</li>
                <li>Avoid counterfeit or illegal goods</li>
                <li>Follow export/import laws</li>
            </ul>
        </div>

        {{-- Section 4 --}}
        <div class="tc-section">
            <h2>4. Buyer Responsibilities</h2>
            <p>Buyers using this platform must:</p>
            <ul>
                <li>Verify supplier details independently</li>
                <li>Conduct due diligence</li>
                <li>Complete payment as agreed</li>
            </ul>
        </div>

        {{-- Section 5 --}}
        <div class="tc-section">
            <h2>5. Prohibited Activities</h2>
            <p>Users must not engage in the following activities:</p>
            <ul>
                <li>Post illegal products</li>
                <li>Create fake company profiles</li>
                <li>Mislead other users</li>
                <li>Engage in spam or fraud</li>
            </ul>
        </div>

        {{-- Section 6 --}}
        <div class="tc-section">
            <h2>6. Account Suspension</h2>
            <p>We reserve the right to suspend or terminate accounts for policy violations without prior notice. This
                includes repeated violations, fraudulent activity, or misuse of the platform.</p>
        </div>

        {{-- Section 7 --}}
        <div class="tc-section">
            <h2>7. Limitation of Liability</h2>
            <p><strong>Buyer Seller Platform</strong> is not responsible for:</p>
            <ul>
                <li>Product quality issues</li>
                <li>Delivery delays</li>
                <li>Customs or cross-border trade issues</li>
                <li>Financial losses from transactions</li>
            </ul>
        </div>

        {{-- Contact Box --}}
        <div class="tc-contact-box">
            <svg class="w-10 h-10 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div>
                <h3>Questions About These Terms?</h3>
                <p>Contact our support team for any clarifications regarding our Terms &amp; Conditions.</p>
                <p style="margin-top:6px;">Email: <a
                        href="mailto:support@buyer-seller-platform.com">support@buyer-seller-platform.com</a></p>
            </div>
        </div>

    </div>

@endsection