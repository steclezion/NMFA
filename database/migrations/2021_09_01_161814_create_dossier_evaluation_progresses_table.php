<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierEvaluationProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_evaluation_progresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dossier_assignment_id');
            $table->foreign('dossier_assignment_id')->references('id')->on('dossier_assignments');
            $table->boolean('QOS_is_done')->default(0);
            $table->boolean('issue_query_is_done')->default(0);
            $table->boolean('qc_sample_is_done')->default(0);
            $table->integer('assessment_submitted')->default(0);
            $table->boolean('assessment_submitted_to_supervisor')->default(0);
            $table->boolean('evaluation_deadline_extended')->default(0);
            $table->integer('progress_percentage');
            $table->integer('day_count')->default(0);
            $table->integer('deferred_assessment_submitted')->default(0);
            $table->boolean('deferred_assessment_submitted_to_supervisor')->default(0);
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
        Schema::dropIfExists('dossier_evaluation_progresses');
    }
}
