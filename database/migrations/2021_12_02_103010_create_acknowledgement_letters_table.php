<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcknowledgementLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acknowledgement_letters', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('RL_squential_number')->unique();
            $table->date('date');
            $table->string('applicant_name')->nullable();
            $table->string('region_state');
            $table->string('contact_person_name');
            $table->string('application_number');
            $table->string('number_days_receipts');
            $table->boolean('applicant_user_id')->nullable();
            $table->foreign('applicant_user_id')->references('id')->on('users');
            $table->boolean('assessor_user_id')->nullable();
            $table->foreign('assessor_user_id')->references('id')->on('users');
            $table->string('uploaded_applicant_document_id')->nullable();
            $table->foreign('uploaded_applicant_document_id')->references('id')->on('documents');
            $table->string('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('acknowledgement_letters');
    }
}
