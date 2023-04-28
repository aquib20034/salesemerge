<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('tot_piece')->nullable();
            $table->unsignedInteger('free_piece')->nullable();
            $table->unsignedInteger('purchase_price')->nullable();
            $table->unsignedInteger('sell_price')->nullable();
            $table->unsignedInteger('unit_sell_price')->nullable();
            $table->unsignedInteger('company_percentage')->nullable();
            $table->unsignedInteger('to_percentage')->nullable();


            $table->unsignedInteger('merchant_id')->nullable();
            $table->unsignedInteger('station_id')->nullable();
            $table->boolean('active')->default(1)->nullable()->comment('null = inactive and 1 = active'); 
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
        Schema::dropIfExists('items');
    }
}
