<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Company extends Model
{
    use SoftDeletes;

    public $table = 'companies';

    protected $fillable = [
        'code',
        'name',
        'email',
        'owner_name',
        'mobile_no',
        'phone_no',
        'logo',
        'address',
        'add_info',
        'active',
        'created_by',
        'updated_by',
        'deleted_at'
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
