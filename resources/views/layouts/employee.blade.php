<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Panel') - Talentifyy</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #00a884;
            --primary-hover: #008f70;
            --primary-light: #e6f7f3;
            --primary-border: #9ee5d4;
            --secondary: #64748b;
            --sidebar-bg: #ffffff;
            --sidebar-border: #f1f5f9;
            --sidebar-text: #64748b;
            --sidebar-hover: #f0faf7;
            --sidebar-active-bg: #00a884;
            --sidebar-active-text: #ffffff;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success: #10b981;
            --success-light: #ecfdf5;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --radius: 14px;
            --shadow: 0 4px 12px rgba(15, 23, 42, 0.03), 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--body-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles - Matching Screenshots */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-border);
            background-color: #ffffff;
        }

        .brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
        }

        .brand-title span { color: var(--primary); }

        .brand-pill {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            background-color: var(--primary-light);
            color: var(--primary);
            padding: 3px 8px;
            border-radius: 9999px;
            border: 1px solid var(--primary-border);
        }

        .sidebar-menu {
            list-style: none;
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            padding: 14px 12px 6px;
        }

        .menu-item { margin-bottom: 4px; }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .menu-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            color: #64748b;
            transition: color 0.15s ease;
        }

        .menu-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .menu-link:hover i {
            color: var(--primary);
        }

        /* Active Navigation Item (Vibrant Royal Blue Pill from Screenshot 1 & 2) */
        .menu-link.active {
            background-color: var(--primary) !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .menu-link.active i {
            color: #ffffff !important;
        }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--sidebar-border);
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid var(--primary-border);
        }

        .user-info { margin-left: 10px; flex: 1; min-width: 0; }
        .user-name { color: #0f172a; font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.73rem; color: var(--text-muted); }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            height: 64px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        }

        .topbar-title { font-size: 1.15rem; font-weight: 700; color: #0f172a; letter-spacing: -0.2px; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .content-body { padding: 25px; flex: 1; }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .card-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; }
        .card-body { padding: 24px; }

        /* Buttons matching Screenshots */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-primary { background-color: var(--primary); color: #ffffff; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: #ffffff; border-color: var(--border-color); color: var(--text-main); }
        .btn-secondary:hover { background-color: #f1f5f9; border-color: #cbd5e1; }
        .btn-danger { background-color: var(--danger); color: #ffffff; }
        .btn-danger:hover { background-color: #dc2626; }
        .btn-warning { background-color: var(--warning); color: #ffffff; }
        .btn-sm { padding: 6px 14px; font-size: 0.82rem; }

        /* Metric Summary Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--primary);
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* Toast Alert Cards */
        .toast-alert {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 22px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #1e293b;
        }

        .toast-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .toast-success-circle { background-color: #ecfdf5; color: #10b981; border: 2px solid #a7f3d0; }
        .toast-danger-circle { background-color: #fef2f2; color: #ef4444; border: 2px solid #fecaca; }
        .toast-warning-circle { background-color: #fffbeb; color: #f59e0b; border: 2px solid #fde68a; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background-color: var(--success-light); color: #065f46; border: 1px solid #a7f3d0; }
        .badge-danger { background-color: var(--danger-light); color: #991b1b; border: 1px solid #fecaca; }
        .badge-warning { background-color: var(--warning-light); color: #92400e; border: 1px solid #fde68a; }
        .badge-primary { background-color: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
        .badge-secondary { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; }
        .table th { background-color: #f8fafc; color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
        .table td { padding: 14px 18px; border-bottom: 1px solid var(--border-color); color: var(--text-main); vertical-align: middle; }

        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #bfdbfe;
            flex-shrink: 0;
        }

        .btn-table-action {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 7px 16px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .btn-table-action:hover { background-color: #1d4ed8; }

        /* Permission Module Cards & Grid Layout */
        .permission-module-box {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
        }

        .permission-module-header {
            background-color: #f8fafc;
            padding: 12px 20px;
            font-weight: 700;
            font-size: 0.92rem;
            color: #0f172a;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .permission-grid {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 14px;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.88rem;
            color: var(--text-main);
            cursor: pointer;
            user-select: none;
            padding: 10px 14px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .checkbox-label:hover {
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; color: #334155; }
        .form-control { width: 100%; padding: 10px 14px; font-size: 0.9rem; border: 1px solid var(--border-color); border-radius: 10px; outline: none; transition: all 0.15s ease; background: #ffffff; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Global Logo Preloader -->
    @include('layouts.partials.preloader')

    <!-- Sidebar Navigation for Employee Panel -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <h1 class="topbar-title">@yield('page_title', 'Employee Portal')</h1>

            <div class="topbar-actions">
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-bell"></i> Notifications
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="content-body">
            <!-- Toast Alert Box -->
            @if(session('success'))
                <div class="toast-alert">
                    <div class="toast-icon-circle toast-success-circle">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-alert">
                    <div class="toast-icon-circle toast-danger-circle">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="toast-alert">
                    <div class="toast-icon-circle toast-warning-circle">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>{{ session('warning') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function swalConfirm(title, text, confirmText, confirmColor, callback) {
            Swal.fire({
                title: title || 'Are you sure?',
                text: text || '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor || '#00a884',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText || 'Yes, proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        }

        function confirmSwalAction(event, form, title, text, confirmBtnText, confirmBtnColor, icon) {
            if (event && typeof event.preventDefault === 'function') {
                event.preventDefault();
            }

            var formElement = typeof form === 'string' ? document.getElementById(form) : form;

            Swal.fire({
                title: title || 'Are you sure?',
                text: text || 'Please confirm this action.',
                icon: icon || 'question',
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor || '#00a884',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtnText || 'Yes, Proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed && formElement) {
                    formElement.submit();
                }
            });
            return false;
        }

        function confirmSwalDelete(arg1, arg2, arg3, arg4) {
            var formElement = null;
            var titleText = 'Confirm Deletion';
            var messageText = 'This action cannot be undone!';

            if (arg1 && typeof arg1.preventDefault === 'function') {
                arg1.preventDefault();
                formElement = arg2;
                titleText = arg3 || titleText;
                messageText = arg4 || messageText;
            } else {
                formElement = arg1;
                titleText = arg2 || titleText;
                messageText = arg3 || messageText;
            }

            if (typeof formElement === 'string') {
                formElement = document.getElementById(formElement);
            }

            Swal.fire({
                title: titleText,
                text: messageText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed && formElement) {
                    formElement.submit();
                }
            });
            return false;
        }
    </script>

    @yield('scripts')
</body>
</html>
