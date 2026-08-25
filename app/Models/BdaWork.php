<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdaWork extends Model
{
    use HasFactory;

    protected $table = 'bda_works';

    protected $fillable = [
        'title',
        'description',
        'file_url',
        'file_path',
        'file_name',
        'uploaded_by',
    ];

    /**
     * Relationship: Get the user who uploaded this work.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Helper to get file extension.
     */
    public function getFileExtensionAttribute()
    {
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }
}
