<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'transaction_type_id',
        'transaction_date',
        'method',
        'detail',
    ];

    public function ledger()
{
    return $this->hasOne(Ledger::class, 'transaction_id', 'id');
}


    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
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
