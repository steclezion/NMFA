<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcknowledgmentLetterReceiptPsursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acknowledgment_letter_receipt_psurs', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('RL_squential_number')->nullable();
            $table->date('date')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('region_state');
            $table->string('contact_person_name');
            $table->string('application_number');
            $table->string('reference_number')->nullable();
            $table->text('edited_html_file')->nullable();
            $table->string('applicant_user_id')->nullable();
            $table->foreign('applicant_user_id')->references('id')->on('users');
            $table->boolean('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->string('uploaded_id')->nullable();
            $table->foreign('uploaded_id')->references('id')->on('documents');
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
        Schema::dropIfExists('acknowledgment_letter_receipt_psurs');
    }
}
