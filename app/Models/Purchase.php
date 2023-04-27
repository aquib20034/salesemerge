<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Purchase extends Model
{
    protected $fillable = [
      'order_no', 
      'company_id',
      'invoice_date',
      'purchase_date',
      'total_amount',
      'bilty_amount',
      'net_amount',
      'pay_amount',
      'created_by',
      'updated_by',
    ];
}
