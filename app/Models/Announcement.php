<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'audience',
        'target_payload',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'target_payload' => 'array',
        'published_at' => 'datetime',
    ];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
