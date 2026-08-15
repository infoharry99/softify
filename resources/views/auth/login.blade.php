<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Talentifyy Enterprise</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 168, 132, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(13, 148, 136, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* Elevated Clean Login Card */
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 45px 40px;
            box-shadow: 0 20px 45px -10px rgba(0, 168, 132, 0.12), 0 0 0 1px rgba(226, 232, 240, 0.8);
            position: relative;
        }

        /* Brand Logo Container */
        .brand-container {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo-img {
            height: 70px;
            width: auto;
            object-fit: contain;
            margin-bottom: 14px;
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .form-input {
            width: 100%;
            padding: 14px 44px 14px 44px;
            font-size: 0.95rem;
            color: #0f172a;
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            outline: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .form-input:focus {
            border-color: #00a884;
            box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
        }

        .form-input:focus ~ .input-icon {
            color: #00a884;
        }

        .toggle-pwd {
            position: absolute;
            right: 16px;
            color: #94a3b8;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.15s ease;
        }

        .toggle-pwd:hover {
            color: #00a884;
        }

        /* Checkbox Row */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            font-size: 0.88rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
            color: #475569;
            font-weight: 500;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #00a884;
            cursor: pointer;
            border-radius: 4px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #00a884;
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.96rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(0, 168, 132, 0.28);
        }

        .btn-submit:hover {
            background-color: #008f70;
            box-shadow: 0 10px 25px rgba(0, 168, 132, 0.38);
            transform: translateY(-1px);
        }

        /* Alert Toast */
        .alert-toast-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.86rem;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Talentifyy Brand Logo Header -->
        <div class="brand-container">
            <img src="{{ asset('images/logo.png') }}" alt="Talentifyy" class="brand-logo-img">
            <h1 class="brand-title">Sign in to Talentifyy</h1>
            <p class="brand-subtitle">Enter your credentials to access your portal</p>
        </div>

        @if($errors->any())
            <div class="alert-toast-danger">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.15rem; color: #ef4444;"></i>
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
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="admin@admin.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input" required placeholder="••••••••">
                    <i class="fa-regular fa-eye toggle-pwd" id="toggleIcon" onclick="togglePwd()"></i>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Keep me signed in</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign in to Portal</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
        function togglePwd() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>
