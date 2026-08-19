<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminLeaveController;
use App\Http\Controllers\Admin\AdminPayrollController;
use App\Http\Controllers\Admin\AdminDocumentController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\EmployeeAttendanceController;
use App\Http\Controllers\Employee\EmployeeLeaveController;
use App\Http\Controllers\Employee\EmployeeSalaryController;
use App\Http\Controllers\Employee\EmployeeDocumentController;
use App\Http\Controllers\Employee\NotificationController;
use App\Http\Controllers\BdaWorkController;
use App\Http\Controllers\TaWorkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->hasRole('super-admin')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('employee.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/home', function () {
    if (Auth::check()) {
        return Auth::user()->hasRole('super-admin')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('employee.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notifications API / Bell
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    // BDA Daily Work Assignment & KPI Management
    Route::get('/bda/work', [BdaWorkController::class, 'index'])->name('bda.work.index');
    Route::post('/bda/work', [BdaWorkController::class, 'store'])->name('bda.work.store');
    Route::get('/bda/work/{task}', [BdaWorkController::class, 'show'])->name('bda.work.show');
    Route::put('/bda/work/{task}/update-task', [BdaWorkController::class, 'updateTask'])->name('bda.work.update_task');
    Route::post('/bda/work/{task}/update-employee', [BdaWorkController::class, 'updateEmployee'])->name('bda.work.update_employee');
    Route::post('/bda/work/{task}/update-lead', [BdaWorkController::class, 'updateLead'])->name('bda.work.update_lead');
    Route::delete('/bda/work/{task}', [BdaWorkController::class, 'destroy'])->name('bda.work.destroy');

    // Talent Acquisition (TA) Work Assignment Management
    Route::get('/ta/work', [TaWorkController::class, 'index'])->name('ta.work.index');
    Route::post('/ta/work', [TaWorkController::class, 'store'])->name('ta.work.store');
    Route::get('/ta/work/{task}', [TaWorkController::class, 'show'])->name('ta.work.show');
    Route::put('/ta/work/{task}/update-task', [TaWorkController::class, 'updateTask'])->name('ta.work.update_task');
    Route::post('/ta/work/{task}/update-employee', [TaWorkController::class, 'updateEmployee'])->name('ta.work.update_employee');
    Route::post('/ta/work/{task}/update-lead', [TaWorkController::class, 'updateLead'])->name('ta.work.update_lead');
    Route::delete('/ta/work/{task}', [TaWorkController::class, 'destroy'])->name('ta.work.destroy');

    // ==========================================
    // ADMIN PANEL ROUTES
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // User Self Profile & Password
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

        // Admin Dashboard
        Route::middleware('permission:dashboard.view')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });

        // User Management (Static routes MUST come before wildcard {user})
        Route::middleware('permission:users.create,hr.create')->group(function () {
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
        });

        Route::middleware('permission:users.view,hr.view')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
        });

        Route::middleware('permission:users.edit,hr.edit')->group(function () {
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
        });

        Route::middleware('permission:users.activate,hr.edit')->group(function () {
            Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        Route::middleware('permission:users.change_password,hr.edit')->group(function () {
            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        });

        Route::middleware('permission:users.delete,hr.delete')->group(function () {
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // Role Management (Static routes MUST come before wildcard {role})
        Route::middleware('permission:roles.create,hr.create')->group(function () {
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        });

        Route::middleware('permission:roles.view,hr.view')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/roles/{role}/users', [RoleController::class, 'users'])->name('roles.users');
        });

        Route::middleware('permission:roles.edit,hr.edit')->group(function () {
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::post('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
        });

        Route::middleware('permission:roles.delete,hr.delete')->group(function () {
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        // Permission Management
        Route::middleware('permission:permissions.view,hr.view')->group(function () {
            Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        });

        Route::middleware('permission:permissions.create,hr.create')->group(function () {
            Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        });

        // Activity Logs
        Route::middleware('permission:activity_logs.view,hr.view')->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
        });

        // Candidate Management (ATS) - Granular Permission Control
        Route::middleware('permission:candidates.create')->group(function () {
            Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
            Route::get('/candidates/quick-create', [CandidateController::class, 'quickCreate'])->name('candidates.quick_create');
            Route::post('/candidates/check-duplicate', [CandidateController::class, 'checkDuplicate'])->name('candidates.check_duplicate');
            Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
        });

        Route::middleware('permission:candidates.view,candidates.create')->group(function () {
            Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
            Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
            Route::get('/candidates/{candidate}/resume', [CandidateController::class, 'downloadResume'])->name('candidates.resume');
            Route::get('/candidates/{candidate}/resume-preview', [CandidateController::class, 'previewResume'])->name('candidates.resume_preview');
        });

        Route::middleware('permission:candidates.edit')->group(function () {
            Route::get('/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('candidates.edit');
            Route::put('/candidates/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');
            Route::post('/candidates/{candidate}/status', [CandidateController::class, 'updateStatus'])->name('candidates.status');
            Route::post('/candidates/{candidate}/edited-resume', [CandidateController::class, 'uploadEditedResume'])->name('candidates.edited_resume');
        });

        Route::middleware('permission:candidates.delete')->group(function () {
            Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');
        });

        // Finance Management (Requirements & Leads)
        Route::middleware('permission:finance.create')->group(function () {
            Route::get('/finance/create', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'create'])->name('finance.create');
            Route::post('/finance', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'store'])->name('finance.store');
        });

        Route::middleware('permission:finance.view')->group(function () {
            Route::get('/finance', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'index'])->name('finance.index');
            Route::get('/finance/{finance}', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'show'])->name('finance.show');
        });

        Route::middleware('permission:finance.edit')->group(function () {
            Route::get('/finance/{finance}/edit', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'edit'])->name('finance.edit');
            Route::put('/finance/{finance}', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'update'])->name('finance.update');
        });

        Route::middleware('permission:finance.delete')->group(function () {
            Route::delete('/finance/{finance}', [\App\Http\Controllers\Admin\FinanceRequirementController::class, 'destroy'])->name('finance.destroy');
        });

        // Employee Management (Admin - Static routes MUST come before wildcard {employee})
        Route::middleware('permission:hr.view')->group(function () {
            Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/create', [AdminEmployeeController::class, 'create'])->name('employees.create');
            Route::post('/employees', [AdminEmployeeController::class, 'store'])->name('employees.store');
            Route::get('/employees/{employee}', [AdminEmployeeController::class, 'show'])->name('employees.show');
            Route::get('/employees/{employee}/edit', [AdminEmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/employees/{employee}', [AdminEmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [AdminEmployeeController::class, 'destroy'])->name('employees.destroy');

            // Documents Upload, Preview, Download & Delete
            Route::post('/employees/{employee}/documents', [AdminDocumentController::class, 'upload'])->name('employees.documents.upload');
            Route::get('/documents/{document}/preview', [AdminDocumentController::class, 'preview'])->name('documents.preview');
            Route::get('/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
            Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
        });

        // Attendance Management (Admin)
        Route::middleware('permission:hr.view')->group(function () {
            Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
            Route::get('/attendance/break-violations', [AdminAttendanceController::class, 'breakViolations'])->name('attendance.break_violations');
            Route::post('/attendance/{attendance}/adjust', [AdminAttendanceController::class, 'adjust'])->name('attendance.adjust');
        });

        // Leave Management (Admin)
        Route::middleware('permission:hr.view')->group(function () {
            Route::get('/leave', [AdminLeaveController::class, 'index'])->name('leave.index');
            Route::post('/leave/{application}/approve', [AdminLeaveController::class, 'approve'])->name('leave.approve');
            Route::post('/leave/{application}/reject', [AdminLeaveController::class, 'reject'])->name('leave.reject');
            Route::get('/leave/{application}/attachment', [AdminLeaveController::class, 'downloadAttachment'])->name('leave.attachment');
            Route::get('/leave/{application}/attachment-preview', [AdminLeaveController::class, 'previewAttachment'])->name('leave.attachment_preview');
            Route::post('/leave/balances/{employee}', [AdminLeaveController::class, 'updateBalances'])->name('leave.balances.update');
        });

        // Payroll Management (Admin & HR)
        Route::middleware('permission:finance.view,hr.view')->group(function () {
            Route::get('/payroll', [AdminPayrollController::class, 'index'])->name('payroll.index');
            Route::post('/payroll/process/{employee}', [AdminPayrollController::class, 'process'])->name('payroll.process');
            Route::put('/payroll/{payroll}', [AdminPayrollController::class, 'updatePayroll'])->name('payroll.update');
            Route::post('/payroll/structure/{employee}', [AdminPayrollController::class, 'updateStructure'])->name('payroll.structure');
            Route::get('/payroll/slip/{payroll}', [AdminPayrollController::class, 'slip'])->name('payroll.slip');
            Route::get('/payroll/download/{payroll}', [AdminPayrollController::class, 'download'])->name('payroll.download');
        });

        // Company Announcements (Admin & HR Only)
        Route::middleware('permission:hr.view')->group(function () {
            Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
            Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
            Route::put('/announcements/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
            Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
        });
    });

    // ==========================================
    // EMPLOYEE PANEL ROUTES
    // ==========================================
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        // Clock In / Clock Out & Break Work Session Actions
        Route::post('/clock-in', [EmployeeDashboardController::class, 'clockIn'])->name('clock_in');
        Route::post('/clock-out', [EmployeeDashboardController::class, 'clockOut'])->name('clock_out');
        Route::post('/break/start', [EmployeeDashboardController::class, 'startBreak'])->name('break.start');
        Route::post('/break/end', [EmployeeDashboardController::class, 'endBreak'])->name('break.end');

        // Employee Personal Views
        Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])->name('attendance');
        Route::get('/leave', [EmployeeLeaveController::class, 'index'])->name('leave.index');
        Route::post('/leave', [EmployeeLeaveController::class, 'store'])->name('leave.store');
        Route::get('/salary', [EmployeeSalaryController::class, 'index'])->name('salary');
        Route::get('/salary/slip/{payroll}', [EmployeeSalaryController::class, 'slip'])->name('salary.slip');
        Route::get('/salary/download/{payroll}', [EmployeeSalaryController::class, 'download'])->name('salary.download');
        Route::get('/documents', [EmployeeDocumentController::class, 'index'])->name('documents');
        Route::get('/documents/{document}/preview', [EmployeeDocumentController::class, 'preview'])->name('documents.preview');
        Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');
    });
});

// Dedicated Web Route for Shared Hosting Database Migration
Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        return '<div style="font-family: system-ui, sans-serif; padding: 40px; background: #e6f7f3; color: #00a884; font-size: 1.25rem; font-weight: 700; border-radius: 16px; border: 2px solid #9ee5d4; max-width: 600px; margin: 50px auto; text-align: center;">' .
               '✓ Success! Live Database Migrations executed successfully (bda_work_assignments, ta_work_assignments & all columns created).<br><br>' .
               '<a href="/admin/dashboard" style="background: #00a884; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 10px; font-size: 1rem; display: inline-block;">Go to Dashboard →</a>' .
               '</div>';
    } catch (\Exception $e) {
        return '<div style="color: #ef4444; font-family: sans-serif; padding: 30px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; max-width: 600px; margin: 50px auto;">' .
               '<strong>Error running migrations:</strong> ' . htmlspecialchars($e->getMessage()) .
               '</div>';
    }
});

// Utility Web Route to Run Database Migrations, Seeder & Clear Caching on Live Hosting Servers without Terminal Access
Route::get('/run-seeder', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => 'RoleAndPermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'EmployeeSystemSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'StudentSeeder', '--force' => true]);
        \App\Models\BdaWorkAssignment::doesntHave('assignee')->delete();
        \App\Models\TaWorkAssignment::doesntHave('assignee')->delete();
        \App\Models\LeaveApplication::doesntHave('employee.user')->delete();
        \App\Models\Role::whereIn('slug', ['support', 'data-entry-team-lead', 'sales', 'accountant'])->delete();
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        return '<div style="font-family: system-ui, sans-serif; padding: 40px; background: #e6f7f3; color: #00a884; font-size: 1.25rem; font-weight: 700; border-radius: 16px; border: 2px solid #9ee5d4; max-width: 600px; margin: 50px auto; text-align: center;">' .
               '✓ Success! Database Migrations & Seeders executed successfully.<br><br>' .
               '<a href="/admin/dashboard" style="background: #00a884; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 10px; font-size: 1rem; display: inline-block;">Go to Dashboard →</a>' .
               '</div>';
    } catch (\Exception $e) {
        return '<div style="color: #ef4444; font-family: sans-serif; padding: 30px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; max-width: 600px; margin: 50px auto;">' .
               '<strong>Error executing migrations/seeder:</strong> ' . htmlspecialchars($e->getMessage()) .
               '</div>';
    }
});
