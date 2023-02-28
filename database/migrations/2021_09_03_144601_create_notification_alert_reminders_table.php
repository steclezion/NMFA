<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationAlertRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_alert_reminders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger ('to_user');
            $table->foreign('to_user')->references('id')->on('users');
            $table->text ('detail');
            $table->string ('type');
            $table->string ('category');
            $table->string ('alert_level');
            $table->boolean ('is_notified');
            $table->string ('message_related_to');
            $table->string ('message_related_id');
            $table->string ('remark');
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
        Schema::dropIfExists('notification_alert_reminders');
    }
}
