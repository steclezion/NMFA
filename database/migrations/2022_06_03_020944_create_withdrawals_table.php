<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('action_taken',20)->nullable();
            $table->text('description')->nullable();
            $table->date('withdrawal_date_requested')->nullable();
            $table->date('action_date')->nullable();
            $table->string('withdrawal_request_reason',191)->nullable();
            $table->string('withdrawal_request_attachment',191)->nullable();
            $table->date('withdrawal_decision_date')->nullable();
            $table->string('withdrawal_decision',191)->nullable();
            $table->string('withdrawal_decision_document',191)->nullable();
            $table->string('withdrawal_decision_reason',191)->nullable();
            $table->string('suspension_status',191)->nullable();
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
        Schema::dropIfExists('withdrawals');
    }
}
