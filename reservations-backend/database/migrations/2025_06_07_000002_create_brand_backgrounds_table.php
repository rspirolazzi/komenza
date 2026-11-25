<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandBackgroundsTable extends Migration
{
    public function up()
    {
        Schema::create('brand_backgrounds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->string('image');
            $table->timestamps();            
        });
        Schema::table('brand_backgrounds', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_backgrounds');
    }
}
