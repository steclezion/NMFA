<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('medical_product_id')->nullable();
            $table->foreign('medical_product_id')->references('id')->on('medicinal_products');
            $table->bigInteger('company_supplier_id')->nullable();
            $table->foreign('company_supplier_id')->references('id')->on('company_suppliers');
            $table->bigInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->boolean('payment_status')->default('0')->nullable();
            $table->boolean('sample_status')->nullable();
            $table->longText('dossier_url')->nullable();
            $table->boolean('flag_dossier_url')->nullable()->comment('If flag is 1 its a link if not its express name(DHL: UPS: FedEx)');
            $table->string('dossier_actual_path')->nullable();
            $table->string('application_number')->nullable();
            $table->string('re_registration_number')->nullable();
            $table->string('registration_type')->nullable();
            $table->string('hold_progress_wizard')->default('0');
            $table->string('progress_percentage')->default('0');
            $table->string('market_status')->default('Active');
            $table->string('declaration_document_id')->nullable();
            $table->foreign('declaration_document_id')->references('id')->on('documents');
            $table->string('application_type')->nullable();;
            $table->string('fast_track_details')->nullable ();
            $table->bigInteger('assigned_To')->nullable ();
            $table->foreign('assigned_To')->references('id')->on('users');
            $table->bigInteger('assigned_By')->nullable();
            $table->foreign('assigned_By')->references('id')->on('users');
            $table->string('Assginment_Date')->nullable();
            $table->string('application_status')->default('processing')->comment ('describe app status: pending ,processing, complete');
            $table->string('dossier_id')->nullable()->comment('dossier Reference after application Reception is complated');;
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
        Schema::dropIfExists('applications');
    }
}
