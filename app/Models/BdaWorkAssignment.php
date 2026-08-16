<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdaWorkAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_by',
        'assigned_to',
        'assigned_date',
        'title',
        'status',
        'target_new_companies',
        'target_linkedin_requests',
        'target_emails',
        'target_cold_calls',
        'target_followups',
        'target_meetings',
        'achieved_new_companies',
        'achieved_linkedin_requests',
        'achieved_emails',
        'achieved_cold_calls',
        'achieved_followups',
        'achieved_meetings',
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
