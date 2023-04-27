<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Sell extends Model
{
    protected $fillable = [
      'order_no', 
      'customer_id',
      'invoice_date',
      'sell_date',
      'total_amount',
      'pay_amount',
      'created_by',
      'updated_by',
    ];
}
