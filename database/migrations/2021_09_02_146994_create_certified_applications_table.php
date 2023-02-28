<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertifiedApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certified_applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('application_number');
            $table->date('certified_date');
            $table->date('expire_date');
            $table->string('description');
            $table->string('certificate_document_id')->nullable();
            $table->foreign('certificate_document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('certified_applications');
    }
}
