<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecisionParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decision_participants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('committee_id')->unsigned()->nullable();
            $table->foreign('committee_id')->references('id')->on('users');
            
            $table->bigInteger ('meeting_id')->unsigned()->nullable();
            $table->foreign('meeting_id')->references('id')->on('meetings');
            
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
        Schema::dropIfExists('decision_participants');
    }
}
