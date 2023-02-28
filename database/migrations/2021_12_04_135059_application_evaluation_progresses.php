<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicationEvaluationProgresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_evaluation_progresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->bigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('task_trackers');
            $table->boolean('application_completed')->default(0);
            $table->boolean('invoice_generated')->default(0);
            $table->boolean('initial_screened')->default(0);
            $table->boolean('deadline_extended')->default(0);
            $table->boolean('assigined')->default(0);
            $table->integer('progress_percentage')->default(0);
            $table->integer('day_count')->default(0);
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
        //
        Schema::dropIfExists('application_evaluation_progresses');
    }
}
