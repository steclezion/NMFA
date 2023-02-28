<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psurs', function (Blueprint $table) {
            // $table->id();
            // $table->string('psur_document_id');
            // $table->string('psur_refrence_number')->nullable();
            // $table->boolean('nmfa_director_flag')->default(0);
            // $table->string('application_id');
            // $table->foreign('application_id')->references('application_id')->on('applications');
            // $table->timestamps();

            $table->id();
            $table->string('psur_document_id');
            $table->string('psur_refrence_number')->nullable();
            $table->boolean('nmfa_director_flag')->default(0);
            $table->string('application_id');
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('assigned_To')->nullable();
            $table->string('assigned_By')->nullable();
            $table->date('Assginment_Date')->nullable();
            $table->date('deadline')->nullable();
            $table->string('psur_review_uploaded_id')->nullable();
            $table->string('psur_review_uploaded_by')->nullable();

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
        Schema::dropIfExists('psurs');
    }
}
