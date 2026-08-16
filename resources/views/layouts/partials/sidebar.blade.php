<!-- Unified Master Sidebar Navigation for Talentifyy -->
<aside class="sidebar">
    <div class="sidebar-brand" style="display: flex; align-items: center; gap: 12px; padding: 20px 24px;">
        <img src="{{ asset('images/logo.png') }}" alt="Talentifyy" style="height: 38px; width: auto; object-fit: contain;">
        <div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #00a884; letter-spacing: -0.3px;">TALENTIFYY</div>
            <div style="font-size: 0.68rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Enterprise Portal</div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <!-- 1. My Personal Workspace (All Employees & Admin) -->
        <li class="menu-section-title">My Workspace</li>

        <li class="menu-item">
            <a href="{{ route('employee.dashboard') }}" class="menu-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house-laptop"></i>
                <span>My Dashboard & Clock-In</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('employee.attendance') }}" class="menu-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                <i class="fa-solid fa-user-clock"></i>
                <span>My Attendance Logbook</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('employee.leave.index') }}" class="menu-link {{ request()->routeIs('employee.leave.*') ? 'active' : '' }}">
                <i class="fa-solid fa-plane-departure"></i>
                <span>My Leave & Balances</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('employee.salary') }}" class="menu-link {{ request()->routeIs('employee.salary*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check-dollar"></i>
                <span>My Salary & Payslips</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('employee.documents') }}" class="menu-link {{ request()->routeIs('employee.documents*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-open"></i>
                <span>My Official Documents</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('notifications.index') }}" class="menu-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>

        @if(auth()->user()->hasRole('bda-team-lead') || auth()->user()->hasRole('bda') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->department === 'BDA')
        <li class="menu-item">
            <a href="{{ route('bda.work.index') }}" class="menu-link {{ request()->routeIs('bda.work.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check" style="color: #00a884;"></i>
                <span>BDA Daily Targets</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasRole('ta-team-lead') || auth()->user()->hasRole('talent-acquisition') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->department === 'Talent')
        <li class="menu-item">
            <a href="{{ route('ta.work.index') }}" class="menu-link {{ request()->routeIs('ta.work.*') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase" style="color: #00a884;"></i>
                <span>TA Job Requisitions</span>
            </a>
        </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('admin.announcements.index') }}" class="menu-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Company Notices</span>
            </a>
        </li>

        <!-- 2. HR & Candidate Recruitment Section -->
        @if(auth()->user()->hasPermission('candidates.view') || auth()->user()->hasRole('talent-acquisition') || auth()->user()->hasRole('ta-team-lead') || auth()->user()->hasRole('data-entry') || auth()->user()->hasRole('data-entry-team-lead') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('hr'))
        
        @if(auth()->user()->hasRole('talent-acquisition') || auth()->user()->hasRole('ta-team-lead') || auth()->user()->hasRole('data-entry') || auth()->user()->hasRole('data-entry-team-lead') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
        <li class="menu-item">
            <a href="{{ route('admin.candidates.index') }}" class="menu-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Candidates ATS</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasRole('hr') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
        <li class="menu-section-title">HR Management</li>

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
        @endif
        @endif

        <!-- 3. Finance & Leads Section -->
        @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
        <li class="menu-section-title">Finance & Sales</li>

        <li class="menu-item">
            <a href="{{ route('admin.finance.index') }}" class="menu-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                <span>Requirements & Leads</span>
            </a>
        </li>
        @endif

        <!-- 4. System & Access Control Section (HR / Admin only) -->
        @if(auth()->user()->hasRole('hr') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
            <li class="menu-section-title">User & Access Control</li>

            <li class="menu-item">
                <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>System Users</span>
                </a>
            </li>

            <!-- <li class="menu-item">
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
            </li> -->
        @endif

        @if(auth()->user()->hasRole('super-admin'))
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Admin Dashboard Overview</span>
                </a>
            </li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->roles->first()->name ?? 'Employee' }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn" title="Sign Out">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </div>
</aside>
