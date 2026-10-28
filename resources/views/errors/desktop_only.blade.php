<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desktop Access Required - Talentifyy</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        :root {
            --primary: #00a884;
            --primary-dark: #008769;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --dark: #0f172a;
            --slate: #475569;
            --border: #e2e8f0;
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
                radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 168, 132, 0.06) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--dark);
        }

        .blocked-card {
            background: #ffffff;
            width: 100%;
            max-width: 520px;
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px var(--border);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .blocked-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #ef4444, #f59e0b, #00a884);
        }

        .brand-logo {
            height: 56px;
            width: auto;
            object-fit: contain;
            margin-bottom: 24px;
        }

        .icon-shield {
            width: 90px;
            height: 90px;
            background: var(--danger-light);
            border: 2px dashed #fca5a5;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .icon-shield .monitor-icon {
            font-size: 2.4rem;
            color: #ef4444;
        }

        .icon-shield .cross-badge {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background: #ef4444;
            color: #ffffff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            border: 2px solid #ffffff;
        }

        .blocked-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fee2e2;
            color: #b91c1c;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .blocked-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .blocked-desc {
            font-size: 0.94rem;
            color: var(--slate);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px 18px;
            text-align: left;
            margin-bottom: 24px;
            font-size: 0.86rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: var(--dark);
            font-weight: 700;
        }

        .policy-list {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.82rem;
            color: #92400e;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .policy-list ul {
            padding-left: 20px;
            margin-top: 6px;
        }

        .policy-list li {
            margin-bottom: 4px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 0.94rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: var(--dark);
        }
    </style>
</head>
<body>
    <div class="blocked-card">
        <img src="{{ asset('images/logo.png') }}" alt="Talentifyy" class="brand-logo" onerror="this.style.display='none'">

        <div class="icon-shield">
            <i class="fa-solid fa-desktop monitor-icon"></i>
            <span class="cross-badge"><i class="fa-solid fa-mobile-screen"></i></span>
        </div>

        <div class="blocked-badge">
            <i class="fa-solid fa-ban"></i> Desktop Access Only
        </div>

        <h1 class="blocked-title">Only Use on Desktop<br><span style="font-size: 1.15rem; color: #ef4444; font-weight: 700;">Mobile Access is Restricted</span></h1>

        <p class="blocked-desc">
            This platform is strictly configured for <strong>Desktop and Laptop computers</strong>. Employees are not permitted to access work portals, CRM, ATS, or attendance from mobile phones.
        </p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-mobile-screen"></i> Detected Device:</span>
                <span class="info-value" style="color: #ef4444;">{{ $deviceInfo ?? 'Mobile / Handheld Device' }}</span>
            </div>
            @if(isset($user) && $user)
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-user"></i> Logged In User:</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-clock"></i> Timestamp:</span>
                <span class="info-value">{{ now()->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="policy-list">
            <strong><i class="fa-solid fa-circle-exclamation"></i> Work Station Guidelines:</strong>
            <ul>
                <li>Please open your office desktop or laptop browser (Chrome, Edge, Firefox).</li>
                <li>Visit this URL from your desktop computer to proceed.</li>
                <li>Mobile screen clock-in or portal usage is logged and blocked.</li>
            </ul>
        </div>

        <div class="btn-group">
            <button onclick="window.location.reload();" class="btn-primary">
                <i class="fa-solid fa-rotate-right"></i> Check Again / Reload
            </button>

            @if(auth()->check())
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-secondary" style="width: 100%;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out Session
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Login Page
            </a>
            @endif
        </div>
    </div>
</body>
</html>
