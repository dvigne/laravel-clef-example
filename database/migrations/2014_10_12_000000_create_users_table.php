<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
          $table->uuid('id');
          $table->bigInteger('clef_id')->nullable();
          $table->string('first');
          $table->string('last');
          $table->string('email')->unique();
          $table->string('password');
          $table->dateTime('logged_out_at')->nullable();
          $table->rememberToken();
          $table->softDeletes();
          $table->timestamps();
          $table->primary('id');
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
