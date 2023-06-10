<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $fillable = [
        'name',
        'account_id',
        'tranasction_id',
        'transaction_type',
        'amount'
    ];

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function acount()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function getAmountAttribute($value)
    {
        if($value){
            return number_format($value, 2, '.', '');
        }
    }
}
