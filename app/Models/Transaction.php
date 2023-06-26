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
        'company_id',
        'branch_id',
        'created_by'
    ];

    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'transaction_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }


    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
