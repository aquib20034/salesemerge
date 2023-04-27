<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Sell_has_item extends Model
{
    protected $fillable = [
       'sell_id',
       'item_id',
       'unit_id',
       'sell_qty',
       'unit_piece',
       'tot_piece',
       'sell_price',
       'tot_price',
    ];
}
