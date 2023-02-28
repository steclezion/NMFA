<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->foreign('application_id')->references('id')->on('applications');

            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('invoice_number');
            $table->string('remark')->nullable();
            $table->decimal('amount', $precision=8, $scale=2);
            $table->string('invoice_document_id')->nullable();
            $table->foreign('invoice_document_id')->references('id')->on('documents'); //invoice_document_id 

            $table->string('applicant_user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('uploaded_invoice_document_id')->nullable();
            $table->foreign('uploaded_invoice_document_id')->references('id')->on('documents'); //invoice_document_id 


            $table->string('uploaded_swift_document_id')->nullable();
            $table->foreign('uploaded_swift_document_id')->references('id')->on('documents'); //invoice_document_id 
            $table->date('date_of_order')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
