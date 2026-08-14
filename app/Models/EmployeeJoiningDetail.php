<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeJoiningDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'joining_date',
        'employment_type',
        'employment_status',
        'probation_end_date',
        'confirmation_date',
        'notice_period_days',
        'work_location',
        'remarks',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'probation_end_date' => 'date',
        'confirmation_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
