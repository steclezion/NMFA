<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_trackers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->dateTime ('start_time')->nullable ();
            $table->dateTime ('end_time')->nullable ();
            $table->Integer('time_spent')->nullable ();
            $table->string ('content_detail', 400)->nullable ();
            $table->Integer('extention_days')->nullable ();
            $table->boolean ('is_active');
            $table->string('related_id')->nullable();

            $table->string('extension_reason')->nullable();
            $table->boolean('deadline_extended')->default('0');
            
            $table->string ('document_id')->nullable ();
            $table->string ('activity_status')->comment ('in progress, stopped, complete');
            $table->string('task_category')->comment ('message, activity, document_submitted....');
            $table->string ('task_activity_title')->nullable ()->comment ('activity main header in timeline ');
            $table->string ('route_link')->nullable ()->comment ('The related Link to be routed');
            $table->bigInteger ('uploaded_document_id')->nullable();
            $table->foreign('uploaded_document_id')->references('id')->on('uploaded_documents');

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
        Schema::dropIfExists('task_trackers');
    }
}
