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
        'schedule_items',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'schedule_items' => 'array',
    ];

    public function getEffectiveScheduleItemsAttribute()
    {
        if (!empty($this->schedule_items) && is_array($this->schedule_items) && count($this->schedule_items) > 0) {
            return $this->schedule_items;
        }

        return [
            ['time_slot' => '10:00 - 10:15', 'activity' => 'Morning Meeting & Target Assignment', 'objective' => 'Daily plan review'],
            ['time_slot' => '10:15 - 11:30', 'activity' => 'Research new IT companies', 'objective' => '15 - 20 companies'],
            ['time_slot' => '11:30 - 12:30', 'activity' => 'Create database (HR, Email, Phone, LinkedIn)', 'objective' => 'Complete verified records'],
            ['time_slot' => '12:30 - 01:30', 'activity' => 'LinkedIn requests & Emails', 'objective' => '20 - 30 quality requests'],
            ['time_slot' => '01:30 - 02:00', 'activity' => 'Lunch Break', 'objective' => '-'],
            ['time_slot' => '02:00 - 04:30', 'activity' => 'Cold Calling HR / Hiring Managers', 'objective' => '25 - 35 calls'],
            ['time_slot' => '04:30 - 05:30', 'activity' => 'Follow-ups', 'objective' => '10 - 15 follow-ups'],
            ['time_slot' => '05:30 - 06:30', 'activity' => 'Client discussion & Meeting booking', 'objective' => '2 - 3 meetings'],
            ['time_slot' => '06:30 - 07:00', 'activity' => 'CRM Update & Daily Report Submission', 'objective' => '100% updated'],
        ];
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
