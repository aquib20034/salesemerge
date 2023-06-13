<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common')->create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->string('title');
            $table->text('description');
            $table->boolean('priority')->default(0)->nullable()->comment('0 = low and 1 = medium and 2 = high');
            $table->boolean('status')->default(0)->nullable()->comment('0 = open and 1 = in_progress and 2 = resloved and 3 = closed');
            $table->json('assign_to')->nullable(); // JSON column to store multiple users
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
        Schema::connection('common')->dropIfExists('tickets');
    }
}
