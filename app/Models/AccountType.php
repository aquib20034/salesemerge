<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    public $table   = 'account_types';

    protected $fillable = [
        'name',
        'parent_id',
        'company_id',
        'branch_id',
        'active'
    ];

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    
    public function parentTypes()
    {
        return $this->belongsTo(AccountType::class, 'parent_id');
    }

    public function getAllParentTypes()
    {
        $type           = $this;
        $parentTypes    = collect();

        while ($type->parentTypes) {
            $parentTypes->push($type->parentTypes);
            $type       = $type->parentTypes;
        }

        $formattedTypes = $parentTypes->pluck('name')->implode('  >  ');
        return $formattedTypes;
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
