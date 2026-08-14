<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceRequirement extends Model
{
    use HasFactory;

    protected $table = 'finance_requirements';

    protected $fillable = [
        'created_by',
        'vendor_name',
        'vendor_location',
        'company_name',
        'selected_candidates_count',
        'budget',
        'date',
        'remaining_payment',
        'status',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'selected_candidates_count' => 'integer',
        'budget' => 'decimal:2',
        'remaining_payment' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
