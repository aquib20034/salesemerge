<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->text('order_note')->nullable();

            //  Pickup Columns //
            $table->date('pickup_date')->nullable();

            $table->integer('pickup_address_id')->nullable();
            $table->text('pickup_address')->nullable();

            $table->integer('pickup_timeslot_id')->nullable();
            $table->text('pickup_timeslot')->nullable();
            
            $table->integer('pickup_rider_id')->nullable();
            $table->string('pickup_rider')->nullable();

            // Delivery Columns //
            $table->date('delivery_date')->nullable();

            $table->integer('delivery_address_id')->nullable();
            $table->text('delivery_address')->nullable();

            $table->integer('delivery_timeslot_id')->nullable();
            $table->text('delivery_timeslot')->nullable();

            $table->integer('delivery_rider_id')->nullable();
            $table->string('delivery_rider')->nullable();

            $table->tinyInteger('tags_printed')->nullable();

            $table->foreignId('status_id');
            $table->foreign('status_id')->references('id')->on('statuses');
            
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
