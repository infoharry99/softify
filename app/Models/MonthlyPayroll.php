<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'working_days',
        'present_days',
        'paid_leave_days',
        'unpaid_leave_days',
        'absent_days',
        'late_deductions',
        'leave_deductions',
        'bonus_amount',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'payment_status',
        'payment_date',
        'processed_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
