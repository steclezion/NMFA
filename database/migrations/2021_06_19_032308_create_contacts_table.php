<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('first_name') ;// add this
            $table->string('middle_name')->nullable(); // add this
            $table->string('last_name'); // add this
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('position');  //
            $table->string('city')->nullable();
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('telephone');
            $table->string('fax')->nullable();
            //$table->string('webiste_url')->nullable();
            $table->string('email');
            $table->string('contact_type')->comment ('either supplier or agent');
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
        Schema::dropIfExists('contacts');
    }
}
