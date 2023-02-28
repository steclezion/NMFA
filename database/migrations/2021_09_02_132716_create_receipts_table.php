<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->foreign('application_id')->references('id')->on('applications');
            $table->bigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->string('receipt_number');
            $table->decimal('amount', $precision=8, $scale=2);
            $table->date('date')->nullable();
            $table->date('Receipt_Date')->nullable();
            $table->string('description')->nullable();
         
            $table->string('invoice_document_id')->nullable();
            $table->foreign('invoice_document_id')->references('id')->on('documents');
            $table->string('receipt_document_id')->nullable();
            $table->foreign('receipt_document_id')->references('id')->on('documents');
            $table->boolean('financial_notification_flag')->default('0');
            $table->string('upload_financial_notification_document_id')->nullable();

            $table->string('upload_financial_notification_to_applicant')->nullable();
            
            $table->date('financial_notification_date_order')->nullable();
            $table->foreign('upload_financial_notification_document_id')->references('id')->on('documents');
          

            $table->timestamps();
            //uploaded documents
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
