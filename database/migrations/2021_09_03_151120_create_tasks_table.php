<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string ('task_name');
            $table->Integer('task_duration_days_plan');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string ('stopping_reason')->comment ('query, pause, stopped, complete');
            $table->Integer('task_duration_days_actual');
            $table->boolean ('is_active');
            $table->boolean ('is_complete');
            $table->string ('task_status')->comment ('in progress, complete, pause');
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
        Schema::dropIfExists('tasks');
    }
}
