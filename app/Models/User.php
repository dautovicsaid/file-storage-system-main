<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    const BYTES_INTO_MB_DIVIDER = 1048576;
    const BYTES_INTO_GB_DIVIDER = 1073741824;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function size_converted_from_bytes($size){
       if($size/self::BYTES_INTO_GB_DIVIDER>=1) {
           return round($size/self::BYTES_INTO_GB_DIVIDER,0);
       } else {
           return round($size/self::BYTES_INTO_MB_DIVIDER,3);
       }
    }

    public static function gb_converted_to_bytes($size) {
            return $size * self::BYTES_INTO_GB_DIVIDER;
    }
}
