<?php

// NOTE:
// After adding new entry here, run the following to
// make the variable available to views, controllers ..
// $ php artisan config:cache


return [


  /*
    |--------------------------------------------------------------------------
    |Application Lifetime
    |--------------------------------------------------------------------------
    |
    | The application details  are kept for a maximum of the below months since Start of the application.
    |
    */
    'application_lifetime_days' => 30,
    
     /*
    |--------------------------------------------------------------------------
    |Assessor Screening Timle Line
    |--------------------------------------------------------------------------
    |
    | The Assessor Screening Timle Line for a maximum of the below months days since Start of the application.
    |
    */
    'Screening_lifetime_days' => 10,
    
    /*
    Payment and Sample Timle Line
    |--------------------------------------------------------------------------
    |
    | The Applicant  submit  payment and Sample with in  maximum of the below 30 days since invoice Issued.
    |
    */
    'payment_sample_report_days' => 30,
    
    

/*
    |--------------------------------------------------------------------------
    | Reminder for application submission  evey 5 day reminder
    |--------------------------------------------------------------------------
    |
    | - Set deadlines for  submissions applicant (deadlines editable)
    | - Reminder every 5 days prior to the deadline
    |
    */
    'applicant_application_reminder_days' => 5,


/*
    |--------------------------------------------------------------------------
    | Reminder for application sample and payment evey 10  day reminder
    |--------------------------------------------------------------------------
    |
    | - Set deadlines for  submissions of application sample and payment (deadlines editable)
    | - Reminder every 10 days prior to the deadline
    |
    */
    'applicant_payment_reminder_days' => 10,

/*
    |--------------------------------------------------------------------------
    | Dossier Files Location
    |--------------------------------------------------------------------------
    |
    | The dossier files are uploaded to app/storage/dossiers (temporary - during dev).
    | For production this location should be changed to external disks.
    | Then configure the external disk in config/filesystem.php and update the below variable accordingly.
    |
    */

    'dossier_dir' => 'dossiers/',

    /*
    |--------------------------------------------------------------------------
    | Dossier Lifetime
    |--------------------------------------------------------------------------
    |
    | The dossier files are kept for a maximum of the below months since registration.
    |
    */
    'dossier_lifetime_months' => 120,



    /*
    |--------------------------------------------------------------------------
    | Reminder for user story 123/ NMFA units report submission reminder
    |--------------------------------------------------------------------------
    |
    | - Set deadlines for report submissions assigned to NMFA units/PERC (deadlines editable)
    | - Reminder 10 days prior to the deadline
    |
    */
    'nmfa_units_remind_before_days' => 10,

    /*
    |--------------------------------------------------------------------------
    | Reminder for user story 142 / QC report first reminder
    |--------------------------------------------------------------------------
    |
    | - Set deadline (editable) for submission of QC analysis report.
    | - Remind one-third and two-third of the total time.
    | * remind when 1/3 of the total time is elapsed
    |
    */

    'qc_report_first_reminder' => 1/3,

    /*
    |--------------------------------------------------------------------------
    | Reminder for user story 142 / QC report second reminder
    |--------------------------------------------------------------------------
    |
    | - Set deadline (editable) for submission of QC analysis report.
    | - Remind one-third and two-third of the total time.
    | * remind when 2/3 of the total time is elapsed
    |
    */

    'qc_report_second_reminder' => 2/3,


    /*
   |--------------------------------------------------------------------------
   | Reminder for user story 157/ Applicant query response reminder
   |--------------------------------------------------------------------------
   |
   | - Set deadline (editable) for submission of query responses
   | - Remind 5 days prior to deadline
   |
   */
    'applicant_query_remind_before_days' => 5,

    /*
  |--------------------------------------------------------------------------
  | Reminder for US-171/ Remind assessor 10 days before dossier eval. deadline
  |--------------------------------------------------------------------------
  |
  | - Remind the assessor for un-submitted tasks 10 days prior to
  |   the deadline via system database
  |
  |
  */
    'dossier_evaluation_total_days' => 130,

    /*
  |--------------------------------------------------------------------------
  | Reminder for US-171/ Remind assessor 10 days before dossier eval. deadline
  |--------------------------------------------------------------------------
  |
  | - Remind the assessor for un-submitted tasks 10 days prior to
  |   the deadline via system database
  |
  |
  */
    'remind_dossier_eval_deadline_before_days' => 10,

    /*
 |--------------------------------------------------------------------------
 | Dossier Assessment report submission limit
 |--------------------------------------------------------------------------
 |
 | Assessor can submit assessment report
 | for maximum of below specified times.
 |
 |
 */
    'assessment_report_submission_limit' => 3,


];
