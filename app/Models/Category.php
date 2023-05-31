<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'company_id',
        'branch_id',
        'active'
    ];

    public function parentCategories()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getAllParentCategories()
    {
        $category = $this;
        $parentCategories = collect();

        while ($category->parentCategories) {
            $parentCategories->push($category->parentCategories);
            $category = $category->parentCategories;
        }

        $formattedCategories = $parentCategories->pluck('name')->implode('  >  ');
        // $formattedCategories = $parentCategories->reverse()->pluck('name')->implode(' > ');

        return $formattedCategories;
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
    
    public function parent() // get parent category
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
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
