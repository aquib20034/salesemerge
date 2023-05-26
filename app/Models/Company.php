<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'owner_name',
        'mobile_no',
        'phone_no',
        'address',
        'image',
        'active'
        
    ];

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getOwnerNameAttribute($value)
    {
        return ucwords($value);
    }
}
