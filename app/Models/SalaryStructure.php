<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'hra',
        'conveyance',
        'allowances',
        'bonus',
        'incentives',
        'pf_deduction',
        'esi_deduction',
        'pt_deduction',
        'tds_deduction',
        'other_deductions',
        'gross_salary',
        'net_salary',
        'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
