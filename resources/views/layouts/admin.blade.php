<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - SalesTaletity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-border: #bfdbfe;
            --secondary: #64748b;
            --sidebar-bg: #ffffff;
            --sidebar-border: #f1f5f9;
            --sidebar-text: #64748b;
            --sidebar-hover: #f8fafc;
            --sidebar-active-bg: #2563eb;
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

        /* Card Container */
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

        /* Top Metric Summary Cards (Matching Screenshot 1 Row 1) */
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
        .stat-card-blue::before { background: #2563eb; }
        .stat-card-green::before { background: #10b981; }
        .stat-card-orange::before { background: #f59e0b; }
        .stat-card-red::before { background: #ef4444; }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .stat-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .stat-icon-blue { background: #eff6ff; color: #2563eb; }
        .stat-icon-green { background: #ecfdf5; color: #10b981; }
        .stat-icon-orange { background: #fffbeb; color: #f59e0b; }
        .stat-icon-red { background: #fef2f2; color: #ef4444; }

        .stat-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 9999px;
        }
        .stat-badge-danger { background: #fef2f2; color: #ef4444; }
        .stat-badge-success { background: #ecfdf5; color: #10b981; }
        .stat-badge-neutral { background: #f1f5f9; color: #64748b; }

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

        /* Status Mini Cards (Matching Screenshot 1 Row 2) */
        .status-row-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }
        .status-mini-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow);
        }
        .dot-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-pending { background-color: #f59e0b; }
        .dot-confirmed { background-color: #2563eb; }
        .dot-completed { background-color: #10b981; }
        .dot-cancelled { background-color: #ef4444; }
        .status-card-num { font-size: 1.2rem; font-weight: 800; color: #0f172a; }
        .status-card-lbl { font-size: 0.8rem; font-weight: 500; color: #64748b; }

        /* Toast Alert Cards (Matching Screenshot) */
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

        /* Badges matching Screenshots */
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

        /* Form Controls */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; color: #334155; }
        .form-control { width: 100%; padding: 10px 14px; font-size: 0.9rem; border: 1px solid var(--border-color); border-radius: 10px; outline: none; transition: all 0.15s ease; background: #ffffff; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; }

        /* Tables matching Screenshot 2 & 3 */
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

        /* Pagination Controls Styling matching Screenshot 2 & 3 */
        .pagination-footer {
            padding: 16px 24px;
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #64748b;
        }

        .pagination {
            display: inline-flex;
            list-style: none;
            gap: 4px;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .pagination .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: var(--text-muted);
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f8fafc;
        }

        .pagination .page-item .page-link:hover:not(.disabled) {
            background-color: #f1f5f9;
            border-color: var(--primary);
            color: var(--primary);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation - Matching Screenshots 1, 2, 3 Layout -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">Sales<span>Taletity</span></div>
            <div class="brand-pill">Admin</div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Admin Dashboard</span>
                </a>
            </li>

            <li class="menu-section-title">HR & Recruitment</li>

            <li class="menu-item">
                <a href="{{ route('admin.candidates.index') }}" class="menu-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Candidates ATS</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.finance.index') }}" class="menu-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Finance Management</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.employees.index') }}" class="menu-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Employees Directory</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.attendance.index') }}" class="menu-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Attendance & Breaks</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.leave.index') }}" class="menu-link {{ request()->routeIs('admin.leave.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-minus"></i>
                    <span>Leave Applications</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.payroll.index') }}" class="menu-link {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Payroll & Payslips</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.announcements.index') }}" class="menu-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Company Notices</span>
                </a>
            </li>

            <li class="menu-section-title">User & Access Control</li>

            <li class="menu-item">
                <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>System Users</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.roles.index') }}" class="menu-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Roles & Permissions</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.permissions.index') }}" class="menu-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-key"></i>
                    <span>Permissions Matrix</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.activity_logs.index') }}" class="menu-link {{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Audit Activity Logs</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->roles->pluck('name')->first() ?? 'Administrator' }}</div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <h1 class="topbar-title">@yield('page_title', 'Dashboard')</h1>

            <div class="topbar-actions">
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-bell"></i> Notifications
                </a>
                <a href="{{ route('admin.profile') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-user"></i> My Profile
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

    @yield('scripts')
</body>
</html>
