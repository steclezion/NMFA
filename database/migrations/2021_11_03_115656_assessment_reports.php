<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AssessmentReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->string ('name');//Requested
            $table->bigInteger ('assessment_from_user_id')->unsigned();
            $table->foreign('assessment_from_user_id')->references('id')->on('users');
            $table->bigInteger ('assessment_to_user_id')->unsigned();
            $table->foreign('assessment_to_user_id')->references('id')->on('users');
            $table->dateTime('assessment_sent_date');
            $table->string ('status');//Requested
            $table->dateTime ('assessment_received_date')->nullable();
            $table->bigInteger('assessment_related_id')->unsigned();
            $table->string('sent_document_id')->nullable();
            $table->string('received_document_id')->nullable();
            $table->foreign('received_document_id')->references('id')->on('uploaded_documents');
            $table->boolean ('attachements_available')->default(0);
            $table->string ('request_subject')->nullable();
            $table->string ('response_description')->nullable();
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
        ma::dropIfExists('assessment_reports');
    }
}
