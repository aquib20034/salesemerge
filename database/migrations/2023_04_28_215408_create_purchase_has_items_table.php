<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseHasItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_has_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('item_piece')->nullable();
            $table->unsignedInteger('purchase_qty')->nullable();
            $table->unsignedInteger('purchase_price')->nullable();
            $table->unsignedInteger('sell_price')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_has_items');
    }
}
