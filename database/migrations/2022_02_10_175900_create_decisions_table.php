<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('meeting_id')->unsigned();
            $table->foreign('meeting_id')->references('id')->on('meetings');  
            
            $table->bigInteger ('dossier_assignment_id')->unsigned();
            $table->foreign('dossier_assignment_id')->references('id')->on('dossier_assignments'); 
            $table->string('decision_status')->nullable()->comment('assigned,rejected,reassigned,deferred');
            $table->dateTime('decision_date')->nullable();
            $table->bigInteger ('downloaded_document_id')->unsigned()->nullable();
            $table->foreign('downloaded_document_id')->references('id')->on('uploaded_documents');            
            $table->bigInteger ('sealed_document_id')->unsigned()->nullable();
            $table->foreign('sealed_document_id')->references('id')->on('uploaded_documents'); 
            $table->boolean ('decision_letter_downloaded')->default(0);
            $table->boolean ('attachments')->default(0);
            $table->string ('downoloded_date')->nullable();
            $table->boolean('appeal')->default(0)->nullable();
            $table->bigInteger ('appeal_letter_id')->unsigned()->nullable();
            $table->foreign('appeal_letter_id')->references('id')->on('uploaded_documents');  
            $table->string('appeal_status')->nullable()->comment('Accepted,Rejected');
            $table->dateTime('appeal_decision_date')->nullable();
            $table->date('deferred_date')->nullable();
            $table->boolean ('locked')->default(0);
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
        Schema::dropIfExists('decisions');
    }
}
