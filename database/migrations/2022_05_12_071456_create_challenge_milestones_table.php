<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_milestones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('challenge_id')->nullable();
            $table->string('milestone_name')->nullable();
            $table->text('milestone_info')->nullable();
            $table->double('milestone_distance')->nullable();
            $table->string('milestone_type')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('specific_date_checkbox')->nullable();
            $table->dateTime('specific_date')->nullable();
            $table->string('milestone_pic')->nullable();
            $table->bigInteger('monthly_log_submit')->nullable();
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
        Schema::dropIfExists('challenge_milestones');
    }
}
