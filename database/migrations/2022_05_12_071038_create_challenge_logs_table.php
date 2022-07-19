<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('participation_id')->nullable();
            $table->string('activity')->nullable();
            $table->timestamp('startDateTime')->nullable();
            $table->time('endTime')->nullable();
            $table->double('calories')->nullable();
            $table->string('device_name')->nullable();
            $table->string('athlete')->nullable();
            $table->bigInteger('activity_id')->nullable();
            $table->string('distance_travelled')->nullable();
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
        Schema::dropIfExists('challenge_logs');
    }
}
