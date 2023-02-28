<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('issue_queries', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });


        Schema::create('issue_queries', function (Blueprint $table) {
            $table->id();
            $table->string('application_id');
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('application_number');
            $table->string('PS_squential_number')->unique();
            $table->date('date');
            $table->string('applicant_name');
            $table->string('region_state');
            $table->string('contact_person_name');
            $table->string('number_days_receipts');
            $table->boolean('applicant_user_id')->nullable();
            $table->foreign('applicant_user_id')->references('id')->on('users');
            $table->boolean('assessor_user_id')->nullable();
            $table->foreign('assessor_user_id')->references('id')->on('users');

// //Assessor Sent Document
//             $table->string('assessor_sent_document_id')->nullable();
//             $table->foreign('assessor_sent_document_id')->references('id')->on('documents');

// //Applicant Sent Document
//             $table->string('applicant_sent_document_id')->nullable();
//             $table->foreign('applicant_sent_document_id')->references('id')->on('documents');
//Id from the Document Table
            $table->string('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');

            $table->string('Name_of_the_product')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('strength')->nullable();
            $table->string('Brand_name')->nullable();
            
            $table->string('Remarks')->nullable();
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
        Schema::dropIfExists('issue_queries');
    }
}
