<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_tasks', function (Blueprint $table) {
            $table->id();
            $table->string ('task_name');
            $table->string('related_task');
            $table->Integer('related_id')->nullable ();
            $table->Integer('task_duration_days_plan');
            $table->dateTime('start_time')->nullable ();
            $table->dateTime('end_time')->nullable ();
            $table->string ('stopping_reason')->comment ('query, pause, stopped, complete')->nullable ();
            $table->dateTime('task_duration_days_actual')->nullable ();
            $table->boolean('is_active')->default (1);
            $table->boolean('is_complete');
            $table->boolean('is_archived');
            $table->date('deadline')->nullable ();
            $table->date('deadline_extended_to')->nullable ();
            $table->string ('task_status')->comment ('in progress, complete, pause/ assigned, in progress, complete');
            $table->integer ('alert_before_days')->default (0);
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
        Schema::dropIfExists('main_tasks');
    }
}
