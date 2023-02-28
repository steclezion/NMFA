<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_compositions', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('composition_name');
            $table->bigInteger('medical_product_id');
            $table->foreign('medical_product_id')->references('id')->on('medicinal_products');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('quantity');
            $table->string('reason');
            $table->string('reference_standard');
            $table->string('type')->comments('Api,Excipients,Solvents');
           
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
        Schema::dropIfExists('product_compositions');
    }
}
