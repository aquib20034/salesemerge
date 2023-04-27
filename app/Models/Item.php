<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    protected $fillable = [
       'name',
       'company_id',
       'unit_id',
       'tot_piece',
       'free_piece',
       'purchase_price',
       'sell_price',
       'unit_sell_price',
       'company_percentage',
       'to_percentage',
       'created_by',
       'updated_by',
    ];
}
