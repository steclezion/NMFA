<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\CountryController;
use App\HTTP\Controllers\Auth\RegisterController;
use App\HTTP\Controllers\BooksController;

use App\Http\Controllers\DossierAssignmentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DossierEvaluationController;
use App\Http\Controllers\HtmlTemplateController;

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
Route::get('/login',function() { return view('auth/login');  })->name('login');
Route::get('/register', function () {  return view('auth/register'); })->name('signup');
Route::get('/register', [App\Http\Controllers\CountryController::class, 'country'])->name('signup');
Route::post('/customregistration', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('customreg');

//Authenticate Routes Login
Route::post('/singin', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('signin');
//Authenticate Routes Logout
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/Validate/post' ,[App\Http\Controllers\Auth\RegisterController::class, 'validate_Register'])->name('RegisterController.validate');
Route::post('/Validate/user' ,[App\Http\Controllers\Auth\RegisterController::class, 'validate_User'])->name('RegisterController.validatesusername');

Route::middleware('auth')->group(function()
{

    Route::get('/', [App\Http\Controllers\ApplicationReceptionController::class, 'Request_for_all'])->name('application_reception');

//Route::get('/', function () { return view('application_Reception'); })->name('application_reception');
//Route::get('/application_status', function () { return view('application_status'); })->name('application_status');

Route::get('/application_reception', [App\Http\Controllers\ApplicationReceptionController::class, 'Request_for_all'])->name('application_reception');
Route::post('/insert/compostion' ,[App\Http\Controllers\ApplicationReceptionController::class, 'store_retrive_composition'])->name('insert.compostion');
Route::post('/company_supplier' ,[App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier'])->name('company_supplier');
Route::post('/company_supplier_update' ,[App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier_update'])->name('company_supplier_update');
Route::post('/application_reception' ,[App\Http\Controllers\ApplicationReceptionController::class, 'company_supplier_save'])->name('company_supplier_save');
Route::post('/agent_save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'agent_save'])->name('agent_save');
Route::post('/agent_update' ,[App\Http\Controllers\ApplicationReceptionController::class, 'agent_update'])->name('agent_update');
Route::post('/product_details/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_details_save'])->name('product_details_save');
Route::post('/product_details/update' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_details_update'])->name('product_details_update');
Route::post('/product_manufacturer/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_manufacturer_save'])->name('product_manufacturer_save');
Route::post('/product_manufacturer/update' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_manufacturer_update'])->name('product_manufacturer_update');
Route::post('/product_composition/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_composition_save'])->name('product_composition_save');
Route::post('/product_Composition/update' ,[App\Http\Controllers\ApplicationReceptionController::class, 'product_composition_update'])->name('product_composition_update');
Route::post('/save_product_manufacturer_api/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'save_product_manufacturer_api'])->name('save_product_manufacturer_api');
Route::post('/application/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'application_save'])->name('application_save');
Route::post('/dossier_status/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'dossier_sample_save'])->name('dossier_sample_save');
Route::post('/decleration_save/save' ,[App\Http\Controllers\ApplicationReceptionController::class, 'decleration_save'])->name('decleration_save');

Route::get('/application_reception/update/{application_id}',[App\Http\Controllers\ApplicationReceptionController::class,'application_reception_complete_wizard_control'])->name('application.update');

Route::get('/application_status', [App\Http\Controllers\application_status::class, 'index'])->name('application_status');
//Route::get('/invoice', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_invoices'])->name('generate_invoices');
//Route::get('/invoice',function() { return view('invoice');  })->name('generate_invoices');

Route::get('/invoice', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_invoices'])->name('generate_invoices');

Route::post('/invoice_generate', [App\Http\Controllers\ApplicationReceptionController::class, 'generate_invoices_now'])->name('invoice_generate');


Route::get('/generate_invoice/{application_id}',[App\Http\Controllers\ApplicationReceptionController::class,'generate_invoices'])->name('application.invoice_generate');

Route::get('/invoice.index',[App\Http\Controllers\invoice::class,'index'])->name('invoices.index');



Route::post('/invoice/save_invoices_now',[App\Http\Controllers\ApplicationReceptionController::class,'save_invoices_now'])->name('invoices.save_invoices_now');



Route::get('/invoice.edit',[App\Http\Controllers\invoice::class,'edit'])->name('invoices.edit');

Route::get('/receipts.index',[App\Http\Controllers\receipts::class,'index'])->name('receipts.index');


Route::get('/receipt',[App\Http\Controllers\ApplicationReceptionController::class, 'generated_invoices'])->name('receipts');

Route::get('/reciepts',[App\Http\Controllers\receipts::class, 'store'])->name('receipts.store');



Route::get('/check_list',[App\Http\Controllers\check_list::class, 'index'])->name('check_list.index');



Route::get('/get_amount_from_invoice/get',[App\Http\Controllers\ApplicationReceptionController::class, 'get_amount_from_invoice'])->name('get_amount_from_invoice');


Route::post('/get_checklist_value',[App\Http\Controllers\ApplicationReceptionController::class, 'get_checklist_value'])->name('get_checklist_value');





Route::resource('books', BooksController::class);

Route::get('/', function () {
    return view('dashboard');
});



Route::get('/template', function () {
    return view('template');
});
Route::get('/template2', function () {
    return view('template2');
});

Route::get('file-import-export', [UserController::class, 'fileImportExport']);
Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');
Route::get('file-export', [UserController::class, 'fileExport'])->name('file-export');





// list all dossiers

Route::get('/dossier_assignment/list_all',[DossierAssignmentController::class,'all_index'])->name('all_index');

// list all unassigned dossiers
Route::get('/dossier_assignment/unassigned',[DossierAssignmentController::class,'unassigned_index'])->name('unassigned_index');
// list all assigned dossiers
Route::get('/dossier_assignment/assigned',[DossierAssignmentController::class,'assigned_index'])->name('assigned_index');

Route::get('/html_template/{id}/{dossier_asg_id}',[HtmlTemplateController::class,'view_html_template'])->name('view_html_template');
Route::resource('html_template',HtmlTemplateController::class);
// assign dossier
Route::post('/dossier_assignment/assign',[DossierAssignmentController::class,'assign_dossier'])->name('assign_dossier');
// view assign dossier page
Route::get('/dossier_assignment/assign/{id}',[DossierAssignmentController::class,'assign_dossier_index']);
Route::get('/retrieve_assessor_assignments',[DossierAssignmentController::class,'retrieve_assessor_assignments'])->name('retrieve_assessor_assignments');
Route::resource('document_types',DocumentTypeController::class);
Route::get('main_task/show/{id}',[\App\Http\Controllers\MainTaskController::class,'show_timeline']);
Route::post('/dossier_evaluation/send_to_qc', [DossierEvaluationController::class,'send_to_qc'])->name('send_to_qc');
Route::post('/dossier_evaluation/issue_query', [DossierEvaluationController::class,'send_query_issue'])->name('send_query_issue');
Route::post('/update_deadline/extend' ,[DossierEvaluationController::class, 'update_deadline'])->name('update_deadline');
Route::get('/update_qos_status/' ,[DossierEvaluationController::class, 'update_qos_status'])->name('update_qos_status');

// ---DOSSIER EVALUATION ---
Route::get('/dossier_evaluation/download_pdf/',[DossierEvaluationController::class,'download_pdf'])->name('download_pdf');
Route::post('/dossier_evaluation/upload_assessment_report/',[DossierEvaluationController::class,'upload_assessment_report'])->name('upload_assessment_report');
    Route::post('/dossier_evaluation/upload_qc_report/',[DossierEvaluationController::class,'upload_qc_report'])->name('upload_qc_report');
    Route::post('/dossier_evaluation/upload_assigned_evaluation_response/',[DossierEvaluationController::class,'upload_assigned_evaluation_response'])->name('upload_assigned_evaluation_response');
Route::post('/dossier_evaluation/upload_query_response/',[DossierEvaluationController::class,'upload_query_response'])->name('upload_query_response');

Route::post('/dossier_evaluation/issue_query_index/{dossier_assig_id}/{document_type_id}',
    [DossierEvaluationController::class,'issue_query_index'])->name('issue_query_index');

Route::get('/dossier_evaluation/uploaded_documents/index', [DossierEvaluationController::class, 'uploaded_documents_index']) ->name('uploaded_documents_index');
Route::post('/dossier_evaluation/assign_dossier_section', [DossierEvaluationController::class, 'assign_dossier_section']) ->name('assign_dossier_section');
    Route::post('/dossier_evaluation/view_document', [DossierEvaluationController::class, 'view_document']) ->name('view_document');
    Route::post('/dossier_evaluation/submit_to_supervisor', [DossierEvaluationController::class, 'submit_to_supervisor']) ->name('submit_to_supervisor');


//Ajax

    Route::get('/retrieve_details/', [DossierEvaluationController::class, 'retrieve_details'])->name('retrieve_details');



Route::post('/dossier_evaluation/documents/delete/{id}',
    [DossierEvaluationController::class,'delete_document'])->name('delete_document');

Route::post('/dossier_evaluation/edit_query_response/',[DossierEvaluationController::class,'edit_query_response'])->name('edit_query_response');
Route::post('/dossier_evaluation/edit_section_assignment_response/',[DossierEvaluationController::class,'edit_section_assignment_response'])->name('edit_section_assignment_response');

Route::post('/dossier_evaluation/edit_qc_response/',
        [DossierEvaluationController::class,'edit_qc_response'])->name('edit_qc_response');


Route::post('/dossier_evaluation/edit_assessment_report/',
        [DossierEvaluationController::class,'edit_assessment_report'])->name('edit_assessment_report');

Route::resource('dossier_evaluation',DossierEvaluationController::class);




// ---TEMPLATES---
// show upload form of new document template
Route::get('/templates/create/template', [TemplateController::class, 'create'])->name('template_create');

// upload document to destination, save document details into db
Route::post('/templates/upload', [TemplateController::class, 'upload'])->name('template_upload');

// list all document templates
Route::get('/templates/', [TemplateController::class, 'index'])->name('template_index');

// delete document
Route::get('/templates/delete/{id}', [TemplateController::class, 'delete']);

// edit document details, upload new template if required
Route::get('/templates/edit/{id}', [TemplateController::class, 'edit']);

// edit document details, upload new template if required
Route::post('/templates/{id}', [TemplateController::class, 'update'])->name('template_update');

//test - notification 
// send notification to logged in user auth()->user()->id
Route::get('dossier_assignment_notification', function(){

    event (new App\Events\DossierAssignmentEvent(auth()->user()->id, 'hello: dossier assigned'));
    return 'Dossier Assignment event fired';
});

});