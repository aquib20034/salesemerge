<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'detail',
        'account_type_id',
        'group_head_id',
        'child_head_id',
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

    public function account_type_tree($row){
        $type_name  = "";
        $type_name  = (isset($row->child_head->name)) ? ($row->child_head->name) : "";
        $type_name .= (isset($row->group_head->name)) ? (" > " . ($row->group_head->name)) : "";
        $type_name .= (isset($row->account_type->name)) ? (" > " . ($row->account_type->name)) : "";

        return $type_name;

    }
    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id', 'id');
    }

    public function group_head()
    {
        return $this->belongsTo(AccountType::class, 'group_head_id', 'id');
    }

    public function child_head()
    {
        return $this->belongsTo(AccountType::class, 'child_head_id', 'id');
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'id', 'account_id');
    }

    // public function ledgers()
    // {
    //     return $this->hasMany(Ledger::class);
    // }

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
