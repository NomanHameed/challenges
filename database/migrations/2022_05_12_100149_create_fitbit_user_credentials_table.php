<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitbitUserCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fitbit_user_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('token_type')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->longText('access_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('expires_in')->nullable();
            $table->text('scope')->nullable();
            $table->text('subscription')->nullable();
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
        Schema::dropIfExists('fitbit_user_credentials');
    }
}
