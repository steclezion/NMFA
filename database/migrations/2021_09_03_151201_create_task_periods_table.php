<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_periods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->dateTime ('task_started');
            $table->dateTime ('task_stopped');
            $table->Integer('time_spent');
            $table->text ('content_detail');
            $table->boolean ('is_active');
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
        Schema::dropIfExists('task_periods');
    }
}
