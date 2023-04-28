<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerHasTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_has_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->string('payment_detail')->nullable();
            $table->double('credit')->nullable();
            $table->double('debit')->nullable();
            $table->unsignedInteger('sell_id')->nullable();

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
        Schema::dropIfExists('customer_has_transactions');
    }
}
