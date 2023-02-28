<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Models\dossier_assignment;
use App\Models\MainTask;
use App\Models\QualityControl;
use App\Models\uploaded_documents;
use App\Models\User;
use App\Notifications\InformationNotification;
use App\Notifications\QC;
use Illuminate\Http\Request;
use App\Http\Controllers\MainTaskController;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use PDF;
use PdfReport;

class InspectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inspection_request_index()
    {
        //
        $qc_documents = QualityControl::where('inspection_to_user_id',auth()->user()->id)
            ->join('dossier_assignments','dossier_assignments.id','quality_controls.qc_related_id')
            ->join('main_tasks','main_tasks.related_id','quality_controls.qc_related_id')
            ->where('main_tasks.related_task','Dossier Evaluation')
                ->select('quality_controls.*','dossier_assignments.id as dossier_assign_id','main_tasks.task_status')->get();


        return view('inspection_unit.inspection_request_index',['qc_documents'=>$qc_documents]);

    }

    public function qc_request_index()
    {
        //
        $qc_documents = QualityControl::where('to_qc_staff_id',auth()->user()->id)
            ->join('dossier_assignments','dossier_assignments.id','quality_controls.qc_related_id')
            ->join('main_tasks','main_tasks.related_id','quality_controls.qc_related_id')
            ->where('related_task','Dossier Evaluation')
            ->select('quality_controls.*','dossier_assignments.id as dossier_assign_id','main_tasks.task_status')->get();
        return view('inspection_unit.inspection_request_index',['qc_documents'=>$qc_documents]);

    }
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


    public function letter_to_qc($id){


        $qc = QualityControl::where('id', $id)->first();

        $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $qc->qc_related_id)
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->leftjoin('checklists', 'checklists.application_id', 'applications.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
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
                'checklists.sample_received_date'
            )
            ->first();





        $qc_staff=DB::table('roles')
            ->join('model_has_roles','roles.id','model_has_roles.role_id')
            ->join('users','users.id','model_has_roles.model_id')
            ->where('roles.name','Quality Control')
            ->get();
        $qc_id=$id;
        return view('html_templates.to_qc_from_inspection_unit',
            ['dossier_evaluation_details' => $dossier_evaluation_details, 'users'=>$qc_staff,'qc_id'=>$qc_id]);
    }
    private function date_formatter($date)
    {
        $formatted_date = date($date);
//        dd($formatted_date);
        $date = new \DateTime($formatted_date);
        $formatted_date = $date->format('Y-m-d');
        return ($formatted_date);
    }

    private function to_qc_pdf($data,$array_testing_reason,$array_storage_condition,$array_urgency)
    {


        $html='
        
    
        <p class="MsoNormal" style="text-align:center" align="center">
        <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family: &quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
              National Medicines and Food Administration
        </span>
    </p>

    <p class="MsoNormal" style="text-align:center" align="center">
        <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
             Sample Request Form for Analysis
        </span>
    </p>


    <table>
    <tr>
    <td>
                <label  class="col-sm-5 col-form-label">Reference number:</label>
                </td>

                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="reference_number" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                <td>
                <label  class="col-sm-5 col-form-label">Manufacturer:</label>

                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="manufacturer" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                </tr>
                <tr>
                <td>
                <label  class="col-sm-5 col-form-label">Generic name:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="generic_name" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                
                </td>
                <td>
                <label  class="col-sm-5 col-form-label">Strength:</label>
               


                </td>
                <td>

                <div class="col-sm-7">
                <input type="text" class="form-control"  name="strength" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
              </div>

                </td>
                </tr>
                <tr>
                <td>
                <label  class="col-sm-5 col-form-label">Dosage form:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="dosage_form" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                
              </div>
              </td>
              <td>
              <label  class="col-sm-5 col-form-label">Batch size:</label>
              </td>
              <td>
              <div class="col-sm-7">
              <input type="text" class="form-control"  name="batch_size" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
            </div>
              </td>
              </tr>
              <tr>
              <td>
                <label  class="col-sm-5 col-form-label">Manufacturing date:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="manufacturing_date" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                <td>
                <label  class="col-sm-5 col-form-label">Expiry date:</label>
                </td>
                <td>
                <div class="col-sm-7">
                <input type="text" class="form-control"  name="expiry_date" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
              </div>
                </td>
                </tr>
                <tr>
                <td>
                
                <label  class="col-sm-5 col-form-label">Country of Origin:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="country_origin" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>

                <td>
                <label  class="col-sm-5 col-form-label">Supplier:</label>

                </td>
                <td>
                <div class="col-sm-7">
                <input type="text" class="form-control"  name="supplier" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
              </div>

                </td>
                </tr>
                <tr>
                <td>

                <label  class="col-sm-5 col-form-label">Date of sampling:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="date_sampling" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                
                </td>
                <td>
                <label  class="col-sm-5 col-form-label">Sample size:</label>
               
                </td>                
                <td>
                <div class="col-sm-7">
                <input type="text" class="form-control"  name="sample_size" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
              </div>
                </td>
                </tr>
                <tr>
                <td>
                <label  class="col-sm-5 col-form-label">Source of the sample:</label>
                </td>
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="sample_source" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                <td>
                <label  class="col-sm-5 col-form-label">Date of submission to QC</label>
                
                </td>                
                <td>
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="submission_date" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                </tr>
            </table>

       
             <table style="width:100%">
             <tr>
             <td>
                <label  class="col-sm-5 col-form-label"> Specification to be used for testing</label>
                </td>
                <td width="75%">
                <div class="col-sm-7">
                  <input type="text" class="form-control"  name="specification_testing" id="inputEmail3" style="border-width:0px;border:none;outline:none;border-bottom: solid thin">
                </div>
                </td>
                </tr>
                </table>
                <table border="1" style="width:100%">

                    <tr>
                    <td >
                            Urgency of Test
                            </td>

                    <td >
                        Storage Condition
                    </td>
                    <td >
                        Reason for Test
                            </td>
                            </tr>
                            <tr>
                <td>
                            
                                <input type="radio" '.$array_urgency['normal'].' name="urgency" /> Normal
                                <br>
                                 
                                <input type="radio" '.$array_urgency['urgent'].' name="urgency" /> Urgent
                         </td>
                         <td>
                                    <input type="radio" '.$array_storage_condition['room_temprature'].' name="storage_condition" /> Room Temperature
                                    <br>
                                    <input type="radio" '.$array_storage_condition['refrigerator'].' name="storage_condition" />Refrigerator
                                    <br>
                                    <input type="radio" '.$array_storage_condition['frozen'].' name="storage_condition" /> Frozen
                               </td>

                  
                               <td>
                                        <input type="radio" '.$array_testing_reason['registration'].' name="testing_reason" /> Registration
                                        <br>
                                        <input type="radio" '.$array_testing_reason['pre_marketing'].'  name="testing_reason" /> Pre-Marketing
                                        <br>
                                        <input type="radio" '.$array_testing_reason['post_marketing'].'  name="testing_reason" /> Post-Marketing
                                        <br>
                                        <input type="radio" '.$array_testing_reason['complaing'].'   name="testing_reason" /> Complaint
                                    </td>
                                    </tr>
                                    </table>
                                <table>
                                <tr>
                                <td>
                                </td>
                                </tr>
                                </table>


                                <table style="width:100%">
                                <tr><td>  <b><u>   Physico-Chemical </u></b> </td><td> <b><u> Microbiology </u></b>   </td>
                                </tr>
                                <tr>
                                <td>                                
                                <input type="checkbox"  '.$data['identification'].' /> Identification
                               
                                </td>
                                <td>
                                <input type="checkbox" '.$data['bacterial_endotoxin'].' /> Bacterial Endotoxin Test
                                </td>
                                </tr>


                                <tr>
                                <td>
                                <input type="checkbox" '.$data['disintegration'].' /> Disintegration
                                </td>
                                <td>
                                <input type="checkbox" '.$data['sterility_test'].'/> Sterility test
                                </td>
                                </tr>
                                <tr>
                                <td>
                                <input type="checkbox" '.$data['dissolution'].'/> Dissolution
                                </td>
                                <td>
                                <input type="checkbox" '.$data['microbial'].'/> Microbial Enumeration Test
                                </td>
                                </tr>

                                <tr>
                                <td>
                                <input type="checkbox" '. $data['friability'].' /> Friability
                                </td>
                                <td>
                                <input type="checkbox" '.$data['test_for_micro_organisms'].'/> Test for Specified Micro-organisms
                                </td>
                                </tr>
                                <tr>
                                <td>
                                <input type="checkbox" '.$data['uniformity_of_dosage'].'/> Uniformity of dosage form
                                </td>
                                <td>
                                <input type="checkbox" '.$data['antimicrobial'].'/> Antimicrobial Effectiveness Test
                                </td>
                                </tr>
                                <tr>
                                <td>
                                <input type="checkbox" '.$data['ph'].' /> pH
                                </td>
                                <td>
                                <input type="checkbox" '.$data['antimicrobial_assay'].'/> Antimicrobial Assay
                                </td>
                                </tr>

                                <tr>
                                <td>
                                <input type="checkbox" '.$data['viscosity'].' /> Viscosity
                                </td>
                                <td>
                                </td>
                                </tr>
                                <tr>
                                <td>
                                <input type="checkbox" '.$data['impurity'].' /> Impurity and related substance
                                </td>
                                <td>
                                </td>
                                </tr>

                                <tr>
                                <td>
                                <input type="checkbox" '.$data['assay'].'/> Assay
                                </td>
                                <td>
                                </td>
                                </tr>

                                <tr>
                                <td>
                                <input type="checkbox"  '.$data['particular_matter'].'/> Test for particular matter
                                </td>
                                <td>
                                </td>
                                </tr>

                                <tr>
                                <td>
                                <input type="checkbox" '.$data['others'].'/> Others
                                </td>
                                <td>
                                </td>
                                </tr>




                                </table>
                                     ';
        return $html;
    }
    public function send_to_qc_from_inspection(Request $request)
    {

        try {

            $testing_reason=$request->input('testing_reason');
            $storage_condition=$request->input('storage_condition');
            $urgency=$request->input('urgency');

            $data=[];
            $data['identification']=$request->input('identification');
            $data['disintegration']=$request->input('disintegration');
            $data['dissolution']=$request->input('dissolution');
            $data['friability']=$request->input('friability');
            $data['uniformity_of_dosage']=$request->input('uniformity_of_dosage');
            $data['ph']=$request->input('ph');
            $data['viscosity']=$request->input('viscosity');
            $data['impurity']=$request->input('impurity');
            $data['assay']=$request->input('assay');
            $data['particular_matter']=$request->input('particular_matter');
            $data['others']=$request->input('others');
            $data['bacterial_endotoxin']=$request->input('bacterial_endotoxin');
            $data['sterility_test']=$request->input('sterility_test');
            $data['microbial']=$request->input('microbial');
            $data['test_for_micro_organisms']=$request->input('test_for_micro_organisms');
            $data['antimicrobial']=$request->input('antimicrobial');
            $data['antimicrobial_assay']=$request->input('antimicrobial_assay');

            $array_testing_reason=[];
            $array_testing_reason['registration']=null;
            $array_testing_reason['pre_marketing']=null;
            $array_testing_reason['post_marketing']=null;
            $array_testing_reason['complaing']=null;
            if($testing_reason=="registration")
            {
                $array_testing_reason['registration']="checked";
            }
            else if($testing_reason=="pre_marketing")
            {
                $array_testing_reason['pre_marketing']="checked";
            }
            else if($testing_reason=="post_marketing")
            {
                $array_testing_reason['post_marketing']="checked";
            }
            else if($testing_reason=="complaing")
            {
                $array_testing_reason['complaing']="checked";
            }

            $array_storage_condition=[];
            $array_storage_condition['room_temprature']=null;
            $array_storage_condition['refrigerator']=null;
            $array_storage_condition['frozen']=null;

            if($storage_condition=="room_temprature")
            {
                $array_storage_condition['room_temprature']="checked";
            }
            else if($storage_condition=="refrigerator")
            {
                $array_storage_condition['refrigerator']="checked";
            }
            else if($storage_condition=="frozen")
            {
                $array_storage_condition['frozen']="checked";
            }
            else
            {

            }
            $array_urgency=[];
            $array_urgency['normal']=null;
            $array_urgency['urgent']=null;


            if($urgency=="normal")
            {
                $array_urgency['normal']="checked";
            }
            else if($urgency=="urgent")
            {
                $array_urgency['urgent']="checked";
            }
            else{

            }




            $html=$this->to_qc_pdf($data,$array_testing_reason,$array_storage_condition,$array_urgency);

                $qc_id=$request->input('qc_id');
                $upload_date=date('Y-m-s-H-m-s ');
                $dir = 'documents/uploads/';
                $file_name='letter_to_qc_unit.pdf';
                $uploaded_file_name=$upload_date.$file_name;
                $to_user=$request->input('to_user');

                $deadline_input = $request->input('deadline');

                $deadline = $this->date_formatter(($deadline_input));
                $data='<img src="images/nmfa_header.png" width="100%" height="100px"/>';
                $data.=$html;
                $data.='<img src="images/nmfa_footer.png" width="100%"/>';
                $pdf = PDF::loadHTML($data);
                $pdf->setPaper ('A4', 'portrait');
                //this is new code  for rendering
            //   $pdf->render($dir.$uploaded_file_name);
            //  return $pdf->stream($dir.$uploaded_file_name);
                $pdf->save ($dir.$uploaded_file_name);
                $path = $dir . $uploaded_file_name;


    } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }


        try {
            // handle transactions automatically
            DB::transaction(function () use ( $request,$path,$deadline,$qc_id,$to_user){


        $qc=QualityControl::find($qc_id);
        $dossier_ass_id =$qc->qc_related_id ;



        $dossier_assignment = dossier_assignment::find($dossier_ass_id);
        //get main task id
        $main_task = $this->get_main_task_id($dossier_ass_id, 'Dossier Evaluation');
        //get the end time from the assessor

        // //first make the html and save it as pdf

        $uploaded_document = new uploaded_documents;
        $description = $request->input('subject');

        $uploaded_document->related_id = $dossier_ass_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Sample Test Request to QC Unit By '. auth()->user()->first_name .' '. auth()->user()->middle_name ;
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 4;
        $uploaded_document->description = $description;
        // insert records
        $uploaded_document->save();


        $pdf_generated_uploaded_id = $uploaded_document->id;
        //save it in qualty_controls table
        QualityControl::where('id',$qc_id)->update([
            'to_qc_staff_id' => $request->input('to_user'),
            'to_qc_sent_Date' => date('Y-m-d H:i:s', strtotime('-3')),
            'status' => 'Request Sent To QC ',
            'to_qc_document_id' => $pdf_generated_uploaded_id,
            'to_qc_lab_subject' => $description,
            'qc_deadline'=>$deadline
        ]);


        $end_time = null;
        $task_category = 'Notice';
        $task_activity_title = 'Request for Sample Test Sent To Quality Control Unit By'.auth()->user()->first_name.' '.auth()->user()->middle_name ;
        $content_details = $description;
        $route_link = '';
        $activity_status = 'inprogress';
        $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));


        //instert the variables above to the queries table


        //insert this into task tracker

        $main_task_inserted=MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
        }
        $new_notification=[];
        $new_notification['type']='Notification';
        $new_notification['data']='Sample test request sent from Inspection Unit.';
        $new_notification['subject']=$description;
        $new_notification['from_user']=auth()->user()->first_name .' '. auth()->user()->middle_name;
        $new_notification['alert_level']='high';
        $new_notification['related_document']=  $pdf_generated_uploaded_id;
        $new_notification['related_id'] = $dossier_ass_id;
        $new_notification['remark']='';
        $user=User::find($to_user);
        Notification::send($user, new InformationNotification($new_notification));
        event (new DossierAssignmentEvent($to_user, 'You have received Sample test Request'));


    });
}
        catch(MainTaskNotInsertedException $e){
            return Redirect()->back()->with('danger',  $e->getMessage());

        }
             catch (\Exception $e) {
                return Redirect()->back()->with('danger', 'Problem with Dossier Section Assignment. ' . $e->getMessage());
            }
            return Redirect('/InspectionRequestController')->with('success', 'Request For Sample Test to QC Sent Successfully.');


    }
}
