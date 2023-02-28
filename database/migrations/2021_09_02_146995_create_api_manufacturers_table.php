<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('manufacturer_name');
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('addressline_one')->nullable(); // add this
            $table->string('addressline_two')->nullable(); // add this
            $table->string('postal_code')->nullable();
            $table->string('telephone',110)->nullable();
            $table->string('unit')->nullable();
            $table->string('block')->nullable();
            $table->string('api_name')->nullable();
           
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
        Schema::dropIfExists('api_manufacturers');
    }
}
