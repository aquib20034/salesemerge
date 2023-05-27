<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
      use HasRoles;

    public $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_no',
        'description',
        'logo',
        'company_id',
        'branch_id',
        'add_info',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function getLogoAttribute($value)
    {
        return ( isset($value) && (file_exists( public_path('uploads/users/'.$value) ))) 
            ? asset('uploads/users/'.$value)
            : asset('uploads/logo_no_image.png');
    }
}
