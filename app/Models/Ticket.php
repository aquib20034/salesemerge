<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'company_id',
        'user_id',
        'category_id',
        'title',
        'description',
        'status',
        'priority',
        'assign_to',
    ];

    protected $casts = [
        'assign_to' => 'array', // Cast the assign_to column as an array
    ];

    // Define array for priority and status mappings
    protected $valueMaps = [
        'priority' => [
            0 => 'low',
            1 => 'medium',
            2 => 'high',
        ],
        'status' => [
            0 => 'open',
            1 => 'in_progress',
            2 => 'resolved',
            3 => 'closed',
        ],
    ];

    // Mutator for "priority" attribute
    public function setPriorityAttribute($value)
    {
        $this->attributes['priority'] = array_search($value, $this->valueMaps['priority']) !== false ? $value : null;
    }

    // Accessor for "priority" attribute
    public function getPriorityAttribute($value)
    {
        return $this->valueMaps['priority'][$value] ?? null;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = array_search($value, $this->valueMaps['status']) !== false ? $value : null;
    }

    public function getStatusAttribute($value)
    {
        return $this->valueMaps['status'][$value] ?? null;
    }


    // Define the relationships
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
//
//    public function category()
//    {
//        return $this->belongsTo(Category::class);
//    }
//
//    public function conversations()
//    {
//        return $this->hasMany(Conversation::class);
//    }
}
