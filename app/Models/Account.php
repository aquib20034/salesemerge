<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'detail',
        'account_type_id',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
        'active'
    ];

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function account_type()
    {
        return $this->belongsTo(Account_type::class, 'account_type_id', 'id');
    }

    public function ledger()
    {
        return $this->hasMany(Ledger::class, 'id', 'account_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function getActiveAttribute($value)
    {
        return ($value == 1) ? "Active" : "Inactive";
    }
}
