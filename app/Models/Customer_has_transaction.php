<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Customer_has_transaction extends Model
{
    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'payment_detail',
        'credit',
        'debit',
        'sell_id'
    ];
}
