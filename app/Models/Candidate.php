<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'hr_id',
        'company_name',
        'name',
        'email',
        'phone',
        'location',
        'skills',
        'experience',
        'job_type',
        'notice_period',
        'current_ctc',
        'expected_ctc',
        'status',
        'resume',
        'note',
        'last_updated_by',
        'is_highlighted',
    ];

    protected $casts = [
        'experience' => 'float',
        'current_ctc' => 'decimal:2',
        'expected_ctc' => 'decimal:2',
        'is_highlighted' => 'boolean',
    ];

    public function hr()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}
