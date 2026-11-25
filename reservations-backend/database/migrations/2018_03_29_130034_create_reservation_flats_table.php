<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReservationFlatsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_flats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('email');
            $table->date('birthday');
            $table->string('mobile');
            $table->string('store')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->integer('people');
            $table->date('reservation_date');
            $table->string('reservation_time');
            $table->longText('token')->nullable();
            $table->datetime('reservation');
            $table->longText('comment')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservation_flats');
    }
}
