@extends('layouts.app')

@section('content')  

<!-- check_list_js.blade.php -->


<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >
  <!-- CodeMirror -->
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/codemirror.css') }} ">
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/theme/monokai.css') }}">
  <!-- SimpleMDE -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
 
<!--   
plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href={{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}>



<link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
    <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}" >

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">



@include('layouts.check_list_js')




<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<!-- <script src="{{ asset('dist/js/demo.js')}}" ></script> -->
  



<meta name="csrf-token" content="{{ csrf_token() }}">



   
   <div class="row">
          <div class="col-12">
            <div class="card" id="print_checklist">
              <div class="card-header">
                <h3 class="card-title">Checklist for Receiving Registration Applications  </h3><br><br>
                <h3 class="pull-left"> Registration Application Reciept/Verification Check List </h3>
                <div class="card">
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-default" >
          <div class="card-header">
          <h3 class="card-title">Section 1: Product Details</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" value= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <tr>
                    <th>Lists</th>
                      <th> </th>
                      <!-- <th style="width: 40px">check Status</th> -->
                    </tr>
                  </thead>
                  <tbody>
                  <!-- <tr>
            <td>Application ID</td>
                <td>
                @foreach($check_list as $application_id )
               {{  $application_id->application_id }}
               @break
               @endforeach
                </td>
                <td><span class="badge bg-success"><i class="fa fa-check"></i></span></td>
                   </tr> -->
                    <tr>
                    <td>Generic Name</td>
                      <td >{{ $font_product_name='' }}
                      @foreach( $check_list as $product_name )
                     {{  $product_name->product_name }}
                       @break
                      @endforeach
                      </td>
                      <!-- <td> @if($product_name->product_name == '') 
                     $font_product_name='<span class="badge bg-danger"><i class="fa fa-minus"></i></span>'
                      @else 
                     <span class="badge bg-success"><i class="fa fa-check"></i></span> 
                     @endif </td> -->
                    </tr>
                    <tr>
                      <td>Brand Name</td>
                      <td>
                      @foreach($check_list as $product_trade_name )
                      {{  $product_trade_name->product_trade_name     }}
                      @break
                      @endforeach
                      </td>
                      </tr>
                      <tr>
                      <td>Strength</td>
                      <td>
                      @foreach( $check_list as $product_name )
                     <!-- {{  $product_name->product_name }} -->
                      
                   
                      <input name="strength"  class="form-control" value="{{$product_name->product_name }}" type="" />
                 @break
                      @endforeach
                      </td>
                     
                    </tr>

                     
                      <tr>
                      <td {{ $i=1 }}>Pharmaceutical form </td>
                      <td>
                      @foreach ($dosage_forms as $dosage_formss)
                      {{  $dosage_formss->name   }}<br>
                      @endforeach
                      </td>
                     
                      </tr>



                           <tr>
                      <td {{ $i=1 }}>Manufacturer/Market Authorization Holder   </td>
                      <td>
                      @foreach ($applicant_contact_info as $supplier_name)
                     {{  $supplier_name->first_name." ".$supplier_name->middle_name." ".$supplier_name->last_name   }}<br>
                      @endforeach
                      </td>
                      
                      </tr>


                      <!-- <tr>
                      <td> Dossier and Sample Status</td>
                      <td>
                      @foreach($check_list as $dossier_url)
                      {{  $dossier_url->dossier_url }}
                      @break
                      @endforeach
                      </td>
                      <td> @if( $dossier_url->dossier_url == '') 
                     $font_product_name='<span class="badge bg-danger"><i class="fa fa-minus"></i></span>'
                      @else <span class="badge bg-success"><i class="fa fa-check"></i></span>  @endif </td>
                      </tr> -->




                      <tr>
                      <td>Type of Registration </td>
                      <td>
                      @php
                      
                      foreach($check_list as $track_mode)
                    {

                    if($track_mode->application_type== 1) 
                    {
                    $track_mode->fast_track_details = 'Standard Mode';
                    }
                    
                echo  $track_mode->fast_track_details;
                      break;
                      }

                      @endphp
                      </td>

                      <!-- <td>
                       @if( $track_mode->fast_track_details == '') 
                       <span class="badge bg-danger"><i class="fa fa-minus"></i></span>
                      @else 
                      <!-- <span class="badge bg-success"><i class="fa fa-check"></i></span> 
                      @endif </td> --> 
                      </tr>
                   
                  </tbody>
                </table>
              </div>
            
            </div>
               </div>
              <!-- /.card-header -->
              <style>
                  th,td {padding: 15px;text-align: left;border: 0.2px solid grey;border-bottom: 1px solid #ddd;}
                  tr { border: 1px dashed black;}
               </style>



















    <!-- Section 2 General requirements for fast-track registration-->
          <div class="row">
          <div class="col-12">
            
                <div class="card">
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-default">
          <div class="card-header">
          <h3 class="card-title">Section 2: General Requirements</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <tr>
                    <th>Lists</th>
                      <th></th>
                      <th style="width: 40px">Yes</th>
                      <th style="width: 40px">No</th>
                      <th style="width: 40px">Not Applicable </th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                     <td>Presence of application letter </td>
                     <td ></td>
                     <td ><input type="checkbox"  style="width: 20px"  id="Presence_of_application_letter_yes" name="Presence_of_application_letter" class="form-control"  width="40"/></td>
                     <td ><input type="checkbox"  style="width: 20px"  id="Presence_of_application_letter_no" name="Presence_of_application_letter" class="form-control"  width="40"/></td>
                     <td> </td>
                   </tr>
                    <tr>
                      <td>Presence of manufacturer and manufacturing sites details </td>    
                      <td > </td>
                     
                      <td ><input type="checkbox"  size="30" hieght="40"  id="manufacturer_information_yes" name="manufacturer_information" class="form-control"  width="40"/></td>
                   
                      <td><input type="checkbox"  size="30" hieght="40"     id="manufacturer_information_no" name="manufacturer_information" class="form-control"  width="40"/></td>
                     
                    </tr>


                          <tr>
       <td>Presence of Local Authorized Agent (LAA) information  </td>
       <td > </td>
                      
                      <td ><input type="checkbox"  size="30" hieght="40"   id="local_agent_yes" name="local_agent" class="form-control"  width="40"/></td>
                   
                     
                      <td ><input type="checkbox"  size="30" hieght="40"    id="local_agent_no" name="local_agent" class="form-control"  /></td>
                      <td ><input type="checkbox"  style="width: 25px"   id="local_agent_not_applicable" name="local_agent" class="form-control"  /></td>
                      </tr>

            <tr>
    <td>Presence of the product in the Eritrean List of medicines (ENLM)   </td>
    <td ></td>
@foreach($product_enlm_list as $list)     @endforeach
               
                      <td ><input type="checkbox"  size="30" hieght="40"  id="enlm_yes" name="enlm" class="form-control"  width="40"/></td>
                
                    <td ><input type="checkbox"  size="30" hieght="40"  id="enlm_no" name="enlm" class="form-control"  width="40"/></td>
                    <td> </td>

                   </tr>


                          <tr>
                          <td>Presence of the submitted dossier in CTD format as per the requested format </td>
    <td ></td>


                
                      <td ><input type="checkbox"  size="30" hieght="40"  id="dossier_ctd_yes" name="dossier_ctd" class="form-control"  width="40"/></td>
                 
                    <td ><input type="checkbox"  size="30" hieght="40"  id="dossier_ctd_no" name="dossier_ctd" class="form-control"  width="40"/></td>
                    <td> </td>

                   </tr>



                      <tr>
                      <td rowspan='5'> Availability of the module of the CTD </td>
                      <td style="text-align: left;">Module I </td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_one_yes" name="module_one" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_one_no" name="module_one" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <tr>
                      <td >Module II </td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_two_yes" name="module_two" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_two_no" name="module_two" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <tr>
                      <td >Module III</td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_three_yes" name="module_three" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_three_no" name="module_three" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <td >Module IV</td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_four_yes"  name="module_four" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_four_no"   name="module_four" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <td >Module V</td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_five_yes" name="module_five" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="module_five_no"  name="module_five" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>

            <tr>
            <td colspan='5'> Remark :
            <textarea    id="summernote"  name="remark" class="form-control"  auto/>
            </textarea>
            </td>
           
            </tr>
            </tbody>
                </table>
              

            <!-- <a  class="btn btn-warning" target="_blank" href="{{ route('generate_invoices') }}">Generate invoice</a> -->

                     @php
               foreach($check_list as $checking_mode)
                    {}
                    @endphp
           
                    <style>
.dark-red {
    color: magenta;
}
</style>
            
      
            @if($checking_mode->supervisor_hold_assessor_progress =='')
            <button class="btn btn-success"  id="save_section_two" > Save  </button> 
            <button style="display:none" id="update_section_two"  class="btn btn-info">Update</button>
            <button title="Supervisor will assess"   class="btn btn-warning"  id="submit_to_supervisor_section_two" > Submit Section Two (Preliminary Assessment- General Requirements ) </button> 

            <br>
           @else
           <button style="display:block" id="update_section_two"  class="btn btn-info">Update</button>
           <br>
           <button title="Supervisor will assess" disabled  class="btn btn-warning"  id="submit_to_supervisor_section_two" >  Section Two (Preliminary Assessment- General Requirements )  Already Sent</button> 

            @endif

               

                

                </div>

       
              </div>
            
              </div>
            





  <!-- Section 3 Specific requirements for fast-track registration-->
  @foreach($check_list as $check_applications_check_list )  @endforeach
  @if($check_applications_check_list->application_type !=1  )


  <div class="row">
          <div class="col-12">
            
                <div class="card">
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-default">
          <div class="card-header">
          <h3 class="card-title">Section 3: Specific requirements for fast-track registration</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <tr>
                    <th>Lists</th>
                      <!-- <th> </th> -->
                      <th style="width: 40px">Yes</th>
                      <th style="width: 40px">No</th>
                      <th style="width: 40px"> Not Applicable</th> 
                    </tr>
                  </thead>
                  <tbody>  
                  <tr>

                 
                  

            <td>Presence valid marketing authorization/ registration date / prequalification letter</td>
            <!-- <td> authorization/ registration date / prequalification letter  </td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="valid_marketing_authorization_yes" name="valid_marketing_authorization" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="valid_marketing_authorization_no" name="valid_marketing_authorization" class="form-control"  width="40"/></td>
            <td>   </td>
               </tr>

            <tr>
            <td>Presence of the Quality Information Summary (QIS) as approved/ endorsed by the reference authority or WHO                         <div class="panel-group"> <div class="panel panel-default"> 
            <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse1"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse1" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [please ensure that either one of this fields are field] </span></div></div></div></div></td>
            <!-- <td> Reference authority   </td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="qis_prequalified_products_yes" name="qis_prequalified_products" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="qis_prequalified_products_no" name="qis_prequalified_products" class="form-control"  width="40"/></td>
            <td>   </td>
            </tr>
            <tr>
            <td> Presence of full assessment report from the reference authority or institution  <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse6"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse6" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [Please ensure that the assessment report is valid] </span></div></div></div></div><br> </td>
            <!-- <td> report from the reference authority or institution   </td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="Presence_of_full_assessment_report_yes" name="Presence_of_full_assessment_report" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="Presence_of_full_assessment_report_no" name="Presence_of_full_assessment_report" class="form-control"  width="40"/></td>
            <td>   </td>
            </tr>


            <tr>
            <td> Presence of the full inspection reports from the reference authority or institution  <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse7"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse7" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [Please ensure that the assessment report is valid]</span></div></div></div></div><br></td>
            <!-- <td >inspection reports</td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="Presence_of_the_full_inspection_reports_yes" name="Presence_of_the_full_inspection_reports" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="Presence_of_the_full_inspection_reports_no" name="Presence_of_the_full_inspection_reports" class="form-control"  width="40"/></td>
            <td>   </td>
            </tr>


            <tr>
            <td> Presence of the Summary Product Characteristics </td>
            <!-- <td > Product Characteristics</td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="Product_Characteristics_yes" name="Product Characteristics" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="Product_Characteristics_no" name="Product Characteristics" class="form-control"  width="40"/></td>
            <td>   </td>
            </tr>


              <tr>
            <td> Presence of the Patient information leaflet </td>
            <!-- <td >Information for the patient/ user</td> -->
            <td ><input type="checkbox"  size="30" hieght="40"  id="information_patient_user_yes" name="information_patient_user" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  size="30" hieght="40"  id="information_patient_user_no" name="information_patient_user" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  style="width: 25px"     id="information_patient_user_not_applicable" name="information_patient_user_not_applicable" class="form-control"  /></td>

            </tr>
      

               <tr>
            <td colspan='5'> Remark :
            <textarea    id="summernotee"  name="remark" class="form-control"  auto/>
            </textarea>
            </td>

            </tr>


                 </tbody>
                </table>

                                
  <button style="display:block" id="update_section_three"  class="btn btn-info" >Save Changes</button>
  <br>
  <button title="Supervisor will Assess"   class="btn btn-secondary"  id="submit_to_supervisor_section_three" > Submit Section Three (Preliminary Assessment-  Specific requirements for fast-track registration ) </button> 


                </div>
               </div>
               </div>

@endif

  <!-- Section 4 Sample details-->
  @foreach($check_list as $check_applications_check_list )  @endforeach
  @if($check_applications_check_list->application_type !=2 )

  <div class="row">
          <div class="col-12">
            
                <div class="card">
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-default">
          <div class="card-header">
          <h3 class="card-title">Section 4: Sample details</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                      <tr>
                      <th>Lists</th>
                      <th> </th>
                      <th style="width: 40px">Yes</th>
                      <th style="width: 40px">No</th>
                      <th style="width: 40px">No Applicable </th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                   
                      <td>1. Availability of Product Sample</td>    
                      <td></td>
                     
                      @foreach($check_list as $sample_Status ) @break
                      @endforeach
                      @if(  $sample_Status->sample_status == '' ) 
                      <td ><input type="checkbox" style="width: 20px"  id="sample_product_yes" name="sample_product" class="form-control"  width="40"/></td>
                      @else 
                      <td ><input type="checkbox"  style="width: 20px"   id="sample_product_yes" name="sample_product_information" class="form-control"  width="40"/></td>
                      @endif
                      <td>
                      <input type="checkbox" size="30" hieght="40"     id="sample_product_no" name="sample_product_no" class="form-control"  width="40"/>
                      </td>
                      <td> 
                      <input type="checkbox" hidden  style="width: 20px"    id="sample_product_not_applicable" name="sample_product_not_applicable" class="form-control"  width="40"/>
                      </td>
                      </tr>

                   
                      


            <tr>
            <td >2. Number of samples received  </td>
            <td colspan='4' > <input type="text"   palceholder = "How many received?? "size="30" hieght="40"     id="Number_of_sample_received" name="number_of_sample_received" class="form-control"  width="40"/></td>
            </tr>
                   
                      <tr>
                      <td>3.Number of sample sent conforms with the Sampling schedule</td>
                      <td>  </td>

                      <td ><input type="checkbox"  size="30" hieght="40"  id="sample_scheduled_yes" name="sample_sent" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="sample_scheduled_no" name="sample_sent" class="form-control"  width="40"/></td>
                      <!-- <td ><input type="checkbox"  style="width: 20px"  id="sample_scheduled_not_applicable" name="sample_sent" class="form-control"  width="40"/></td> -->
                     </tr>

                      <tr>
                      <td >4.Labelling Information</td>
                      
                      <td colspan="4"> Remark <input id="section_four_remark"    type="text"  size="30" hieght="40"  id="labelinfo"  
                      
                      name="label_info" class="form-control"  width="40"/></td>
                      </tr>

                      <tr>
<td>5.Sample net volume or weight</td>

<!-- <td ><input type="checkbox"  size="30" hieght="40"  id="sample_volume_yes" name="sample_volume" class="form-control"  width="40"/></td>
<td ><input type="checkbox"  size="30" hieght="40"  id="sample_volume_no" name="sample_volume" class="form-control"  width="40"/></td> -->
<td colspan='4' >
Net Weight or Volume:<input   size="30" hieght="40"  id="sampling_net_weight" placeholder="Enter Weight"  name="sampling_net_weight" class="form-control"  width="40"/> 
Remark <input   id="section_four_remark_netweight"  type="text"  size="30" hieght="40"  id="labelinfo"  name="label_info" class="form-control"  width="40"/>

</td>
                     
                      </tr>


                      <tr>
                      <td>6.Availability of packaging inserts</td>
                      <td></td>
                      <td ><input type="checkbox"  style="width: 20px"  id="availability_packages_yes" name="availability_packages" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  style="width: 20px"  id="availability_packages_no" name="availability_packages" class="form-control"  width="40"/></td>
                      <td>  </td>
                      </tr>

                          <tr>
                      <td>7.Samples are manufactured in the same manufacturing<br> premises as that stated in the application form.</td>
                      <td> </td>
                      <td ><input type="checkbox"  style="width: 20px"  id="manufacturing_premises_yes" name="manufacturing premises" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  style="width: 20px"  id="manufacturing_premises_no" name="manufacturing premises" class="form-control"  width="40"/></td>
                      </tr>
                    

                      <tr>
                      <td>8.Samples have at least 60% of their shelf-life <br> remaining at the time of submission.</td>
                      <td> </td>
                      <td ><input type="checkbox"  style="width: 20px"  id="sample_shelf_life_yes" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  style="width: 20px"  id="sample_shelf_life_no" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  style="width: 20px"  id="sample_shelf_life_not_applicable" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      </tr>

                       <td>9.Availability of an official certificate of analysis <br>from the manufacturer of the same batch of sample.</td>
                     <td>  </td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="availability_certificate_analysis_yes" name="availability_certificate_analysis" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  size="30" hieght="40"  id="availability_certificate_analysis_no" name="availability_certificate_analysis" class="form-control"  width="40"/>
                     
                      </td>
                     
                      <td>  </td>
                      </tr>

                      <tr>
                      <td colspan='3'> Remark : <textarea  id="summernote_Remark_section_four"  name="remark" class="form-control"  auto/> </textarea> </td>
                      <td>  </td>
                      <td>  </td>
                      </tr>


                  </tbody>
                </table>
                <br>
                <button style="display:block" id="update_section_four"  class="btn btn-info" >Save Changes</button>
                <br>
                <button title="Supervisor will Assess"   class="btn btn-warning"  id="submit_to_supervisor_section_four" > Submit Section Four (Preliminary Assessment-  Sample details ) </button> 


                </div>
               </div>
               </div>

@endif
 <!--Section 4: General Requirements  -->



                    <div class="row">
          <div class="col-12">
            
                <div class="card">
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-default">
          <div class="card-header">
          <h3 class="card-title">Section 5: Payment details</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
              <div class="card-body">
              <table class="table table-bordered  table-condensed table-striped">
                    <thead>
                    <tr>
                    <th>Lists</th>
                    <th> </th>
                    <th style="width: 40px">Yes</th>
                    <th style="width: 40px">No</th>
                    </tr>
                    </thead>
                  <tbody>
                  <tr>
                  <td > Invoice Number <br></td>
                  @foreach($invoice_number as $inv_num ) 
                  @php if($inv_num->invoice_number == '') { $invoice_number='--'; } else { $invoice_number = $inv_num->invoice_number;}  @endphp
                  
                  @endforeach 
 <td ><input type="text"  size="30" hieght="40" readonly  id="Generated_Invoice_Number"  value="{{ $invoice_number }}" placeholder="Invoice Number" name="Generated_Invoice_Number" class="form-control"  width="40"/></td>
                 </tr>
                 <tr>
        <td > Registration fee paid. <br></td>
       <!-- <td ><input type="text"  size="30" hieght="40"  id="checking_application_fee_no" name="checking_application_fee" class="form-control"  width="40"/></td> -->
       <td> </td> 
    
       <td ><input type="checkbox"    id="checking_application_fee_yes" name="checking_application_fee" class="form-control"  width="40"/></td>
       <td ><input type="checkbox"   id="checking_application_fee_no" name="checking_application_fee" class="form-control"  width="40"/></td>
       </tr>
       <tr>
       <td >Receipt Number</td>
       <td >
       @php $receipt = '';  @endphp
       @foreach($receipts_number as $receipts) 
       @php if($receipts->receipt_number == '') {  $receipt = ''; } else { $receipt = $receipts->receipt_number;}  @endphp
       @endforeach
      
      <input type="text"  size="30" hieght="40"  readonly placeholder="Receipt  Number"  value="{{ $receipt }}" id="Application_Receipt_Number" name="Application_Receipt_Number"  class="form-control"  width="40"/>
  
      </td>
      </tr>
      </tbody>
      </table>


<br>
             <table class="table table-bordered  table-condensed table-striped">
                    <thead>
                    <tr>
                   
                   
                    </tr>
                    </thead>
                  <tbody>
                  <tr>
                  <td > Remark <br></td>
 <td ><textarea type="text"  size="30" hieght="40"  id="Remark_section_five" placeholder="" name="Remark" class="form-control"  width="40"/></textarea></td>
                 </tr>
                

       <tr>
       <td >Over All Comments</td>
      <td ><textarea type="text"    placeholder="over_all_comment"  id="over_all_comment" name="Application_Receipt_Number"  class="form-control"  width="40"/>  </textarea>
      </td>
      </tr>
      </tbody>
      </table>
  <!-- <a  class="btn btn-warning" target="_blank" href="{{ route('receipts') }}">Save Receipts</a> -->
                <br><br/>
                <button style="display:block" id="update_section_five"  class="btn btn-info" >Save</button> </td>
  
            
</div>
                    
<br/><br/>
                <h2>
                <div class="form-group">
                    <label style="font-size:20px;" for="exampleInputEmail1">Assessor Name</label>
                    <input type="text" name="assessor_name"  readonly value=" {{ Auth::user()->first_name." ".Auth::user()->middle_name." ".Auth::user()->last_name }}"class="form-control" id="exampleInputEmail1" placeholder="Enter Assessor Name">
                  </div>


                      <div class="form-group">
                      <label style="font-size:20px;" for="exampleInputEmail1">Date</label>
                      <input type="text" name="date" readonly value="@php $t=time(); echo date("Y-m-d",$t); @endphp"  class="form-control" id="exampleInputEmail1" placeholder="Enter Assessor Name">
                      </div>


                    <div class="form-group">
                    <label style="font-size:20px;" for="exampleInputEmail1">Dossier Files [shared Path Name]</label>
                    <input type="path" name="path"  class="form-control" id="path_dossier" placeholder="C:/Dossier/sample/">
                    </div>


              <button class="btn btn-primary"  id="save_processed_check_list" > Save  </button> 
              <br>
              <div class="pull-right">
              <button class="btn btn-info"  id="print_check" > <i class="fas fa-print">  Print </i>  </button> 
              <button class="btn btn-warning"  id="print_check" > <i class="fas fa-check">  Acknowledgement Letter </i>  </button> 
              </div>
              </div>
              </h2>
              </div>
              </div>
            
              </div>
              </div>

  </div>
              </div>
              
  </div>
              </div>
              </div>
              </div>
              
  </div>
              </div>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        </div>
        


@endsection