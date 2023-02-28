<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserForgotPasswordAnswerQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_forgot_password_answer_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('question_number_one')->nullable(); // add this
            $table->integer('question_number_two')->nullable(); // add this
            $table->integer('question_number_three')->nullable(); // add this
            $table->string('answer_number_one')->nullable(); // add this
            $table->string('answer_number_two')->nullable(); // add this
            $table->string('answer_number_three')->nullable(); // add this
            $table->Integer('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_forgot_password_answer_questions');
    }
}
