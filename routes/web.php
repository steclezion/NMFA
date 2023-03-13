<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth/register');
})->name('signup');
Route::get('/register', [App\Http\Controllers\CountryController::class, 'country'])->name('signup');
Route::get('/forgotpassword', function () {
    return view('auth/forgot_password');
})->name('forgot_password');
Route::post('/Validate/user', [App\Http\Controllers\Auth\RegisterController::class, 'validate_User'])->name('RegisterController.validatesusername');

Route::post('/Validate/post', [App\Http\Controllers\Auth\RegisterController::class, 'validate_Register'])->name('RegisterController.validate');

//user.forgot_password_confirmation_page
Route::get('/forgot_password_congratulations_page', function () {
    return view('auth/forgot_password_congratulations_page');
})->name('user.forgot_password_congratulations_page');
Route::get('/forgot_password_with_verification', [App\Http\Controllers\CountryController::class, 'forgotpassword'])->name('forgot_password_with_verification');
Route::post('/forgot_password_with_verification/page', [App\Http\Controllers\UserController::class, 'forgotpassword_verification'])->name('user.forgot_password_with_verification');
//forgot_password_confirmation
Route::get('/forgot_password_confirmation', [App\Http\Controllers\UserController::class, 'forgot_password_confirmation_page'])->name('user.forgot_password_confirmation_page');
Route::post('/forgot_password_confirmation', [App\Http\Controllers\UserController::class, 'forgot_password_confirmation'])->name('user.forgot_password_confirmation');
Route::post('/user_check_password', [App\Http\Controllers\UserController::class, 'forgot_password'])->name('users.forgot_password');
Route::post('/customregistration', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('customreg');
//check Validity of the Password  check_password
Route::get('/check_passwordd', [App\Http\Controllers\Auth\RegisterController::class, 'check_validity_strength'])->name('check_passwordd');
//Authenticate Routes Login
Route::post('/', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('signin');
//Route::post('/dashboard',function() { return view('dashboard');  })->name('signin');
//Authenticate Routes Logout
// Telephone Code
Route::post('/get_tele_code/tele_country_code', [App\Http\Controllers\Auth\RegisterController::class, 'get_tele_code'])->name('get_tele_code');

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function () {
    Route::get('', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::middleware('auth')->group(function () {
        Route::get('/user_profile', [App\Http\Controllers\user_profile_controller::class, 'user_profile'])->name('user_profile');

        Route::post('/users_update/{id}', [App\Http\Controllers\user_profile_controller::class, 'update_user'])->name('users_profile.update');

        ///check Validity of the Password  check_password
        Route::get('/check_password', [App\Http\Controllers\user_profile_controller::class, 'check_validity_strength'])->name('check_password');

        Route::get('/application_reception', [App\Http\Controllers\ApplicationReceptionController::class, 'Request_for_all'])->name('application_reception');
        //application_reception_re_registration
        Route::get('/application_reception_re_registration/{application_id}/re_register', [App\Http\Controllers\ApplicationReceptionController::class, 'application_reception_re_registration'])->name('application_reception_re_registration');

        Route::get('/application_in_processing', [App\Http\Controllers\application_status::class, 'application_reception_on_going'])->name('application_in_processing');


        Route::get('/in_process_on_going_renew_application', [App\Http\Controllers\application_status::class, 'application_reception_on_going_renew'])->name('in_process_on_going_renew_application');
        
        Route::post('/insert/compostion', [App\Http\Controllers\ApplicationReceptionController::class, 'store_retrive_composition'])->name('insert.compostion');
        Route::post('/company_supplier', [App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier'])->name('company_supplier');
        Route::post('/generate.application_number', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_new_application'])->name('generate.application_number');

        Route::post('/generate_re_new_application', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_re_new_application'])->name('generate_re_new_application');

        Route::post('/company_supplier_update', [App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier_update'])->name('company_supplier_update');
        Route::post('/application_reception', [App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier_save'])->name('company_supplier_save');
        Route::post('/agent_save', [App\Http\Controllers\ApplicationReceptionController::class, 'agent_save'])->name('agent_save');
        Route::post('/agent_info', [App\Http\Controllers\ApplicationReceptionController::class, 'agent_info'])->name('agent_info');
        Route::post('/agent_update', [App\Http\Controllers\ApplicationReceptionController::class, 'agent_update'])->name('agent_update');
        Route::post('/product_details/save', [App\Http\Controllers\ApplicationReceptionController::class, 'product_details_save'])->name('product_details_save');
        Route::post('/product_details/save/other', [App\Http\Controllers\ApplicationReceptionController::class, 'product_details_save_other'])->name('product_details_save_other');
        Route::get('/sample', [App\Http\Controllers\ApplicationReceptionController::class, 'Request_for_all_sample']);
        Route::post('/product_details/update', [App\Http\Controllers\ApplicationReceptionController::class, 'product_details_update'])->name('product_details_update');
        Route::post('/product_details/update/other', [App\Http\Controllers\ApplicationReceptionController::class, 'product_details_update_other'])->name('product_details_update_other');


        Route::post('/product_manufacturer/save', [App\Http\Controllers\ApplicationReceptionController::class, 'product_manufacturer_save'])->name('product_manufacturer_save');
        Route::post('/product_manufacturer/update', [App\Http\Controllers\ApplicationReceptionController::class, 'product_manufacturer_update'])->name('product_manufacturer_update');
        Route::post('/product_composition/save', [App\Http\Controllers\ApplicationReceptionController::class, 'product_composition_save'])->name('product_composition_save');
        Route::post('/product_Composition/update', [App\Http\Controllers\ApplicationReceptionController::class, 'product_composition_update'])->name('product_composition_update');

        Route::post('/composition.retreive', [App\Http\Controllers\ApplicationReceptionController::class, 'composition_retreive'])->name('composition.retreive');
        //manufacture.retreive
        Route::post('/manufacturer.retreive', [App\Http\Controllers\ApplicationReceptionController::class, 'manufacturer_retreive'])->name('manufacturer.retreive');
        //manufacturer_api_.retreive
        Route::post('/manufacturer_api_.retreive', [App\Http\Controllers\ApplicationReceptionController::class, 'manufacturer_retreive_api'])->name('manufacturer_api_.retreive');


        Route::post('/save_product_manufacturer_api/save', [App\Http\Controllers\ApplicationReceptionController::class, 'save_product_manufacturer_api'])->name('save_product_manufacturer_api');
        Route::post('/save_product_manufacturer_api/update', [App\Http\Controllers\ApplicationReceptionController::class, 'update_product_manufacturer_api'])->name('update_product_manufacturer_api');
        Route::delete('/product_manufacturer_api/delete', [App\Http\Controllers\ApplicationReceptionController::class, 'delete_manufacturer_elements_api'])->name('manufacturer_delete');

        Route::get('/attach_payment_swift', [App\Http\Controllers\ApplicationReceptionController::class, 'attach_payment_swift'])->name('swift_payment');

        //received_swift_payments
        Route::get('/received_swift_payments', [App\Http\Controllers\invoice::class, 'received_swift_payments'])->name('received_swift_payments');


        Route::get('/dossier_status_sample/{application_id}/add_dossier_info', [App\Http\Controllers\ApplicationReceptionController::class, 'dossier_control_wizard'])->name('application_set.dossier');


        Route::post('/application/save', [App\Http\Controllers\ApplicationReceptionController::class, 'application_save'])->name('application_save');
        Route::post('/application/save_re', [App\Http\Controllers\ApplicationReceptionController::class, 'application_save_re_register'])->name('application_save_re_register');


        Route::post('/application/update', [App\Http\Controllers\ApplicationReceptionController::class, 'application_update'])->name('application_update');
        //Route::post('ss/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'dossier_sample_save'])->name('dossier_sample_save');
        Route::post('/decleration_save/save', [App\Http\Controllers\ApplicationReceptionController::class, 'decleration_save'])->name('decleration_save');
        Route::get('/application_reception/{application_id}/view_completed_application', [App\Http\Controllers\ApplicationReceptionController::class, 'view_completed_application'])->name('view_completed_application');

        Route::get('/application_reception/{application_id}/view_completed_application_re', [App\Http\Controllers\ApplicationReceptionController::class, 'view_completed_application_re'])->name('view_completed_application_re');

        Route::get('/application_reception/{application_id}/update', [App\Http\Controllers\ApplicationReceptionController::class, 'application_reception_complete_wizard_control'])->name('application.update');
        
        //application.process_further
        Route::get('/application_reception_re_registration_update/{application_id}/update', [App\Http\Controllers\ApplicationReceptionController::class, 'application_reception_complete_wizard_control_re'])->name('application_reception_re_registration_update');

        Route::get('/application/{application_id}/process_further', [App\Http\Controllers\ApplicationReceptionController::class, 'application_futher_process'])->name('application.process_further');

        Route::get('/application_status', [App\Http\Controllers\application_status::class, 'index'])->name('application_status');

        Route::get('/dec/{id}/retreive', [App\Http\Controllers\ApplicationReceptionController::class, 'completeApplicationStatus'])->name('completeApplicationStatus');

        Route::get('/print/{id}/print', [App\Http\Controllers\ApplicationReceptionController::class, 'Application_general_print'])->name('completeApplicationStatus');

        //registed_application
       Route::get('/registered_applicationn', [App\Http\Controllers\application_status::class, 'registerd_application'])->name('registered_application');
        
        
        //unregisted_application
        Route::get('/applications_fully_registered', [App\Http\Controllers\application_status::class, 'nmfa_director_applications'])->name('nmfa_registered_application');

        //upload_alert_psur_file
        Route::post('/upload_alert_psur_file', [App\Http\Controllers\PsurController::class, 'upload_alert_psur_file'])->name('upload_alert_psur_file');

        //upload_alert_nmfa_director_file
        Route::post('/upload_alert_nmfa_director_file', [App\Http\Controllers\NmfaDirectorController::class, 'upload_alert_nmfa_director_file'])->name('upload_alert_nmfa_director_file');



        //upload_alert_psur_file
        Route::get('/psur_acknowledgment_list', [App\Http\Controllers\PsurController::class, 'psur_acknowledgment_list'])->name('psur_acknowledgment_list');

        //perc_psur_status_list
        Route::get('/perc_psur_status_list', [App\Http\Controllers\PsurController::class, 'perc_psur_status_list'])->name('perc_psur_status_list');


        Route::post('/dossier_status/save', [App\Http\Controllers\ApplicationReceptionController::class, 'dossier_sample_save'])->name('dossier_sample_save');
        Route::post('/dossier_status/update', [App\Http\Controllers\ApplicationReceptionController::class, 'dossier_sample_update'])->name('dossier_sample_update');
        Route::get('/dossier_sample_status', [App\Http\Controllers\application_status::class, 'dossier_sample_status'])->name('dossier_sample_status');
        Route::get('/dossier_status_sample/{application_id}/update_dossier_info', [App\Http\Controllers\ApplicationReceptionController::class, 'dossier_sample_status_edit'])->name('dossier_sample_status_edit');
        Route::get('/dossier_sample_status_renew', [App\Http\Controllers\application_status::class, 'dossier_sample_status_renew'])->name('dossier_sample_status_renew');

        Route::delete('/product_composition/delete', [App\Http\Controllers\ApplicationReceptionController::class, 'delete_composition_elements'])->name('composition_delete');
        Route::delete('/product_manufacturer/delete', [App\Http\Controllers\ApplicationReceptionController::class, 'delete_manufacturer_elements'])->name('manufacturer_delete');
        //Route to Invoice
        Route::get('/invoice', [App\Http\Controllers\invoice::class, 'generate_invoices'])->name('generate_invoices');
        
        Route::post('/invoice_generate', [App\Http\Controllers\invoice::class, 'generate_invoices_now'])->name('invoice_generate');
        Route::get('/generate_invoice/{application_id}', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_invoices'])->name('application.invoice_generate');
        Route::get('/invoice.index', [App\Http\Controllers\invoice::class, 'index'])->name('invoices.index');
        Route::post('/invoice/save_invoices_now', [App\Http\Controllers\invoice::class, 'create'])->name('invoices.save_invoices_now');
        Route::get('/invoice.edit', [App\Http\Controllers\invoice::class, 'edit'])->name('invoices.edit');
        Route::get('/generate_pdf/{user_id}', [App\Http\Controllers\invoice::class, 'generatePDF'])->name('generate_pdf_invoice');
        Route::get('/receipts', [App\Http\Controllers\receipts::class, 'index'])->name('receipts');
        Route::get('/receipts_received', [App\Http\Controllers\receipts::class, 'Received'])->name('receipts.received');
        Route::get('/receipts_all', [App\Http\Controllers\receipts::class, 'receipts_all'])->name('receipts.received.all');
        Route::get('/Acknowledgement_of_receipt/{application_id}', [App\Http\Controllers\receipts::class, 'Acknowledgement_of_receipt'])->name('receipts.Acknowledgement_of_receipt');
        Route::post('/upload_acknowledgment_receipt', [App\Http\Controllers\receipts::class, 'upload_acknowledgment_receipt'])->name('receipts.upload_acknowledgment_receipt');
        Route::post('/upload_to_applicant_receipt', [App\Http\Controllers\receipts::class, 'upload_to_applicant'])->name('receipts.upload_to_applicant');
        //acknowledgment_receipt.remove   retrive_file_uploaded_to_applicant
        Route::delete('/delete_uploaded_file', [App\Http\Controllers\receipts::class, 'delete_file_uploaded_to_applicant'])->name('reciept.acknowledgment_receipt.remove');
        Route::post('/retrive_file_uploaded_to_applicant', [App\Http\Controllers\receipts::class, 'retrive_file_uploaded_to_applicant'])->name('receipts.retrive_file_uploaded_to_applicant');
        //receipts.retrive_file_uploaded_to_applicant_financial_section
        Route::post('/retrive_file_uploaded_to_applicant_financial_section', [App\Http\Controllers\receipts::class, 'retrive_file_uploaded_to_applicant_financial_section'])->name('receipts.retrive_file_uploaded_to_applicant_financial_section');
        //Delete File reciept.delete_file_uploaded_financial_notification.remove
        Route::delete('/reciept.delete_file_uploaded_financial_notification.remove', [App\Http\Controllers\receipts::class, 'delete_file_uploaded_financial_notification'])->name('reciept.delete_file_uploaded_financial_notification.remove');
        //receipts.upload_to_financial_document_applicant receipts.upload_to_financial_document_applicant
        Route::post('/receipts.upload_to_financial_document_applicant', [App\Http\Controllers\receipts::class, 'upload_financial_document_applicant'])->name('receipts.upload_to_financial_document_applicant');
        //fetch_uploaded_invoice_letter
        Route::post('/fetch_uploaded_invoice_letter', [App\Http\Controllers\invoice::class, 'fetch_uploaded_invoice_letter'])->name('fetch_uploaded_invoice_letter');
        //delete_file_uploaded_invoice_letter.remove
        Route::delete('/delete_file_uploaded_invoice_letter_remove', [App\Http\Controllers\invoice::class, 'delete_file_uploaded_invoice_letter'])->name('delete_file_uploaded_invoice_letter.remove');
        Route::post('/get_amount_from_invoice/get', [App\Http\Controllers\receipts::class, 'get_amount_from_invoice'])->name('get_amount_from_invoice');
        Route::post('/reciepts', [App\Http\Controllers\receipts::class, 'store'])->name('receipts.store');
        //Route to Check List
        Route::post('/get_checklist_value', [App\Http\Controllers\ApplicationReceptionController::class, 'get_checklist_value'])->name('get_checklist_value');
        
        
        
        Route::get('/check_list', [App\Http\Controllers\check_list::class, 'index'])->name('check_list.index');//report_list.index
       
       
        Route::get('/check_list_test/{application_id}', [App\Http\Controllers\check_list::class, 'get_checked_values'])->name('application.checklist');


        //checklist_renew
        Route::get('/checklist_renew', [App\Http\Controllers\check_list::class, 'checklist_renew'])->name('application.checklist_renew');
        
        Route::get('/check_list_process_register/{application_id}', [App\Http\Controllers\check_list::class, 'process_checklist_register'])->name('application.checklist_re');


        //supervisor_check_progress_of_assessor.checklist_progress
        Route::get('/checklist/partially_Saved_application/{application_id}', [App\Http\Controllers\check_list::class, 'get_checked_partially_Saved'])->name('application.checklist_update');


        Route::get('/cchecklist/partially_Saved_application/{application_id}', [App\Http\Controllers\check_list::class, 'get_checked_partially_Saved_re'])->name('application.checklist_update_re');


        //supervisor_check_progress_of_assessor.checklist_progress
        Route::get('/checklist/print_checklist/{application_id}', [App\Http\Controllers\check_list::class, 'print_process_check_list'])->name('checklist.process_check_list');

        Route::get('/checklist/print_checklist-re/{application_id}', [App\Http\Controllers\check_list::class, 'print_process_check_list_re'])->name('checklist.process_check_list_re');

        Route::get('/check_list_test', function () {
            return view('process_check_list');
        });

        //Timeline controller
        Route::get('/Timeline', [App\Http\Controllers\Timeline::class, 'Timeline'])->name('Timeline');
        Route::get('/Timeline_show_applicant/{application_id}', [App\Http\Controllers\Timeline::class, 'Timeline_Applicant'])->name('Timeline_show_applicant');
        Route::get('/SuperVisorTimeline', [App\Http\Controllers\Timeline::class, 'SuperVisor_Timeline'])->name('SupervisorTimeline');
        Route::get('/Timeline_show_supervisor/{application_id}', [App\Http\Controllers\Timeline::class, 'Timeline_Supervisor'])->name('Timeline_show_supervisor');
        Route::get('/AssessorTimeline', [App\Http\Controllers\Timeline::class, 'Assessor_Timeline'])->name('AssessorTimeline');
        Route::get('/Timeline_show_assessor/{application_id}', [App\Http\Controllers\Timeline::class, 'Timeline_Assessor'])->name('Timeline_show_assessor');
        //Settings
        Route::resource('enlm', medicinesController::class);
        Route::resource('dosageforms', DosageFormsController::class);
        Route::resource('route_of_administration', route_administrations_controller::class);
        Route::resource('country_list', CountryListController::class);


        //Acknowledgement Letter_preliminary_screening
        Route::get('/Acknowledgement_Letter_preliminary_screening/{application_id}', [App\Http\Controllers\check_list::class, 'Acknowledgement_Letter'])->name('Acknowledgement_Letter_preliminary_screening');

        //Acknowledgement Letter_preliminary_screening
        Route::get('/reject_Acknowledgement_Letter_preliminary_screening_application/{application_id}', [App\Http\Controllers\check_list::class, 'reject_Acknowledgement_Letter_preliminary_screening_application'])->name('reject_Acknowledgement_Letter_preliminary_screening_application');


        //Acknowledgement_of_Receipt_of_Registration_Application
        Route::get('/Acknowledgement_of_Receipt_of_Registration_Application/{application_id}', [App\Http\Controllers\check_list::class, 'Acknowledgement_of_Receipt_of_Registration_Application'])->name('Acknowledgement_of_Receipt_of_Registration_Application');


        Route::post('/save_section_two_checklist/save', [App\Http\Controllers\check_list::class, 'save_section_two']);//->name('get_checklist_value');
        Route::post('/submit_section_two_checklist/save', [App\Http\Controllers\check_list::class, 'submit_section_two']);//->name('get_checklist_value');
        Route::post('/submit_section_three_checklist/save', [App\Http\Controllers\check_list::class, 'submit_section_three']);//->name('get_checklist_value');
        Route::post('/submit_section_four_checklist/save', [App\Http\Controllers\check_list::class, 'submit_section_four']);//->name('get_checklist_value');
        Route::post('/update_section_two_checklist/update', [App\Http\Controllers\check_list::class, 'update_section_two']);//->name('get_checklist_value');
        Route::post('/update_section_three_checklist/update', [App\Http\Controllers\check_list::class, 'update_section_three']);//->name('get_checklist_value');
        Route::post('/update_section_four_checklist/update', [App\Http\Controllers\check_list::class, 'update_section_four']);//->name('get_checklist_value');
        Route::post('/update_section_five_checklist/update', [App\Http\Controllers\check_list::class, 'update_section_five']);//->name('get_checklist_value');
        //SupervisorToAssessorController
        Route::get('/supervisor_check_progress_of_assessor/checklist_assessor', [App\Http\Controllers\SupervisorToAssessorController::class, 'index'])->name('SupervisorToAssessorController');

        //application_submited
        Route::get('/Application_submitted', [App\Http\Controllers\SupervisorToAssessorController::class, 'all_applications'])->name('Application_submitted');

        //supervisor_check_progress_of_assessor.checklist_progress
        Route::get('/supervisor_check_progress_of_assessor/checklist_assessor/process_check_list_partially_Saved_assessor/{application_id}', [App\Http\Controllers\SupervisorToAssessorController::class, 'supervise_assessor_progress'])->name('supervisor_check_progress_of_assessor.checklist_progress');
        Route::get('/supervisor_track_application_status/{application_id}', [App\Http\Controllers\SupervisorToAssessorController::class, 'supervisor_track_application_status'])->name('supervisor_track_application_status.application');

        //save_acknowledgment_letter/save
        Route::post('/save_acknowledgment_letter/save', [App\Http\Controllers\AcknowledgementLetterController::class, 'save_acknowledgementLetter'])->name('save_acknowledgement_letter');

        ///save_letter_reject_acknowledgment/save
        Route::post('/save_letter_reject_acknowledgment/save', [App\Http\Controllers\AcknowledgementLetterController::class, 'save_rejection_letter'])->name('save_rejection_letter');


        //save_acknowledgment_letter_psur/save
        Route::post('/save_acknowledgment_letter_psur/save', [App\Http\Controllers\AcknowledgmentLetterReceiptPsurController::class, 'save_acknowledgementLetter_psur'])->name('save_acknowledgement_letter_psur');

        //Download_file
        Route::get('Download_file', [App\Http\Controllers\PsurController::class, 'Download_file'])->name('Download_file');


        // upload_acknowledgment_letter/save     upload_file_issued_query
        Route::post('/upload_file_acknowledgement', [App\Http\Controllers\AcknowledgementLetterController::class, 'upload_file_acknowledgement'])->name('upload_file_acknowledgement');

        //upload_file_acknowledgement
        // upload_acknowledgment_letter/save     upload_file_issued_query
        Route::post('/upload_file_acknowledgement_reject', [App\Http\Controllers\AcknowledgementLetterController::class, 'upload_file_acknowledgement_reject'])->name('upload_file_acknowledgement_reject');


        //upload_file_acknowledgement_psur
        Route::post('/upload_file_acknowledgement_psur', [App\Http\Controllers\AcknowledgmentLetterReceiptPsurController::class, 'upload_file_acknowledgement_psur'])->name('upload_file_acknowledgement_psur');

        Route::post('/upload_file_swift_payment', [App\Http\Controllers\ApplicationReceptionController::class, 'upload_file_swift_payment'])->name('upload_file_swift_payment');

        //fetch_file_swift_payment'
        Route::post('/fetch_file_swift_payment', [App\Http\Controllers\ApplicationReceptionController::class, 'fetch_file_swift_payment'])->name('fetch_file_swift_payment');


        //Upload_review_report_of_PSUR
        Route::post('/Upload_review_report_of_PSUR', [App\Http\Controllers\PsurController::class, 'Upload_review_report_of_PSUR'])->name('Upload_review_report_of_PSUR');

        //fetch_review_report_of_PSUR
        Route::post('/fetch_review_report_of_PSUR', [App\Http\Controllers\PsurController::class, 'fetch_review_report_of_PSUR'])->name('fetch_review_report_of_PSUR');


        //delete_file_uploaded_acknowledgment_letter.remove
        Route::delete('/delete_file_uploaded_acknowledgment_letter.remove', [App\Http\Controllers\AcknowledgementLetterController::class, 'delete_file_uploaded_acknowledgment_letter'])->name('delete_file_uploaded_acknowledgment_letter.remove');

        //delete_file_uploaded_acknowledgment_letter_psur
        Route::delete('/delete_file_uploaded_acknowledgment_letter_psur.remove', [App\Http\Controllers\AcknowledgmentLetterReceiptPsurController::class, 'delete_file_uploaded_acknowledgment_letter_psur'])->name('delete_file_uploaded_acknowledgment_letter_pusr.remove');


        //delete_file_swift_payment
        Route::delete('/delete_file_swift_payment', [App\Http\Controllers\ApplicationReceptionController::class, 'delete_file_swift_payment'])->name('delete_file_swift_payment.remove');


        //Acknowledgment Letter fetch_uploaded_acknowledgement_letter_if_any
        Route::post('/take_uploaded_acknowledgement_letter_if_any', [App\Http\Controllers\AcknowledgementLetterController::class, 'fetch_uploaded_acknowledgement_letter_if_any'])->name('fetch_uploaded_acknowledgement_letter_if_any');

        //fetch_uploaded_acknowledgement_letter_if_any
        Route::post('/take_uploaded_acknowledgement_letter_if_any_psur', [App\Http\Controllers\AcknowledgmentLetterReceiptPsurController::class, 'fetch_uploaded_acknowledgement_letter_if_any_psur'])->name('fetch_uploaded_acknowledgement_letter_if_any_psur');


        //Generating Financial_Notification generating_financial_Notifications
        Route::get('/generating_financial_notifications', [App\Http\Controllers\receipts::class, 'generating_financial_notifications'])->name('generating_financial_notifications');
        //Saving finacial Notification to the database  save_financial_notification
        Route::post('/save_financial_notification', [App\Http\Controllers\receipts::class, 'save_financial_notification'])->name('save_financial_notification');
        //financial_notification.application
        Route::get('/financial_notification/{application_id}/application', [App\Http\Controllers\receipts::class, 'financial_notification_generate'])->name('financial_notification.application');
        //Issue Query
        Route::get('/Preliminary_screening_queries/{application_id}', [App\Http\Controllers\IssueQueryController::class, 'issue_query'])->name('application.IssueQuery');

        //upload_file_psur
        Route::post('/upload_file_psur', [App\Http\Controllers\PsurController::class, 'upload_file_psur'])->name('upload_file_psur');
        //fetch_psur_uploaded_files


        //upload_alert_psur_file
        Route::post('/upload_alert_psur_file', [App\Http\Controllers\PsurController::class, 'upload_alert_psur_file'])->name('upload_alert_psur_file');


        //Mailing
        Route::post('/mailing', [App\Http\Controllers\MailingController::class, 'mailing'])->name('mailing');

        //mailing_send
        Route::post('/mailing_send', [App\Http\Controllers\MailingController::class, 'mailing_send'])->name('mailing_send');


        //Mailing_supervisor
        Route::post('/mailing_su', [App\Http\Controllers\MailingController::class, 'mailing_su'])->name('mailing_su');

        //mailing_send_supervisor
        Route::post('/mailing_send_su', [App\Http\Controllers\MailingController::class, 'mailing_send_su'])->name('mailing_send_su');


        Route::post('/fetch_psur_uploded_files', [App\Http\Controllers\PsurController::class, 'fetch_psur_uploaded_files'])->name('fetch_psur_uploaded_files');
        
        //delete_file_data_uploaded_psur
        Route::post('/delete_file_data_uploaded_psur', [App\Http\Controllers\PsurController::class, 'delete_file_data_uploaded_psur'])->name('delete_file_data_uploaded_psur');
        
        //delete_file_data_uploaded_nmfa_director
         Route::post('/delete_file_data_uploaded_nmfa_director', [App\Http\Controllers\NmfaDirectorController::class, 'delete_file_data_uploaded_nmfa_director'])->name('delete_file_data_uploaded_nmfa_director');
        
        
        //fetch_alert_uploaded_files
        Route::post('/fetch_alert_uploaded_files', [App\Http\Controllers\PsurController::class, 'fetch_alert_uploaded_files'])->name('fetch_alert_uploaded_files');


        //fetch_alert_uploaded_files_nmfa
        Route::post('/fetch_alert_uploaded_files_nmfa', [App\Http\Controllers\NmfaDirectorController::class, 'fetch_alert_uploaded_files_nmfa'])->name('fetch_alert_uploaded_files_nmfa');

       //alert_from_nmfa_director
        Route::get('/alert_from_nmfa_director', [App\Http\Controllers\NmfaDirectorController::class, 'alert_from_nmfa_director'])->name('alert_from_nmfa_director');


        //check_preliminary_screening
        Route::post('/check_preliminary_screening', [App\Http\Controllers\check_list::class, 'check_preliminary_screening'])->name('check_preliminary_screening');


        //upload_file_CV_screen
        Route::post('/upload_file_CV_screen', [App\Http\Controllers\UserController::class, 'upload_file_CV_screen'])->name('upload_file_CV_screen');
        //upload_CV
        Route::post('/upload_file_CV', [App\Http\Controllers\UserController::class, 'Upload_CV'])->name('upload_file_CV');
        //delete_file_data_uploaded_cv
        Route::post('/delete_file_data_uploaded_cv', [App\Http\Controllers\UserController::class, 'delete_file_data_uploaded_cv'])->name('delete_file_data_uploaded_cv');
        // Issue Query  Front
        Route::post('/Preliminary_screening_queries/application_issuing_queries', [App\Http\Controllers\IssueQueryController::class, 'issue_query_front'])->name('application.IssueQuery_front');
        //save_preliminary_screening/save
        Route::post('/save_preliminary_screening/save', [App\Http\Controllers\IssueQueryController::class, 'save_issue_preliminary_screening'])->name('save_preliminary_screening');
        //     upload_file_issued_query
        Route::post('/upload_file_issued_query', [App\Http\Controllers\IssueQueryController::class, 'upload_file_issued_query'])->name('upload_file_issued_query');
        // upload_invoice_letter/save     upload_file_invoice_letter
        Route::post('/upload_invoice_letter', [App\Http\Controllers\invoice::class, 'upload_invoice_letter'])->name('upload_invoice_letter');
        //upload_file_issued_query_front_section
        Route::post('/upload_file_issued_query_from_front_section', [App\Http\Controllers\IssueQueryController::class, 'upload_file_issued_query_from_front_section'])->name('upload_file_issued_query_from_front_section');
        //upload_file_issued_query_from_to_assessor
        Route::post('/upload_file_issued_query_from_to_assessor', [App\Http\Controllers\IssueQueryController::class, 'upload_file_issued_query_from_applicant_to_assessor'])->name('upload_file_issued_query_from_to_assessor');
        //retrive_issued_query_from_applicant
        Route::post('/retrive_issued_query_from_applicant', [App\Http\Controllers\IssueQueryController::class, 'retrive_issued_query_from_applicant'])->name('retrive_issued_query_from_applicant');
        //retrive_issued_query_from_assessor
        Route::post('/retrive_issued_query_from_assessor', [App\Http\Controllers\IssueQueryController::class, 'retrive_issued_query_from_assessor'])->name('retrive_issued_query_from_assessor');
        //retrive_issued_query_from_front_section
        Route::post('/retrive_issued_query_from_front_section', [App\Http\Controllers\IssueQueryController::class, 'retrive_issued_query_from_front_section'])->name('retrive_issued_query_from_front_section');
        //retrive_anwered_query_from_applicant
        Route::post('/retrive_anwered_query_from_applicant', [App\Http\Controllers\IssueQueryController::class, 'retrive_anwered_query_from_applicant'])->name('retrive_anwered_query_from_applicant');
        //delete_file_data_applicant
        Route::post('/delete_file_data_applicant', [App\Http\Controllers\IssueQueryController::class, 'delete_file_data_applicant'])->name('delete_file_data_applicant');
        //delete_file_data_Assessor
        Route::post('/delete_file_data_Assessor', [App\Http\Controllers\IssueQueryController::class, 'delete_file_data_Assessor'])->name('delete_file_data_Assessor');
        // Preliminary Screening For Applicant
        Route::get('/preliminary_screening_query', [App\Http\Controllers\IssueQueryController::class, 'get_issued_queries'])->name('preliminary_screening_query_handle_applicant');
        // Preliminary Screening For Assesor
        Route::get('/preliminary_screening_query/Assessor', [App\Http\Controllers\IssueQueryController::class, 'issue_queries'])->name('preliminary_screening_query_handle_assesor');

        Route::get('/applicant/queries', [App\Http\Controllers\IssueQueryController::class, 'all_queries'])->name('all_queries');


        // fetch_application_number
        // Route::get('/fetch_application_number', [App\Http\Controllers\IssueQueryController::class, 'fetch_application_number'])->name('fetch_application_number');
        //Retreive Country Id   Get_country_id/GetId
        Route::post('Get_country_id/GetId', [App\Http\Controllers\ApplicationReceptionController::class, 'Get_country_id'])->name('Get_country_id');
        //Get Report Value
        $time = time();
        $date = date("Y-m-d", $time);
        $uri = 'report_list/' . sha1($date);
        Route::get('report_list/index' . $uri, [App\Http\Controllers\report_list::class, 'index'])->name('report_list.index');  //  Year.Generate_Year
        //Gnerate Reports From Data  Generate_Product_Report
        Route::POST('/Year_Generate_Report', [App\Http\Controllers\ReportController::class, 'Generate_Year_Report'])->name('Year.Generate_Year');
        Route::POST('/Generate_Product_Report', [App\Http\Controllers\ReportController::class, 'Generate_Product_Report'])->name('Product.Generate_Product_Report');
        Route::POST('/Generate_Applicant_Report', [App\Http\Controllers\ReportController::class, 'Generate_Applicant_Report'])->name('Applicant.Generate_Applicant_Report');
        Route::POST('/Generate_Country_Report', [App\Http\Controllers\ReportController::class, 'Generate_Address_Report'])->name('Country.Generate_Address_Report');
        Route::POST('/Generate_Applicant_Type_Report', [App\Http\Controllers\ReportController::class, 'Generate_Applicant_Type_Report'])->name('Applicant_Type.Generate_Applicant_Report');
        Route::get('reports/applications_processed', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'applications_processed'])->name('applications_processed_report');
        //Assign and un assignment of supervisor to the Assessor  assign_unassign_applicant
       
       
        Route::resource('assignment', AssignmentUnassignmentController::class);
        // Route::resource('un-assignment', AssignmentUnassignmentController::class);
        
        Route::GET('/un-assigned', [App\Http\Controllers\AssignmentUnassignmentController::class, 'unassigned'])->name('un-assignment.index');

        //un-assigned PSUR
        Route::GET('/un_assigned_psur', [App\Http\Controllers\PsurController::class, 'un_assigned_psur'])->name('un_assigned_psur.index');


          //un-assigned PSUR
          Route::GET('/psur_reviewed_report', [App\Http\Controllers\PsurController::class, 'psur_reviewed_report'])->name('psur_reviewed_report');

        //assignment_psur.store
        Route::POST('/assignment_psur/store', [App\Http\Controllers\PsurController::class, 'store'])->name('assignment_psur.store');

        //un_assigned_psur.index
        Route::GET('/assigned_psur', [App\Http\Controllers\PsurController::class, 'assigned_psur'])->name('assigned_psur.index');


        Route::get('/Acknowledgement_Letter_post_marketing/{application_id}', [App\Http\Controllers\AcknowledgmentLetterReceiptPsurController::class, 'Acknowledgement_Letter'])->name('Acknowledgement_Letter_post_marketing');


        Route::GET('/all_assigned_unassigned', [App\Http\Controllers\AssignmentUnassignmentController::class, 'all_assigned_unassigned'])->name('assignment.all_assigned_unassigned');
      
      
        //  Document Received and Uploaded To Applicant
        Route::GET('/documents_checked_from_assessor_perc_nmfa_director', [App\Http\Controllers\DocumentReceivedUploadedController::class, 'index'])->name('doc.index');
        
        
        Route::GET('/documents_financial_notification', [App\Http\Controllers\DocumentReceivedUploadedController::class, 'financial_notification'])->name('documents_financial_notification');

        //documents_financial_notification
        Route::GET('/documents_psurs', [App\Http\Controllers\DocumentReceivedUploadedController::class, 'documents_psurs'])->name('documents_psurs');

          //documents_financial_notification
          Route::GET('/documents_nmfa_alert', [App\Http\Controllers\DocumentReceivedUploadedController::class, 'documents_nmfa_alerts'])->name('documents_nmfa_alerts');


        Route::GET('/documents_invoice_received', [App\Http\Controllers\DocumentReceivedUploadedController::class, 'invoice_receipts'])->name('documents_invoice.invoice_received');
        Route::get('html-Pdf', 'HTMLPDFController@htmlPdf')->name('htmlPdf'); //customer_contact
        //Validation  customer Supplier Email,InstitutionalEmail
        Route::post('/Validate/email/customer_supply', [App\Http\Controllers\ApplicationReceptionController::class, 'validate_email'])->name('Email');
        //Validation   ,customer_contact
        Route::post('/Validate/email/customer_contact', [App\Http\Controllers\ApplicationReceptionController::class, 'validate_email_customer_contact'])->name('customer_contact');
        // Telephone Code
        Route::post('/get_tele_code/tele_code', [App\Http\Controllers\ApplicationReceptionController::class, 'get_tele_code'])->name('tele_code');
        //Validation  URL
        Route::post('/Validate/url/customer_supply', [App\Http\Controllers\ApplicationReceptionController::class, 'validate_url'])->name('Url');
        //Validation  Local Agent Information
        Route::post('/Validate/email/local_agent', [App\Http\Controllers\ApplicationReceptionController::class, 'validate_local_agent_email'])->name('local_agent_email');
        //Validation  URL for nanufacturer
        Route::post('/Validate/email/manufacturers', [App\Http\Controllers\ApplicationReceptionController::class, 'manufactrer_email'])->name('manufactrer_email');
        //Validation  URL for nanufacturer api
        Route::post('/Validate/email/api_manufactrer_email', [App\Http\Controllers\ApplicationReceptionController::class, 'api_manufactrer_email'])->name('api_manufactrer_email');
        Route::resource('books', BooksController::class);
        // Route::get('/', function () {return view('dashboard');});
        Route::get('/template', function () {
            return view('template');
        });
        Route::get('/template2', function () {
            return view('template2');
        });
        Route::get('file-import-export', [UserController::class, 'fileImportExport']);
        Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');
        Route::get('file-export', [UserController::class, 'fileExport'])->name('file-export');

// ----------------------------------Release Two Dossier Evaluation--------------------------

        // list all dossiers
        Route::get('/dossier_assignment/list_all', [App\Http\Controllers\DossierAssignmentController::class, 'all_index'])->name('all_index');
        // list all unassigned dossiers
        Route::get('/dossier_assignment/unassigned', [App\Http\Controllers\DossierAssignmentController::class, 'unassigned_index'])->name('unassigned_index');
        // list all assigned dossiers
        Route::get('/dossier_assignment/assigned', [App\Http\Controllers\DossierAssignmentController::class, 'assigned_index'])->name('assigned_index');
        Route::get('/dossier_assignment/reassign/{id}', [App\Http\Controllers\DossierAssignmentController::class, 'reassign_dossier_index'])->name('reassign_dossier_index');

        Route::get('/html_template/{id}/{dossier_asg_id}', [App\Http\Controllers\HtmlTemplateController::class, 'view_html_template'])->name('view_html_template');
        Route::resource('html_template', HtmlTemplateController::class);
        // assign dossier
        Route::get('/dossier_assignment/Dossiers', [App\Http\Controllers\DossierAssignmentController::class, 'index'])->name('dossier_tab');
        Route::post('/dossier_assignment/assign', [App\Http\Controllers\DossierAssignmentController::class, 'assign_dossier'])->name('assign_dossier');
        Route::post('/dossier_assignment/reassign', [App\Http\Controllers\DossierAssignmentController::class, 'reassign_dossier'])->name('reassign_dossier');
        // view assign dossier page
        Route::get('/dossier_assignment/assign/{id}', [App\Http\Controllers\DossierAssignmentController::class, 'assign_dossier_index']);
        Route::get('/retrieve_assessor_assignments', [App\Http\Controllers\DossierAssignmentController::class, 'retrieve_assessor_assignments'])->name('retrieve_assessor_assignments');
        Route::resource('document_types', DocumentTypeController::class);
        Route::post('/section_assignment/upload_evaluation', [App\Http\Controllers\DossierSectionAssigController::class, 'dossier_section_upload'])->name('dossier_section_upload');
        Route::post('/section_assignment/request_deadline_extension', [App\Http\Controllers\DossierSectionAssigController::class, 'dossier_section_deadline_extension'])->name('dossier_section_deadline_extension');
        Route::get('/section_assignment', [App\Http\Controllers\DossierSectionAssigController::class, 'index'])->name('dossier_section_assign_index');
        Route::get('/section_assignment/finished_sections', [App\Http\Controllers\DossierSectionAssigController::class, 'finished_index'])->name('finished_dossier_section_assign_index');
        Route::get('/section_assignment/show/{id}', [App\Http\Controllers\DossierSectionAssigController::class, 'show'])->name('dossier_section_assign_show');
        Route::get('main_task/show/{id}', [App\Http\Controllers\MainTaskController::class, 'show_timeline']);
        Route::post('/dossier_evaluation/send_to_inspection/', [App\Http\Controllers\DossierEvaluationController::class, 'send_to_inspection'])->name('send_to_inspection');
        Route::post('/dossier_evaluation/issue_query/', [App\Http\Controllers\DossierEvaluationController::class, 'send_query_issue'])->name('send_query_issue');
        Route::post('/dossier_evaluation/download_issue_query/', [App\Http\Controllers\DossierEvaluationController::class, 'download_query_issue'])->name('download_query_issue');
        Route::post('/update_deadline/extend', [App\Http\Controllers\DossierEvaluationController::class, 'update_deadline'])->name('update_deadline');
        Route::post('/update_deadline/app_extend', [App\Http\Controllers\application_status::class, 'app_update_deadline'])->name('app_update_deadline');
        Route::get('/update_qos_status/', [App\Http\Controllers\DossierEvaluationController::class, 'update_qos_status'])->name('update_qos_status');
        // ---DOSSIER EVALUATION ---
        Route::get('/dossier_evaluation/download_pdf/', [App\Http\Controllers\DossierEvaluationController::class, 'download_pdf'])->name('download_pdf');


        Route::post('/dossier_evaluation/download_qualitycontrol_pdf/', [App\Http\Controllers\DossierEvaluationController::class, 'download_qualitycontrol_pdf'])->name('download_qualitycontrol_pdf');

        Route::post('/dossier_evaluation/upload_assessment_report/', [App\Http\Controllers\DossierEvaluationController::class, 'upload_assessment_report'])->name('upload_assessment_report');
        Route::post('/dossier_evaluation/upload_qc_report/', [App\Http\Controllers\DossierEvaluationController::class, 'upload_qc_report'])->name('upload_qc_report');
        Route::post('/dossier_evaluation/upload_assigned_evaluation_response/', [App\Http\Controllers\DossierEvaluationController::class, 'upload_assigned_evaluation_response'])->name('upload_assigned_evaluation_response');
        Route::post('/dossier_evaluation/upload_query_response/', [App\Http\Controllers\DossierEvaluationController::class, 'upload_query_response'])->name('upload_query_response');
        Route::post('/dossier_evaluation/issue_query_index/{dossier_assig_id}/{document_type_id}', [App\Http\Controllers\DossierEvaluationController::class, 'issue_query_index'])->name('issue_query_index');
        Route::get('/dossier_evaluation/uploaded_documents/index/', [App\Http\Controllers\DossierEvaluationController::class, 'uploaded_documents_index'])->name('uploaded_documents_index');
        Route::post('/dossier_evaluation/assign_dossier_section/', [App\Http\Controllers\DossierEvaluationController::class, 'assign_dossier_section'])->name('assign_dossier_section');
        Route::post('/dossier_evaluation/view_document/', [App\Http\Controllers\DossierEvaluationController::class, 'view_document'])->name('view_document');
        Route::get('/dossier_evaluation', [App\Http\Controllers\DossierEvaluationController::class, 'index'])->name('dossier_evaluation_index');
        Route::get('supervisor/completed_dossier_evaluation', [App\Http\Controllers\DossierEvaluationController::class, 'completed_dossier_evaluation_index'])->name('completed_dossier_evaluation_index');
        Route::get('/completed_dossier_evaluation', [App\Http\Controllers\SupervisorController::class, 'completed_dossier_evaluation_index'])->name('completed_dossier_evaluations_index');
        Route::get('/dossier_evaluation/create', [App\Http\Controllers\DossierEvaluationController::class, 'create'])->name('dossier_evaluation_create');
        Route::get('/dossier_evaluation/edit/{id}', [App\Http\Controllers\DossierEvaluationController::class, 'edit'])->name('dossier_evaluation_edit');
        Route::get('/supervisor/assessor_report_submition/}', [App\Http\Controllers\DossierEvaluationController::class, 'supervisor_assessor_initial_submition'])->name('supervisor_assessor_initial_submition');
        Route::post('/dossier_evaluation/submit_to_supervisor', [App\Http\Controllers\DossierEvaluationController::class, 'submit_to_supervisor'])->name('submit_to_supervisor');
        /*Route::get('/update_qos_status/' ,[App\Http\Controllers\DossierEvaluationController::class, 'update_qos_status'])->name('update_qos_status');//this is for supervisor controller*/
        Route::get('/Assessment_reports/submitted', [App\Http\Controllers\SupervisorController::class, 'assessment_report_index'])->name('assessment_report_index');
        Route::get('/Meetings', [App\Http\Controllers\MeetingController::class, 'index'])->name('meeting_index');
        Route::get('/Assessment_reports/completed/assessments', [App\Http\Controllers\SupervisorController::class, 'completed_assessment_report_index'])->name('completed_assessment_report_index');
        Route::get('/Assessment_reports/submitted/{id}', [App\Http\Controllers\SupervisorController::class, 'show'])->name('assessment_report_detail');
        Route::post('/Assessment_reports/upload_comment', [App\Http\Controllers\SupervisorController::class, 'upload_commented_document'])->name('upload_commented_document');
        Route::get('/Supervisor/certification', [\App\Http\Controllers\SupervisorController::class, 'decision_que'])->name('decision_que');
        Route::get('/Supervisor/dealine_list', [App\Http\Controllers\SupervisorController::class, 'deadline_index'])->name('deadline_index');
        Route::get('/Supervisor/app_dealine_list', [App\Http\Controllers\SupervisorToAssessorController::class, 'app_deadline_index'])->name('app_deadline_index');
        Route::post('/Dossier Evaluation /request_deadline_extension', [App\Http\Controllers\SupervisorController::class, 'dossier_evaluation_deadline_extension'])->name('dossier_evaluation_deadline_extension');
        Route::get('/Supervisor/ongoing_Dossier_evaluations', [App\Http\Controllers\SupervisorController::class, 'supvervisor_ongoing_dossier_tasks'])->name('supvervisor_ongoing_dossier_tasks');

        Route::get('/supervisor/reregister_request_index', [\App\Http\Controllers\ReregistrationController::class, 'reregister_request_index'])->name('reregister_request_index');
        Route::post('/supervisor/update_renewal_deadline', [\App\Http\Controllers\ReregistrationController::class, 'update_renewal_deadline'])->name('update_renewal_deadline');


//applicant
        Route::get('/CompletedApplications/index', [App\Http\Controllers\DecisionController::class, 'applicant_decision_index'])->name('applicant_decision_index');
        Route::get('/Decisions/Details/{id}', [App\Http\Controllers\DecisionController::class, 'decision_applicant_details'])->name('decision_applicant_details');
        Route::post('/Decisions/deferment/query_response', [App\Http\Controllers\DecisionController::class, 'query_response'])->name('query_response');

        Route::post('/Decision/deferment_deadline_extension_request', [\App\Http\Controllers\DecisionController::class, 'applicant_query_deferment_deadline_extension_request'])->name('query_deferment_deadline_extension');


//variation release 4

        Route::get('/Applications/Completed/{id}', [App\Http\Controllers\DecisionController::class, 'applicant_decision_details'])->name('applicant_decision_details');
        Route::get('/Applications/reregistraton_open_index', [App\Http\Controllers\DecisionController::class, 'reregistraton_open_index'])->name('reregistraton_open_index');


        Route::get('/Variation/index/{id}', [App\Http\Controllers\VariationController::class, 'variation_applicant_index'])->name('variation_applicant_index');
        Route::get('/Variation/index/', [App\Http\Controllers\VariationController::class, 'index'])->name('variation_index');
        Route::post('/Variation/new', [App\Http\Controllers\VariationController::class, 'new_variation'])->name('new_variation');
        Route::post('/Variation/download_acknowledgment_letter', [App\Http\Controllers\VariationController::class, 'download_acknowledgment_letter'])->name('download_acknowledgment_letter');
        Route::get('/Variation/Acknowledgment/{id}', [App\Http\Controllers\VariationController::class, 'acknowledgment_details'])->name('variation_acknowledgment');
        Route::post('/Variation/Accept/Acknowledgment', [\App\Http\Controllers\VariationController::class, 'send_variation_acknowledgment'])->name('send_variation_acknowledgment');
        Route::get('/retrieve_assessor_variation_assignments', [App\Http\Controllers\VariationController::class, 'retrieve_assessor_assignments'])->name('retrieve_assessor_variation_assignments');
        Route::get('variation/assign/{id}', [App\Http\Controllers\VariationController::class, 'assign_variation_index'])->name('assign_variation_index');
        Route::post('/variation/assign', [App\Http\Controllers\VariationController::class, 'assign_variation'])->name('assign_variation');
        Route::get('variation/variation_query_template/{id}/{variation_id}', [App\Http\Controllers\VariationController::class, 'variation_template'])->name('variation_template');
        Route::post('/variation/submitAssessment/', [App\Http\Controllers\VariationController::class, 'send_variation_assessment'])->name('send_variation_assessment');
        Route::post('/variation/issue_query/', [App\Http\Controllers\VariationController::class, 'send_variation_query_issue'])->name('send_variation_query_issue');
        Route::get('/variation_evaluation', [App\Http\Controllers\VariationController::class, 'ongoing_index'])->name('variation_evaluation_index');

        Route::get('/variation_evaluation/edit/{id}', [App\Http\Controllers\VariationController::class, 'edit'])->name('variation_evaluation_edit');
        Route::get('/variation_evaluation/variation_decision_details/{id}', [App\Http\Controllers\VariationController::class, 'variation_decision_details'])->name('variation_decision_details');


        Route::post('/variation_evaluation/edit_variation_query_response/', [App\Http\Controllers\VariationController::class, 'edit_variation_query_response'])->name('edit_variation_query_response');


        Route::post('/Variations/decision/download_decision_letter', [App\Http\Controllers\VariationController::class, 'download_decision_letter'])->name('download_variation_decision_letter');
        Route::post('/Variation/Decision', [\App\Http\Controllers\VariationController::class, 'send_variation_decision'])->name('send_variation_decision');
        Route::post('/Variation/Appeal', [\App\Http\Controllers\VariationController::class, 'appeal_reject'])->name('appeal_reject');

        Route::get('/Variation/Applicant/Details/{id}', [App\Http\Controllers\VariationController::class, 'variation_applicant_details'])->name('variation_applicant_details');


        // Suspensions
        Route::get('/suspensions/index', [\App\Http\Controllers\SuspensionController::class, 'index'])->name('suspensions.index');
        Route::get('/suspensions/show/{suspension_id}', [\App\Http\Controllers\SuspensionController::class, 'show'])->name('suspensions.show');
        Route::get('/suspensions/show_app/{application_id}', [\App\Http\Controllers\SuspensionController::class, 'show_app'])->name('suspensions.show_app');
        Route::post('/suspensions/store', [App\Http\Controllers\SuspensionController::class, 'store'])->name('suspensions.store');
        Route::get('/suspensions/suspensions_index', [\App\Http\Controllers\SuspensionController::class, 'suspended_index'])->name('suspensions.suspended_index');
        Route::get('/suspensions/ceased_index', [\App\Http\Controllers\SuspensionController::class, 'ceased_index'])->name('suspensions.ceased_index');
        Route::post('/suspensions/store_appeal', [App\Http\Controllers\SuspensionController::class, 'store_appeal'])->name('suspensions.store_appeal');
        Route::post('/suspensions/store_appeal_moh', [App\Http\Controllers\SuspensionController::class, 'store_appeal_moh'])->name('suspensions.store_appeal_moh');
        Route::post('/suspensions/revoke_decision', [App\Http\Controllers\SuspensionController::class, 'revoke_decision'])->name('suspensions.revoke_decision');
        Route::post('/suspensions/void_decision', [App\Http\Controllers\SuspensionController::class, 'void_decision'])->name('suspensions.void_decision');
        Route::get('/suspensions/index_history', [\App\Http\Controllers\SuspensionController::class, 'index_history'])->name('suspensions.index_history');
        Route::get('/suspensions/wonder_history', [\App\Http\Controllers\SuspensionController::class, 'index_history'])->name('suspensions.wonder_history');
        Route::post('/suspensions/update', [App\Http\Controllers\SuspensionController::class, 'update'])->name('suspensions.update');
        Route::get('/suspensions/show_void/{suspension_id}', [\App\Http\Controllers\SuspensionController::class, 'show_void'])->name('suspensions.show_void');
        Route::post('/suspensions/store_response_letter', [App\Http\Controllers\SuspensionController::class, 'store_response_letter'])->name('suspensions.store_response_letter');
        Route::post('/suspensions/update_suspension_deadline', [App\Http\Controllers\SuspensionController::class, 'update_suspension_deadline'])->name('suspensions.update_suspension_deadline');
        Route::post('/suspensions/request_suspension_deadline_extension', [App\Http\Controllers\SuspensionController::class, 'request_suspension_deadline_extension'])->name('suspensions.request_suspension_deadline_extension');
        Route::post('/suspensions/store_sealed_letter', [\App\Http\Controllers\SuspensionController::class, 'store_sealed_letter'])->name('suspensions.store_sealed_letter');
        Route::post('/suspensions/suspend_to_cease', [\App\Http\Controllers\SuspensionController::class, 'suspend_to_cease'])->name('suspensions.suspend_to_cease');


        // Withdrawals
        Route::post('/withdrawals/store_withdrawal', [App\Http\Controllers\WithdrawalController::class, 'store_withdrawal'])->name('withdrawals.store_withdrawal');
        Route::get('/withdrawals/withdrawn_index', [\App\Http\Controllers\WithdrawalController::class, 'withdrawn_index'])->name('withdrawals.withdrawn_index');
        Route::get('/withdrawals/show/{withdrawal_id}', [\App\Http\Controllers\WithdrawalController::class, 'show_withdrawal'])->name('withdrawals.show');
        Route::post('/withdrawals/withdrawal_decision', [\App\Http\Controllers\WithdrawalController::class, 'withdrawal_decision'])->name('withdrawals.withdrawal_decision');
        Route::post('/withdrawals/update', [\App\Http\Controllers\WithdrawalController::class, 'update'])->name('withdrawals.update');
        Route::get('/withdrawals/withdrawn_requests', [\App\Http\Controllers\WithdrawalController::class, 'withdrawn_requests'])->name('withdrawals.withdrawn_requests');


// Reports for suspensions and withdrawals
        Route::get('/suspensions/show_report', [\App\Http\Controllers\SuspensionController::class, 'show_report'])->name('suspensions.show_report');
        Route::get('/suspensions/debug_report', [\App\Http\Controllers\SuspensionController::class, 'debug_report'])->name('suspensions.debug_report');
        Route::post('/suspensions/debug_report', [\App\Http\Controllers\SuspensionController::class, 'debug_report'])->name('suspensions.debug_report');


//release 3 supervisor control
        Route::post('/variation/upload_query_response/', [App\Http\Controllers\VariationController::class, 'upload_variation_query_response'])->name('upload_variation_query_response');

        Route::post('/Certifications/download_market_authorization_letter', [App\Http\Controllers\DecisionController::class, 'download_market_authorization_letter'])->name('download_market_authorization_letter');


        Route::get('/Decisions/Decision', [App\Http\Controllers\DecisionController::class, 'decision_index'])->name('decision_index');


        Route::post('/Decisions/deferment/send_deferral_query', [App\Http\Controllers\DecisionController::class, 'send_deferral_query'])->name('send_deferral_query');
        Route::post('/Decisions/deferment/return_to_assessor', [App\Http\Controllers\DecisionController::class, 'return_deferment_to_assessor'])->name('return_deferment_to_assessor');
        Route::post('/Decisions/download_decision_letter', [App\Http\Controllers\DecisionController::class, 'download_decision_letter'])->name('download_decision_letter');
        Route::get('/Decision/edit/{id}', [App\Http\Controllers\DecisionController::class, 'decision_details'])->name('decision_details');
        Route::post('/Supervisor/perc_invitation', [App\Http\Controllers\MeetingController::class, 'perc_decision_invitation'])->name('perc_decision_invitation');
        Route::post('/Supervisor/other_invitation', [App\Http\Controllers\MeetingController::class, 'other_invitation'])->name('other_invitation');
        Route::get('/Supervisor/certification/onload', [\App\Http\Controllers\SupervisorController::class, 'decision_que_onload'])->name('decision_que_onload');
        Route::get('/Supervisor/create/DecisionIinvitation', [\App\Http\Controllers\MeetingController::class, 'create'])->name('create_meeting');
        Route::get('/Supervisor/create/invitation', [\App\Http\Controllers\MeetingController::class, 'create_other_meeting'])->name('create_other_meeting');
        Route::get('/Supervisor/meeting/invitation', [\App\Http\Controllers\MeetingController::class, 'new_meeting'])->name('new_meeting');
        Route::get('/Meeting/upload/{id}', [\App\Http\Controllers\MeetingController::class, 'upload_meeting_details'])->name('uploade_meeting_details');
        Route::post('/Meeting/Reject', [\App\Http\Controllers\DecisionController::class, 'send_application_rejection'])->name('send_application_rejection');
        Route::post('/Meeting/Defer', [\App\Http\Controllers\DecisionController::class, 'send_application_deferral'])->name('send_application_deferral');
        Route::post('/Meeting/Accept', [\App\Http\Controllers\DecisionController::class, 'send_application_accept'])->name('send_application_accept');
        Route::post('/Supervisor/invitation_meeting/new', [\App\Http\Controllers\MeetingController::class, 'store'])->name('store_meeting');
        Route::post('/Supervisor/meeting/new', [\App\Http\Controllers\MeetingController::class, 'other_meeting_store'])->name('other_store_meeting');
        Route::post('/Meeting/upload', [\App\Http\Controllers\MeetingController::class, 'update_meeting'])->name('update_meeting');
        Route::post('/Meeting/postpone_meeting', [\App\Http\Controllers\MeetingController::class, 'postpone_meeting'])->name('postpone_meeting');
        Route::post('/Decision/upload/rejection', [\App\Http\Controllers\DecisionController::class, 'update_reject_decision'])->name('update_reject_decision');
        Route::get('/IssuedQueries', [App\Http\Controllers\DossierEvaluationController::class, 'evaluation_queries_index'])->name('evaluation_queries_index');


//PERC
        Route::get('/Meeting/PERC/Invitation', [\App\Http\Controllers\MeetingController::class, 'perc_meeting_index'])->name('perc_meeting_index');
        Route::get('/Meeting/PERC/Invitation_details/{id}', [\App\Http\Controllers\MeetingController::class, 'invitation_details'])->name('invitation_details');


//Release 4
        Route::post('/applicant/reregister_request', [\App\Http\Controllers\ReregistrationController::class, 'reregistration_deadline_extension_request'])->name('reregistration_deadline_extension_request');

        //Ajax
        Route::get('/retrieve_details', [App\Http\Controllers\DossierEvaluationController::class, 'retrieve_details'])->name('retrieve_details');
        Route::get('/deferred/query_details', [App\Http\Controllers\DecisionController::class, 'query_details'])->name('query_details');
        Route::get('/assesment_details', [App\Http\Controllers\DecisionController::class, 'assesment_details'])->name('assesment_details');
        Route::get('/retrieve_notifications', [App\Http\Controllers\NotificationController::class, 'retrieve_notifications'])->name('retrieve_notifications');
        Route::get('/retrieve_unit_staff', [App\Http\Controllers\DossierEvaluationController::class, 'retrieve_unit_staff'])->name('retrieve_unit_staff');
        Route::get('/update_dossier_tab', [App\Http\Controllers\DossierEvaluationController::class, 'update_dossier_tab'])->name('update_dossier_tab');

        Route::get('/retrive_all_information', [App\Http\Controllers\DecisionController::class, 'retrive_all_information'])->name('retrive_all_information');
        Route::get('/Meeting/retrive_information', [\App\Http\Controllers\MeetingController::class, 'retrive_application_information'])->name('information_retriver_ajax');
        Route::get('/Meeting/decision', [\App\Http\Controllers\MeetingController::class, 'product_decision'])->name('product_decision');

        Route::post('/dossier_evaluation/documents/delete/{id}', [App\Http\Controllers\DossierEvaluationController::class, 'delete_document'])->name('delete_document');
        Route::post('/dossier_evaluation/edit_query_response/', [App\Http\Controllers\DossierEvaluationController::class, 'edit_query_response'])->name('edit_query_response');
        Route::post('/dossier_evaluation/edit_section_assignment_response/', [App\Http\Controllers\DossierEvaluationController::class, 'edit_section_assignment_response'])->name('edit_section_assignment_response');
        Route::get('/DossierEvalutaion/ToQC', [App\Http\Controllers\DossierEvaluationController::class, 'to_qc_from_inspection_view'])->name('to_qc_from_inspection_view');
        Route::post('/dossier_evaluation/eletter_to_qcdit_qc_response/', [App\Http\Controllers\DossierEvaluationController::class, 'edit_qc_response'])->name('edit_qc_response');
        Route::get('/Notifications/', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification_index');
        Route::get('/Notifications/show/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notification_show');
        Route::post('/dossier_evaluation/edit_assessment_report/', [App\Http\Controllers\DossierEvaluationController::class, 'edit_assessment_report'])->name('edit_assessment_report');
        Route::get('/InspectionRequestController/', [App\Http\Controllers\InspectionRequestController::class, 'inspection_request_index'])->name('inspection_request_index');
        Route::get('/QCController/', [App\Http\Controllers\InspectionRequestController::class, 'qc_request_index'])->name('qc_request_index');
        Route::get('/InspectionRequestController/letter_to_qc/{id}', [App\Http\Controllers\InspectionRequestController::class, 'letter_to_qc'])->name('letter_to_qc');
        Route::post('/InspectionRequestController/send_to_qc_from_inspection/', [App\Http\Controllers\InspectionRequestController::class, 'send_to_qc_from_inspection'])->name('send_to_qc_from_inspection');
        Route::post('/Download_PDF/', [App\Http\Controllers\DossierEvaluationController::class, 'save_to_draft'])->name('save_to_draft');
        // ---TEMPLATES--- // show upload form of new document template
        Route::get('/templates/create/template', [App\Http\Controllers\TemplateController::class, 'create'])->name('template_create');
        // upload document to destination, save document details into db
        Route::post('/templates/upload', [App\Http\Controllers\TemplateController::class, 'upload'])->name('template_upload');
        // list all document templates
        Route::get('/assessment_report_templates', [App\Http\Controllers\TemplateController::class, 'index'])->name('template_index');
        // delete document
        Route::get('/templates/delete/{id}', [App\Http\Controllers\TemplateController::class, 'delete']);
        // edit document details, upload new template if required
        Route::get('/templates/edit/{id}', [App\Http\Controllers\TemplateController::class, 'edit'])->name('template_edit');
        // edit document details, upload new template if required
        Route::post('/templates/{id}', [App\Http\Controllers\TemplateController::class, 'update'])->name('template_update');
        // DOSSIER
        Route::get('/dossier/index', [\App\Http\Controllers\DossierController::class, 'index'])->name('dossier.index');
        Route::get('/dossier/show/{id}', [\App\Http\Controllers\DossierController::class, 'show'])->name('dossier.show');
        Route::get('/dossier/delete/{id}', [\App\Http\Controllers\DossierController::class, 'delete_all'])->name('dossier.delete_all');
        //test
        Route::get('/handle', [\App\Console\Commands\registration_expiry_notification::class, 'handle']);


        //Reports -  fetch the report routes

        Route::post('/assessor_task_reports', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_assessor_tasks'])->name('assessor_report');
        Route::Post('/application_eval_status', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_evaluation_status'])->name('get_application_eval_status');
        Route::Post('/assessor_tasks_timelapse', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_assessor_tasks_timelapse'])->name('get_assessor_tasks_timelapse');
        Route::Post('/sample_test_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_sample_test_report'])->name('get_sample_test_report');
        Route::Post('/meeting_reports', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_meetings'])->name('get_meetings');
        Route::Post('/appeal_reports', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_appeal'])->name('get_appeals');
        Route::Post('/application_processed', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_application_processed'])->name('get_application_processed');
        Route::Post('/get_regulatory_time_taken_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_regulatory_time_taken'])->name('get_regulatory_time_taken');
        Route::Post('/get_variation_assessment_time_taken_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_variation_assessment_time_taken_report'])->name('get_variation_assessment_time_taken');


        //get_application_received
        Route::Post('/get_application_received', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_application_received'])->name('get_application_received');


        // REPORTS - Search Criteria page (index pages) routes
        Route::get('/reports/assessor_tasks_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'assessor_tasks_report'])->name('assessor_tasks_report');
        Route::get('reports/application_eval_status', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'evaluation_status_report_index'])->name('application_eval_status_report_index');
        Route::get('reports/assessor_tasks_timelapse', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'assessor_tasks_timelapse_index'])->name('assessor_tasks_timelapse_index');
        Route::get('reports/sample_test_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'sample_test_report_index'])->name('sample_test_report_index');
        // Route::get('reports/', [\App\Http\Controllers\Reports\AssessorReportsController::class,'evaluation_status_report_index'])->name('application_eval_status_report_index');
        Route::get('reports/meeting_reports', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'meeting_reports'])->name('meeting_report_index');
        Route::get('reports/appeal_reports', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'appeal_reports'])->name('appeal_report_index');
        Route::get('reports/applications_processed', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'applications_processed'])->name('applications_processed_report');
        Route::get('reports/regulatory_time_taken_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'regulatory_time_taken_index'])->name('regulatory_time_taken_index');
        Route::get('reports/get_variation_assessment_time_taken_report', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'get_variation_assessment_time_taken_index'])->name('get_variation_assessment_time_taken_index');


        //applications_received_report
        Route::get('reports/applications_received', [\App\Http\Controllers\Reports\AssessorReportsController::class, 'applications_received'])->name('applications_received_report');

        // Yemane Extension

        Route::post('/Query/request_deadline_extension', [App\Http\Controllers\DossierEvaluationController::class, 'query_deadline_extension'])->name('query_deadline_extension');
        Route::post('/VariationQuery/request_deadline_extension', [App\Http\Controllers\VariationController::class, 'variation_query_deadline_extension'])->name('variation_query_deadline_extension');
        Route::post('/Sample/request_deadline_extension', [App\Http\Controllers\InspectionRequestController::class, 'sample_deadline_extension'])->name('sample_deadline_extension');



    });
}
);