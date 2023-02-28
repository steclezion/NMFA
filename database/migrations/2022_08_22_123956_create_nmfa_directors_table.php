<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNmfaDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nmfa_directors', function (Blueprint $table) {
            $table->id();
            $table->string('nmfa_directors_document_id');
            $table->string('nmfa_directors_refrence_number')->nullable();
            $table->boolean('nmfa_director_flag')->default(0);
            $table->string('application_id');
            $table->foreign('application_id')->references('application_id')->on('applications');
            $table->string('Send_To')->nullable();
            $table->string('Send_To_id')->nullable();
            $table->date('Assginment_Date')->nullable();
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
        Schema::dropIfExists('nmfa_directors');
    }
}
