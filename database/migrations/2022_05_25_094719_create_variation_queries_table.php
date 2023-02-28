<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_queries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('query_from_user_id');
            $table->foreign('query_from_user_id')->references('id')->on('users');
            $table->bigInteger ('query_to_user_id');
            $table->foreign('query_to_user_id')->references('id')->on('users');
            $table->dateTime('query_sent_date');
            $table->string('status');
            $table->dateTime('query_received_date')->nullable();
            $table->dateTime('query_deadline');
            $table->string ('query_extend_reason')->nullable();
            $table->integer ('query_extend_count')->nullable();
            $table->bigInteger('query_related_id');
            $table->foreign('query_related_id')->references('id')->on('dossier_assignments');
            $table->bigInteger('sent_document_id');
            $table->foreign('sent_document_id')->references('id')->on('uploaded_documents');
            $table->bigInteger('received_document_id')->nullable();
            $table->foreign('received_document_id')->references('id')->on('uploaded_documents');
            $table->boolean ('attachments_available')->default(0);
            $table->string('request_subject')->nullable();
            $table->text('response_description')->nullable();
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
        Schema::dropIfExists('variation_queries');
    }
}
