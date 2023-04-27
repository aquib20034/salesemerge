<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Purchase_has_item extends Model
{
    protected $fillable = [
       'purchase_id',
       'item_id',
       'item_piece',
       'purchase_qty',
       'purchase_price',
       'sell_price',
    ];
}
