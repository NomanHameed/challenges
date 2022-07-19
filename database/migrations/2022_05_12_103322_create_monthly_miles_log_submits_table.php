<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyMilesLogSubmitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_miles_log_submits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('milestone_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('submit_status')->nullable();
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
        Schema::dropIfExists('monthly_miles_log_submits');
    }
}
