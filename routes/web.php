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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

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
        Route::middleware('permission:users.create')->group(function () {
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
        });

        Route::middleware('permission:users.view')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
        });

        Route::middleware('permission:users.edit')->group(function () {
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
        });

        Route::middleware('permission:users.activate')->group(function () {
            Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        Route::middleware('permission:users.change_password')->group(function () {
            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        });

        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // Role Management (Static routes MUST come before wildcard {role})
        Route::middleware('permission:roles.create')->group(function () {
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        });

        Route::middleware('permission:roles.view')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/roles/{role}/users', [RoleController::class, 'users'])->name('roles.users');
        });

        Route::middleware('permission:roles.edit')->group(function () {
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::post('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
        });

        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        // Permission Management
        Route::middleware('permission:permissions.view')->group(function () {
            Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        });

        Route::middleware('permission:permissions.create')->group(function () {
            Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        });

        // Activity Logs
        Route::middleware('permission:activity_logs.view')->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
        });

        // Candidate Management (HR ATS) - Granular Permission Control
        Route::middleware('permission:hr.create')->group(function () {
            Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
            Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
        });

        Route::middleware('permission:hr.view')->group(function () {
            Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
            Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
            Route::get('/candidates/{candidate}/resume', [CandidateController::class, 'downloadResume'])->name('candidates.resume');
        });

        Route::middleware('permission:hr.edit')->group(function () {
            Route::get('/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('candidates.edit');
            Route::put('/candidates/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');
            Route::post('/candidates/{candidate}/status', [CandidateController::class, 'updateStatus'])->name('candidates.status');
        });

        Route::middleware('permission:hr.delete')->group(function () {
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

            // Documents Upload
            Route::post('/employees/{employee}/documents', [AdminDocumentController::class, 'upload'])->name('employees.documents.upload');
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
        });

        // Payroll Management (Admin)
        Route::middleware('permission:finance.view')->group(function () {
            Route::get('/payroll', [AdminPayrollController::class, 'index'])->name('payroll.index');
            Route::post('/payroll/process/{employee}', [AdminPayrollController::class, 'process'])->name('payroll.process');
            Route::post('/payroll/structure/{employee}', [AdminPayrollController::class, 'updateStructure'])->name('payroll.structure');
            Route::get('/payroll/slip/{payroll}', [AdminPayrollController::class, 'slip'])->name('payroll.slip');
        });

        // Company Announcements (Admin)
        Route::middleware('permission:dashboard.view')->group(function () {
            Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
            Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
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
        Route::get('/documents', [EmployeeDocumentController::class, 'index'])->name('documents');
        Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');
    });
});
