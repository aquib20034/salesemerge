<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('owner_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->text('add_info')->nullable();
            $table->boolean('active')->default(1)->nullable()->comment('null = inactive and 1 = active');
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
