<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellHasItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_has_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sell_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('sell_qty')->nullable();
            $table->unsignedInteger('unit_piece')->nullable();
            $table->unsignedInteger('tot_piece')->nullable();
            $table->unsignedInteger('sell_price')->nullable();
            $table->unsignedInteger('tot_price')->nullable();
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
        Schema::dropIfExists('sell_has_items');
    }
}
