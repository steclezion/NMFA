<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocalAgentTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('local_agent_template', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('user_id');
            $table->foreign('user_id')->references('id')->on('user_id');
            $table->string('trade_name');
            $table->bigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('state')->nullable();
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code');
            $table->foreign('country_code')->references('country_code')->on('countries');
            $table->string('telephone');
            $table->string('webiste_url')->nullable();
            $table->string('email');
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
    }
}
