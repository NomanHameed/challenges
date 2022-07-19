<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_job_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('command_name')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('offset')->nullable();
            $table->bigInteger('block_size')->nullable();
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
        Schema::dropIfExists('cron_job_checks');
    }
}
