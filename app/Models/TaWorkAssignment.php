<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaWorkAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_by',
        'assigned_to',
        'assigned_date',
        'job_title',
        'location',
        'experience',
        'budget',
        'duration',
        'job_description',
        'target_profiles',
        'profiles_sourced',
        'status',
        'employee_notes',
        'lead_notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
    ];

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
