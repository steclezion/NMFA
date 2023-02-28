<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizedCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *///Nmfa approved medicines and 
    public function up()
    {
        Schema::create('authorized_countries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('medical_product_id');
            $table->foreign('medical_product_id')->references('id')->on('medicinal_products');
            $table->bigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('company_suppliers');
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->boolean ('is_active');
            $table->date ('authorized_date')->nullable ();
            $table->date ('deauthorized_date')->nullable ();
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
        Schema::dropIfExists('authorized_countries');
    }
}
