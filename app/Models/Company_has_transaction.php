<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Company_has_transaction extends Model
{
    protected $fillable = [
        'company_id',
        'payment_method_id',
        'payment_detail',
        'credit',
        'debit',
        'purchase_id',
    ];
}
