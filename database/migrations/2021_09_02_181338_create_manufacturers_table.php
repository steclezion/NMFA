<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->bigInteger ('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger ('product_id');
            $table->foreign('product_id')->references('id')->on('medicinal_products');
            $table->string('name');
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('city')->nullable ();
            $table->string('state')->nullable();
            $table->string('addressline_one')->nullable(); // add this
            $table->string('addressline_two')->nullable(); // add this
            $table->string('postal_code')->nullable();
            $table->unsignedInteger('telephone')->nullable();
            $table->string('country_code');
            $table->foreign('country_code')->references('country_code')->on('countries');
           // $table->string('webiste_url')->nullable ();
           // $table->string('email')->unique();
            $table->string('activity')->nullable ();
            $table->string('block')->nullable ();
            $table->string('unit')->nullable ();
            ;

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
        Schema::dropIfExists('manufacturers');
    }
}
