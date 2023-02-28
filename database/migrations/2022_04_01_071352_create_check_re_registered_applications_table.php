<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckReRegisteredApplicationsTable extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_re_registered_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('application_id');
            $table->foreign('application_id')->references('id')->on('applications');
            $table->string('application_number');
            $table->string('re_registration_number');
            $table->string('old_id');
            $table->foreign('old_id')->references('application_id')->on('applications');
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
        Schema::dropIfExists('check_re_registered_applications');
    }
}
