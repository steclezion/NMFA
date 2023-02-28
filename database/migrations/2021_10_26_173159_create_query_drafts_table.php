<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueryDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_drafts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dossier_assignment_id');
            $table->foreign('dossier_assignment_id')->references('id')->on('dossier_assignments');
            $table->text('html_draft')->nullable();
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
        Schema::dropIfExists('query_drafts');
    }
}
