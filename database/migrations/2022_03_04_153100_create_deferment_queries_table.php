<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefermentQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deferment_queries', function (Blueprint $table) {
            $table->id();
            $table->string('sent_subject')->nullable();
            $table->string('received_subject')->nullable();
            $table->bigInteger ('decision_id');
            $table->foreign('decision_id')->references('id')->on('decisions');
            $table->string('sent_date');
            $table->string('status');
            $table->dateTime('received_date')->nullable();
            $table->bigInteger('sent_document_id')->nullable();
            $table->foreign('sent_document_id')->references('id')->on('uploaded_documents');
            $table->bigInteger('received_document_id')->nullable();
            $table->foreign('received_document_id')->references('id')->on('uploaded_documents');

            $table->boolean('deadline_requested')->default(0);
            $table->date('deadline')->nullable();
            $table->text('sent_query')->nullable();
            $table->text('received_response')->nullable();
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
        Schema::dropIfExists('deferment_queries');
    }
}
