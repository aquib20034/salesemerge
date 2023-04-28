<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_no')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('sell_date')->nullable();
            $table->double('total_amount')->nullable();
            $table->float('bilty_amount')->nullable();
            $table->double('net_amount')->nullable();
            $table->double('pay_amount')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
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
        Schema::dropIfExists('sells');
    }
}
