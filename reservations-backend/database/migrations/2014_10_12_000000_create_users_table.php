<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $roles = [
                \App\Admin::$ROLE_STORE,
                \App\Admin::$ROLE_BRAND,
                \App\Admin::$ROLE_ADMIN,
            ];

            $table->increments('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->date('birthday')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email', 150)->unique();
            $table->string('password')->nullable();
            $table->enum('role', $roles)->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
