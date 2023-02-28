<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
use App\Models\agents_template;
use App\Models\company_suppliers_template;
use App\Models\payment_configuration;
use App\Models\invoices;
use App\Models\declerations;
use App\Models\contacts;
use App\Models\fast_track_application;
use App\Models\User;
use App\Models\product_details;
use App\Models\DosageForms;
use App\Models\apis;
use App\Models\route_administrations;
use App\Models\company_suppliers;
use App\Models\agents;
use App\Models\medicinal_products;
use App\Models\manufacturers;
use App\Models\api_manufacturers;
use App\Models\product_composition;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Events\ApplicationReceiptionEvent;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Models\TaskTracker;
use App\Models\documents;

use PDF;
use DataTables;


class ApplicationReceptionController extends Controller
{
    // It uses to give a retrieval for all request come from the view section

    public $return_data,$Email,$i,$tele,$code,$Url;

    function __construct()

    {

          $this->middleware('permission:application-list|application-status-list|assesor_roles');
        



          $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);

          $this->middleware('permission:role-create', ['only' => ['create','store']]);
  
          $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
  
          $this->middleware('permission:role-delete', ['only' => ['destroy']]);


    }



public function generate_new_application(Request $request)
{
  try
  {
$applications =  new applications;
$t=time();
$year = Date('Y');
$count = applications::where('application_number', '<>', null)->count();
$count_sequence = $count + 1;
$zero_filled_counter = sprintf('%04d', $count_sequence);

$squential_application_number= 'NMFA/AR/'.$year."/".$zero_filled_counter;

$squential_application_number_dossier = 'NMFA_AR_'.$year."_".$zero_filled_counter;



$update_application_number = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'application_number' =>$squential_application_number,
  ]
);



//Update Hold Progress Bar Report

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

//Create a NEw Directory using the Specified Application number

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

// // Uses to insert data in to the Acknowledgment Letter



//Create directory For Storing dossier files

$path = public_path('dossiers')."/".$squential_application_number_dossier;
// $generated_dossier_path = Storage::url('public/dossiers/'.$squential_application_number_dossier);
$generated_dossier_path_application_number = $squential_application_number_dossier;

//dd(Storage::deleteDirectory($path));

$Dossier_File_creation_directory = mkdir($path,1);
// if(Storage::makeDirectory($path) == true)
// {

//   $update_applications = DB::table('applications')
//   ->where('applications.application_id', $request->application_id)
//   ->update([ 'dossier_actual_path' => $generated_dossier_path_application_number ]);
// }
// else
// {
//   Storage::deleteDirectory($path);
//   Storage::makeDirectory($path);
// }


$update_applications = DB::table('applications')
->where('applications.application_id', $request->application_id)
->update([ 'dossier_actual_path' => $generated_dossier_path_application_number ]);




return response()->json(['Message'=>true,'application_number'=> $squential_application_number]);


   }
catch(Exception $e)
{
return response()->json(['Message'=>$e,'item'=>'error'.$e]);
}


}




    public function Request_for_all()
    {
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        //$route_administrations = route_administrations::all();
        $route_administrations = route_administrations::all()->sortBy('name');

        $agents = agents::all()->sortBy('trade_name');

        $agents_template = agents_template::all()->sortBy('trade_name');

        $company_suppliers = company_suppliers::all()->sortBy('trade_name');

        $company_suppliers_template = DB::select('select * from  company_supplier_template  where (is_Approved_By_NMFA = 1 )  order by trade_name  ASC');


        $product_details = DB::select('select * from  medicines where (is_enlm =1 and is_approved=1)  order by product_name ASC');
       // $product_details =  product_details::all()->sortBy('product_name');
      
 
       
       
    

             return view('application_reception.application_reception',[
                'countries' => $countries,
                'fast_track_applications' =>$fast_track_applications,
                'dosage_forms'=>  $dosage_forms,
                'apis'=>  $apis,
                'route_administrations'=>$route_administrations ,
                'company_suppliers'=> $company_suppliers,
                'company_suppliers_template' =>  $company_suppliers_template,
                'agents'=>$agents,
                'agents_template'=>$agents_template,
                'medicines'=>$product_details,
            ]);
          }



          public function company_supplier(Request $request)
          {
            try{

                $trade_name=$request->trade_name;

                $check_trade_name = company_suppliers_template::where('trade_name',$trade_name)
                ->join('countries','countries.id','company_supplier_template.country_id')

                ->get();

                return response()->json([

                        "id" => $check_trade_name[0]->id,
                        "trade_name" => $check_trade_name[0]->trade_name,
                        "country_id" => $check_trade_name[0]->country_id,
                        "city" => $check_trade_name[0]->city,
                        "state" => $check_trade_name[0]->state,
                        "address_line_one" => $check_trade_name[0]->address_line_one,
                        "address_line_two" => $check_trade_name[0]->address_line_two,
                        "country_code" => $check_trade_name[0]->country_code,
                        "telephone" => $check_trade_name[0]->telephone,
                        "email" => $check_trade_name[0]->email,
                        "postal_code" => $check_trade_name[0]->postal_code,
                        "webiste_url" => $check_trade_name[0]->webiste_url,
                        "International_dialing" => $check_trade_name[0]->International_dialing,
                        //"contacts_id" => $check_trade_name[0]->contacts_id,
                        "created_at" => $check_trade_name[0]->created_at,
                        "updated_at" => $check_trade_name[0]->updated_at,
                        "country_name" => $check_trade_name[0]->country_name,
                        "is_active" => $check_trade_name[0]->is_active

                                       ]);
                             }

            catch(Exception $e)
               {
                return response()->json(['trade'=>$e,'item'=>'error'.$e]);
                }


        }


        public function get_tele_code(Request $request)
        {

            $countries = new Country;
            $Check_tele = DB::select('select * from  countries  where id = ?', [$request['tele']]);
           foreach($Check_tele as $tele)
           {
             $this->tele = $tele->country_name;
             $this->code = $tele->International_dialing;
           }

        return response()->json(['Code'=> $this->code ]);

        }






        public function agent_info(Request $request)
        {
               try{

              $trade_name=$request->trade_name;

              $check_trade_name = agents_template::where('trade_name',$trade_name)
              ->join('countries','countries.id','local_agent_template.country_id')

              ->get();

              return response()->json([

                      "id" => $check_trade_name[0]->id,
                      "trade_name" => $check_trade_name[0]->trade_name,
                      "country_id" => $check_trade_name[0]->country_id,
                      "city" => $check_trade_name[0]->city,
                      "state" => $check_trade_name[0]->state,
                      "address_line_one" => $check_trade_name[0]->address_line_one,
                      "address_line_two" => $check_trade_name[0]->address_line_two,
                      "country_code" => $check_trade_name[0]->country_code,
                      "telephone" => $check_trade_name[0]->telephone,
                      "email" => $check_trade_name[0]->email,
                      "postal_code" => $check_trade_name[0]->postal_code,
                      "webiste_url" => $check_trade_name[0]->webiste_url,
                      "International_dialing" => $check_trade_name[0]->International_dialing,
                      //"contacts_id" => $check_trade_name[0]->contacts_id,
                      "created_at" => $check_trade_name[0]->created_at,
                      "updated_at" => $check_trade_name[0]->updated_at,
                      "country_name" => $check_trade_name[0]->country_name,
                      "is_active" => $check_trade_name[0]->is_active

                                     ]);
                           }

          catch(Exception $e)
             {
              return response()->json(['trade'=>$e,'item'=>'error'.$e]);
              }


      }





        public function company_supplier_save(Request $request){
            //dd($request->session()->all());
          //dd($new_application_id);
         
    
     try
     {
       $contact = new contacts;
       $contact->first_name = $request->first_name;
       $contact->middle_name = $request->middle_name;
       $contact->last_name = $request->last_name;
       $contact->country_id = $request->country_id_contact;
       $contact->position = $request->position;
       $contact->city = $request->city_contact;
       $contact->address_line_one = $request->address_line_one_contact;
       $contact->address_line_two = $request->address_line_two_contact;
       $contact->postal_code = $request->postal_code;
       $contact->telephone = $request->telephone_contact;
       $contact->contact_type = $request->contact_type;
      // $contact->webiste_url = $request->webiste_url_contact;
       $contact->email= $request->email_contact;
       $contact->user_id = $request->user_id;
       $contact->application_id =  $request->application_id;

          $contact_check = $contact->save();
//For New Supplier
$Get_Contact_Supplier_ID= contacts::where('application_id',$request->application_id)
->where('contact_type', 'Supplier')
->get();

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;

$current_progress = $Application_ID[0]->hold_progress_wizard.",1";

$update_applications_details_hold_wizard = DB::table('applications')->where('application_id', $request->application_id)->update(['hold_progress_wizard' =>$current_progress,]);

$company_suppliers = new company_suppliers;

if( $request->trade_name == 'Other')
{
 $Other_trade =  DB::table('company_supplier_template')->insert(['user_id'=>auth()->user()->id,'trade_name'=>$request->trade_name_other,'country_id'=>$request->country_id, 'state'=>$request->state,'is_Approved_By_NMFA'=>0,'country_code'=>$request->country_code,'is_Registerd_company' => 'NEW','created_at'=>now(),'updated_at'=>now()]);
 $Trade_ID = company_suppliers_template::where('trade_name',$request->trade_name_other)->get();
$Trade_Name = $request->trade_name_other;
}
else
{
  $Trade_Name = $request->trade_name;
}


// $company_suppliers->trade_name = $request->trade_name;
$company_suppliers->trade_name = $Trade_Name;
$company_suppliers->country_id = $request->country_id;
$company_suppliers->city = $request->city;
$company_suppliers->state = $request->state;
$company_suppliers->address_line_one = $request->address_line_one;
$company_suppliers->address_line_two = $request->address_line_two;
$company_suppliers->country_code = $request->country_code;
$company_suppliers->telephone = $request->telephone;
$company_suppliers->contacts_id = $Get_Contact_Supplier_ID[0]->id;
$company_suppliers->webiste_url = $request->webiste_url;
$company_suppliers->email= $request->email;
$company_suppliers->postal_code= $request->postal_code;
$company_suppliers->user_id = $request->user_id;
$company_suppliers->application_id = $request->application_id;
$company_suppliers->postal_code =  $request->postal_code;

$company_suppliers_check =$company_suppliers->save();


$Supplier_ID  = company_suppliers::where('application_id',$request->application_id)->get();
$Supplier_ID[0]->id;

$update_applications_details = DB::table('applications')->where('application_id', $request->application_id)->update(['company_supplier_id' =>$Supplier_ID[0]->id,]);

$Supplier_ID  = company_suppliers::where('application_id',$request->application_id)->get();
$Supplier_ID[0]->id;


if(  ($contact_check == true)   &&  ( $company_suppliers_check ==true)  )
{
  if( $request->trade_name == 'Other') { $company_supplier_template_id=$Trade_ID[0]->id;  } else {$company_supplier_template_id=0;}

    return response()->json(['company_supplier_template_id'=>$company_supplier_template_id,'supplyInfo'=>$contact->save(),'Contact_ID'=>$Get_Contact_Supplier_ID[0]->id,'Supplier_ID'=>$Supplier_ID[0]->id]);

}

    }
     catch(Exception $e)
        {

          DB::table('company_suppliers')->where('application_id', '=',$request->application_id)->delete();
          DB::table('contacts')->where('application_id', '=',$request->application_id)->where('contact_type', '=','Supplier')->delete();
    
return response()->json(['supplyInfo'=>$e,'item'=>'error'.$e]);

         }


 }






 public function company_supplier_update(Request $request){

try{


$contact = new contacts;
$contact->id = $request->contact_id;


$contacts = contacts::find($contact->id);
$contact->first_name = $request->first_name;
$contact->middle_name = $request->middle_name;
$contact->last_name = $request->last_name;
$contact->country_id = $request->country_id_contact;
$contact->position = $request->position;
$contact->city = $request->city_contact;
$contact->address_line_one = $request->address_line_one_contact;
$contact->address_line_two = $request->address_line_two_contact;
$contact->postal_code = $request->postal_code;
$contact->telephone = $request->telephone_contact;
$contact->contact_type = $request->contact_type;
//$contact->webiste_url = $request->webiste_url_contact;
$contact->email = $request->email_contact;
$contact->user_id = $request->user_id;
$contact->application_id = $request->application_id;

//$contact->save();


$affected_Contacts = DB::table('contacts')
              ->where('id', $contact->id)
              ->update(
                  [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'country_id' => $request->country_id_contact,
                    'position' => $request->position,
                    'city' => $request->city_contact,
                    'telephone' => $request->telephone_contact,
                    'address_line_one' => $request->address_line_one_contact,
                    'address_line_two' => $request->address_line_two_contact,
                    'postal_code' => $request->postal_code,
                    'email'=> $request->email_contact,
                    'contact_type'=>$request->contact_type,
                    'application_id' => $request->application_id
                  ]
                    );


$company_suppliers = new company_suppliers;
$company_suppliers->id= $request->supplier_id;
$company_suppliers = company_suppliers::find($company_suppliers->id);
$company_suppliers->trade_name = $request->trade_name;
$company_suppliers->country_id = $request->country_id;
$company_suppliers->city = $request->city;
$company_suppliers->state = $request->state;
$company_suppliers->address_line_one = $request->address_line_one;
$company_suppliers->address_line_two = $request->address_line_two;
$company_suppliers->country_code = $request->country_code;
$company_suppliers->telephone = $request->telephone;
$company_suppliers->contacts_id = $request->contact_id;
$company_suppliers->webiste_url = $request->webiste_url;
$company_suppliers->email= $request->email;
$company_suppliers->application_id = $request->application_id;
$company_suppliers->user_id = $request->user_id;
//$company_suppliers->institutional_email =  $request->institutional_email;
$company_suppliers->country_code =  $request->country_code;
//$affected_Company_supplier=$company_suppliers->save();

$company_suppliers = new company_suppliers;

// dd($request->company_supplier_template_id);
if( $request->trade_name == 'Other')
{

  if($request->company_supplier_template_id !=0)
  {
$affected_company_supplier_template = DB::table('company_supplier_template')
->where('id', $request->company_supplier_template_id)
->update(
  [
  'trade_name' => $request->trade_name_other,
  'country_id' => $request->country_id,
  'state' => $request->state,
  'country_code' => $request->country_code
  ]
);

$affected_Company_supplier = DB::table('company_suppliers')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'trade_name' => $request->company_supplier_template_id,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'state' => $request->state,
                'address_line_one' => $request->address_line_one,
                'address_line_two' => $request->address_line_two,
                'telephone' =>    $request->telephone,
                'contacts_id' =>  $request->contact_id,
                'webiste_url' =>  $request->webiste_url,
                'email'=>         $request->email,
                'application_id' => $request->application_id,
                'country_code' =>   $request->country_code
                //'institutional_email' =>$company_suppliers->institutional_email
                ]
              );

   // dd($affected_Company_supplier);


}
else
{
  return response()->json([
    'supplyInfo_updated'=>-1,
    'Contact_ID'=>$request->contact_id,
    'Supplier_ID'=>$request->supplier_id,
    'company_supplier_template_id' => $request->company_supplier_template_id,
     ]);


}
}
else if( $request->trade_name != 'Other')
{
  $affected_company_supplier_template=0;
  $affected_Company_supplier = DB::table('company_suppliers')
  ->where('application_id', $request->application_id)
  ->update(
    [
    'trade_name' => $request->trade_name,
    'country_id' => $request->country_id,
    'city' => $request->city,
    'state' => $request->state,
    'address_line_one' => $request->address_line_one,
    'address_line_two' => $request->address_line_two,
    'telephone' =>    $request->telephone,
    'contacts_id' =>  $request->contact_id,
    'webiste_url' =>  $request->webiste_url,
    'email'=>         $request->email,
    'application_id' => $request->application_id,
    'country_code' =>   $request->country_code
    //'institutional_email' =>$company_suppliers->institutional_email
    ]
  );

// dd($affected_Company_supplier);
}







if($affected_Contacts == 1 ||   $affected_Company_supplier ==1  ||  $affected_company_supplier_template==1   )
  {
    return response()->json([
    'supplyInfo_updated'=>1,
    'Contact_ID'=>$request->contact_id,
    'Supplier_ID'=>$request->supplier_id,
    'company_supplier_template_id' => $request->company_supplier_template_id, ]);
  }


  else
  {
    return response()->json([
        'supplyInfo_updated'=>0,
        'Contact_ID'=>$request->contact_id,
        'Supplier_ID'=>$request->supplier_id,
        'company_supplier_template_id' => $request->company_supplier_template_id,
         ]);
  }

}

catch(Exception $e)
{


 return response()->json(['supplyInfo'=>$e,'item'=>'error'.$e]);

 }


}






 public function  agent_save(Request $request){
    //dd(count($check_trade_name));
   //dd($check_trade_name[0]->country_name);
 // foreach( $check_trade_name as $trade ){ dd($trade);}
 //   $return_data = "";
// dd($request->all());
 // contacts::create($request->all());'

try
{
//For New Contacts
$contact = new contacts;


$contact->first_name = $request->first_name;
$contact->middle_name = $request->middle_name;
$contact->last_name = $request->last_name;
$contact->country_id = $request->country_id_contact;
$contact->position = $request->position;
$contact->city = $request->city_contact;
$contact->address_line_one = $request->address_line_one_contact;
$contact->address_line_two = $request->address_line_two_contact;
$contact->postal_code = $request->postal_code;
$contact->telephone = $request->telephone_contact;
$contact->contact_type = $request->contact_type;
//$contact->webiste_url = $request->webiste_url_contact;
$contact->email= $request->email_contact;
$contact->user_id=$request->user_id;
$contact->application_id =  $request->application_id;

$contact_age_info = $contact->save();


//For New Agent
$Get_Contact_Agent_ID= contacts::where('email',$request->email_contact)
->where('contact_type', 'Agent')
->get();


$agent = new agents;
$agent->trade_name = $request->trade_name;
$agent->country_id = $request->country_id;
$agent->city = $request->city;
$agent->state = $request->state;
$agent->address_line_one = $request->address_line_one;
$agent->address_line_two = $request->address_line_two;
$agent->country_code = $request->country_code;
$agent->telephone = $request->telephone;
$agent->contacts_id = $Get_Contact_Agent_ID[0]->id;
$agent->webiste_url = $request->webiste_url;
$agent->email= $request->email;
$agent->postal_code= $request->postal_code;
$agent->user_id=$request->user_id;
$agent->application_id =  $request->application_id;

$agent_info = $agent->save();



$Agent_ID  = agents::where('application_id',$request->application_id)->get();
$Agent_ID[0]->id;

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",2";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

$update_applications_details = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'agent_id' =>$Agent_ID[0]->id,
  ]
);

if($contact_age_info == true &&   $agent_info==true)
{
return response()->json(['AgentInfo'=>$contact->save(),'Contact_ID'=>$Get_Contact_Agent_ID[0]->id,'Agent_ID'=>$Agent_ID[0]->id]);
}

else if( ($contact_age_info == true)  &&   ($agent_info==false)  )
{
  $Get_Contact_Agent_ID= contacts::where('application_id',$request->application_id)
  ->where('contact_type', 'Agent')
  ->get();
      $contact = new contacts;
      $contact = contacts::find($Get_Contact_Agent_ID[0]->id);
      $contact->delete();
}


else if( ($contact_age_info == false)  &&   ($agent_info==true)  )
{
  $Get_Contact_Agent_ID= agents::where('application_id',$request->application_id)
  ->get();
      $agent = new agents;
      $contact = agent::find($Get_Contact_Agent_ID[0]->id);
      $contact->delete();
}


}
catch(Exception $e)
{
  DB::table('agents')->where('application_id', '=',$request->application_id)->delete();
  DB::table('contacts')->where('application_id', '=',$request->application_id)->where('contact_type', '=','Agent')->delete();

return response()->json(['AgentInfo'=>$e,'item'=>'error'.$e]);

 }


}












public function  agent_update(Request $request){
    //dd(count($check_trade_name));
   //dd($check_trade_name[0]->country_name);
 // foreach( $check_trade_name as $trade ){ dd($trade);}
 //   $return_data = "";
// dd($request->all());
 // contacts::create($request->all());'

try{
//For New Contacts
$contact = new contacts;


$contact->first_name = $request->first_name;
$contact->middle_name = $request->middle_name;
$contact->last_name = $request->last_name;
$contact->country_id = $request->country_id_contact;
$contact->position = $request->position;
$contact->city = $request->city_contact;
$contact->address_line_one = $request->address_line_one_contact;
$contact->address_line_two = $request->address_line_two_contact;
$contact->postal_code = $request->postal_code;
$contact->telephone = $request->telephone_contact;
$contact->contact_type = $request->contact_type;
//$contact->webiste_url = $request->webiste_url_contact;
$contact->email= $request->email_contact;
$contact->id = $request->contact_id;
$contact->application_id = $request->application_id;
//$contact->save();
$affected_Contacts = DB::table('contacts')
              ->where('application_id', $request->application_id)
              ->where('id',$request->contact_id)
              ->update(
                  [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'country_id' => $request->country_id_contact,
                    'position' => $request->position,
                    'city' => $request->city_contact,
                    'address_line_one' => $request->address_line_one_contact,
                    'address_line_two' => $request->address_line_two_contact,
                    'postal_code' => $request->postal_code,
                   // 'webiste_url' => $request->webiste_url_contact,
                    'email'=> $request->email_contact,
                    'contact_type'=>$request->contact_type

                  ]
                    );
//dd($request->application_id);


$agent = new agents;
$agent->trade_name = $request->trade_name;
$agent->country_id = $request->country_id;
$agent->city = $request->city;
$agent->state = $request->state;
$agent->address_line_one = $request->address_line_one;
$agent->address_line_two = $request->address_line_two;
$agent->country_code = $request->country_code;
$agent->telephone = $request->telephone;
$agent->contacts_id = $request->contact_id;
$agent->webiste_url = $request->webiste_url;
$agent->email= $request->email;
$agent->postal_code= $request->postal_code;
$agent->id = $request->agent_id;
$agent->application_id = $request->id;
//$agent->save();



$affected_Agent = DB::table('agents')
              ->where('application_id', $request->application_id)
              ->where('id' , $agent->id)
              ->update(
                ['trade_name' => $request->trade_name,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'state' => $request->state,
                'address_line_one' => $request->address_line_one,
                'address_line_two' => $request->address_line_two,
                'country_code' => $request->country_code,
                'telephone' => $request->telephone,
                'contacts_id' => $agent->contacts_id,
                'webiste_url' => $request->webiste_url,
                'email'=>$request->email

                 ]
              );



if($affected_Agent == 1 ||   $affected_Contacts==1) {return response()->json(
       ['AgentupdateInfo'=>'Agent and contact information updated successfully',
       'message'=>true,
       'status'=>1
       ]
    );}
    else
    {

        return response()->json(
            ['AgentupdateInfo'=>'No update is made!!',
            'message'=>false

            ]);
    }
}

catch(Exception $e)
{


 return response()->json(['AgentInfo'=>$e,'item'=>'error'.$e]);

 }


}





//Product Details Save Functions



public function product_details_save(Request $request){

    try{
              //  dd($request->all());
      $select_data_dosage_form= DB::table('dosage_forms')->where('id', $request->dosage_form_id)->get();
      @$dosage_form = $select_data_dosage_form[0]->name;

      $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
      @$strength_name = $select_data_strength[0]->product_name;



       $medicinal_details =  medicinal_products::create($request->all());

       $medicinal_details_ID  = medicinal_products::where('application_id',$request->application_id)->get();
       @$medicinal_details_ID[0]->id;

       $Application_ID  = applications::where('application_id',$request->application_id)->get();
       @$Application_ID[0]->hold_progress_wizard;

       @$current_progress = $Application_ID[0]->hold_progress_wizard.",3";

       $update_applications_details = DB::table('applications')
       ->where('application_id', $request->application_id)
       ->update(
         [
         'medical_product_id' =>$medicinal_details_ID[0]->id,
         'hold_progress_wizard' => $current_progress,
         ]
       );

      

//dd($medicinal_details);

        $productdetial_id  = medicinal_products::where('product_trade_name',$request->product_trade_name)->get();

        
        return response()->json(['Message'=>true,
        'productdetial_id'=>$productdetial_id[0]->id,
        'strength_name'=>$strength_name,
        'dosage_form'=>$dosage_form,
        ]);


    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function product_details_save_other(Request $request )
{

  try{

  //  dd($request->generic_approved_name_other);

    $Medicine_table =  DB::table('medicines')->insert(['product_name' => $request->generic_approved_name_other, 'medicine_id' => $request->generic_approved_name_other, 'product_description' => '---', 'is_enlm' => 0, 'is_approved' => 0, 'created_at' => now() ,'updated_at'=> now() ]);

    $Medicine_table = DB::table('medicines')
    ->select('medicines.*')
    ->where('product_name',$request->generic_approved_name_other)
    ->get();


   //  dd($Medicine_table[0]->id);

//$medicinal_details =  medicinal_products::create($request->all());
$medicinal_details = new medicinal_products();
$medicinal_details->user_id = $request->user_id;
$medicinal_details->product_trade_name = $request->product_trade_name ;
$medicinal_details->medicine_id= $Medicine_table[0]->id;
$medicinal_details->dosage_form_id= $request->dosage_form_id;
$medicinal_details->route_administration_id = $request->route_administration_id;
$medicinal_details->description = $request->description ;
//$medicinal_details->strength_amount = $request->strength_amount;
//$medicinal_details->strength_unit = $request->strength_unit;
$medicinal_details->strength_amount_strength_unit = $request->strength_amount_strength_unit;
$medicinal_details->pharmaco_therapeutic_classification = $request->pharmaco_therapeutic_classification;
$medicinal_details->storage_condition = $request->storage_condition;
$medicinal_details->shelf_life_amount = $request->shelf_life_amount;
$medicinal_details->shelf_life_unit = $request->shelf_life_unit;
$medicinal_details->proposed_shelf_life_amount= $request->proposed_shelf_life_amount;
$medicinal_details->proposed_shelf_life_unit= $request->proposed_shelf_life_unit;
$medicinal_details->proposed_shelf_life_after_reconstitution_amount=$request->proposed_shelf_life_after_reconstitution_amount;
$medicinal_details->proposed_shelf_life_after_reconstitution_unit =  $request->proposed_shelf_life_after_reconstitution_unit;
$medicinal_details->visual_description = $request->visual_description;
$medicinal_details->commercial_presentation = $request->commercial_presentation;
$medicinal_details->container = $request->container;
$medicinal_details->packaging = $request->packaging;
$medicinal_details->category_use = $request->category_use;
$medicinal_details->application_id= $request->application_id;
$medicinal_details->save();




    $medicinal_details_ID  = medicinal_products::where('application_id',$request->application_id)->get();
    $medicinal_details_ID[0]->id;

    $Application_ID  = applications::where('application_id',$request->application_id)->get();
    $Application_ID[0]->hold_progress_wizard;

    $current_progress = $Application_ID[0]->hold_progress_wizard.",3";

    $update_applications_details = DB::table('applications')
    ->where('application_id', $request->application_id)
    ->update(
      [
      'medical_product_id' =>$medicinal_details_ID[0]->id,
      'hold_progress_wizard' => $current_progress,
      ]
    );



     // dd($medicinal_details);
     $productdetial_id  = medicinal_products::where('product_trade_name',$request->product_trade_name)->get();
     return response()->json(['Message'=>true,'productdetial_id'=>$productdetial_id[0]->id]);


 }

 catch(Exception $e){
     return response()->json(['Message'=>$e,'item'=>'error'.$e]);
     }
}


public function product_details_update(Request $request){

    try{
      //  dd($request->all());
        $medicinal_products = medicinal_products::find($request->id);
        $medicinal_products->user_id=$request->user_id;
        $medicinal_products->product_trade_name= $request->product_trade_name;
        $medicinal_products->medicine_id= $request->medicine_id;
        $medicinal_products->dosage_form_id= $request->dosage_form_id;
        $medicinal_products->route_administration_id= $request->route_administration_id;
        $medicinal_products->description= $request->description;
        $medicinal_products->strength_amount_strength_unit= $request->strength_amount_strength_unit;
        // $medicinal_products->strength_amount= $request->strength_amount;
        // $medicinal_products->strength_unit= $request->strength_unit;
        $medicinal_products->pharmaco_therapeutic_classification=$request->pharmaco_therapeutic_classification;
        $medicinal_products->storage_condition=$request->storage_condition;
        $medicinal_products->shelf_life_amount= $request->shelf_life_amount;
        $medicinal_products->shelf_life_unit=$request->shelf_life_unit;
        $medicinal_products->proposed_shelf_life_amount= $request->proposed_shelf_life_amount;
        $medicinal_products->proposed_shelf_life_unit= $request->proposed_shelf_life_unit;
        $medicinal_products->proposed_shelf_life_after_reconstitution_amount =$request->proposed_shelf_life_after_reconstitution_amount ;
        $medicinal_products->proposed_shelf_life_after_reconstitution_unit = $request->proposed_shelf_life_after_reconstitution_unit ;
        $medicinal_products->visual_description= $request->visual_description;
        $medicinal_products->commercial_presentation =  $request->commercial_presentation ;
        $medicinal_products->container= $request->container;
        $medicinal_products->packaging= $request->packaging;
        $medicinal_products->category_use= $request->category_use;
        $medicinal_products->id= $request->id;
        $medicinal_products->application_id =  $request->application_id;
        $medicinal_products=$medicinal_products->save();

        $select_data_dosage_form= DB::table('dosage_forms')->where('id', $request->dosage_form_id)->get();
        $dosage_form = $select_data_dosage_form[0]->name;
  
        $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
        $strength_name = $select_data_strength[0]->product_name;




        $productdetial_id  = medicinal_products::where('application_id',$request->application_id)->get();
        if( $medicinal_products==1)
        {

          //  return response()->json(['Message'=>true,'productdetial_id'=>$productdetial_id[0]->id]);
           return response()->json(['Message'=>true,
            'productdetial_id'=>$productdetial_id[0]->id,
            'strength_name'=>$strength_name,
            'dosage_form'=>$dosage_form,
            ]);


        }
        else
        {

            return response()->json(['Message'=>false,'productdetial_id'=>$productdetial_id[0]->id]);
        }



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}




public function product_manufacturer_update(Request $request)
{
          try{
       // dd($request->id);
        $manufacturers = manufacturers::find($request->id);
        $manufacturers->user_id = $request->user_id;
        $manufacturers->name=$request->name;
        $manufacturers->country_id=$request->country_id;
        $manufacturers->city= $request->city;
        $manufacturers->state=$request->state;
        $manufacturers->addressline_one=$request->addressline_one;
        $manufacturers->addressline_two=$request->addressline_two;
        $manufacturers->postal_code=$request->postal_code;
        $manufacturers->telephone = $request->telephone;
        $manufacturers->country_code =$request->country_code;
       // $manufacturers->webiste_url=  $request->webiste_url;
        $manufacturers->activity=$request->activity;
        //$manufacturers->email=$request->email;
        $manufacturers->block=$request->block;
        $manufacturers->unit=$request->unit;
        $manufacturers->product_id=$request->product_id;
        $manufacturers->application_id=$request->application_id;
        //$manufacturers->id= $request->id;
        $manufacturers->save();

         $manufacturers = manufacturers::where('application_id',$request->application_id) ->orderBy('id', 'ASC')->get();;
         //->where('id',$request->id)
        // ->where('email',$request->email)
         //->join('countries','countries.id','manufacturers.country_id')


         $countries = Country::where('id',$request->country_id)
         // ->where('email',$request->email)
          //->join('countries','countries.id','manufacturers.country_id')
          ->get();

       $return_data = "";$i=1;
         foreach($manufacturers as $manufacturer)

         {

            $return_data .= "<tr><td>".$manufacturer->id."</td>";
            $return_data .= "<td>".$manufacturer->application_id."</td>";
            $return_data .= "<td>".$manufacturer->name."</td>";
            $return_data .= "<td>".$countries[0]->country_name."</td>";
            $return_data .= "<td>".$manufacturer->postal_code."</td>";
            $return_data .= "<td>".$manufacturer->telephone."</td>";
            $return_data .= "<td>".$manufacturer->state."</td>";
            $return_data .= "<td>".$manufacturer->addressline_one."</td>";
            $return_data .= "<td>".$manufacturer->addressline_two."</td>";
            //$return_data .= "<td>".$manufacturer->webiste_url."</td>";
            $return_data .= "<td>".$manufacturer->activity."</td>";
            $return_data .= "<td>".$manufacturer->block."</td>";
            $return_data .= "<td>".$manufacturer->unit."</td>";
             //$return_data .= "<td>".$manufacturer->email."</td>";
             $return_data .= "<td>".$manufacturer->city."</td>";
             $return_data .= "<td>".$manufacturer->country_code."</td>";
             $return_data.="<td>
        <a href='#' class='btn btn-warning btn-sm' value='".$manufacturer->id."'  ><i class='fas fa-pencil-alt'></i> </a>
               <br/> <br/>
        <a href='#' class='btn btn-danger btn-sm' value='".$manufacturer->id."'  onclick= 'Delete_manufacture('".$manufacturer->id."')' ><i class='fas fa-trash'></i> </a>
               </td></tr> ";



         }


         return response()->json(['renderd_manufacturer_table'=>$return_data,'Message'=>true,'Manufacturer_id'=>$manufacturer->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}

public function delete_manufacturer_elements(Request $request)
{

  $id = $request->id;
  //dd($request->application_id);
  //$product_composition = product_composition::find($id)->delete();
  $manufacturerss=DB::table('manufacturers')->where('id', '=', $request->id)->delete();
  //$manufacturers = manufacturers::where('application_id',$request->application_id)->orderBy('id', 'ASC')->get();
  try
  {
  $manufacturers = DB::table('manufacturers')
            ->select('manufacturers.*')
            ->where('application_id', '=', $request->application_id)
            ->get();


            foreach($manufacturers as $manufacturer){
              if($manufacturer->id != '' ){
        @$countries = Country::where('id', $manufacturers[0]->country_id)->get();$country = $countries[0]->country_name;}
             else
             {  $country = ''; $return_data ="";
             }

              }


    $return_data = "";$i=1;
  foreach($manufacturers as $manufacturer)
  {

      if($manufacturer->id != '' )
      {
 $return_data .= "<tr><td>".$manufacturer->id."</td>";
 $return_data .= "<td>".$manufacturer->application_id."</td>";
 $return_data .= "<td>".$manufacturer->name."</td>";
 $return_data .= "<td>".$country."</td>";
 $return_data .= "<td>".$manufacturer->postal_code."</td>";
 $return_data .= "<td>".$manufacturer->telephone."</td>";
 $return_data .= "<td>".$manufacturer->state."</td>";
 $return_data .= "<td>".$manufacturer->addressline_one."</td>";
 $return_data .= "<td>".$manufacturer->addressline_two."</td>";
 //$return_data .= "<td>".$manufacturer->webiste_url."</td>";
 $return_data .= "<td>".$manufacturer->activity."</td>";
 $return_data .= "<td>".$manufacturer->block."</td>";
 $return_data .= "<td>".$manufacturer->unit."</td>";
 //$return_data .= "<td>".$manufacturer->email."</td>";
 $return_data .= "<td>".$manufacturer->city."</td>";
 $return_data .= "<td>".$manufacturer->country_code."</td>";
 $return_data.="
   <td>
  <button class='btn btn-warning btn-sm'  ><i class='fas fa-pencil-alt'></i></button>
  <br/><br/>
  <button class='btn btn-danger btn-sm' onclick='Delete_Manufacturer($manufacturer->id)' ><i class='fas fa-trash'></i> </button>
  </td>

  </tr> ";

      }
      else
        {
        $return_data ="";
        }

}

return response()->json(['renderd_product_manufacturer_table'=>$return_data,'Message'=>true,'Manufacturer_id'=>$manufacturer->id]);

  }

            catch(Exception $e){
              $return_data ="";
            return  response()->json(['Message'=>false,'renderd_product_manufacturer_table'=>$return_data,'Manufacturer_id'=>$request->id]);



              }


}



////////////////////////////////////////////////////////////
public function delete_manufacturer_elements_api(Request $request)
{

  $id = $request->id;
  $manufacturerss_api=DB::table('api_manufacturers')->where('id', '=', $request->id)->delete();

  try
  {
  $manufacturers_api = DB::table('api_manufacturers')
            ->select('*')
            ->where('application_id', '=', $request->application_id)
            ->orderby('id','ASC')
            ->get();


            foreach($manufacturers_api as $manufacturer){
              if($manufacturer->id != '' )
              {
            @$countries = Country::where('id', $manufacturers_api[0]->country_id)->get();$country = $countries[0]->country_name;
              }
             else
             {  $country = ''; $return_data ="";
             }

              }

   $return_data = "";$i=1;
  foreach($manufacturers_api as $manufacturer_api)
  {

      if($manufacturer_api->id != '' )
      {
        $return_data .= "<tr><td>".$manufacturer_api->id."</td>";
        $return_data .= "<td>".$manufacturer_api->application_id."</td>";
        $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
        $return_data .= "<td>".$countries [0]->country_name."</td>";
        $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
        $return_data .= "<td>".$manufacturer_api->telephone."</td>";
        $return_data .= "<td>".$manufacturer_api->city."</td>";
        $return_data .= "<td>".$manufacturer_api->state."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";

        $return_data.="<td>
        <button class='btn btn-warning btn-sm'   ><i class='fas fa-pencil-alt'></i>  </button>
        <br/> <br/>
        <span class='btn btn-danger btn-sm'  onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i> </span>
        </td></tr> ";
      }
      else
        {

        $return_data ="";

        }

}

return response()->json(['renderd_manufacturer_api_table'=>$return_data,'Message'=>true,'Manufacturer_id'=>$manufacturer_api->id]);

  }

            catch(Exception $e){

            return  response()->json(['Message'=>false,'item'=>'error'.$e]);



              }


}


///////////////////////////////////////////////////////////////

public function product_manufacturer_save(Request $request)
{
          try{

            $product_manufacturer=  manufacturers::create($request->all());
            // dd($medicinal_details);
             $manufacturers = manufacturers::where('application_id',$request->application_id)
             ->orderBy('id', 'ASC')
            // ->where('email',$request->email)
             //->join('countries','countries.id','manufacturers.country_id')
             ->get();

         $countries = Country::where('id',$request->country_id)
         // ->where('email',$request->email)
          //->join('countries','countries.id','manufacturers.country_id')
          ->get();

          $Application_ID  = applications::where('application_id',$request->application_id)->get();
          $Application_ID[0]->hold_progress_wizard;
          $current_progress = $Application_ID[0]->hold_progress_wizard.",5";
          $update_applications_details_hold_wizard = DB::table('applications')
          ->where('application_id', $request->application_id)
          ->update(
            [
            'hold_progress_wizard' => $current_progress,
            ]
          );


       $return_data = "";$i=1;
         foreach($manufacturers as $manufacturer)

         {

        $return_data .= "<tr><td>".$manufacturer->id."</td>";
        $return_data .= "<td>".$manufacturer->application_id."</td>";
        $return_data .= "<td>".$manufacturer->name."</td>";
        $return_data .= "<td>".$countries[0]->country_name."</td>";
        $return_data .= "<td>".$manufacturer->postal_code."</td>";
        $return_data .= "<td>".$manufacturer->telephone."</td>";
        $return_data .= "<td>".$manufacturer->state."</td>";
        $return_data .= "<td>".$manufacturer->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer->addressline_two."</td>";
        //$return_data .= "<td>".$manufacturer->webiste_url."</td>";
        $return_data .= "<td>".$manufacturer->activity."</td>";
        $return_data .= "<td>".$manufacturer->block."</td>";
        $return_data .= "<td>".$manufacturer->unit."</td>";
        // $return_data .= "<td>".$manufacturer->email."</td>";
         $return_data .= "<td>".$manufacturer->city."</td>";
         $return_data .= "<td>".$manufacturer->country_code."</td>";
         $return_data.="<td>

          <button class='btn btn-warning btn-sm' value='".$manufacturer->id."'   ><i class='fas fa-pencil-alt'></i> </button>
           <br/> <br/>
           <button class='btn btn-danger btn-sm' value='".$manufacturer->id."'  onclick='Delete_Manufacturer($manufacturer->id)' ><i class='fas fa-trash'></i> </button>
           </td></tr> ";




         }


         return response()->json(['renderd_manufacturer_table'=>$return_data,'Message'=>true,'Manufacturer_id'=>$manufacturers[0]->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}





public function product_composition_save(Request $request)
{
          try{
        //dd($request->all());

        $product_compositions =  product_composition::create($request->all());
        // dd($medicinal_details);
         $product_compositions = product_composition::where('application_id',$request->application_id)
         //->where('medical_product_id',$request->medical_product_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
         ->get();



$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",4";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);


       $return_data = "";$i=1;
         foreach($product_compositions as $product_composition)

         {

        $return_data .= "<tr><td>".$product_composition->id."</td>";
        $return_data .= "<td>".$product_composition->application_id."</td>";
        $return_data .= "<td>".$product_composition->composition_name."</td>";
        $return_data .= "<td>".$product_composition->quantity."</td>";
        $return_data .= "<td>".$product_composition->reason."</td>";
        $return_data .= "<td>".$product_composition->reference_standard."</td>";
        $return_data .= "<td>".$product_composition->type."</td>";


        $return_data.="<td>

        <button
        class='btn btn-warning btn-sm' value='".$product_composition->application_id."'
        id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
        </button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>


         </td></tr> ";


         }


         return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>true,'Compostion_id'=>$product_composition->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}




 public function delete_composition_elements(Request $request)
{
 $id = $request->id;
 //dd($id);
 //$product_composition = product_composition::find($id)->delete();

 $product_composition=DB::table('product_compositions')->where('id', '=', $request->id)->delete();

 $product_compositions = product_composition::where('application_id',$request->application_id)
 //->where('medical_product_id',$request->medical_product_id)
// ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
 ->get();



$return_data = "";$i=1;
 foreach($product_compositions as $product_composition)

 {
  if($product_composition->id !='')
  {
$return_data .= "<tr><td>".$product_composition->id."</td>";
$return_data .= "<td>".$product_composition->application_id."</td>";
$return_data .= "<td>".$product_composition->composition_name."</td>";
$return_data .= "<td>".$product_composition->quantity."</td>";
$return_data .= "<td>".$product_composition->reason."</td>";
$return_data .= "<td>".$product_composition->reference_standard."</td>";
$return_data .= "<td>".$product_composition->type."</td>";


$return_data.="<td>
<button
        class='btn btn-warning btn-sm' value='".$product_composition->application_id."'
        id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
        </button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>

 </td></tr> ";
  }
  else
  {
    return $return_data='';
  }
 }


 return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>true,'Compostion_id'=>@$product_composition->id]);



}


public function product_composition_update(Request $request)
{
          try{
        ///dd($request->name);

        $product_compositions = product_composition::find($request->id);
        $product_compositions->user_id = $request->user_id;


        $product_compositions->composition_name =$request->composition_name;
        $product_compositions->reason =  $request->reason;
        $product_compositions->reference_standard = $request->reference_standard;
        $product_compositions->type = $request->type;
        $product_compositions->medical_product_id = $request->medical_product_id;
        $product_compositions->quantity = $request->quantity;
        $product_compositions->composition_name = $request->composition_name;
        $product_compositions->application_id = $request->application_id ;
        $product_compositions->id = $request->id;
        $product_compositions->save();

         $product_compositions = product_composition::where('application_id',$request->application_id)
         //->where('medical_product_id',$request->medical_product_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
         ->get();

       $return_data = "";$i=1;
         foreach($product_compositions as $product_composition)

         {

        $return_data .= "<tr><td>".$product_composition->id."</td>";
        $return_data .= "<td>".$product_composition->application_id."</td>";
        $return_data .= "<td>".$product_composition->composition_name."</td>";
        $return_data .= "<td>".$product_composition->quantity."</td>";
        $return_data .= "<td>".$product_composition->reason."</td>";
        $return_data .= "<td>".$product_composition->reference_standard."</td>";
        $return_data .= "<td>".$product_composition->type."</td>";


        $return_data.="<td>

        <button
        class='btn btn-warning btn-sm' value='".$product_composition->application_id."'
        id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
        </button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>
         </td></tr> ";


         }


         return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>true,'Compostion_id'=>$product_composition->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}

//Api Product Manufacturer


public function save_product_manufacturer_api(Request $request)
{
          try{
        ///dd($request->name);

        $manufacturer_apis = api_manufacturers::create($request->all());
        // dd($medicinal_details);
        $manufacturer_apis = api_manufacturers::where('user_id',$request->user_id)
         ->where('application_id',$request->application_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
        ->orderby('id','ASC')
         ->get();


         $countries = Country::where('id',$request->country_id)
         // ->where('email',$request->email)
          //->join('countries','countries.id','manufacturers.country_id')
          ->get();



$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",6";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);


       $return_data = "";$i=1;
         foreach( $manufacturer_apis as $manufacturer_api)

         {

        $return_data .= "<tr><td>".$manufacturer_api->id."</td>";
        $return_data .= "<td>".$manufacturer_api->application_id."</td>";
        $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
        $return_data .= "<td>". $countries[0]->country_name."</td>";
        $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
        $return_data .= "<td>".$manufacturer_api->telephone."</td>";
        $return_data .= "<td>".$manufacturer_api->city."</td>";
        $return_data .= "<td>".$manufacturer_api->state."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";
        //$return_data .= "<td>".$manufacturer_api->webiste_url."</td>";
        //$return_data .= "<td>".$manufacturer_api->email."</td>";

        ;

        $return_data.="<td>
        <button class='btn btn-warning btn-sm' value='".$manufacturer_api->id."'  ><i class='fas fa-pencil-alt'></i> </button>
         <br/> <br/>

         <span
         class='btn btn-danger btn-sm' value='".$manufacturer_api->id."'
         onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i>
         </span>
         </td></tr> ";


         }


         return response()->json(['renderd_manufacturer_api_table'=>$return_data,'Message'=>true,'Manufacturer_api_id'=>$manufacturer_api->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}










public function update_product_manufacturer_api(Request $request)
{
          try{

            $manufacturer_update_api = DB::table('api_manufacturers')
            ->where('id', $request->id_for_update_api)
            ->where('application_id', $request->application_id)
            ->orderby('id','ASC')
            ->update(
                 [
                  'manufacturer_name' => $request->manufacturer_name,
                  'country_id' => $request->country_id,
                  'city' => $request->city,
                  'state' => $request->state,
                  'addressline_one' => $request->addressline_one,
                  'addressline_two' => $request->addressline_two,
                  'postal_code' => $request->postal_code,
                  'telephone' => $request->telephone,

                  ]
                  );


                  $countries = Country::where('id',$request->country_id)
                  // ->where('email',$request->email)
                   //->join('countries','countries.id','manufacturers.country_id')
                   ->get();


                  $manufacturer_apis = api_manufacturers::where('user_id',$request->user_id)
                  ->where('application_id',$request->application_id)
                 // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
                  ->get();

         $Application_ID  = applications::where('application_id',$request->application_id)->get();
         $Application_ID[0]->hold_progress_wizard;
         $current_progress = $Application_ID[0]->hold_progress_wizard.",6";
         $update_applications_details_hold_wizard = DB::table('applications')
         ->where('application_id', $request->application_id)
         ->update(
           [
           'hold_progress_wizard' =>$current_progress,
           ]
         );


                $return_data = "";$i=1;
                  foreach( $manufacturer_apis as $manufacturer_api)

                  {

                 $return_data .= "<tr><td>".$manufacturer_api->id."</td>";
                 $return_data .= "<td>".$manufacturer_api->application_id."</td>";
                 $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
                 $return_data .= "<td>".$countries [0]->country_name."</td>";
                 $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
                 $return_data .= "<td>".$manufacturer_api->telephone."</td>";
                 $return_data .= "<td>".$manufacturer_api->city."</td>";
                 $return_data .= "<td>".$manufacturer_api->state."</td>";
                 $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
                 $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";
                 //$return_data .= "<td>".$manufacturer_api->webiste_url."</td>";
                 //$return_data .= "<td>".$manufacturer_api->email."</td>";

                 ;

                 $return_data.="<td>
               <button class='btn btn-warning btn-sm'   ><i class='fas fa-pencil-alt'></i>  </button>
                 <br/> <br/>
               <span class='btn btn-danger btn-sm' onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i> </span>
                  </td></tr> ";


                  }


                  return response()->json(['renderd_manufacturer_api_table'=>$return_data,'Message'=>true,'Manufacturer_api_id'=>$manufacturer_api->id]);



             }

             catch(Exception $e){
                 return response()->json(['Message'=>$e,'item'=>'error'.$e]);
                 }

}



public function Get_country_id(Request $request)
{

  $countries = Country::where('country_name',$request->country_name)
  // ->where('email',$request->email)
   //->join('countries','countries.id','manufacturers.country_id')
   ->get();

   return response()->json(['CountryID'=>$countries[0]->id]);


}





public function random($length)
{


$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
$rendered_value='';
for($i=0;$i<$length;$i++)
{
    $rendered_value.=substr($chars,mt_rand(0,strlen($chars)),1);
}

return $rendered_value;
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
//Saving Application Status

public function application_save(Request $request)
{
    $applications =  new applications;


          try
           {
        $t=time();
       $year = Date('Y');
        $count = applications::where('id', '<>', null)->count();
        $count_sequence = $count + 1;
        $zero_filled_counter = sprintf('%04d', $count_sequence);
        $random_application_id= 'NMFA_'.$year."_".$zero_filled_counter;
        // $random_application_id= 'NMFA_'.date("Y-m-d",$t)."_".ApplicationReceptionController::random(12);
        $request['application_id'] = $random_application_id;
        $applications = applications::create($request->all());
        $decleration = DB::table('declarations')->insert([
            'application_id' => $request['application_id'],
            ]);


            $request->session()->put('new_application_id', $random_application_id);

            $application=applications::where('application_id',$random_application_id)->first();

            $duration_days = 30;
            $task_name = 'Application';
            $related_task = 'Application';
            $related_id =  $application->id;
            $start_time = date('Y-m-d H:i:s', strtotime('-3'));
            $end_time = date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $stopping_reason = '';
            $task_duration_days_actual = '';
            $is_active = 1;
            $is_complete = 0;
            $is_archived = 0;
            $task_status = 'Inprogress';
            $deadline = $end_time;

            //notify before days
            $alert_before_days = 5;


            // alert('Main Task');
            // Log::alert('main receiption application');

           MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);

            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
            $task_category = 'Applying';
            $task_activity_title = 'Application process';
            $content_details = 'New application initiated ';
            $route_link = '';
            $activity_status = 'inprogress';
            $uploaded_document_id = null;
            // application_evaluation_progresses::insert([
            //  'application_id'=>$application->id,

            //]);
            //insert this into task tracker

           MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
          
          
           $tasks = TaskTracker::where('task_id', $main_task->id)
            ->where('task_category','Applying')
            ->first();
            
            //->OrderBy('task_trackers.id', 'desc')
    
            application_evaluation_progresses::insert([
            'application_id'=>$application->id,
            'task_id' =>$tasks->id,
            ]);



            //$user=User::where('id', $application->user_id)->first();


            $user=User::where('id', $application->user_id)->first();
           
           
            $new_notification=[];
            

    
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='New Application';
        $new_notification['from_user'] = 'System Notification';
        $new_notification['data'] = 'New Appication has intiated by applicant';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $application->id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
            
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'New Application has initiated and  should complete before  deadline (30 day) '));
   
            $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$random_application_id)
        ->get();

$Application_ID  = applications::where('application_id', $request['application_id'])->get();

$Application_ID[0]->hold_progress_wizard;
$current_progress = "0";


$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request['application_id'])
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

       return response()->json(['Message'=>true,'application_id'=> $random_application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function application_update(Request $request)
{
// dd($request->all());

  $applications =  new applications;


  try
   {

$update_applications_details = DB::table('applications')
->where('application_id', $request['application_id'])
->update(
[
'application_type' =>    $request->application_type,
'fast_track_details' =>  $request['fast_track_details'],
'progress_percentage' => $request->progress_percentage,

]
);

return response()->json(['Message'=>true,'Application_Type'=> $request['fast_track_details']]);
    }
catch(Exception $e)
{
return response()->json(['Message'=>$e,'item'=>'error'.$e]);
}


}


public function dossier_sample_save(Request $request)
{

    $dossier_status =  new applications;

          try
           {
            $dossier_status = DB::table('applications')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'dossier_url' => $request->dossier_url,
                'sample_status' => $request->sample_status,

                ]
              );
        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();


$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";


$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

$main_task = $this->get_main_task_id($applications[0]->id,'Application');
$end_time = date('Y-m-d H:i:s', strtotime('-3'));
$issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
$task_category = 'Applying';
$task_activity_title = 'Dossier link created';
$content_details = 'New application dossier link created ';
$route_link = '';
$activity_status = 'Locked';
$uploaded_document_id = null;
MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);


$dataa = User::orderBy('id','ASC')->get();


              foreach ($dataa as $key => $user)
              {
              if(!empty($user->getRoleNames()))
              {
              foreach($user->getRoleNames() as $v)
              {
              if($v=='Supervisor')
              {
                $new_notification=[];
                $new_notification['type'] = 'Notification';
                $new_notification['subject'] ='Dossier has been Created';
                $new_notification['from_user'] = 'System Reminder';
                $new_notification['data'] = 'Applicant  has created new dossier link';
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $request->application_id;
                $new_notification['alert_level'] = null;
                $new_notification['remark'] = null;
              // ::send($users, new ($invoice));


              Notification::send($user, new ApplicationReceiptionNotification($new_notification));
              event(new ApplicationReceiptionEvent($user->id, 'New application has been created and Dossier submitted for application number '. $applications[0]->application_number ));


              }
            }
          }
        }

       return response()->json(['Message'=>true,'application_id'=> $request->application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function dossier_sample_update(Request $request)
{

    $dossier_status =  new applications;

          try
           {
            $dossier_status = DB::table('applications')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'dossier_url' => $request->dossier_url
                // 'sample_status' => $request->sample_status,

                ]
              );
        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";


$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

       return response()->json(['Message'=>true,'application_id'=> $request->application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }



        $dataa = User::orderBy('id','ASC')->get();


        foreach ($dataa as $key => $user)
        {
        if(!empty($user->getRoleNames()))
        {
        foreach($user->getRoleNames() as $v)
        {
        if($v=='Supervisor')
        {
          $new_notification=[];
          $new_notification['type'] = 'Notification';
          $new_notification['subject'] ='Dossier link Updated';
          $new_notification['from_user'] = 'System Reminder';
          $new_notification['data'] = 'Applicant  has updated new dossier link';
          $new_notification['related_document'] = null;
          $new_notification['related_id'] = $request->application_id;
          $new_notification['alert_level'] = null;
          $new_notification['remark'] = null;
        // ::send($users, new ($invoice));


        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
        event(new ApplicationReceiptionEvent($user->id, ' New application  Dossier Link Updated' ));




        }
        }
        }
        }

}

public function decleration_save(Request $request)
{
    $decleration =  new applications;

          try
           {
            $decleration = DB::table('declarations')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'date' => $request->date,
                'position' => $request->position,
                'user_id'=> $request->user_id,
                'qualification'=> $request->qualification,
                 'name'=>$request->name,

                ]
              );

        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();


        $main_task = $this->get_main_task_id($applications[0]->id,'Application');
        $end_time = date('Y-m-d H:i:s', strtotime('-3'));
        $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
        $task_category = 'Applying';
        $task_activity_title = 'Declaration note saved successfully';
        $content_details = 'Declaration note saved successfully ';
        $route_link = '';
        $activity_status = 'Locked';
        $uploaded_document_id = null;
        
        MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
        

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",7";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);



$rendered_html_data = $this->rendered_html_data($request->application_id);
$pdf = PDF::loadHTML($rendered_html_data);
$pdf->setPaper ('A4', 'portrait');
// <--- load your view into theDOM wrapper;

$time=time();
$path = public_path('storage/DeclarationNote/');
// <--- folder to store the pdf documents into the server;
$fileName = "DeclarationNote"."-".$request->application_id.$time."-".'.pdf' ; // <--giving the random filename,
$pdf->save($path.$fileName);

$generated_pdf_link = Storage::url('public/DeclarationNote/'.$fileName);



$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '3';
$documents->ref_num = $request->application_id;
$documents->description = 'Declaration';
$documents->save();


$update_application_number = DB::table('declarations')
->where('application_id', $request->application_id)
->update(
  [
  'document_attachment_id' =>$documents->id,
  ]
);





return response()->json(['Message'=>true,'application_id'=> $request->application_id,'Download_Link'=>$generated_pdf_link ] );

   }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}


public function rendered_html_data($application_id)
{

   
  
  $path_header = "images/nmfa_header.png";
  $path_footer = "images/nmfa_footer.png";

  $decleration =  new applications;
  
$issue_declarations  =   declerations::where('application_id',$application_id)->get();


$Product_details= DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
  'medicinal_products.id as _id',
 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name',
 'route_administrations.name as route_name',
 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id',
 'route_administrations.id as route_id',
 'dosage_forms.id as dosage_id','dosage_forms.name as dname', 
 'medicines.*',
 'medicinal_products.*',
 'route_administrations.*',
 'dosage_forms.*',
  )
->where('medicinal_products.application_id',$application_id)
->get();


$company_supplier_per_applicant = company_suppliers::where('application_id',$application_id)->get();


// dd($company_supplier_per_applicant);
//$applications = applications::create($request->all());
$issue_declarations =   declerations::where('application_id',$application_id)->get();
 
foreach($issue_declarations   as $checked_issue_declarations) {

  $namee =  $checked_issue_declarations['name'];
  $qualification =  $checked_issue_declarations['qualitification'];
  $position = $checked_issue_declarations['position'];
  $date = $checked_issue_declarations['date'];



}

$rendered_template = "
  <!DOCTYPE html>
  <html lang='en-US'>
      <head>
     </head>

     <!-- Main content -->
            <div class='invoice p-3 mb-3'>
              <!-- title row -->
         
                <div class='col-12'>
                  <h4>
<img src='".$path_header."'  alt='image' height='100' width='690'/>
                  </h4>
                </div>
   
              <p class='decleration'> 
              I, the undersigned certify that all the information in this form and all accompanying documentation submitted to Eritrea 
              for the registration of (".$Product_details[0]->product_trade_namme.", ".$Product_details[0]->medicine_product_name." and ".$Product_details[0]->dname.") manufactured at 
      
          (".$company_supplier_per_applicant[0]->trade_name." , ".$company_supplier_per_applicant[0]->address_line_one." and  ".$company_supplier_per_applicant[0]->address_line_two.")
          
          is true and correct. I further certify that I have examined the following statements and I attest to their correctness:- 
              </P>
              
              <p class='decleration'>
              1.	The current edition of the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products or equivalent guideline is applied in full in all premises involved in the manufacture of this medicine. 
              <br/>
              2.	The formulation per dosage form correlates with the master formula and with the batch manufacturing record. 
              <br/>
              3.	The manufacturing procedure is exactly as specified in the master formula and batch manufacturing record.
              <br/>
              4.	Each batch of all starting materials is either tested or certified (in accompanying certificate of analysis for that batch) against the full specifications in the accompanying documentation and must comply fully with those specifications before it is released for manufacturing purposes. 
              <br/>
              5.	All batches of the active pharmaceutical ingredient(s) are obtained from the source(s) specified in the accompanying documentation. 
              <br/>
              6.	No batch of active pharmaceutical ingredient(s) will be used unless a copy of the batch certificate established by the manufacturer is available. 
              <br/>
              7.	Each batch of the container/closure system is tested or certified against the full specifications in the accompanying documentation and complies fully with those specifications before released for the manufacturing purposes. 
              <br/>
              8.	Each batch of the finished product is either tested, or certified (in an accompanying certificate of analysis for that batch), against the full specifications in the accompanying documentation and complies fully with release specifications before released for sale. 
              <br/>
              9.	The person releasing the product is an authorized person as defined by the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products
              <br/>
              10.	The procedures for control of the finished product have been validated. The assay method has been validated for accuracy, precision, specificity and linearity. 
              <br/>
              11.	All the documentation referred to in this application is available for review during GMP inspection. 
              <br/>
              12.	Clinical trials (where applicable) were conducted in accordance with ICH, WHO or equivalent guidelines for Good Clinical Practice, 
              <br/>
              I also agree that: 
              <br/>
              13.	As a holder of marketing authorization/registration of the product I will adhere to Eritrean National Pharmacovigilance Policy requirements for handling adverse reactions. 
              <br/>
              14.	As holder of registration I will adhere to Eritrean requirements for handling batch recalls of the products.
              <br/>
                          </p>
   <div class='col-12 col-sm-6'>
                     <p> Name: $namee </p>
                     <p> Qualification: $qualification </p>
                      <p> Position:$position </p>
                      <p>Date: $date</p>
                  </div>
                      </div>
  <p>
<img src='".$path_footer."'  height='100' width='690'/>       
</p>        
              </div>

         

  </body>
</html>
  ";

  return $rendered_template;
}


public function edit(application $application)
{
  // return  ($task->id).($task->description);
   //$tasks = Task::find($task->id);

  //dd($Todo_update_id);
  //return $Todo_update_id;
 // return view('todos.edit');
 return view('application_reception.update',compact('task'));

}

           public function application_reception_complete_wizard_control(Request $request)
             {

    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    $route_administrations = route_administrations::all()->sortBy('name');
    $agents = agents::all()->sortBy('trade_name');
    $company_suppliers = company_suppliers::all()->sortBy('trade_name');

    $product_detailss =  product_details::all()->sortBy('product_name');

    $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

    $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
    $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

     $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
     $agents_template = agents_template::all()->sortBy('trade_name');


     $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


     $applications_status = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->select('countries.*','countries.id as countryid','applications.*',
             'invoices.*','contacts.*', 'invoices.amount',
             'medicines.product_name',
             'medicinal_products.product_trade_name',
             'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
             'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
             'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
             'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
             'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
             'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
             'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
             'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
             'contacts.last_name as cont_last_name','contacts.id as cont_id',
             'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
             'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email',
             'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
             'contacts.position as cont_position','contacts.city as cont_city'
              )
            ->where('applications.application_id',$request->application_id)
            ->orderBy('invoices.invoice_number','ASC')
            ->get();

            if(empty($applications_status[0]->cont_id) )
            {
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
                        ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                        ->get();

            }
            else if(!empty($applications_status[0]->cont_id) )
            {

           $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
               //->Orwhere('id',68)
               ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
               ->get();


            }



$agent_contact_info = DB::table('applications')
                      ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                      ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                      ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                      ->select('agents.*','contacts.*','agents.trade_name as business_name','agents.state as agent_state','agents.address_line_one as agent_address_line_one','agents.address_line_two as  agent_address_line_two','agents.city as agent_city','agents.postal_code as agent_postal_code','agents.telephone as agent_telephone','agents.webiste_url as agent_webiste_url','agents.email as agent_email','agents.country_code as agent_country_code','contacts.id as cont_id','agents.id as agent_id','contacts.first_name as cont_first','contacts.middle_name as cont_middle','contacts.last_name as cont_last','contacts.position as cont_position','contacts.city as cont_city','contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two','contacts.postal_code as cont_postal_code','contacts.telephone as cont_tele',/*'contacts.webiste_url as cont_url',*/'contacts.email as cont_email')
                       ->where('contact_type','Agent')
                       ->where('agents.application_id',$request->application_id)
                       ->get();



 $agent_contact_County_info = DB::table('countries')->where('id','68')
                         ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                         ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
  'medicinal_products.id as _id',
 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name',
 'route_administrations.name as route_name',
 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id',
 'route_administrations.id as route_id',
 'dosage_forms.id as dosage_id',
 'medicines.*',
 'medicinal_products.*',
 'route_administrations.*',
 'dosage_forms.*',
  )
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$request->application_id)
                            ->select('applications.*','medicines.*')
                            ->get();


$product_manufacturers_info = DB::table('manufacturers')
                                     ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                     ->where('manufacturers.application_id',$request->application_id)
                                     ->select('manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                     'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                     ->get();

 $api_manufacturers_info = DB::table('api_manufacturers')
                               ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                              ->where('api_manufacturers.application_id',$request->application_id)
                              ->select(   'api_manufacturers.*','countries.*',
                                          'api_manufacturers.manufacturer_name as api_name',
                                          'api_manufacturers.id as api_id')
                              ->get();

 @$decleration_info = DB::table('declarations')
 ->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
             ->where('declarations.application_id',$request->application_id)
                             ->select('declarations.*','documents.*','declarations.name as decname')
                             ->get();


  @$docu_id = DB::table('documents')
   ->where('documents.id',$decleration_info[0]->document_attachment_id)
   ->select('documents.*')
   ->get();



         return view('application_reception.update',[

            'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
            'decleration_info'=> $decleration_info,
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_detailss,
            'application_check_wizard'=>$application_check_wizard,
            'explode' =>  $explode,
            'company_supplier_per_applicant'=> $company_supplier_per_applicant,
            'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
            'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
            'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
            'applications_status'  => $applications_status,
            'country_contact_info' => $country_contact_info,
            'agent_contact_County_info'=>$agent_contact_County_info,
            'agent_contact_info' => $agent_contact_info,
            'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
            'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
            'product_details_general'=> $product_details,
            'product_composition_info'=>$product_composition_info,
            'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
            'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
            'product_manufacturers_info'=>$product_manufacturers_info,
            'company_suppliers_template' =>$company_suppliers_template,
            'agents_template'=>$agents_template ,
            'api_manufacturers_info' => $api_manufacturers_info,
            'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
            'docu_id' =>  @$docu_id,
        ]);


}


public function view_completed_application(Request $request)
{

    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    $route_administrations = route_administrations::all()->sortBy('name');
    $agents = agents::all()->sortBy('trade_name');
    $company_suppliers = company_suppliers::all()->sortBy('trade_name');

    $product_detailss =  product_details::all()->sortBy('product_name');

    $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

    $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
    $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

     $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
     $agents_template = agents_template::all()->sortBy('trade_name');


     $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


     $applications_status = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->select('countries.*','countries.id as countryid','applications.*',
             'invoices.*','contacts.*', 'invoices.amount',
             'medicines.product_name',
             'medicinal_products.product_trade_name',
             'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
             'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
             'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
             'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
             'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
             'company_suppliers.postal_code as Supplier_postal_code',
             'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
             'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
             'contacts.last_name as cont_last_name','contacts.id as cont_id',
             'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
             'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email',
             'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
             'contacts.position as cont_position','contacts.city as cont_city'
              )
            ->where('applications.application_id',$request->application_id)
            ->orderBy('invoices.invoice_number','ASC')
            ->get();

            if(empty($applications_status[0]->cont_id) )
            {
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
                        ->Orwhere('id',68)
                    ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                    ->get();

            }
            else if(!empty($applications_status[0]->cont_id) )
            {

           $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
               //->Orwhere('id',68)
               ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
               ->get();


            }



$agent_contact_info = DB::table('applications')
                      ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                      ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                      ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                      ->select('agents.*','contacts.*','agents.trade_name as business_name','agents.state as agent_state','agents.address_line_one as agent_address_line_one','agents.address_line_two as  agent_address_line_two','agents.city as agent_city','agents.postal_code as agent_postal_code','agents.telephone as agent_telephone','agents.webiste_url as agent_webiste_url','agents.email as agent_email','agents.country_code as agent_country_code','contacts.id as cont_id','agents.id as agent_id','contacts.first_name as cont_first','contacts.middle_name as cont_middle','contacts.last_name as cont_last','contacts.position as cont_position','contacts.city as cont_city','contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two','contacts.postal_code as cont_postal_code','contacts.telephone as cont_tele',/*'contacts.webiste_url as cont_url',*/'contacts.email as cont_email')
                       ->where('contact_type','Agent')
                       ->where('agents.application_id',$request->application_id)
                       ->get();



 $agent_contact_County_info = DB::table('countries')->where('id','68')
                         ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                         ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
  'medicinal_products.id as _id',
 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name',
 'route_administrations.name as route_name',
 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id',
 'route_administrations.id as route_id',
 'dosage_forms.id as dosage_id',
 'medicines.*',
 'medicinal_products.*',
 'route_administrations.*',
 'dosage_forms.*',
  )
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$request->application_id)
                            ->select('applications.*','medicines.*')
                            ->get();


$product_manufacturers_info = DB::table('manufacturers')
                                     ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                     ->where('manufacturers.application_id',$request->application_id)
                                     ->select('manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                     'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                     ->get();

 $api_manufacturers_info = DB::table('api_manufacturers')
                               ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                              ->where('api_manufacturers.application_id',$request->application_id)
                              ->select(   'api_manufacturers.*','countries.*',
                                          'api_manufacturers.manufacturer_name as api_name',
                                          'api_manufacturers.id as api_id')
                              ->get();



                              $decleration_info = DB::table('declarations')
                              ->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
                              ->where('declarations.application_id',$request->application_id)
                              ->select('declarations.*','documents.*','declarations.name as decname')
                              ->get();
                             

                             
                               @$docu_id = DB::table('documents')
                                ->where('documents.id',$decleration_info[0]->document_attachment_id)
                                ->select('documents.*')
                                ->get();




         return view('application_reception.view_completed_application',[

            'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
            'decleration_info'=> $decleration_info,
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_detailss,
            'application_check_wizard'=>$application_check_wizard,
            'explode' =>  $explode,
            'company_supplier_per_applicant'=> $company_supplier_per_applicant,
            'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
            'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
            'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
            'applications_status'  => $applications_status,
            'country_contact_info' => $country_contact_info,
            'agent_contact_County_info'=>$agent_contact_County_info,
            'agent_contact_info' => $agent_contact_info,
            'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
            'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
            'product_details_general'=> $product_details,
            'product_composition_info'=>$product_composition_info,
            'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
            'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
            'product_manufacturers_info'=>$product_manufacturers_info,
            'company_suppliers_template' =>$company_suppliers_template,
            'agents_template'=>$agents_template ,
            'api_manufacturers_info' => $api_manufacturers_info,
            'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
            'docu_id' =>    $docu_id,
        ]);


}







public function dossier_sample_status_edit(Request $request)
{

    //dd( $request->application_id );
    $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
    $application_number = $application_check_wizard[0]->application_number;
    foreach($application_check_wizard as $app)
    {
  $explode = explode(',', $app->hold_progress_wizard);

    }

 return view('dossier_status_sample.doosier_status_edit_applicant_info',
   ['application_id'=>$request->application_id,
   'application_check_wizard'=> $application_check_wizard,
   'application_number'=> $application_number,

   ]);



}



public function dossier_control_wizard(Request $request)
 {


  //dd( $request->application_id );
  $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
  $application_number = $application_check_wizard[0]->application_number;
  foreach($application_check_wizard as $app)
  {
$explode = explode(',', $app->hold_progress_wizard);

  }



  return view('dossier_status_sample.dossier_status_check',
  [

    'application_id'=>$request->application_id,
    'application_number'=>$application_number,
  ]);


}



public function get_checklist_value(Request $request)
{


  $applications = DB::table('applications')
  ->where('application_id', '=',$request->application_id )
  ->select('applications.*')
  ->get();

foreach($applications as $application)
{


if(!empty($application->medical_product_id) )
{
$checked="checked";
}

$rendered_template =  "<div class='card-body'>
<!-- Minimal style -->
<div class='row'>
  <div class='col-sm-6'>
    <!-- checkbox -->
    <div class='form-group clearfix'>
<div class='icheck-primary d-inline'>
        <input type='checkbox' id='product_name' >
        <label for='checkboxPrimary3'>
          Product Information
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='company_supplier_name' >
        <label for='checkboxPrimary3'>
          Supplier Information
        </label>
      </div>
   </div>

      <div class='form-group clearfix'>
      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Manufacturer Infromation
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Agent Infomation
        </label>
      </div>
     </div>
<div class='form-group clearfix'>
      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Product Composition
        </label>
      </div>


     </div>
  </div>


<br><br>


    <div class='col-sm-6'>
    <!-- checkbox -->
    <div class='form-group clearfix'>



      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Sample Infromation
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Api product Information
        </label>
      </div>



    </div>
    <div class='form-group clearfix'>



      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Payment Information
        </label>
      </div>

<div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Dossier Information
        </label>
      </div>



    </div>

  </div>

</div>
</div>
</div>";


return response()->json(
  [

  'application_id'=>$request->application_id,
  'rendered_html' => $rendered_template,
  'Invoice_Created' => 'true',

  ]);



}





}






public function validate_email(Request $request  )
{
    $this->Email="";
    $company_suppliers = new company_suppliers;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from  company_suppliers where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle'></i>  Error Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}



  //  return view('user.index', ['users' => $users]);  validate_email_customer_contact
}



public function validate_email_customer_contact(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from  contacts  where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
  return response()->json(['success'=>'Good To go']);
// return response()->json(['error'=>"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> Error Email Already Registered</span>"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}



public function manufactrer_email(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from manufacturers  where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle'> </i> Error Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}


public function api_manufactrer_email(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from    api_manufacturers where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle'></i> Error Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}










public function validate_url(Request $request  )
{

 $url = $request->url;

 if (!filter_var($url, FILTER_VALIDATE_URL) === false)
{

  return response()->json(['Message'=>'1','success'=>"<i class='fa fa-check-circle'> $url is  a valid URL "]);

}
else if (!filter_var($url, FILTER_VALIDATE_URL) === true)
{

return response()->json(['Message'=>'0','error'=>"<i class='fa fa-exclamation-triangle'> $url is not  a valid URL"]);

}


}








public function validate_local_agent_email(Request $request  )
{
    $this->Email="";
    $company_suppliers = new agents;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from  agents where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle'></i>  Error Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}



  //  return view('user.index', ['users' => $users]);  validate_email_customer_contact
}










public function Request_for_all_sample()
{
    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    //$route_administrations = route_administrations::all();
    $route_administrations = route_administrations::all()->sortBy('name');

    $agents = agents::all()->sortBy('trade_name');

    $agents_template = agents_template::all()->sortBy('trade_name');

    $company_suppliers = company_suppliers::all()->sortBy('trade_name');

    $company_suppliers_template = DB::select('select * from  company_supplier_template  where (is_Approved_By_NMFA = 1 )  order by trade_name  ASC');


    $product_details = DB::select('select * from  medicines where (is_enlm =1 and is_approved=1)  order by product_name ASC');
   // $product_details =  product_details::all()->sortBy('product_name');


         return view('app_recep',[
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'company_suppliers_template' =>  $company_suppliers_template,
            'agents'=>$agents,
            'agents_template'=>$agents_template,
            'medicines'=>$product_details,
        ]);
      }

}
