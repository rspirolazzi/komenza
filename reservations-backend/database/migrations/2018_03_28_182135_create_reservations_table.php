<?php

use App\Models\Admin\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReservationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $status = [
                Reservation::$STATUS_OPEN,
                Reservation::$STATUS_CANCELED,
                Reservation::$STATUS_ATTENDED,
                Reservation::$STATUS_NOT_ATTENDED,
                Reservation::$STATUS_NOT_STATUS,
                Reservation::$STATUS_MAXIMUM_CAPACITY,
            ];

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->unsignedInteger('company_id');
            $table->integer('people')->default(1);
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->longText('comment')->nullable();
            $table->longText('token')->nullable();
            $table->longText('admin_notes')->nullable();
            $table->enum('status', $status)->default(Reservation::$STATUS_OPEN);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->index('reservation_date');
            $table->index('reservation_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }
}
