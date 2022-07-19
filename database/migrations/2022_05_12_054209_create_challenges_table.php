<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->binary('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('event_start_date')->nullable();
            $table->timestamp('event_end_date')->nullable();
            $table->timestamp('reg_start_date')->nullable();
            $table->timestamp('reg_end_date')->nullable();
            $table->text('display_name')->nullable();
            $table->string('price_type')->nullable();
            $table->integer('status')->nullable();
            $table->integer('challenge_status')->nullable();
            $table->integer('allowed_participants')->nullable();
            $table->string('image')->nullable();
            $table->string('challenge_details_page_pic')->nullable();
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
        Schema::dropIfExists('challenges');
    }
}
