<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    public $table = 'branches';

    protected $fillable = [
        'name',
        'company_id',
        'mobile_no',
        'phone_no',
        'address',
        'active',
        'created_by',
        'updated_by',
        'deleted_at'
    ];
}
