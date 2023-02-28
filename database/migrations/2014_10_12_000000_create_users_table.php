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
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable(); //???
            $table->bigInteger('country_id')->unsigned()->nullable();
           
            $table->string('city');
            $table->string('street');
            $table->string('state')->nullable();
            $table->string('addressline_one')->nullable(); // add this
            $table->string('addressline_two')->nullable(); // add this
            $table->string('postal_code');
            $table->string('country_code')->nullable();
            $table->string('prefixes')->nullable();
            $table->string('position')->nullable();
            $table->string('avatar_path')->nullable(); //???

            $table->bigInteger('telephone');
            $table->string('fax')->nullable();
            $table->string('user_name')->unique ('user_name');
            $table->string('website_url')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('business_address'); // add this
            $table->timestamp('email_verified_at')->nullable();
            $table->Integer('upload_cv_id')->nullable(); // add this
            $table->rememberToken();
            $table->timestamps();



            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('country_code')->references('country_code')->on('countries');
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
