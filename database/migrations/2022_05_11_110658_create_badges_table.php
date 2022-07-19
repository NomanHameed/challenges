<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('badge_type')->nullable();
            $table->string('badge_condition')->nullable();
            $table->double('condition_limit')->nullable();
            $table->integer('specific_date_checkbox')->nullable();
            $table->timestamp('specific_date')->nullable();
            $table->text('badge_info')->nullable();
            $table->string('badge_logo')->nullable();
            $table->integer('is_seen')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('badges');
    }
}
