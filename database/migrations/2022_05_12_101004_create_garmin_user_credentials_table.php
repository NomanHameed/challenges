<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarminUserCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garmin_user_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('token_type')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->longText('access_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('expires_in')->nullable();
            $table->text('identifier')->nullable();
            $table->text('secret')->nullable();
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
        Schema::dropIfExists('garmin_user_credentials');
    }
}
