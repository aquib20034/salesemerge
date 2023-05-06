<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Company extends Model
{
    use SoftDeletes;

    public $table = 'companies';

    protected $fillable = [
        'name',
        'owner_name',
        'contact_no',
        'address',
        'created_by',
        'updated_by',
        'deleted_at'
    ];
}
