@extends('layouts.app')

@section('content')

    <style>
        .pp-hero {
            background: linear-gradient(135deg, #0b2a4a 0%, #1a4b7c 60%, #24618e 100%);
            padding: 56px 0 40px;
            text-align: center;
            color: #fff;
        }

        .pp-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .pp-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        .pp-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .pp-section {
            margin-bottom: 38px;
        }

        .pp-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0b2a4a;
            border-left: 4px solid #ff6a00;
            padding-left: 12px;
            margin-bottom: 14px;
        }

        .pp-section p {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.9;
            margin-bottom: 10px;
        }

        .pp-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
        }

        .pp-section ul li {
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

        .pp-section ul li::before {
            content: '';
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff6a00;
            flex-shrink: 0;
        }

        .pp-section ul.plain-list {
            display: block;
        }

        .pp-section ul.plain-list li {
            background: none;
            border: none;
            padding: 4px 0;
            font-size: 14px;
        }

        .pp-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 38px;
        }

        .pp-card-head {
            background: #0b2a4a;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 10px 20px;
        }

        .pp-card-body {
            padding: 20px;
        }

        .pp-contact-box {
            background: linear-gradient(135deg, #ff6a00, #f97316);
            color: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pp-contact-box svg {
            flex-shrink: 0;
            opacity: 0.9;
        }

        .pp-contact-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .pp-contact-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .pp-contact-box a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }

        .pp-effective {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 13px;
            color: #16a34a;
            font-weight: 600;
            margin-bottom: 36px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    {{-- Hero --}}
    <div class="pp-hero">
        <div class="max-w-3xl mx-auto px-4">
            <h1>Privacy Policy</h1>
            <p>Buyer Seller Platform — Your Global B2B Marketplace</p>
        </div>
    </div>

    <div class="pp-wrap">


        <div class="pp-section">
            <p>Welcome to <strong>Buyer Seller Platform</strong>, a global B2B (Business to Business) marketplace connecting
                verified buyers and sellers across multiple countries. We respect your privacy and are committed to
                protecting your business and personal information.</p>
        </div>

        {{-- Section 1 --}}
        <div class="pp-section">
            <h2>1. Information We Collect</h2>
            <p>We may collect the following types of information from users of our platform:</p>

            <div class="pp-card">
                <div class="pp-card-head">A. Business Information</div>
                <div class="pp-card-body">
                    <ul>
                        <li>Company Name</li>
                        <li>GST / VAT / Tax ID Number</li>
                        <li>Business Registration Details</li>
                        <li>Business Address</li>
                        <li>Contact Person Details</li>
                    </ul>
                </div>
            </div>

            <div class="pp-card">
                <div class="pp-card-head">B. Personal Information</div>
                <div class="pp-card-body">
                    <ul>
                        <li>Full Name</li>
                        <li>Email Address</li>
                        <li>Phone Number</li>
                        <li>Login Credentials</li>
                        <li>Profile Information</li>
                    </ul>
                </div>
            </div>

            <div class="pp-card">
                <div class="pp-card-head">C. Transaction Data</div>
                <div class="pp-card-body">
                    <ul>
                        <li>Product Listings</li>
                        <li>Business Inquiries</li>
                        <li>Buyer-Seller Communication</li>
                        <li>Order Information</li>
                    </ul>
                </div>
            </div>

            <div class="pp-card">
                <div class="pp-card-head">D. Technical Data</div>
                <div class="pp-card-body">
                    <ul>
                        <li>IP Address</li>
                        <li>Browser Type</li>
                        <li>Device Information</li>
                        <li>Cookies</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="pp-section">
            <h2>2. How We Use Your Information</h2>
            <p>We use collected information to:</p>
            <ul>
                <li>Create and manage user accounts</li>
                <li>Facilitate B2B trade communication</li>
                <li>Improve platform performance</li>
                <li>Prevent fraud and misuse</li>
                <li>Comply with international trade laws</li>
            </ul>
        </div>

        {{-- Section 3 --}}
        <div class="pp-section">
            <h2>3. Multi-Country Data Processing</h2>
            <p>As a global platform, your data may be stored and processed outside your country. We ensure compliance with
                applicable international data protection laws including GDPR (EU), PDPA (India), and other relevant regional
                frameworks.</p>
        </div>

        {{-- Section 4 --}}
        <div class="pp-section">
            <h2>4. Data Security</h2>
            <p>We implement industry-standard safeguards to protect your information:</p>
            <ul>
                <li>SSL Encryption</li>
                <li>Secure Cloud Hosting</li>
                <li>Role-based Access Control</li>
                <li>Regular Security Audits</li>
            </ul>
            <p style="margin-top:14px;">However, no digital system can guarantee 100% security. We encourage users to keep
                their login credentials confidential and report any suspected unauthorized activity immediately.</p>
        </div>

        {{-- Section 5 --}}
        <div class="pp-section">
            <h2>5. User Rights</h2>
            <p>As a user of Buyer Seller Platform, you have the following rights regarding your personal data:</p>
            <ul>
                <li>Access their data</li>
                <li>Update incorrect information</li>
                <li>Request deletion</li>
                <li>Withdraw consent</li>
            </ul>
        </div>

        {{-- Contact Box --}}
        <div class="pp-contact-box">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div>
                <h3>Have Questions About Your Privacy?</h3>
                <p>Reach out to our dedicated support team and we'll get back to you promptly.</p>
                <p style="margin-top:6px;">Email: <a
                        href="mailto:support@buyer-seller-platform.com">support@buyer-seller-platform.com</a></p>
            </div>
        </div>

    </div>

@endsection