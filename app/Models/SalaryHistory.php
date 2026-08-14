<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'previous_net_salary',
        'new_net_salary',
        'salary_components',
        'effective_date',
        'reason',
        'updated_by',
        'remarks',
    ];

    protected $casts = [
        'salary_components' => 'array',
        'effective_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
