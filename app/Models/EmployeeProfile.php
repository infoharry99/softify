<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'dob',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
