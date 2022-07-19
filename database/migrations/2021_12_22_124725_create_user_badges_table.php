<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('challenge_id')->nullable();
            $table->bigInteger('badge_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('badge_type')->nullable();
            $table->string('badge_status')->nullable();
            $table->dateTime('assign_date')->nullable();
            $table->integer('status')->nullable();
            $table->tinyInteger('is_seen')->nullable();
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
        Schema::dropIfExists('user_badges');
    }
}
