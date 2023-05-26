<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'mobile_no',
        'phone_no',
        'address',
        'active'
        
    ];
}
