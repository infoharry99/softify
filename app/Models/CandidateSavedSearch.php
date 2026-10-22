<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateSavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'search_query',
        'boolean_enabled',
        'filters',
        'results_count',
    ];

    protected $casts = [
        'boolean_enabled' => 'boolean',
        'filters' => 'array',
        'results_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
