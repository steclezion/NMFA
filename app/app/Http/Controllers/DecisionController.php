<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Events\DossierAssignmentEvent;

use App\Models\Attachment;
use App\Models\applications;
use App\Models\DefermentQuery;
use App\Models\AppSetting;
use App\Models\AssessmentReport;
use App\Models\User;
use \Mpdf\Mpdf as PDFF;
use Illuminate\Support\Facades\Storage;

use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\uploaded_documents;
use App\Models\MainTask;
use App\Models\Meeting;
use App\Models\Decision;
use App\Models\DecisionParticipant;
use App\Models\uploaded_documnts;
use App\Notifications\InformationNotification;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use PDF;

class DecisionController extends Controller
{


    //this function below retrieves the main task id based on the given assignment id
    private function get_main_task_id($dossier_ass_id, $related_type = 'Dossier Evaluation')
    {

        $main_task = MainTask::where('related_id', $dossier_ass_id)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {

            return 0; //means false
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function applicant_decision_index()
    {
        $products=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->where('applications.user_id',auth()->user()->id)
        ->where('decisions.sealed_document_id','!=',null)
        ->select('applications.*','medicines.product_name','decisions.decision_status','decisions.id as decision_id','decisions.locked')
        ->get();

        return view('decisions.applicant_index',['products'=>$products]);
    }
    public function decision_index()
    {


        $rejected_decisions=$meetings = Meeting::join('decisions','decisions.meeting_id','meetings.id')
        ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->where('meetings.supervisor_id',auth()->user()->id)
        ->where('decision_status','Rejected')
        ->orWhere('decision_status','Reassign')
        ->select('decisions.*','meetings.meeting_date',
        'company_suppliers.trade_name as company_name',        
        'medicinal_products.product_trade_name',)
        ->get();
        $deferred_decisions=$meetings = Meeting::join('decisions','decisions.meeting_id','meetings.id')
        ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->where('decision_status','Deferred')
        ->where('meetings.supervisor_id',auth()->user()->id)
        ->select('decisions.*','meetings.meeting_date',
        'company_suppliers.trade_name as company_name',        
        'medicinal_products.product_trade_name',)
        ->get();
        $accepted_decisions=$meetings = Meeting::join('decisions','decisions.meeting_id','meetings.id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
        ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
        ->join('users as applicant', 'applicant.id', 'applications.user_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->where('decision_status','Accepted')
        ->where('meetings.supervisor_id',auth()->user()->id)
        ->select('decisions.*','meetings.meeting_date',
        'company_suppliers.trade_name as company_name',        
        'medicinal_products.product_trade_name',
        'certifications.registration_number','certifications.certificate_number')
        ->get();
        return view('decisions.decision_tab',['rejected_decisions'=>$rejected_decisions
        ,'deferred_decisions'=>$deferred_decisions,'accepted_decisions'=>$accepted_decisions]);
    }
   

    public function send_application_rejection(Request $request)
    {
       
        try {

            // query response attached in zip file
            $attachment = $request->file('attachment');
            $dir = 'documents/uploads';
            $attachment_available=false;
            $attach_path=null;
            if($attachment!=null)
            {
            $attachment_filename = time() . '_' . $attachment->getClientOriginalName();
            $attach_path = $dir . '/' . $attachment_filename;
            
            $attachment->move($dir, $attachment_filename);
            $attachment_available=true;
            }
            

            // Sealed Rejection Letter from the company
            $rejection_letter_file = $request->file('rejection_letter');
            $rejection_letter_filename = time() . '_' . $rejection_letter_file->getClientOriginalName();
            //todo: change dir to storage disk
           
            
            $rejection_letter_path = $dir . '/' . $rejection_letter_filename;

            // Upload files (copy files to destination)
            $rejection_letter_file->move($dir, $rejection_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $rejection_letter_path, $request,$attachment_available) {
                $decision_id = $request->input('decision_id');
                $uploaded_document = new uploaded_documents;
                $description = 'Rejection letter to applicant and agent';
        
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num ='';
                $uploaded_document->name = 'Application Rejection Letter sent to Applicant and Agent ';
                $uploaded_document->path = $rejection_letter_path;
                $uploaded_document->document_type = 22; //TODO seed to document type 22 as rejection document
                $uploaded_document->description = $description;

                
                $uploaded_document->save();
                $uploaded_document_id = $uploaded_document->id;

                //Insert attachment to attachments table
                if($attachment_available){
                Attachment::insert([
                    'uploaded_documents_id' => $uploaded_document->id,
                    'path' => $attach_path,
                ]);
                
                }
       
                $decision_id=$request->input('decision_id');
               

                
                Decision::where('id',$decision_id)->update(
                    [
                        'sealed_document_id'=>$uploaded_document->id,
                        'appeal'=>true,
                        'attachments'=>$attachment_available
                    ]
                    );
                   //Lock the application after two months of rejection (if appeal is not received)

//Todo main task, notification etc..

            
            }); // end transaction

             return Redirect()->back()->with('success', 'Rejection Letter Successfully Sent to Applicant.');





            } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }

    }

    public function send_application_deferral(Request $request)
    {
       
        try {

            // query response attached in zip file
            $attachment = $request->file('attachment');
            $dir = 'documents/uploads';
            $attachment_available=false;
            $attach_path=null;
            if($attachment!=null)
            {
            $attachment_filename = time() . '_' . $attachment->getClientOriginalName();
            $attach_path = $dir . '/' . $attachment_filename;
            
            $attachment->move($dir, $attachment_filename);
            $attachment_available=true;
            }
            

            // Sealed Deferment Letter from the company
            $deferment_letter_file = $request->file('deferment_letter');
            $deferment_letter_filename = time() . '_' . $deferment_letter_file->getClientOriginalName();
            //todo: change dir to storage disk
           
            
            $deferment_letter_path = $dir . '/' . $deferment_letter_filename;

            // Upload files (copy files to destination)
            $deferment_letter_file->move($dir, $deferment_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $deferment_letter_path, $request,$attachment_available) 
            {
                $decision_id=$request->input('decision_id');
                $uploaded_document = new uploaded_documents;
                $description = 'Deferral letter to applicant and agent';
        
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num ='';
                $uploaded_document->name = 'Application Deferment Letter sent to Applicant and Agent ';
                $uploaded_document->path = $deferment_letter_path;
                $uploaded_document->document_type = 24; //TODO seed to document type 24 as deferral document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                if($attachment_available){
                    Attachment::insert([
                        'uploaded_documents_id' => $uploaded_document->id,
                        'path' => $attach_path,
                    ]);
                    
                    }
           
                    Decision::where('id',$decision_id)->update(
                        [
                            'sealed_document_id'=>$uploaded_document->id,
                            'attachments'=>$attachment_available
                        ]
                        );
                
            
                        $decision=Decision::where('id',$decision_id)->first();
                        DefermentQuery::insert(
                            [
                                'decision_id'=>$decision_id,
                                'sent_date'=>now(),
                                'status'=>'Sent',
                                'sent_document_id'=>$uploaded_document->id,
                                'sent_subject'=>'Deferment Decision',
                                'sent_query'=>'Deferment Decision Letter Sent to the Applicant, Deadline is '.$decision->deferred_date

                            ]   
                            );

                    //this goes to main task
                   
               //Todo main task, notification etc..

            
            }); // end transaction

             return Redirect()->back()->with('success', 'Deferment Letter Successfully Sent to Applicant.');





            } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }

    }


    public function send_application_accept(Request $request)
    {
       
    try {

            // query response attached in zip file
            $attachment = $request->file('attachment');
            $dir = 'documents/uploads';
            $attachment_available=false;
            $attach_path=null;
            if($attachment!=null)
            {
            $attachment_filename = time() . '_' . $attachment->getClientOriginalName();
            $attach_path = $dir . '/' . $attachment_filename;
            
            $attachment->move($dir, $attachment_filename);
            $attachment_available=true;
            }
            

            // Sealed Rejection Letter from the company
            $acceptance_letter_file = $request->file('acceptance_letter');
            $mah_letter_file = $request->file('mah_letter');
            $acceptance_letter_filename = time() . '_' . $acceptance_letter_file->getClientOriginalName();
            $mah_letter_filename = time() . '_' . $mah_letter_file->getClientOriginalName();
            //todo: change dir to storage disk
           
            
            $acceptance_letter_path = $dir . '/' . $acceptance_letter_filename;
            $mah_letter_path = $dir . '/' . $mah_letter_filename;

            // Upload files (copy files to destination)
            $acceptance_letter_file->move($dir, $acceptance_letter_filename);
            $mah_letter_file->move($dir, $mah_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $acceptance_letter_path,$mah_letter_path, $request,$attachment_available) {

                $decision_id=$request->input('decision_id');

                $decision = Decision::find($decision_id);



                $dossier_assignment = dossier_assignment::find($decision->dossier_assignment_id);

                $application = applications::find($dossier_assignment->application_id);
                $uploaded_document = new uploaded_documents;
                $description = 'Accpetance letter to applicant and agent';
        
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num ='';
                $uploaded_document->name = 'Application Acceptance Letter sent to Applicant and Agent ';
                $uploaded_document->path = $acceptance_letter_path;
                $uploaded_document->document_type = 25; //TODO seed to document type 25 as Accept document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $acceptance_letter_id=$uploaded_document->id;




                $uploaded_document = new uploaded_documents;
                $description = 'MAH Certificate to applicant and agent';
        
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num ='';
                $uploaded_document->name = 'MAH Certificate sent to Applicant and Agent ';
                $uploaded_document->path = $acceptance_letter_path;
                $uploaded_document->document_type = 26; //TODO seed to document type 26 as MAH Certificate  document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $mah_letter_id=$uploaded_document->id;


                if($attachment_available){
                    Attachment::insert([
                        'uploaded_documents_id' => $acceptance_letter_id,
                        'path' => $attach_path,
                    ]);
                    
                    }

                
                Decision::where('id',$decision_id)->update(
                    [
                        'decision_status'=>'Accepted',
                        'sealed_document_id'=>$acceptance_letter_id,
                        'attachments'=>$attachment_available
                    ]
                    );





                    DB::table('certifications')->where('decision_id',$decision_id)->update(
                        [
                            
                            'sealed_MA_document'=>$mah_letter_id,
                        ]
                    );




                if ($application->application_type == 1) {

                        applications::where('id', $application->id)
                            ->update([
                                'progress_percentage' => $application->progress_percentage + 10,
                            ]);


                    }else{ // fast track
                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $application->progress_percentage + 20,
                        ]);

                }


                   


// generate certificate 
// generate registration number 
// generate registration number 

                    //this goes to main task
                   
//Todo main task, notification etc..

            
            }); // end transaction

             return Redirect()->back()->with('success', 'Accpetance Letter Successfully Sent to Applicant.');





            } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }

    }
    public function decision_details($id)
    {
        $decision=Decision::where('decisions.id',$id)
        ->join('meetings','meetings.id','decisions.meeting_id')
        ->leftjoin('uploaded_documents as downloaded_document','downloaded_document.id','decisions.downloaded_document_id')
        ->leftjoin('uploaded_documents as sealed_document','sealed_document.id','decisions.sealed_document_id')
        ->leftjoin('uploaded_documents as minutes_document','minutes_document.id','meetings.minutes_id')
        ->leftjoin('uploaded_documents as appeal_letter','appeal_letter.id','decisions.appeal_letter_id')
        ->select('decisions.*','meetings.description','meetings.meeting_date','meetings.time','meetings.venue',
        'downloaded_document.path as downloaded_document_path','sealed_document.path as sealed_document_path','minutes_document.path as minute_path','appeal_letter.path as appeal_letter_path')
        ->first();
        // dd($decision);
        // dd($decision);
        $attachment=Attachment::where('uploaded_documents_id',$decision->sealed_document_id)->first();
        $participants=DB::table('decision_participants')->join('users','users.id','decision_participants.committee_id')
        ->where('meeting_id',$decision->meeting_id)
        ->get();
        $deferment_queries=DefermentQuery::where('deferment_queries.decision_id',$decision->id)
        ->leftjoin('uploaded_documents as sent_document','sent_document.id','deferment_queries.sent_document_id')
        ->leftjoin('uploaded_documents as received_document','received_document.id','deferment_queries.received_document_id')
        ->select('deferment_queries.*','sent_document.path as sent_document_path','received_document.path as received_document_path')
        ->get();
        $check_query_with_out_deferment=DefermentQuery::where('deferment_queries.decision_id',$decision->id)
        ->where('received_date',null)
        ->get();
        $all_queries_reponsed=false;
        if(count($check_query_with_out_deferment)==0)
        {
            $all_queries_reponsed=true;
           
        }
        
            

        // dd($participants);
        $assessor = User::join('dossier_assignments', 'dossier_assignments.assessor_id', 'users.id')
        ->where('dossier_assignments.id', $decision->dossier_assignment_id)
            ->select('users.*')
            ->first();


        $certification=null;
        if($decision->decision_status=='Accepted'){
            $certification=DB::table('certifications')
            ->leftjoin('uploaded_documents as downloaded_MAH_document','downloaded_MAH_document.id','certifications.MA_document_downloaded')
            ->leftjoin('uploaded_documents as sealed_MAH_document','sealed_MAH_document.id','certifications.sealed_MA_document')
            ->where('decision_id',$decision->id)
            ->select('certifications.*','downloaded_MAH_document.path as downloaded_MAH_document_path','sealed_MAH_document.path as sealed_MAH_document_path')
            ->first();
            $view="decisions.accepted_edit";
        }
        
        elseif($decision->decision_status=='Deferred'){
            $view="decisions.deferred_edit";
        }
        
        else{
            $view="decisions.rejected_edit";
        }
        $date=date('Y-m-d');


        return view($view,['decision'=>$decision,'participants'=>$participants,'date'=>$date,'all_queries_reponsed'=>$all_queries_reponsed
        ,'certificate'=>$certification,'attachment'=>$attachment ,'deferment_queries'=>$deferment_queries, 'assessor' => $assessor]);
    }
    public function update_reject_decision(Request $request)
    {
        
        
        try {

            $appeal_accepted=$request->input('appeal');//0 for reject 1 for accepted
        
            if($appeal_accepted==1)
            {
                $file=$request->file('accepted_document');
            }
            elseif($appeal_accepted==0)
            {
                $file=$request->file('rejected_document');
            }
            $decision_id=$request->input('decision_id');

        
            
            $filename = time() . '_' . $file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;
    
            $file->move($dir, $filename);
                $uploaded_document = new uploaded_documents;
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->name = $filename;
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 24; //appeal document id
                $uploaded_document->description = 'Appeal Document';
                // insert records
                $saved = $uploaded_document->save();
                if (!$saved) {
                    $this->rollback_db('danger', 'ERROR 1: Problem with Insert into table: uploaded_documents. ');
                }
                $pdf_generated_uploaded_id = $uploaded_document->id;
    
        } catch (\Exception $e) {
    
            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
    
        }
        try {
            // handle transactions automatically
            DB::transaction(function () use ($pdf_generated_uploaded_id, $path, $request,$decision_id,$appeal_accepted) 
            {
                $decision=Decision::find($decision_id);
                $dossier_ass=dossier_assignment::where('id',$decision->dossier_assignment_id)->first();
                if($appeal_accepted==1)
                {
                    Decision::where('id',$decision_id)->update([
                        'appeal'=>1,
                        'appeal_letter_id'=>$pdf_generated_uploaded_id,
                        'appeal_status'=>'Accepted',
                        'decision_status'=>'Reassign',
                    ]);
                    DB::table('dossiers')->where('id',$dossier_ass->dossier_id)->update(
                        [
                            'assignment_status'=>8
                        ]
                        );
                    //lock previous dossier assignment

                    
                }
                else
                {
                    Decision::where('id',$decision_id)->update([
                        'appeal'=>1,
                        'appeal_letter_id'=>$pdf_generated_uploaded_id,
                        'appeal_status'=>'Rejected'
                    ]);
                    //lock the product
                }
                

              







            });
            return Redirect()->back()->with('success', 'Appeal Document Successfully Uploaded.');

            } 
        
            catch (\Exception $e) {
                return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
            }
            return Redirect()->back()->with('success', 'Meeting Data Inserted Successfully.');

            

    }



    public function retrive_all_information(Request $request)
    {
        
        
        try {

            $id = $request->id;
            $decision=Decision::find($id);
    
    
        $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $decision->dossier_assignment_id)       
        ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
        ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
        ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('users as applicant', 'applicant.id', 'applications.user_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
                ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                ->join('countries', 'countries.id', 'company_suppliers.country_id')
               ->select(
            'dossier_assignments.id as dossier_ass_id',
            'dossier_assignments.assigned_datetime',
            'dossiers.dossier_ref_num',
            'dossier_assignments.dossier_id',
            'dossiers.assignment_status',
            'assessors.first_name',
            'assessors.middle_name',
            'supervisors.first_name as name',
            'supervisors.middle_name as m_name',
            'medicinal_products.*',
            'dosage_forms.name as dosage_name',
            'applications.created_at',
            'applications.progress_percentage',
            'applications.application_type',
            'applications.application_number',
            'company_suppliers.trade_name as company_name',
            'company_suppliers.city',
            'company_suppliers.state',
            'company_suppliers.address_line_one',
            'countries.country_name',
            'applicant.first_name as applicant_first_name',
            'applicant.middle_name  as applicant_middle_name',
            'route_administrations.name as route_administration_name',
            'dosage_forms.name as dosage_form_name',
            'medicines.product_name'
        )
        ->first();

        $certificate=DB::table('certifications')->where('decision_id',$decision->id)->first();
        $applied_date=$dossier_evaluation_details->created_at;
        
        $converted_date=$applied_date->format('d-M-Y');
    
        return response()->json(['data' => $dossier_evaluation_details,'created_at'=>$converted_date,'certificate'=>$certificate]);
    
    
    }
    catch (\Exception $e) {
        return response()->json(['data' => $e, 'item' => 'error' . $e]);
    }
    return response()->json(['data' => false, 'item' => 'item_success']);
    
            

    }
    public function download_decision_letter(Request $request)
    {

        try {
            $decision_id=$request->input('decision_id');
            $decision=Decision::where('id',$decision_id)->first();
            $deadline=null;
            if($decision->decision_status=='Deferred')
            {

                $deadline=$request->input('deadline');
                
            }
            if($decision->decision_status=='Accepted')
            {

                $year_setting = AppSetting::where('name','current_year')->first();
                $year_in_db=$year_setting->value;
                $year_from_system=date('Y');
        
                if($year_in_db==$year_from_system){
                    $product_setting=AppSetting::where('name','product_registration_counter')->first();
                    $count=$product_setting->value;
                    $zero_filled_counter = sprintf('%04d', $count);

                    //always check R1
                    $registration_num =  'NMFA/GM/R1/'. $year_from_system . '/' . $zero_filled_counter;
                    $certificate_number =   $zero_filled_counter . '/' . $year_from_system ;
                    //increment the counter
                    AppSetting::where('name','product_registration_counter')->update(
                        [
                        'value'=>$count+1,
                        ]
                    );
        
                }
        
                else
                {
                    //update the current year in db to the system year
                    AppSetting::where('name','current_year')->update(
                        [
                            'value'=>$year_from_system,
                        ]
                    );
                    //reset the counter to 1
                    $count=1;
                    AppSetting::where('name','dossier_ref_num_counter')->update(
                        [
                        'value'=>$count+1,
                        ]
                    );
                    AppSetting::where('name','product_registration_counter')->update(
                        [
                        'value'=>$count+1,
                        ]
                    );
        
        
                    $zero_filled_counter = sprintf('%04d', $count);
                    
                    $registration_num =  'NMFA/GM/R1/'. $year_from_system . '/' . $zero_filled_counter;
                    $certificate_number =   $zero_filled_counter . '/' . $year_from_system ;
        
                }

                

                DB::table('certifications')->insert(
                    [
                        'decision_id'=>$decision_id,
                        'registration_number'=>$registration_num,
                        'certificate_number'=>$certificate_number

                    ]
                );
            }





            $upload_date = date('Y-m-s-H-m-s');
            $dir = 'documents/uploads/';
            $file_name = 'decision_letter.pdf';
            $uploaded_file_name = $upload_date . $file_name;
    
            $data = '<img src="images/nmfa_header.png" width="100%"/>';
            $data .= $request->input('data');
            $data .= '<img src="images/nmfa_footer.png" width="100%"/>';
            $pdf = PDF::loadHTML($data);
            $pdf->setPaper('A4', 'portrait');
            // return $pdf->stream($dir.$uploaded_file_name);
            $tes=$pdf->save($dir . $uploaded_file_name);
            $path = $dir . $uploaded_file_name;


           
            $uploaded_document = new uploaded_documents;
            $uploaded_document->related_id = $decision_id;
            $uploaded_document->name = $file_name;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 25; //Downloaded decision document id
            $uploaded_document->description = 'Decision Document';
            // insert records
            $saved = $uploaded_document->save();
            $decision=Decision::where('id',$decision_id)->first();



                Decision::where('id',$decision_id)->update(
                    [
                    'downloaded_document_id'=>$uploaded_document->id,
                    'decision_letter_downloaded'=>1,
                    'downoloded_date'=>now(),
                    'deferred_date'=>$deadline
                    ]);
          
    
            // dd($tes);
        } catch (\Exception $e) {
    
            return Redirect()->back()->with('danger', 'Problem with File Download. ' . $e->getMessage());
    
        }

        // $request->session()->flash('download.request');
        // return response()->download($path);

     return redirect('/Decision/edit/'.$decision_id)->with('success', 'Decision Letter Saved Successfully.');
        
    }

    public function download_market_authorization_letter(Request $request)
    {
        try {

            $decision_id = $request->id;
            $data="";
//             $data .=' <div style="border-style:solid;border-width:2px; padding-left:0px">
//             <div style="border-width:2px border-style:solid">
//             <div style="border-style:solid;border-color: blue;border-width:5px">
//             <table width="100%" height="100px" border=1>
//             <tr>
//             <td width="20%">
//             <img src="/images/camel.png" style="height:100px;width:100px;"/>
//             </td>
//             <td width="60%">
//             <h1 align="center" style="margin-top:0in;text-align:center;line-height:100%">
//             <span style="font-size:16.0pt;line-height:100%">The State of Eritrea<o:p></o:p></span></h1>

// <h1 align="center" style="margin-top:0in;text-align:center;line-height:100%">
// <span style="font-size:16.0pt;line-height:100%">Ministry of Health<o:p></o:p></span></h1>

// <h1 align="center" style="margin-top:0in;text-align:center;line-height:100%"><span style="font-size:16.0pt;mso-bidi-font-size:14.0pt;line-height:100%">&nbsp; </span><span style="font-size:16.0pt;
// line-height:100%">National Medicines and Food Administration<o:p></o:p></span></h1>
//             </td>
            
//             <td width="20%">
//             <img src="/images/MOH.png" style="height:100px;width:100px;"/>
//             </td>
//             </tr>
            
//             </table>';
            $data =$request->dat;

            // $data .=
        

            $upload_date = date('Y-m-s-H-m-s');
            $dir = 'documents/uploads/';
            $file_name = 'MAH_Letter.pdf';
            $uploaded_file_name = $upload_date . $file_name;


            $pdf = PDF::loadHTML($data);
            $pdf->setPaper('A4', 'portrait');
            // return $pdf->stream($dir.$uploaded_file_name);
            $tes=$pdf->save($dir . $uploaded_file_name);
            $path = $dir . $uploaded_file_name;


           
            $uploaded_document = new uploaded_documents;
            $uploaded_document->related_id = $decision_id;
            $uploaded_document->name = $file_name;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 26; //Downloaded MAH
            $uploaded_document->description = 'MAH Document';
            // insert records
            $uploaded_document->save();


            
            DB::table('certifications')->where('decision_id',$decision_id)->update(
                [
                    'MA_document_downloaded'=>$uploaded_document->id,
                ]
                );
    
    return response()->json(['data' => 'success']);
    
    
}
catch (\Exception $e) {
    return response()->json(['data' => $e, 'item' => 'error' . $e]);
}
return response()->json(['data' => false, 'item' => 'item_success']);

}

public function send_deferral_query(Request $request)
{

    try {

        // query response attached in zip file
        $document = $request->file('document');
        $dir = 'documents/uploads';
        $document_available=false;
        $document_path=null;
        if($document!=null)
        {
        $document_filename = time() . '_' . $document->getClientOriginalName();
        $document_path = $dir . '/' . $document_filename;
        
        $document->move($dir, $document_filename);
        $document_available=true;
        }
    } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }

    try {
        DB::transaction(function () use ($document_path, $request,$document_available) {


            $subject=$request->input('subject');
            $body=$request->input('body');
            $decision_id=$request->input('decision_id');

           
            $document_id=null;
            if($document_available)
            {
           
            $uploaded_document = new uploaded_documents;
        
            $uploaded_document->related_id = $decision_id;
            $uploaded_document->ref_num ='';
            $uploaded_document->name = 'Query Document';
            $uploaded_document->path = $document_path;
            $uploaded_document->document_type = 24; //TODO seed to document type 24 as deferment document
            $uploaded_document->description = $subject;

            $uploaded_document->save();
            $document_id=$uploaded_document->id;
            }



            DefermentQuery::insert(
                [
                    'decision_id'=>$decision_id,
                    'sent_date'=>now(),
                    'status'=>'Sent',
                    'sent_document_id'=>$document_id,
                    'sent_subject'=>$subject,
                    'sent_query'=>$body

                ]   
                );





        }); // end transaction

        return Redirect()->back()->with('success', 'Query Sent to Applicant');





       } catch (\Exception $e) {

   return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

}


        
    
}

public function decision_applicant_details($id)
{

        $decision=Decision::where('decisions.id',$id)
        ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('applications','applications.id','dossier_assignments.application_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('meetings','meetings.id','decisions.meeting_id')
        ->leftjoin('uploaded_documents as sealed_document','sealed_document.id','decisions.sealed_document_id')
        ->leftjoin('uploaded_documents as appeal_letter','appeal_letter.id','decisions.appeal_letter_id')
        ->select('decisions.*','meetings.description','meetings.meeting_date','meetings.time','meetings.venue',
        'sealed_document.path as sealed_document_path',
        'appeal_letter.path as appeal_letter_path','applications.application_number','applications.application_type','medicines.product_name')
        ->first();
        $attachment=Attachment::where('uploaded_documents_id',$decision->sealed_document_id)->first();
       
        $deferment_queries=DefermentQuery::where('deferment_queries.decision_id',$decision->id)
        ->leftjoin('uploaded_documents as sent_document','sent_document.id','deferment_queries.sent_document_id')
        ->leftjoin('uploaded_documents as received_document','received_document.id','deferment_queries.received_document_id')
        ->select('deferment_queries.*','sent_document.path as sent_document_path','received_document.path as received_document_path')
        ->get();
        $check_query_with_out_deferment=DefermentQuery::where('deferment_queries.decision_id',$decision->id)
        ->where('received_date',null)
        ->get();
        $all_queries_reponsed=false;
        if(count($check_query_with_out_deferment)==0)
        {
            $all_queries_reponsed=true;
           
        }
        
         $certification=DB::table('certifications')
            ->leftjoin('uploaded_documents as downloaded_MAH_document','downloaded_MAH_document.id','certifications.MA_document_downloaded')
            ->leftjoin('uploaded_documents as sealed_MAH_document','sealed_MAH_document.id','certifications.sealed_MA_document')
            ->where('decision_id',$decision->id)
            ->select('certifications.*','downloaded_MAH_document.path as downloaded_MAH_document_path','sealed_MAH_document.path as sealed_MAH_document_path')
            ->first();
           
        return view('decisions.applicant_decision_details',['decision'=>$decision,'all_queries_reponsed'=>$all_queries_reponsed
        ,'certificate'=>$certification,'attachment'=>$attachment ,'deferment_queries'=>$deferment_queries]);
}


public function query_response(Request $request)
{
    
    try {

        // query response attached in zip file
        $document = $request->file('document');
        $dir = 'documents/uploads';
        $document_available=false;
        $document_path=null;
        if($document!=null)
        {
        $document_filename = time() . '_' . $document->getClientOriginalName();
        $document_path = $dir . '/' . $document_filename;
        
        $document->move($dir, $document_filename);
        $document_available=true;
        }
    } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }

    try {
        DB::transaction(function () use ($document_path, $request,$document_available) {


            $subject=$request->input('subject');
            $body=$request->input('body');
            $deferment_id=$request->input('deferment_id');


            $deferment=DefermentQuery::where('id',$deferment_id)->first();
           
            $document_id=null;
            if($document_available)
            {
           
            $uploaded_document = new uploaded_documents;
        
            $uploaded_document->related_id = $deferment->decision_id;
            $uploaded_document->ref_num ='';
            $uploaded_document->name = 'Response Document';
            $uploaded_document->path = $document_path;
            $uploaded_document->document_type = 24; //TODO seed to document type 24 as deferment document
            $uploaded_document->description = $subject;

            $uploaded_document->save();
            $document_id=$uploaded_document->id;
            }


            DefermentQuery::where('id',$deferment_id)->update(
                [
                    
                    'received_date'=>now(),
                    'status'=>'Received',
                    'received_document_id'=>$document_id,
                    'received_subject'=>$subject,
                    'received_response'=>$body

                ]   
                );





        }); // end transaction

        return Redirect()->back()->with('success', 'Query Response Sent');





       } catch (\Exception $e) {

   return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
       }
}

public function return_deferment_to_assessor(Request $request)
{
    try {
        DB::transaction(function () use ( $request) {
    $deadline=$request->input('evaluationDeadline');
    $decision_id=$request->input('decision_id');
    $data=User::join('dossier_assignments','dossier_assignments.assessor_id','users.id')
    ->join('decisions','decisions.dossier_assignment_id','dossier_assignments.id')
    ->where('decisions.id',$decision_id)
    ->select('users.id as assessor_id','decisions.dossier_assignment_id')
    ->first();


    //update main tasks taskstatus to inprogress
    MainTask::where('related_id',$data->dossier_assignment_id)
    ->where('related_task','Dossier Evaluation')
    ->update([
        'task_status'=>'Inprogress',
        'deadline' => $deadline,
    ]);
    


    $dossier_assignment=dossier_assignment::where('id',$data->dossier_assignment_id)->first();

    // update decision to decision_assignment_status
    dossier::where('id',$dossier_assignment->dossier_id)->update([
        'dossiers.assignment_status'=> 3
    ]);

  



    Decision::where('id',$decision_id)->update(['locked'=>1]);


    


    $new_notification = [];
        $new_notification['type'] = 'Notification';
        $new_notification['data'] = 'Deferred Product ';
        $new_notification['subject'] = 'Deferment';
        $new_notification['alert_level'] = '';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $decision_id;
        $new_notification['remark'] = '';
        $new_notification['from_user']=auth()->user()->first_name .' '. auth()->user()->middle_name;
        $user = User::find($data->assessor_id);
            Notification::send($user, new InformationNotification($new_notification));

            event(new DossierAssignmentEvent($user->id, 'Evaluated Dossier Returned. '));









            
        }); // end transaction




            return Redirect()->back()->with('success', 'Query Response Sent');




        } 
        catch (\Exception $e) {
 
    return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
        }


}
}
