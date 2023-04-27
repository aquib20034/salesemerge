<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'owner_name',
        'contact_no',
        'address',
        'created_by',
        'updated_by',
        
    ];
}
