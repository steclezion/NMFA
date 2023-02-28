<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierSectionAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_section_assignments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dossier_id')->unsigned();
            $table->foreign('dossier_id')->references('id')->on('dossiers');
            $table->bigInteger ('section_from_user_id')->unsigned();
            $table->foreign('section_from_user_id')->references('id')->on('users');
            $table->bigInteger ('section_to_user_id')->unsigned();
            $table->foreign('section_to_user_id')->references('id')->on('users');
            $table->dateTime('section_sent_date');
            $table->string ('status')->comment('Assigned or Evaluated');
            $table->dateTime ('section_received_date')->nullable();
            $table->dateTime ('section_deadline');
            $table->string ('section_extend_reason')->nullable();
            $table->bigInteger('section_related_id')->unsigned();
            $table->foreign('section_related_id')->references('id')->on('dossier_assignments');
            $table->bigInteger('sent_document_id')->unsigned();
            $table->foreign('sent_document_id')->references('id')->on('uploaded_documents');
            $table->bigInteger('received_document_id')->unsigned()->nullable();
            $table->foreign('received_document_id')->references('id')->on('uploaded_documents');
            $table->string ('assignment_description')->nullable();
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
        Schema::dropIfExists('dossier_section_assignments');
    }
}
