<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('decision meeting,other meeting');
            $table->dateTime('meeting_date')->nullable();
            $table->string('description')->nullable();
            $table->string('venue')->nullable();
            $table->string('time')->nullable();
            $table->boolean('postponed')->default(0)->comment('true if the meeting date is postponed');
            $table->string('postponed_reason')->nullable();
            $table->string('postponed_date')->nullable();
            $table->string('postponed_time')->nullable();
            $table->bigInteger ('minutes_id')->unsigned()->nullable();
            $table->foreign('minutes_id')->references('id')->on('uploaded_documents');     
            $table->bigInteger ('supervisor_id')->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users');   
            
            $table->bigInteger ('invitation_document_id')->unsigned()->nullable();
            $table->foreign('invitation_document_id')->references('id')->on('uploaded_documents');     
            
             
            $table->boolean('done')->default(0)->nullable();
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
        Schema::dropIfExists('meetings');
    }
}
