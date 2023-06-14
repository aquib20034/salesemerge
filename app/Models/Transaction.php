<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'detail',
        'account_id',
        'transaction_date'
    ];

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'transaction_id', 'id');
    }

    public function getTransactionDateAttribute($value)
    {
        if($value){
            return Carbon::parse($value)->format('m-d-Y h:i:s A');
        }
    }

    public function getCreatedAtAttribute($value)
    {
        if($value){
            return Carbon::parse($value)->format('m-d-Y h:i:s A');
        }
    }
    
    public function getCreatedAtTime($timestamp)
    {
        if($timestamp){
            return Carbon::parse($timestamp)->format('h:i:s A');
        }
    }
}
