<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MedicinalProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicinal_products', function (Blueprint $table) {
            $table->id();
           // $table->bigInteger('medicine_id');
           // $table->foreign('medicine_id')->references('id')->on('medicines');
           $table->string('application_id')->unique();
           $table->foreign('application_id')->references('id')->on('applications');
           $table->bigInteger ('user_id');
           $table->foreign('user_id')->references('id')->on('users');
            $table->string('product_trade_name')->nullable();
            $table->bigInteger('medicine_id');
            $table->foreign('medicine_id')->references('id')->on('medicines')->comment('Generic Name');
            $table->bigInteger('dosage_form_id');
            $table->bigInteger('route_administration_id');
            $table->foreign('dosage_form_id')->references('id')->on('dosage_forms');
            $table->foreign('route_administration_id')->references('id')->on('route_administrations');
            $table->text('description')->nullable();
            $table->text('strength_amount_strength_unit')->nullable();
            //$table->string('strength_unit')->nullable();
            $table->string('pharmaco_therapeutic_classification');
            $table->string('storage_condition');
            $table->double('shelf_life_amount')->nullable();
            $table->string('shelf_life_unit');
            $table->double('proposed_shelf_life_amount');
            $table->string('proposed_shelf_life_unit');
            $table->double('proposed_shelf_life_after_reconstitution_amount');
            $table->string('proposed_shelf_life_after_reconstitution_unit');
            $table->text('visual_description');
            $table->string('commercial_presentation');
            $table->text('container');
            $table->string('packaging');
            $table->string('category_use');
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
        Schema::dropIfExists('medicinal_products ');
    }
}
