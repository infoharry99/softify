<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'reporting_manager_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportingManager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager_id');
    }

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function joiningDetail()
    {
        return $this->hasOne(EmployeeJoiningDetail::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function attendanceBreaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class);
    }

    public function salaryHistories()
    {
        return $this->hasMany(SalaryHistory::class);
    }

    public function monthlyPayrolls()
    {
        return $this->hasMany(MonthlyPayroll::class);
    }
}
