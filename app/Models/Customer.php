<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 
        'customer_type_id', 
        'contact_no', 
        'city_id', 
        'address',
        'created_by',
        'updated_by',
    ];
}
