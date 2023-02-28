<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanySupplierTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('company_supplier_template', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger ('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('trade_name')->unique();
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('state')->nullable();
            $table->string('country_code');
            $table->foreign('country_code')->references('country_code')->on('countries');
            $table->boolean('is_Approved_By_NMFA')->nullable();
            $table->string('is_Registerd_company')->nullable();
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
        //
    }
}
