<?php

namespace App\Http\Controllers;

use App\Models\document_received_uploaded;
use App\Models\application_receipt_of_registration;
use App\Models\Acknowledgement_letter;
use App\Models\invoices ;
use App\Models\receipt;
use App\Models\Book;
use App\Models\Assignment_unassignment;
use App\Models\applications;
use Illuminate\Http\Request;
use App\Models\documents;
use App\Models\User;
use App\Models\company_suppliers;
use App\Models\manufacturers;
use App\Models\medicinal_products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash;
use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Events\ApplicationReceiptionEvent;

use Illuminate\Support\Arr;



 use DataTables;

class DocumentReceivedUploadedController extends Controller
{
  

  public function acknowlegement_receipt(Request $request)
  {

    $dataa = User::orderBy('id','ASC')->get();
     if ($request->ajax())
{

$data = application_receipt_of_registration::join('applications','applications.application_id','application_receipt_of_registrations.application_id')
->join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
->join('contacts','contacts.application_id','application_receipt_of_registrations.application_id')
//->join('manufacturers', 'application_receipt_of_registrations.application_id', '=', 'manufacturers.application_id')
->join('medicinal_products', 'application_receipt_of_registrations.application_id', '=', 'medicinal_products.application_id')
->join('company_suppliers','application_receipt_of_registrations.application_id','=','company_suppliers.application_id')
->join('invoices','application_receipt_of_registrations.application_id','=','invoices.application_id')
->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
->join('checklists','checklists.application_id','application_receipt_of_registrations.application_id')
->select(
'applications.*',
'applications.application_number as app_number',
'application_receipt_of_registrations.*',
'application_receipt_of_registrations.created_at as ackdate',
'documents.*',
'checklists.*',
'contacts.*', 
'medicines.product_name',
'medicinal_products.product_trade_name',
//'manufacturers.name as manufacturer_name',
'company_suppliers.trade_name as cs_tradename'
)
->where('contacts.contact_type','=','Supplier')
->where('application_receipt_of_registrations.document_id','!=',null)
->where('applications.user_id','=',auth()->user()->id)
->orderBy('applications.application_number','ASC')
->get();




// $data = Book::latest()->get();
        return Datatables::of($data)
                ->addIndexColumn()


                                    
->addColumn('application_typee', function($row){
if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}  
else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}

return $application_type;
                })


->addColumn('action', function($row){

$btn = '<a href="'.@$row->path.'" 
 class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';


return $btn;
                })
            
                ->addColumn('application_status', function($row){


                    if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                    elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                    elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }


                    $btn = "<span class='$badge'>  $row->application_status  </span>";

                    return $btn;
                                    })

                                    ->addColumn('registration_type', function ($row) {

                                        if ($row->registration_type == 'New') {
                                            $badge = 'badge bg-secondary';
                                        } elseif ($row->registration_type == 'Re-new') {
                                            $row->registration_type='Re-newal';
                                            $badge = 'badge bg-success';
                        
                                        }
                                        $btn = "<span class='$badge'>  $row->registration_type  </span>";
                                        return $btn;
                                    })

                ->rawColumns(['action','application_typee','application_status','registration_type'])
                ->make(true);
}
  
      return view('Document_Received.from_acknowledgment_letter_registration');






  }



    public function index(Request $request)
    {

        $dataa = User::orderBy('id','ASC')->get();


        

        if ($request->ajax())
{


    

    $data = Acknowledgement_letter::join('applications','applications.application_id','acknowledgement_letters.application_id')
    ->rightjoin('documents','documents.id','acknowledgement_letters.uploaded_applicant_document_id')
    ->leftjoin('contacts','contacts.application_id','acknowledgement_letters.application_id')
    ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
    ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->select(
    'applications.*',
    'applications.application_number as app_number',
    'acknowledgement_letters.*',
    'acknowledgement_letters.created_at as ackdate',
    'documents.*',
    'checklists.*',
    'invoices.*',
    'contacts.*', 
    'medicines.product_name',
    'medicinal_products.product_trade_name',
    'manufacturers.name as manufacturer_name',
    'company_suppliers.trade_name as cs_tradename'
    )
    ->where('contacts.contact_type','=','Supplier')
    ->where('acknowledgement_letters.document_id','!=',null)
    ->where('applications.user_id','=',auth()->user()->id)
    ->orderBy('applications.application_number','ASC')
    ->get();


    

 // $data = Book::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()


                                        
   ->addColumn('application_typee', function($row){
   if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}  
   else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}
    
    return $application_type;
                    })


   ->addColumn('action', function($row){
   
    $btn = '<a href="'.@$row->path.'" 
     class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';
 
    
    return $btn;
                    })
                
                    ->addColumn('application_status', function($row){


                        if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                        elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                        elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }


                        $btn = "<span class='$badge'>  $row->application_status  </span>";

                        return $btn;
                                        })

                                        ->addColumn('registration_type', function ($row) {

                                            if ($row->registration_type == 'New') {
                                                $badge = 'badge bg-secondary';
                                            } elseif ($row->registration_type == 'Re-new') {
                                                $row->registration_type='Re-newal';
                                                $badge = 'badge bg-success';
                            
                                            }
                                            $btn = "<span class='$badge'>  $row->registration_type  </span>";
                                            return $btn;
                                        })



                    ->rawColumns(['action','application_typee','application_status','registration_type'])
                    ->make(true);
}
      
       // return view('assign_unassign.assigned',compact('dataa'));

        return view('Document_Received.from_acknowledgment');

        



    }



public function invoice_receipts(Request $request)
{

    $dataa = User::orderBy('id','ASC')->get();
    if ($request->ajax())
{

 $data = invoices::join('applications','applications.application_id','invoices.application_id')
->leftjoin('documents','documents.id','invoices.uploaded_invoice_document_id')
->leftjoin('contacts','contacts.application_id','invoices.application_id')
->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
->leftjoin('checklists','checklists.application_id','applications.application_id')
->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
->select(
'applications.*',
'applications.application_number as app_number',
'documents.*',
'checklists.*',
'invoices.*',
'contacts.*', 
'medicines.product_name',
'medicinal_products.product_trade_name',
'manufacturers.name as manufacturer_name',
'company_suppliers.trade_name as cs_tradename',
'invoices.created_at as ackdate',
)
->where('contacts.contact_type','=','Supplier')
->where('documents.document_type','=',21)
->where('invoices.applicant_user_id','=',auth()->user()->id)
->orderBy('applications.application_number','ASC')
->get();


// $data = Book::latest()->get();
        return Datatables::of($data)->addIndexColumn()
        ->addColumn('application_typee', function($row){
            if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}  
            else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}
             
return $application_type;
                })


->addColumn('action', function($row){

$btn = '<a target="_blank" href="'.@$row->path.'" 
 class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';


return $btn;
                })
            

                ->addColumn('application_status', function($row){

                    if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                    elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                    elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
                    $btn = "<span class='$badge'>  $row->application_status  </span>";

                    return $btn;

                })



                ->rawColumns(['action','application_typee','application_status'])
                ->make(true);
}
  
   // return view('assign_unassign.assigned',compact('dataa'));

    return view('Document_Received.from_invoice_received');


}


    public function financial_notification(Request $request)
    {

  $dataa = User::orderBy('id','ASC')->get();
        if ($request->ajax())
{

     $data = receipt::join('applications','applications.application_id','receipts.application_id')
    ->rightjoin('documents','documents.id','receipts.upload_financial_notification_to_applicant')
    ->leftjoin('contacts','contacts.application_id','receipts.application_id')
    ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
    ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->select(

    'applications.*',
    'receipts.*',
    'receipts.created_at as ackdate',
    'documents.*',
    'applications.application_number as app_number',
    'documents.*',
    'checklists.*',
    'invoices.*',
    'contacts.*', 
    'medicines.product_name',
    'medicinal_products.product_trade_name',
    'manufacturers.name as manufacturer_name',
    'company_suppliers.trade_name as cs_tradename',

)
    ->where('contacts.contact_type','=','Supplier')
    ->where('receipts.financial_notification_flag','!=',0)
    ->where('applications.user_id','=',auth()->user()->id)
    ->orderBy('applications.application_number','ASC')
    ->get();


    // $data = Book::latest()->get();
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('application_typee', function($row){
                if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}
                else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}
                 
    return $application_type;
                    })


   ->addColumn('action', function($row){
   
    $btn = '<a target="_blank" href="'.@$row->path.'" 
     class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';
 
    
    return $btn;
                    })
                

                    ->addColumn('application_status', function($row){

                        if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                        elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                        elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
                        $btn = "<span class='$badge'>  $row->application_status  </span>";

                        return $btn;

                    })



                    ->rawColumns(['action','application_typee','application_status'])
                    ->make(true);
}
      
       // return view('assign_unassign.assigned',compact('dataa'));

        return view('Document_Received.from_financial_notification');


    }






    public function documents_psurs(Request $request)
    {

  $dataa = User::orderBy('id','ASC')->get();
        if ($request->ajax())
{

     $data = applications::join('acknowledgment_letter_receipt_psurs','applications.application_id','acknowledgment_letter_receipt_psurs.application_id')
    ->rightjoin('documents','documents.id','acknowledgment_letter_receipt_psurs.uploaded_id')
    ->leftjoin('contacts','contacts.application_id','applications.application_id')
    ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
    ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
    ->leftjoin('certifications','decisions.id','certifications.decision_id')
    ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->select(
    'applications.*',
    'certifications.*','certifications.registration_number as regnumber',
    'contacts.*', 'acknowledgment_letter_receipt_psurs.*', 'documents.*','applications.application_number as app_number',
    'documents.*',
    'checklists.*',
    'acknowledgment_letter_receipt_psurs.created_at as ackdate',
    'contacts.*', 
    'medicines.product_name',
    'medicinal_products.product_trade_name',
    'manufacturers.name as manufacturer_name',
    'company_suppliers.trade_name as cs_tradename',

)
->where('contacts.contact_type','=','Supplier')
    ->where('applications.user_id','=',auth()->user()->id)
    ->orderBy('applications.application_number','ASC')
    ->get();


    // $data = Book::latest()->get();
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('application_typee', function($row){
                if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}
                else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}
                 
    return $application_type;
                    })


   ->addColumn('action', function($row){
   
    $btn = '<a target="_blank" href="'.@$row->path.'" 
     class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';
 
    
    return $btn;
                    })
                

                    ->addColumn('application_status', function($row){

                        if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                        elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                        elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
                        $btn = "<span class='$badge'>  $row->application_status  </span>";

                        return $btn;

                    })



                    ->rawColumns(['action','application_typee','application_status'])
                    ->make(true);
}
      
       // return view('assign_unassign.assigned',compact('dataa'));

        return view('Document_Received.from_acknowledgment_letter_receipt_psurs');


    }




    public function documents_nmfa_alerts(Request $request)
    {

       

  $dataa = User::orderBy('id','ASC')->get();


        if ($request->ajax())
{

     $data = applications::join('contacts','contacts.application_id','applications.application_id')
    ->join('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    ->join('checklists','checklists.application_id','applications.application_id')
    ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
    ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
    ->leftjoin('certifications','decisions.id','certifications.decision_id')
    ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->join('nmfa_directors','applications.application_id','=','nmfa_directors.application_id')
    ->join('documents','documents.id','=','nmfa_directors.nmfa_directors_document_id')
    ->select(
    'applications.*',
    'certifications.*','certifications.registration_number as regnumber',
    'contacts.*',
     'documents.*','applications.application_number as app_number',
    'documents.*',
    'checklists.*',
    'nmfa_directors.*',
    'nmfa_directors.created_at as ackdate', 
    'medicines.product_name',
    'medicinal_products.product_trade_name',
    'manufacturers.name as manufacturer_name',
    'company_suppliers.trade_name as cs_tradename',

)
->where('contacts.contact_type','=','Supplier')
    ->orwhere('nmfa_directors.Send_To_id','=',auth()->user()->id)
    ->Where('nmfa_directors.Send_To_id','=','Both')
    ->distinct()
    ->orderBy('applications.application_number','ASC')
    ->get();



    // $data = Book::latest()->get();
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('application_typee', function($row){
                if($row->application_type == 1) {$application_type="<span class='badge bg-primary'> Standard Mode </span>";}
                else { $application_type= "<span class='badge bg-warning'> Fast Track Mode</span>" ;}
                     return $application_type;
                    })


   ->addColumn('action', function($row){
   
    $btn = '<a  target="_blank" target="_blank"  href="'.@$row->path.'" 
     class="btn-success btn-sm editAssign"><b class="fas fa-file-download">   </b> </a>';
 
    
    return $btn;
                    })
                

                    ->addColumn('application_status', function($row){

                        if($row->application_status == 'processing') {  $badge = 'badge bg-warning'; }
                        elseif($row->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
                        elseif($row->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
                        $btn = "<span class='$badge'>  $row->application_status  </span>";

                        return $btn;

                    })
                    ->addColumn('regnumber', function($row){

                        if($row->regnumber== '') {  $row->regnumber='N/A'; }
                        elseif($row->regnumber != '') { $row->regnumber=$row->regnumber; }
                    
                        $btn = "<span >  $row->regnumber </span>";

                        return $btn;

                    })


                    ->rawColumns(['action','application_typee','application_status','regnumber'])
                    ->make(true);
}
      
       // return view('assign_unassign.assigned',compact('dataa'));

        return view('Document_Received.from_nmfa_directors');


    }




    public function all_assigned_unassigned(Request $request)
    {

      
        $dataa = User::orderBy('id','ASC')->get();
        
   
        if ($request->ajax())
{


    

    $data = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
    ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('users','users.id','applications.user_id')
 
    ->join('contacts','contacts.application_id','applications.application_id')
    ->select('applications.*',
    DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname'),
    'medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
    'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
    'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    //->where('applications.assigned_To','<>',NULL)
    ->orderBy('application_number','ASC')
    ->get();

 // $data = Book::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('assigned_To', function($row){
                        if($row->assigned_To !='')
                        {
                        $assinged_To_full_Name=User::select('users.*')
                        ->where('id','=',$row->assigned_To)
                        ->get();
                        return $assinged_To_full_Name[0]->first_name." ".$assinged_To_full_Name[0]->middle_name." ".$assinged_To_full_Name[0]->last_name;
                        }
                        else { return '-';  }
                    
                    })

        ->addColumn('assigned_By', function($row)
            {
                if($row->assigned_By !='')
                   {
        $assinged_By_full_Name=User::select('users.*')
        ->where('id','=',$row->assigned_By)
        ->get();
        return $assinged_By_full_Name[0]->first_name." ".$assinged_By_full_Name[0]->middle_name." ".$assinged_By_full_Name[0]->last_name;
                   }
                   else { return '-';  }
    
    })

        ->addColumn('Assginment_Date', function($row){
            return $row->Assginment_Date;
            })
        
            ->addColumn('action', function($row){
                if($row->assigned_By !='')
                {
    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'"
     data-original-title="Edit" class="edit btn btn-warning btn-sm editAssign">Check</a>';
                }
                else
                {
                    $btn = '<a target="_blank" href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'"
                    data-original-title="Edit" class="edit btn btn-primary btn-sm editAssign">Assign</a>';
                
                   // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
                   
                   return $btn;
                }
    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
    
    return $btn;
                    })
                    ->addColumn('application_status', function($row){
   
                        $btn = '<a target="_blank" href="javascript:void(0)"  style="background-color:violet;color:black"class="btn btn-default btn-sm">'.$row->application_status.'</a>';
                     return $btn;
                     })
                    ->rawColumns(['action','application_status'])
                    ->make(true);
}
      
        //return view('assign_unassign');

        return view('assign_unassign.unassigned_assigned_all',compact('dataa'));

        



    }
     


    public function unassigned(Request $request)
    {

       
        $dataa = User::orderBy('id','ASC')->get();
        
   
        if ($request->ajax())
{


    

    $data = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
    ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('users','users.id','applications.user_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->select('applications.application_number',DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname'),'medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*','contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.assigned_To','=',NULL)
    ->orderBy('application_number','ASC')
    ->get();

 

           // $data = Book::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'"
     data-original-title="Edit" class="edit btn btn-primary btn-sm editAssign">Assign</a>';
 
    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
    
    return $btn;
                    })
                    ->addColumn('application_status', function($row){
   
                        $btn = '<a href="javascript:void(0)"  class="btn btn-warning btn-sm">'.$row->application_status.'</a>';
                     return $btn;
                     })
                    ->rawColumns(['action','application_status'])
                    ->make(true);
}
      
        //return view('assign_unassign');

        return view('assign_unassign.unassigned',compact('dataa'));

        



    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $application=applications::where('application_id',$request->application_id)->first();
        if( $application->assigned_To != null)
       {
         $fresh=true;
       }
       else
       {
        $fresh=false;
       }

        $assign= applications::updateOrCreate(
        ['application_id' => $request->application_id], 
        ['assigned_To' => $request->assigned_To, 
        'assigned_By' => $request->assigned_By,
        'Assginment_Date' => now()],
    );  
    
    $application=applications::where('application_id',$request->application_id)->first();
            
    $duration_days = 10;
            
    //MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);
    
    $main_task = $this->get_main_task_id($application->id,'Application');
    $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
    $issued_datetime = date('Y-m-d H:i:s');
    $task_category = 'Screening';
    $task_activity_title = 'Screening process';
    $content_details = 'Application assigned to assesor for  screening ';
    $route_link = '';
    $activity_status = 'Inprogress';
    $uploaded_document_id = null;
    // application_evaluation_progresses::insert([
    //  'application_id'=>$application->id,
    
    //]);
    //insert this into task tracker
    if(!$fresh)
    {
    MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
    }
    if($fresh)
    {
    $tasks = TaskTracker::where('task_id', $main_task->id)
    ->where('task_category','Screening')
    ->first();
    TaskTracker::where('id', $tasks->id)
            ->update(
                [
                    'start_time' => $issued_datetime,
                ]
            );
        }
    //->OrderBy('task_trackers.id', 'desc') 
    /* 
if($fresh)
    {
    application_evaluation_progresses::insert([
    'application_id'=>$application->id,
    'task_id' =>$tasks->id,
    ]);
}
else
{
    application_evaluation_progresses::where('task_id', $tasks->id)
            ->update(
                [
                    'day_count' => 0,
                ]
            );
}
*/
    $user=User::where('id', $assign->assigned_To)->first();
   
   
    $new_notification=[];
    
    
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='New Application Screening Assigned By Supervisor';
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = 'Appication with application No: '.$request->application_id.' has been assigned  by supervisor';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
    // ::send($users, new ($invoice));
    if(!$fresh)
    {
    Notification::send($user, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->id, 'New appication with application No: '.$request->application_id .' has been assigned by supervisor for screening' ));
    }
    else
    {

        $new_notification=[];
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='New Application Screening Assigned By Supervisor';
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = 'Appication with application No: '.$request->application_id.' has been  reassigned  by supervisor';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
        event(new ApplicationReceiptionEvent($user->id, 'Appication with application No: '.$request->application_id.' has been reassigned by supervisor  for screening'));
        
    }

       return response()->json($assign);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
       // dd( response()->json($book));
        return response()->json($book);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id)->delete();
     
        return response()->json($book);
    }






    private function get_main_task_id($application_id, $related_type = 'Application')
    {
        $main_task = MainTask::where('related_id', $application_id)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {
    
            return 0; //means false
        }}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




}
