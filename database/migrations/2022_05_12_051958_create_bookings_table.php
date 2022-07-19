<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('provider_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('service_charge')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->string('note')->nullable();
            $table->integer('status')->nullable();
            $table->integer('payout_status')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
