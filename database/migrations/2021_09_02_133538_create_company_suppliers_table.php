<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger ('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('trade_name');
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('address_line_one')->nullable(); 
            $table->string('address_line_two')->nullable(); 
            $table->string('postal_code')->nullable(); 
            $table->string('country_code');
            $table->foreign('country_code')->references('country_code')->on('countries');
            $table->string('telephone');
            $table->string('email');
            //$table->string('institutional_email')->unique();
            $table->string('webiste_url');
            $table->boolean('is_verified')->default(0);
            $table->bigInteger ('contacts_id');
            $table->foreign('contacts_id')->references('id')->on('contacts');
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
        Schema::dropIfExists('company_suppliers');
    }
}
