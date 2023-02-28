<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspensions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('action_taken')->nullable();
            $table->string('appeal_status',191)->nullable();
            $table->bigInteger('active')->default('1');
            $table->text('description')->nullable();
            //$table->string('appeal_status',191)->nullable();
            $table->string('appeal_document_user',191)->nullable();
            $table->string('appeal_description_user',191)->nullable();
            $table->date('action_date')->nullable();
            $table->date('suspended_till_date')->nullable();
            $table->string('appeal_document_who',191)->nullable();
            $table->string('suspension_status',191)->default('active');
            $table->string('appeal_document_moh',191)->nullable();
            $table->text('void_reason')->nullable();
            $table->string('appeal_description_moh',191)->nullable();
            $table->string('decision_response_letter',191)->nullable();
            $table->string('void_remark',191)->nullable();
            $table->string('suspension_document',191)->nullable();
            $table->string('appeal_accepted',20)->nullable();
            $table->string('decision_response',191)->nullable();
            $table->string('response_remark',191)->nullable();
            $table->string('sealed_letter',191)->nullable();
            $table->string('deadline_update_reason',191)->nullable();
            $table->date('deadline_extension_requested')->nullable();
            $table->string('request_accepted',15)->nullable();
            $table->string('request_deadline_extension_reason',191)->nullable();
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
        Schema::dropIfExists('suspensions');
    }
}
