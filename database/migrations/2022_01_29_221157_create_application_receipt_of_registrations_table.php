<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationReceiptOfRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        Schema::create('application_receipt_of_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('Reference_number')->unique();
            $table->string('application_number');
            $table->date('reference_letter_dated')->nullable();
            $table->string('received_document_types')->nullable();
            $table->integer('No_of_DVDs_received')->nullabel();
            $table->integer('uploaded_to_applicant')->nullable();
            $table->integer('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
            $table->timestamps();
        });
    }

    /**nullable(); 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_receipt_of_registrations');
    }
}
