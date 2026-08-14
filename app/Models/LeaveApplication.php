<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'total_days',
        'is_half_day',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'admin_remark',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'is_half_day' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
