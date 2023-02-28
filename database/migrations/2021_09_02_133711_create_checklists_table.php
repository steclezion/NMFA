<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
             // $table->boolean('is_application_appendices')->nullable();
            // $table->boolean('is_who_prequalification')->nullable();
           // $table->boolean('is_refrence_strigent_regulatory')->nullable();
          // $table->boolean('is_any_country')->nullable();
         // $table->boolean('is_dossier_ctd_format')->nullable();
        // $table->boolean('is_qis_prequalified_products')->nullable();

          //step 2
            $table->id();
            $table->string('application_number');
            $table->string('application_id')->unique();
            $table->foreign('application_id')->references('id')->on('applications');
            $table->boolean('is_application_letter')->nullable();
            $table->boolean('is_manufacturer_inforamation')->nullable();
            $table->string('is_local_agent')->nullable();
            $table->boolean('is_enlm')->nullable();
            $table->boolean('submitted_dossier_in_CTD_format')->nullable();
            $table->boolean('is_module_one')->nullable();
            $table->boolean('is_module_two')->nullable();
            $table->boolean('is_module_three')->nullable();
            $table->boolean('is_module_four')->nullable();
            $table->boolean('is_module_five')->nullable();
            $table->text('Remark_step_two')->nullable();



            //step 3 submitted dossier in CTD format
            $table->boolean('Presence_valid_marketing_authorization')->nullable();
            $table->boolean('Presence_of_the_Quality_Information_Summary')->nullable();
            $table->boolean('Presence_of_full_assessment_report_from_the_reference_authority')->nullable();
            $table->boolean('Presence_of_the_full_inspection')->nullable();
            $table->boolean('Presence_of_the_Summary_Product_Characteristics')->nullable();
            $table->boolean('Presence_of_the_Patient_information_leaflet')->nullable();
            $table->text('Remark_step_three')->nullable();

            //step4 - Sample Details

            $table->unsignedInteger('Availability_of_Product_Sample')->nullable();
            $table->string('Number_of_samples_received')->nullable();
            $table->unsignedInteger('Number_of_sample_sent_conforms_with_the_sampling_schedule')->nullable();
            $table->text('Labelling_Information')->nullable();
            $table->text('sample_received_date')->nullable();
            $table->string('Sample_net_volume_or_weight')->nullable();
            $table->string('Sample_net_volume_or_weight_remark')->nullable();
            $table->boolean('availability_packages')->nullable();
            $table->boolean('manufactured_in_the_same_manufacturing_premises')->nullable();
            $table->boolean('Availability_of_an_official_of_analysis')->nullable();
            $table->string('Availability_of_an_official_of_analysis_remark')->nullable();
            $table->string('Samples_have_at_least_60_perecent')->nullable();
            $table->text('summernote_Remark_section_four')->nullable();

           //Step 5 Payment Details
            $table->string('is_invoice_number_generated')->nullable();
            $table->string('is_application_payment_fee')->nullable();
            $table->string('is_application_receipt_number')->nullable();
            $table->string('remark_section_five')->nullable();
            $table->string('over_all_comment')->nullable();
            $table->string('receiving_officer')->nullable();
            $table->foreign('receiving_officer')->references('id')->on('users')->nullable();//Assessor is the officer receiver
            $table->date('date')->nullable();
            $table->string('Shared_Path_Name')->nullable();
            $table->string('supervisor_hold_assessor_progress')->nullable();
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
        Schema::dropIfExists('checklists');
    }
}
