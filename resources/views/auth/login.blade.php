<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - SalesTaletity Enterprise</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        /* Split Screen Card (Matching Screenshot) */
        .login-wrapper {
            background: #ffffff;
            width: 100%;
            max-width: 980px;
            border-radius: 24px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 420px 1fr;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12);
            border: 1px solid #e2e8f0;
        }

        /* Left Hero Dark Panel */
        .hero-panel {
            background-color: #0b172a;
            color: #ffffff;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hero-logo-box {
            width: 42px;
            height: 42px;
            background: #2563eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .hero-brand-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.2px;
        }

        .hero-brand-sub {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* Pulse Wave Vector (Matching Screenshot) */
        .pulse-graphic {
            margin: 30px 0;
            width: 100%;
            height: 70px;
        }

        .hero-content {
            margin-bottom: 25px;
        }

        .hero-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 12px;
            letter-spacing: -0.4px;
        }

        .hero-desc {
            font-size: 0.88rem;
            color: #94a3b8;
            line-height: 1.55;
        }

        /* Bottom Stats Row */
        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
        }

        .stat-num {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
        }

        .stat-lbl {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 2px;
        }

        /* Right Form Panel */
        .form-panel {
            padding: 50px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .secure-tag {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #2563eb;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        /* Light Blue Input Box (Matching Screenshot) */
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #64748b;
            font-size: 1rem;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            font-size: 0.92rem;
            color: #0f172a;
            background-color: #f0f6ff;
            border: 1px solid #dbeabe;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .checkbox-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 0.875rem;
            color: #475569;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        /* Sign In Button (Matching Screenshot) */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        /* Bottom Status Badge */
        .system-status-bar {
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.78rem;
            color: #64748b;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #d1fae5;
            color: #065f46;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
        }

        /* Alert Toast */
        .alert-toast-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        @media (max-width: 850px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            .hero-panel {
                display: none;
            }
            .form-panel {
                padding: 35px 25px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Hero Dark Panel -->
        <div class="hero-panel">
            <div class="hero-brand">
                <div class="hero-logo-box">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div class="hero-brand-name">SalesTaletity</div>
                    <div class="hero-brand-sub">Enterprise HR & Management</div>
                </div>
            </div>

            <div class="hero-content">
                <!-- EKG Pulse Wave Vector SVG (Matching Screenshot) -->
                <svg class="pulse-graphic" viewBox="0 0 300 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 35H60L75 10L90 60L105 20L120 45L135 35H180L195 15L210 55L225 35H300" stroke="#38bdf8" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <h1 class="hero-title">Enterprise Admin & Employee Portal</h1>
                <p class="hero-desc">Manage employees, attendance, leave requests, monthly payroll, and ATS candidate recruitment from a single secure dashboard.</p>
            </div>

            <div class="hero-stats">
                <div class="stat-box">
                    <div class="stat-num">100+</div>
                    <div class="stat-lbl">Employees</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">99.9%</div>
                    <div class="stat-lbl">Uptime</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">IST</div>
                    <div class="stat-lbl">Live Clock</div>
                </div>
            </div>
        </div>

        <!-- Right Sign In Form Panel (Matching Screenshot) -->
        <div class="form-panel">
            <div class="secure-tag">Secure Access</div>
            <h2 class="form-title">Sign in</h2>
            <p class="form-subtitle">Enter your credentials to access your portal.</p>

            @if($errors->any())
                <div class="alert-toast-danger">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem; color: #ef4444;"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <div class="input-group">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="admin@admin.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-input" required placeholder="••••••••">
                    </div>
                </div>

                <div class="checkbox-flex">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign in
                </button>
            </form>

            <div class="system-status-bar">
                <span class="status-pill">
                    <span class="status-dot"></span> All systems operational
                </span>
                <span>• SSL Encrypted</span>
            </div>
        </div>
    </div>

</body>
</html>
