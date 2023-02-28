<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('decision_id')->unsigned()->nullable();
            $table->foreign('decision_id')->references('id')->on('decisions');
            $table->string('registration_number')->nullable()->unique();
            $table->string('certificate_number')->nullable()->unique();
            $table->bigInteger ('MA_document_downloaded')->unsigned()->nullable();
            $table->foreign('MA_document_downloaded')->references('id')->on('uploaded_documents');
            $table->bigInteger ('sealed_MA_document')->unsigned()->nullable();
            $table->foreign('sealed_MA_document')->references('id')->on('uploaded_documents');
            $table->dateTime('certified_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('reregister_requested_deadline')->nullable();
            $table->text('reregister_request_reason')->nullable();
            $table->dateTime('reregister_extended_deadline')->nullable();
            $table->text('reregister_extended_desc')->nullable();
            $table->string('status')->nullable()->comment('registration_active|reregistration_open|reregistration_expired');
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
        Schema::dropIfExists('certifications');
    }
}
