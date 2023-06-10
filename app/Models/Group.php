<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'company_id',
        'branch_id',
        'active'
    ];

    public function parentGroups()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    public function getAllParentGroups()
    {
        $group = $this;
        $parentGroups = collect();

        while ($group->parentGroups) {
            $parentGroups->push($group->parentGroups);
            $group = $group->parentGroups;
        }

        $formattedGroups = $parentGroups->pluck('name')->implode('  >  ');
        // $formattedGroups = $parentGroups->reverse()->pluck('name')->implode(' > ');

        return $formattedGroups;
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
    
    public function parent() // get parent group
    {
        return $this->belongsTo(Group::class, 'parent_id', 'id');
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
