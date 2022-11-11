<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'path',
    ];

    protected $appends = ['is_folder'];

    public function shared_files(){
        $this->belongsTo(SharedFile::class);
    }
    public function getIsFolderAttribute()
    {
        return $this->extension == null;

    }
}
