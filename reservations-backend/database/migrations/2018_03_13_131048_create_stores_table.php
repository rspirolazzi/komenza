<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('brand_id')->unsigned();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('text_schedule')->nullable();
            $table->longText('delivery_times')->nullable();
            $table->integer('map_zoom')->nullable()->default(8);
            $table->integer('max_persons')->default(9);
            $table->integer('max_persons_day')->default(40);
            $table->unsignedInteger('company_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('brand_id')->references('id')->on('brands');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stores');
    }
}
