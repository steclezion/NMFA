<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('certificate_id')->unsigned();
            $table->foreign('certificate_id')->references('id')->on('certifications');
            $table->bigInteger('assessor_id')->unsigned()->nullable();
            $table->foreign('assessor_id')->references('id')->on('users');
            $table->string('variation_reference_number')->nullable();
            $table->string('applicant_subject')->nullable();
            $table->string('supervisor_subject')->nullable();
            $table->bigInteger('supervisor_id')->unsigned();
            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->bigInteger('application_id')->unsigned();
            $table->foreign('application_id')->references('id')->on('applications');
            $table->dateTime('assigned_datetime')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->bigInteger ('variation_document_id')->unsigned()->nullable();
            $table->foreign('variation_document_id')->references('id')->on('uploaded_documents');
            $table->boolean ('attachments')->default(0);            
            $table->bigInteger ('acknowledgment_document_id')->unsigned()->nullable();
            $table->foreign('acknowledgment_document_id')->references('id')->on('uploaded_documents');
           
            $table->bigInteger ('assessment_report_document_id')->unsigned()->nullable();
            $table->foreign('assessment_report_document_id')->references('id')->on('uploaded_documents');
            

            $table->bigInteger ('sealed_acknowledgment_document_id')->unsigned()->nullable();
            $table->foreign('sealed_acknowledgment_document_id')->references('id')->on('uploaded_documents'); 
            $table->string('status')->nullable();
               
            $table->boolean('locked')->default(0);

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
        Schema::dropIfExists('variations');
    }
}
