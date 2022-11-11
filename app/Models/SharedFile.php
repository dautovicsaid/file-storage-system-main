<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedFile extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /**
     * Get the associated file
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Get the user who shared the file
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
