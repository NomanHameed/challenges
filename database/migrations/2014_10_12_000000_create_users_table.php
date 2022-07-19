<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('mobile_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('is_email_verified')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('user_type')->nullable();
            $table->integer('status')->nullable();
            $table->string('device_token')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('profile_marker_pic')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook')->nullable();            
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
