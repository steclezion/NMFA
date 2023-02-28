<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
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
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


//     public function Generate_Year_Report(Request $request)
//     {
//         //dd($request->all());

//        $return_data_table='' ; $return_data_card='';

//        // dd($request->all());
//         $countries = Country::all()->sortBy('country_name');;
//         $fast_track_applications =  fast_track_application::all()->sortBy('name');;
//         $dosage_forms  = DosageForms::all()->sortBy('name');;
//         $apis  = apis::all()->sortBy('api_name');;
//         $route_administrations = route_administrations::all()->sortBy('name');
//         $agents = agents::all()->sortBy('trade_name');
//         $company_suppliers = company_suppliers::all()->sortBy('trade_name');
//         $product_details =  product_details::all()->sortBy('product_name');
               
//         if($request->application_Stat == 'all') { 
            
//             $applications = DB::table('applications')
//            // ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
//             ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
//             ->leftjoin('users', 'applications.user_id','=', 'users.id')
//             ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
//             ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
//             ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
//             ->select('contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name','applications.*','users.*', 'medicinal_products.product_trade_name', 
//             'company_suppliers.trade_name','invoices.invoice_number',
//             'invoices.remark','invoices.amount')
//              ->DISTINCT('applications.application_id')
//              ->where('contacts.contact_type','=','Supplier')
//              ->whereNotNull('medicinal_products.product_trade_name')
//              ->where('applications.application_status','=','completed')
//              ->orwhere('applications.application_status','=','processing')
//              ->whereYear('applications.created_at' , '=',$request->From_Date_Year)
//              ->whereYear('applications.created_at' ,'=',$request->To_Date_Year)
//              ->get();
        
//         }
//             else
//             {
            
            
//                 $applications = DB::table('applications')
//                 //->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
//                 ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
//                 ->leftjoin('users', 'applications.user_id','=', 'users.id')
//                 ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
//                 ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
//                 ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
//                 ->select('contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name','applications.*','users.*', 'medicinal_products.product_trade_name', 
//                  'company_suppliers.trade_name','invoices.invoice_number',
//                 'invoices.remark','invoices.amount')
//                 //->DISTINCT('applications.application_id')
//                  ->where('contacts.contact_type','=','Supplier')
//                  ->whereNotNull('medicinal_products.product_trade_name')
//                  ->where('applications.application_status','=',$request->application_Stat)
//                  ->whereYear('applications.created_at' , '=',$request->From_Date_Year)
//                  ->ORwhereYear('applications.created_at' ,'=',$request->To_Date_Year)
//                  ->get();}

// $i=1;
    
//          foreach($applications as $application)
                
//          {

//             $return_data_table .= "<tr><td>".$i++."</td>";
//             $return_data_table .= "<td>".$application->application_status."</td>";
//             $return_data_table .= "<td>".$application->product_trade_name."</td>";
//             $return_data_table .= "<td>".$application->trade_name."</td>";
//             $return_data_table .= "<td>".$application->cfirst_name." ".$application->cmiddle_name." ".$application->clast_name."</td></tr>";
           
//             // $return_data_table  .= "<td>".$request->From_Date_Year."</td>";
//             // $return_data_table  .= "<td>".$request->To_Date_Year."</td></tr>";
        
//         }
       

//          $return_data_card.="<div class='card-body' style='display:block; background-color:orange' id='rendered_year_response'>
//          <h2> Applications received By Year-Range From  ".$request->From_Date_Year."  To  ".$request->To_Date_Year." </h2>
//          <table id='example1' class='table table-bordered table-striped' >
//            <thead><tr>
//              <th>ID</th>
//              <!--<th>ApplicationID</th>-->
//              <th>Application Status</th>
//              <th>Product Name</th>
//              <th>Supplier Name</th>
//              <th>Supplier contact Name</th>
             
//              <!--<th>Action</th>-->
//            </tr>
//            </thead>
//            <tbody>
//            ".$return_data_table."
//            </tbody>
//            <tfoot>
         
           
//            </tfoot>
//          </table>
//        </div>
//        <!-- /.card-body -->";

// return response()->json(['rendered_card'=>$return_data_card]);


//     }



//Generate_Year_Report

    public function Generate_Year_Report(Request $request)
    {
        //dd($request->all());

       $return_data_table='' ; $return_data_card='';

       // dd($request->all());
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        $route_administrations = route_administrations::all()->sortBy('name');
        $agents = agents::all()->sortBy('trade_name');
        $company_suppliers = company_suppliers::all()->sortBy('trade_name');
        $product_details =  product_details::all()->sortBy('product_name');
               
        if($request->application_Stat == 'all') { 
            
            $applications = DB::table('applications')
            ->select('application_status',DB::raw('year(created_at) as Year '),DB::raw('count(application_status) as Appstat'))
            ->whereYear('applications.created_at' , '>=',$request->From_Date_Year)
             ->orwhereYear('applications.created_at' ,'<=',$request->To_Date_Year)
              ->groupBy('application_status',DB::raw('year(created_at)' ) )
             //->having()
             ->get();
        }
            else
            {
            
            
                $applications = DB::table('applications')
                ->select('application_status',DB::raw('year(created_at) as Year'),DB::raw('count(application_status) as Appstat'))
                 ->where('applications.application_status','=',$request->application_Stat)
                 ->whereYear('applications.created_at' , '=',$request->From_Date_Year)
                 ->whereYear('applications.created_at' ,'=',$request->To_Date_Year)
                 ->groupBy('application_status',DB::raw('year(created_at)' ) )
                    //->having()
                    ->get();

                 }

$i=1;
    
         foreach($applications as $application)
                
         {

            $return_data_table .= "<tr><td>".$i++."</td>";
            $return_data_table .= "<td>".$application->application_status."</td>";
            $return_data_table .= "<td>".$application->Year."</td>";
            $return_data_table .= "<td>".$application->Appstat."</td>";
         
        }
       

         $return_data_card.="<div class='card-body' style='display:block; background-color:orange' id='rendered_year_response'>
         <h2> Applications received By Year-Range From  ".$request->From_Date_Year."  To  ".$request->To_Date_Year." </h2>
         <table id='example1' class='table table-bordered table-striped' >
           <thead><tr>
             <th>ID</th>
             <!--<th>ApplicationID</th>-->
             <th>Application Status</th>
             <th>Year</th>
             <th>Count Number of Applications</th>
             
             
             <!--<th>Action</th>-->
           </tr>
           </thead>
           <tbody>
           ".$return_data_table."
           </tbody>
           <tfoot>
         
           
           </tfoot>
         </table>
       </div>
       <!-- /.card-body -->";

return response()->json(['rendered_card'=>$return_data_card]);


    }



    //Generate_Applicant_Report

    public function Generate_Applicant_Report(Request $request)
    {
        //dd($request->all());

       $return_data_table='' ; $return_data_card='';

   
            if($request->user_id==0)
            {
            $applications = DB::table('applicationss')
            ->join('users', 'applications.user_id','=', 'users.id')
             ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->select('application_status',DB::raw('date(created_at) as Date '),DB::raw('count(application_id) as Appcount'))
            ->whereDate('applications.created_at' , '>=',$request->From_Date_Year)
            ->orwhereDate('applications.created_at' ,'<=',$request->To_Date_Year)
            ->groupBy('application_status',DB::raw('year(created_at)' ) )
             //->having()
             ->get();
            }
            

$i=1;
    
         foreach($applications as $application)
                
         {

            $return_data_table .= "<tr><td>".$i++."</td>";
            $return_data_table .= "<td>".$application->application_status."</td>";
            $return_data_table .= "<td>".$application->Year."</td>";
            $return_data_table .= "<td>".$application->Appstat."</td>";
         
        }
       

         $return_data_card.="<div class='card-body' style='display:block; background-color:orange' id='rendered_year_response'>
         <h2> Applications received By Year-Range From  ".$request->From_Date_Year."  To  ".$request->To_Date_Year." </h2>
         <table id='example1' class='table table-bordered table-striped' >
           <thead><tr>
             <th>ID</th>
             <!--<th>ApplicationID</th>-->
             <th>Application Status</th>
             <th>Year</th>
             <th>Count Number of Applications</th>
             
             
             <!--<th>Action</th>-->
           </tr>
           </thead>
           <tbody>
           ".$return_data_table."
           </tbody>
           <tfoot>
         
           
           </tfoot>
         </table>
       </div>
       <!-- /.card-body -->";

return response()->json(['rendered_card'=>$return_data_card]);


    }







































    public function Generate_Product_Report(Request $request)
    {
        //dd($request->all());

       $return_data_table='' ; $return_data_card='';

       // dd($request->all());
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        $route_administrations = route_administrations::all()->sortBy('name');
        $agents = agents::all()->sortBy('trade_name');
        $company_suppliers = company_suppliers::all()->sortBy('trade_name');
        $product_details =  product_details::all()->sortBy('product_name');
               

         
        if($request->application_Stat == 'all') { 
            
            $applications = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->leftjoin('users', 'applications.user_id','=', 'users.id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->select('contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name','applications.*','users.*', 'medicinal_products.product_trade_name', 
            'manufacturers.name','company_suppliers.trade_name','invoices.invoice_number','medicinal_products.*',
            'invoices.remark','invoices.amount')
             ->where('contacts.contact_type','=','Supplier')
             ->whereNotNull('medicinal_products.product_trade_name')
             
             ->where('applications.application_status','=','completed')
             ->orwhere('applications.application_status','=','processing')
             ->where('medicinal_products.medicine_id' , '=' ,$request->medicine_id)
             ->get();
        
        }
            else
            {
            
            
                $applications = DB::table('applications')
                ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
                ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                ->leftjoin('users', 'applications.user_id','=', 'users.id')
                ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
                ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
                ->select('contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name','applications.*','users.*', 'medicinal_products.product_trade_name', 
                'manufacturers.name','company_suppliers.trade_name','invoices.invoice_number',
                'invoices.remark','invoices.amount')
                 ->where('contacts.contact_type','=','Supplier')
                 ->whereNotNull('medicinal_products.product_trade_name')
                 ->where('applications.application_status','=',$request->application_Stat)
                 ->where('medicinal_products.medicine_id' , '=' ,$request->medicine_id)
                 ->get();}

$i=1;
    
         foreach($applications as $application)
                
         {

            $return_data_table .= "<tr><td>".$i++."</td>";
            $return_data_table .= "<td>".$application->product_trade_name."</td>";
            $return_data_table .= "<td>".$application->application_status."</td>";
           
            $return_data_table .= "<td>".$application->trade_name."</td>";
            $return_data_table .= "<td>".$application->cfirst_name." ".$application->cmiddle_name." ".$application->clast_name."</td></tr>";
           
            // $return_data_table  .= "<td>".$request->From_Date_Year."</td>";
            // $return_data_table  .= "<td>".$request->To_Date_Year."</td></tr>";
        
        }
       

         $return_data_card.="<div class='card-body' style='display:block; background-color: skyblue' id='rendered_year_response'>
         <h2> Applications received By Product Name  </h2>
         <table id='example1' class='table table-bordered table-striped' >
           <thead><tr>
             <th>ID</th>
             <!--<th>ApplicationID</th>-->
             <th>Product Name</th>
             <th>Application Status</th>
             
             <th>Supplier Name</th>
             <th>Supplier contact Name</th>
             
             <!--<th>Action</th>-->
           </tr>
           </thead>
           <tbody>
           ".$return_data_table."
           </tbody>
           <tfoot>
         
           
           </tfoot>
         </table>
       </div>
       <!-- /.card-body -->";
       
return response()->json(['rendered_card'=>$return_data_card]);


    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
