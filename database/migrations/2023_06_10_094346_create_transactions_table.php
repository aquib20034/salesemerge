<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_type_id')->nullable();
            $table->unsignedInteger('custom_id')->nullable()->comment('custom trnx id'); 
            $table->unsignedInteger('reference_id')->nullable()->comment('against which trnx held'); 
            $table->unsignedInteger('account_id')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->string('method')->nullable();
            $table->string('cheque_no')->nullable();
            $table->text('detail')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
