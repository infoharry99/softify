<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'first_login_at',
        'last_logout_at',
        'total_working_minutes',
        'total_break_minutes',
        'effective_working_minutes',
        'late_minutes',
        'early_logout_minutes',
        'is_admin_adjusted',
        'admin_remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'first_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
        'is_admin_adjusted' => 'boolean',
    ];

    protected $appends = [
        'clock_in',
        'clock_out',
    ];

    public function getClockInAttribute()
    {
        return $this->first_login_at;
    }

    public function getClockOutAttribute()
    {
        return $this->last_logout_at;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function sessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }
}
