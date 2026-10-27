<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted | Talentifyy Enterprise</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />

    <style>
        :root {
            --primary: #00a884;
            --primary-dark: #008f70;
            --primary-light: #e6f7f3;
            --navy: #0f172a;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 168, 132, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.04) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .success-card {
            background: #ffffff;
            width: 100%;
            max-width: 580px;
            border-radius: 24px;
            padding: 48px 36px;
            text-align: center;
            box-shadow: 0 20px 45px -10px rgba(0, 168, 132, 0.12), 0 0 0 1px rgba(226, 232, 240, 0.8);
            position: relative;
        }

        .brand-logo-img {
            height: 56px;
            width: auto;
            object-fit: contain;
            margin-bottom: 24px;
        }

        .success-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 24px auto;
            border: 2px solid #9ee5d4;
            box-shadow: 0 0 0 8px rgba(0, 168, 132, 0.1);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 168, 132, 0.3); }
            70% { box-shadow: 0 0 0 16px rgba(0, 168, 132, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 168, 132, 0); }
        }

        .success-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .success-desc {
            font-size: 0.95rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .candidate-badge-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 28px;
            text-align: left;
        }

        .candidate-badge-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.88rem;
            margin-bottom: 6px;
        }

        .candidate-badge-row:last-child {
            margin-bottom: 0;
        }

        .badge-label {
            color: #64748b;
            font-weight: 600;
        }

        .badge-value {
            color: #0f172a;
            font-weight: 700;
        }

        .status-pill {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-another {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary);
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 168, 132, 0.25);
        }

        .btn-another:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="success-card">
    <img src="{{ asset('images/logo.png') }}" alt="Talentifyy Enterprise" class="brand-logo-img">

    <div class="success-icon-wrap">
        <i class="fa-solid fa-check"></i>
    </div>

    <h1 class="success-title">
        {{ $isUpdate ? 'Application Updated Successfully!' : 'Application Submitted Successfully!' }}
    </h1>

    <p class="success-desc">
        Thank you, <strong>{{ $candidateName }}</strong>! Your candidate profile and credentials have been received directly into our recruitment tracking system. Our talent acquisition specialists will review your application and reach out regarding matching opportunities.
    </p>

    <div class="candidate-badge-box">
        <div class="candidate-badge-row">
            <span class="badge-label">Applicant Name:</span>
            <span class="badge-value">{{ $candidateName }}</span>
        </div>
        @if($jobTitle)
            <div class="candidate-badge-row">
                <span class="badge-label">Target Role:</span>
                <span class="badge-value">{{ $jobTitle }}</span>
            </div>
        @endif
        <div class="candidate-badge-row">
            <span class="badge-label">Recruitment Status:</span>
            <span class="status-pill">
                <i class="fa-solid fa-circle-check"></i> Received & Under Review
            </span>
        </div>
        <div class="candidate-badge-row">
            <span class="badge-label">Submission Time:</span>
            <span class="badge-value">{{ now()->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    <div>
        <a href="{{ route('careers.apply') }}" class="btn-another">
            <i class="fa-solid fa-arrow-rotate-left"></i> Submit Another Application
        </a>
    </div>

    <div style="margin-top: 28px; font-size: 0.8rem; color: #94a3b8;">
        &copy; {{ date('Y') }} Talentifyy Enterprise Portal. All rights reserved.
    </div>
</div>

</body>
</html>
