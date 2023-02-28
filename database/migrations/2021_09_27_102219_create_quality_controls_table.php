<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_controls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('from_user_id')->unsigned();
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->bigInteger ('inspection_to_user_id')->unsigned();
            $table->foreign('inspection_to_user_id')->references('id')->on('users');
            $table->dateTime('inspection_sent_date');
            $table->string ('status');//Requested
            $table->dateTime ('qc_received_date')->nullable();
            $table->dateTime ('qc_deadline')->nullable();
            $table->string ('qc_extend_reason')->nullable();
            $table->bigInteger('qc_related_id')->unsigned();
            $table->foreign('qc_related_id')->references('id')->on('dossier_assignments');
            $table->bigInteger('sent_document_id')->unsigned();
            $table->foreign('sent_document_id')->references('id')->on('uploaded_documents');
            $table->bigInteger('received_document_id')->unsigned()->nullable();
            $table->foreign('received_document_id')->references('id')->on('uploaded_documents');
            $table->boolean ('attachments_available')->default(0);
            $table->string ('request_subject')->nullable();
            $table->string ('response_description')->nullable();
            $table->bigInteger ('to_qc_staff_id')->unsigned()->nullable();
            $table->foreign('to_qc_staff_id')->references('id')->on('users');
            $table->bigInteger('to_qc_document_id')->unsigned()->nullable();
            $table->foreign('to_qc_document_id')->references('id')->on('uploaded_documents');
            $table->string ('to_qc_lab_subject')->nullable();
            $table->dateTime ('to_qc_sent_date')->nullable();
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
        Schema::dropIfExists('quality_controls');
    }
}
