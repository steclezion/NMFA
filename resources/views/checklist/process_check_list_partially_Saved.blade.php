@extends('layouts.app')

@section('content')  


  @php $invoice_num=''; $receipt='';  $comment=''; $remark_section_five='';@endphp

  
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

<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<!-- <script src="{{ asset('dist/js/demo.js')}}" ></script> -->
  
@include('layouts.check_list_js')



<meta name="csrf-token" content="{{ csrf_token() }}">
  






 
   
 <div class="row"  id="print_checklist">
          <div class="col-12">
          @php foreach($check_list as $dossier )     @endphp

      <div class="card-header">
      <h3 class="card-title">Checklist for Receiving Registration Applications
      



       
       </h3><br><br>
                <h3 class="pull-left"> Preliminary Screening Checklist</h3>
       <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-outline  card-blue" >
          <div class="card-header">
          <h3 class="card-title">Section 1: Product Details</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" value= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
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
                      <!-- <tr>
                      <td>Strength</td>
                      <td>
                      @foreach( $check_list as $product_name )
                     <!-- {{  $product_name->product_name }} -
                      
                   
                      <input name="strength"  class="form-control" value="{{$product_name->product_name }}" type="" />
                 @break
                      @endforeach
                      </td>
                     
                    </tr> -->

                     
                      <tr>
                      <td {{ $i=1 }}>Pharmaceutical form </td>
                      <td>
                      @foreach ($dosage_forms as $dosage_formss)    @endforeach
                      {{  $dosage_formss->name   }}<br>
                  
                      </td>
                     
                      </tr>

    <tr>
                                    <td {{ $i=1 }}>Manufacturer/Market Authorization Holder </td>
                                    <td>
                                        @foreach ($check_list as $supplier_name)  @endforeach       {{ $supplier_name->trade_name  }}
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
            
               
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-outline  card-blue">
          <div class="card-header">
          <h3 class="card-title">Section 2: General Requirements</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
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
                      <th style="width: 40px">No Applicable </th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                     <td>Presence of application letter </td>
                     <td ></td>
                     @foreach($check_list as $check_lists )
                       <!--$invoice_number->invoice_number-->
                      @break
                      @endforeach
    @php if( $check_lists->is_application_letter == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 
                     <td ><input type="checkbox"  {{ $check_yes }}  style="width: 25px"   id="Presence_of_application_letter_yes" name="Presence_of_application_letter" class="form-control"  width="40"/></td>
                     <td ><input type="checkbox"  {{ $check_no }}  style="width: 25px"   id="Presence_of_application_letter_no" name="Presence_of_application_letter" class="form-control"  width="40"/></td>
                     <td> </td>
                   </tr>
                    <tr>
                      <td>Presence of manufacturer and manufacturing sites details </td>    
                      <td ></td>
 @php if( $check_lists->is_manufacturer_inforamation == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox"  {{ $check_yes}}  style="width: 25px"   id="manufacturer_information_yes" name="manufacturer_information" class="form-control"  width="40"/></td>
                   
                      <td><input type="checkbox"  {{ $check_no }}  style="width: 25px"      id="manufacturer_information_no" name="manufacturer_information" class="form-control"  width="40"/></td>
                     
                    </tr>


                          <tr>
       <td>Presence of Local Authorized Agent (LAA) information </td>
       <td > </td>
@php
 if( $check_lists->is_local_agent == 1) { $check_yes='checked';$check_no='';$check_not_app='';} 
 else if($check_lists->is_local_agent == 'no_app') {$check_no='';$check_yes=''; $check_not_app='checked';} 
 else if($check_lists->is_local_agent == 0) {$check_no='checked';$check_yes=''; $check_not_app='';} 
@endphp 

                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"     id="local_agent_yes" name="local_agent" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no}}  style="width: 25px"      id="local_agent_no" name="local_agent" class="form-control"  /></td>
                      <td ><input type="checkbox" {{$check_not_app }}  style="width: 25px" id="local_agent_not_applicable" name="local_agent" class="form-control"  /></td>
                      </tr>

                   <tr>
    <td>Presence of the product in the Eritrean List of medicines (ENLM)    
    <div class="panel-group"> <div class="panel panel-default">
    <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> 
    <!-- <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse3"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse3" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue">  [If the pharmaceutical product is not listed in the ENLM ensure that the reason for its permit registration is filled in the comment section.] </span></div></div></div>  -->
    </td>
    <td ></td>
 
    @php if( $check_lists->is_enlm  == 1) { $check_yes='checked';$check_no='';$check_not_app='';} 
     else if($check_lists->is_enlm  == 0) {$check_no='checked';$check_yes=''; $check_not_app='';} 
      @endphp 

                
                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="dossier_ctd_yes" name="dossier_ctd" class="form-control"  width="40"/></td>
                 
                    <td ><input type="checkbox"  {{ $check_no }}style="width: 25px"   id="dossier_ctd_no" name="dossier_ctd" class="form-control"  width="40"/></td>
                    <td> </td>

                   </tr>

  <tr>
    <td>Presence of the submitted dossier in CTD format as per the requested format  
    
       <!-- <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse3"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse3" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue">  [If the pharmaceutical product is not listed in the ENLM ensure that the reason for its permit registration is filled in the comment section.] </span></div></div></div> -->
        </td>

    <td > </td>


       @php if( $check_lists->submitted_dossier_in_CTD_format == 1) { $check_yes='checked';$check_no='';$check_not_app='';} 
     else if($check_lists->submitted_dossier_in_CTD_format == 0) {$check_no='checked';$check_yes=''; $check_not_app='';}   @endphp 

                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="enlm_yes" name="enlm" class="form-control"  width="40"/></td>
                
                    <td ><input type="checkbox"  {{ $check_no}} style="width: 25px"   id="enlm_no" name="enlm" class="form-control"  width="40"/></td>
                    <td> </td>

                   </tr>

                      <tr>
                      @php if( $check_lists->is_module_one == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 
           <td rowspan='5'> Availability of the module of the CTD </td>
           <!-- <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse4"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse4" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue">  [Please tick the boxes if the relevant modules are available or if certain modules are missing please fill the comment section the acceptable reasons of its absence]</span></div></div></div></div> -->
            </td>
                      <td style="text-align: left;">Module I </td>
                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="module_one_yes" name="module_one" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no}}  style="width: 25px"   id="module_one_no" name="module_one" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <tr>
                      <td >Module II </td>
                      @php if( $check_lists->is_module_two == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="module_two_yes" name="module_two" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no}}  style="width: 25px"   id="module_two_no" name="module_two" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <tr>
                      <td >Module III</td>
                      @php if( $check_lists->is_module_three == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="module_three_yes" name="module_three" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no}}  style="width: 25px"   id="module_three_no" name="module_three" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <td >Module IV</td>
                      @php if( $check_lists->is_module_four == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox" {{ $check_yes }}  style="width: 25px"   id="module_four_yes"  name="module_four" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  {{ $check_no }} style="width: 25px"   id="module_four_no"   name="module_four" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>
                      <td >Module V</td>
                      @php if( $check_lists->is_module_five == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox"  {{ $check_yes}} style="width: 25px"   id="module_five_yes" name="module_five" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  {{ $check_no }} style="width: 25px"   id="module_five_no"  name="module_five" class="form-control"  width="40"/></td>
                      <td> </td>
                      </tr>

            <tr>
            <td colspan='5'>Remark :
            <textarea id="summernote" name="remark" class="form-control"  auto/>
          @php echo  trim($check_lists->Remark_step_two); @endphp
            </textarea>
            </td>
           
            </tr>
            </tbody>
                </table>
              

                         @php
               foreach($check_list as $checking_mode)
                    {}
                    @endphp
           
                    <style>
.dark-red {
    color: magenta;
}
</style>
            
      
            @if($check_lists->is_application_letter == '')
            <button class="btn btn-success"  id="save_section_two" > Save  </button> 
            <button style="display:none" id="update_section_two"  class="btn btn-primary">Update</button>
            <br>
            <button title="Supervisor will assess"   class="btn btn-warning"  id="submit_to_supervisor_section_two" > Submit section two to supervisor </button> 

            <br>
           @else
           <button style="display:block" id="update_section_two"  class="btn btn-primary">Update</button>
           <br>
           <button title="Supervisor will assess"  class="btn btn-info"  id="submit_to_supervisor_section_two" >  Re-Submit section two to supervisor</button> 

            @endif
           
           
                

                </div>

       
              </div>
            
              </div>
            
  <!-- Section 4 Specific requirements for fast-track registration-->
  @foreach($check_list as $check_applications_check_list )  @endforeach
  @if($check_applications_check_list->application_type !=1  )
  <div class="row">
          <div class="col-12">
            
             
             
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-outline  card-blue">
          <div class="card-header">
          <h3 class="card-title">Section 3: Specific requirements for fast-track registration</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
           
            </div>
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <tr>
                    <th>Lists</th>
                    
                      <th style="width: 40px">Yes</th>
                      <th style="width: 40px">No</th>
                      <th style="width: 40px">Not Applicable</th>
                     
                    </tr>
                  </thead>
                  <tbody>  
                  <tr>

                 
                  

            <td>Presence valid marketing authorization/ registration date / prequalification letter</td>
           
            @php if( $check_lists->Presence_valid_marketing_authorization == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

            <td ><input type="checkbox"   {{ $check_yes}} style="width: 25px"   id="valid_marketing_authorization_yes" name="valid_marketing_authorization" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"   {{ $check_no}} style="width: 25px"   id="valid_marketing_authorization_no" name="valid_marketing_authorization" class="form-control"  width="40"/></td>
          
               </tr>

            <tr>
            <td>Presence of the Quality Information Summary (QIS) as approved/ endorsed by the reference authority or WHO                         <div class="panel-group"> <div class="panel panel-default"> 
            <!-- <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse1"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse1" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [please ensure that either one of this fields are field] </span></div></div></div></div> -->
            </td>
          
            @php if( $check_lists->Presence_of_the_Quality_Information_Summary == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

            <td ><input type="checkbox" {{ $check_yes}}   style="width: 25px"   id="qis_prequalified_products_yes" name="qis_prequalified_products" class="form-control"  width="40"/></td>
            <td ><input type="checkbox" {{ $check_no}}   style="width: 25px"   id="qis_prequalified_products_no" name="qis_prequalified_products" class="form-control"  width="40"/></td>
         
            </tr>
            <tr>
            <td> Presence of full assessment report from the reference authority or institution  
            <!-- <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse6"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse6" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [Please ensure that the assessment report is valid] </span></div></div></div></div><br> -->
             </td>
        
            @php if( $check_lists->Presence_of_full_assessment_report_from_the_reference_authority == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

            <td ><input type="checkbox" {{ $check_yes}}   style="width: 25px"   id="Presence_of_full_assessment_report_yes" name="Presence_of_full_assessment_report" class="form-control"  width="40"/></td>
            <td ><input type="checkbox"  {{ $check_no}}  style="width: 25px"   id="Presence_of_full_assessment_report_no" name="Presence_of_full_assessment_report" class="form-control"  width="40"/></td>
          
            </tr>


            <tr>
            <td> Presence of the full inspection reports from the reference authority or institution 
             <!-- <div class="panel-group"> <div class="panel panel-default"> <div class="panel-heading pace-center-simple-lime"> <h4 class="panel-title"> <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapse7"><b class="fas fa-angle-double-right"> </b> </a></h4></div><div id="collapse7" class="panel-collapse collapse"><div class="panel-body"><span style="color:skyblue"> [Please ensure that the assessment report is valid]</span></div></div></div></div><br> -->
             </td>
          
            @php if( $check_lists->Presence_of_the_full_inspection == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

            <td ><input {{ $check_yes}}  type="checkbox"  style="width: 25px"   id="Presence_of_the_full_inspection_reports_yes" name="Presence_of_the_full_inspection_reports" class="form-control"  width="40"/></td>
            <td ><input {{ $check_no}} type="checkbox"  style="width: 25px"   id="Presence_of_the_full_inspection_reports_no" name="Presence_of_the_full_inspection_reports" class="form-control"  width="40"/></td>
          
            </tr>


            <tr>
            <td> Presence of the Summary Product Characteristics </td>
        
            @php if( $check_lists->Presence_of_the_Summary_Product_Characteristics == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

            <td ><input {{ $check_yes}} type="checkbox"  style="width: 25px"   id="Product_Characteristics_yes" name="Product Characteristics" class="form-control"  width="40"/></td>
            <td ><input {{ $check_no}} type="checkbox"  style="width: 25px"   id="Product_Characteristics_no" name="Product Characteristics" class="form-control"  width="40"/></td>
          
            </tr>


              <tr>
            <td> Presence of the Patient information leaflet </td>
           
            @php if( $check_lists->Presence_of_the_Patient_information_leaflet== 1) 
            { $check_yes='checked';$check_no='';$check_not_app='';} 
             elseif( $check_lists->Presence_of_the_Patient_information_leaflet== 0)  
              {$check_no='checked';$check_yes=''; $check_not_app='';}
             else
             {$check_no='';$check_yes=''; $check_not_app='checked';}
             @endphp 

            <td ><input type="checkbox" {{ $check_yes}}  style="width: 25px"   id="information_patient_user_yes" name="information_patient_user" class="form-control"  width="40"/></td>
            <td ><input type="checkbox" {{ $check_no}}  style="width: 25px"   id="information_patient_user_no" name="information_patient_user" class="form-control"  width="40"/></td>
            <td ><input type="checkbox" {{$check_not_app }}  style="width: 25px"     id="information_patient_user_not_applicable" name="information_patient_user_not_applicable" class="form-control"  /></td>

            </tr>
      

               <tr>
            <td colspan='5'> Remark :
            <textarea    id="summernotee"  name="remark" class="form-control"  auto/>

            {{ $check_lists->Remark_step_three }}

            </textarea>
            </td>

            </tr>


                 </tbody>
                </table>

                                
  <button style="display:block" id="update_section_three"  class="btn btn-info" >Save Changes</button>
  <br>
  <button title="Supervisor will Assess"   class="btn btn-secondary"  id="submit_to_supervisor_section_three" > Submit Section Three (Specific requirements for fast-track registration )  to supervisor</button> 

                </div>
               </div>
               </div>


@endif
  <!-- Section 4 Sample details-->
  @foreach($check_list as $check_applications_check_list )  @endforeach
  @if($check_applications_check_list->application_type !=2 )
  <div class="row">
          <div class="col-12">
            
               
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-outline  card-blue">
          <div class="card-header">
          <h3 class="card-title">Section 4: Sample details</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
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
                     @php if( $check_lists->Availability_of_Product_Sample == 1) { $check_yes='checked';$check_no='';$check_not_app='';}  else if($check_lists->Availability_of_Product_Sample == 0) {$check_no='checked';$check_yes=''; $check_not_app='';} else {$check_no='';$check_yes=''; $check_not_app='checked';}  @endphp 

                      @foreach($check_list as $sample_Status ) @break
                      @endforeach
                      @if(  $sample_Status->sample_status == '' ) 
                      <td ><input type="checkbox" {{ $check_yes }}   style="width: 20px"  id="sample_product_yes" name="sample_product" class="form-control"  width="40"/></td>
                      @else 
                      <td ><input type="checkbox" {{ $check_yes }}  style="width: 20px"   id="sample_product_yes" name="sample_product_information" class="form-control"  width="40"/></td>
                      @endif
                      <td>
                      <input type="checkbox" {{ $check_no }}   size="30" hieght="40"     id="sample_product_no" name="sample_product_no" class="form-control"  width="40"/>
                      </td>
                      <td> 
                      <input type="checkbox" hidden  {{ $check_not_app }}  style="width: 20px"    id="sample_product_not_applicable" name="sample_product_not_applicable" class="form-control"  width="40"/>
                      </td>
                      </tr>

                   
                      


            <tr>
            <td >2. Number of samples received  </td>
            <td colspan='4' > <input type="number" min='0'  onkeyup="AllowonlyText_Tele(event,'Number_of_sample_received')"  palceholder = "How many received?? "size="30" hieght="40"   value="{{ $check_lists->Number_of_samples_received}}"    id="Number_of_sample_received" name="number_of_sample_received" class="form-control"  width="40"/></td>
            </tr>
                   
                      <tr>
                      <td>3.Number of sample sent conforms with the Sampling schedule</td>
                      <td>  </td>
                      @php if( $check_lists->Number_of_sample_sent_conforms_with_the_sampling_schedule == 1) { $check_yes='checked';$check_no='';$check_not_app='';}  else if($check_lists->Number_of_sample_sent_conforms_with_the_sampling_schedule == 0) {$check_no='checked';$check_yes=''; $check_not_app='';} else {$check_no='';$check_yes=''; $check_not_app='checked';}  @endphp 

                      <td ><input type="checkbox"  {{ $check_yes }} size="30" hieght="40"  id="sample_scheduled_yes" name="sample_sent" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  {{ $check_no  }}  size="30" hieght="40"  id="sample_scheduled_no" name="sample_sent" class="form-control"  width="40"/></td>
                      <!-- <td ><input type="checkbox"  style="width: 20px"  id="sample_scheduled_not_applicable" name="sample_sent" class="form-control"  width="40"/></td> -->
                     </tr>

                      <tr>
                      <td >4.Labelling Information</td>
                      <td colspan="4"> Remark 
                      
                      <input value="{{ $check_lists->Labelling_Information }}" id="section_four_remark"  type="text"  size="30" hieght="40"  id="labelinfo"  
                      
                      name="label_info" class="form-control"  width="40"/>
                      

                      
                      
                      </td>
                      </tr>

                      <tr>
<td>5.Sample net volume or weight</td>

<!-- <td ><input type="checkbox"  size="30" hieght="40"  id="sample_volume_yes" name="sample_volume" class="form-control"  width="40"/></td>
<td ><input type="checkbox"  size="30" hieght="40"  id="sample_volume_no" name="sample_volume" class="form-control"  width="40"/></td> -->
<td colspan='4' >

Net Weight or Volume:<input  value="{{ $check_lists->Sample_net_volume_or_weight }}" size="30" hieght="40"  id="sampling_net_weight" placeholder="Enter Weight or Volume"  name="sampling_net_weight" class="form-control"  width="40"/> 
Remark <input value="{{ $check_lists->Sample_net_volume_or_weight_remark }}" id="section_four_remark_netweight"  type="text"  size="30" hieght="40"  id="labelinfo"  name="label_info" class="form-control"  width="40"/>

</td>
                     
                      </tr>


                      <tr>
                      <td>6.Availability of packaging inserts</td>
                      <td></td>
                      @php if( $check_lists->availability_packages== 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox"  {{ $check_yes}} style="width: 20px"  id="availability_packages_yes" name="availability_packages" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox"  {{ $check_no}} style="width: 20px"  id="availability_packages_no" name="availability_packages" class="form-control"  width="40"/></td>
                      <td>  </td>
                      </tr>

                          <tr>
                      <td>7.Samples are manufactured in the same manufacturing<br> premises as that stated in the application form.</td>
                      <td> </td>
                      @php if( $check_lists->manufactured_in_the_same_manufacturing_premises == 1) { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox" {{ $check_yes}}  style="width: 20px"  id="manufacturing_premises_yes" name="manufacturing premises" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no}}  style="width: 20px"  id="manufacturing_premises_no" name="manufacturing premises" class="form-control"  width="40"/></td>
                      </tr>
                    

                      <tr>
                      <td>8.Samples have at least 60% of their shelf-life <br> remaining at the time of submission.</td>
                      <td> </td>
                      @php 
              
  if( $check_lists->Samples_have_at_least_60_perecent == 1){ $check_yes='checked';$check_no='';$check_not_app='';}  
  else if( $check_lists->Samples_have_at_least_60_perecent== 'no_app'){$check_no='';$check_yes='';$check_not_app='checked'; } 
  else if( $check_lists->Samples_have_at_least_60_perecent == 0){$check_no='checked';$check_yes='';$check_not_app=''; } 
                       
                       @endphp 

                      <td ><input type="checkbox" {{ $check_yes }} style="width: 20px"  id="sample_shelf_life_yes" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no }} style="width: 20px"  id="sample_shelf_life_no" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_not_app }} style="width: 20px"  id="sample_shelf_life_not_applicable" name="sample_shelf_life" class="form-control"  width="40"/></td>
                      </tr>

                       <td>9.Availability of an official certificate of analysis <br>from the manufacturer of the same batch of sample.</td>
                     <td>  </td>
        @php if( $check_lists->Availability_of_an_official_of_analysis == 1) 
        { $check_yes='checked';$check_no='';}  else {$check_no='checked';$check_yes=''; } @endphp 

                      <td ><input type="checkbox"  {{ $check_yes }}  size="30" hieght="40"  id="availability_certificate_analysis_yes" name="availability_certificate_analysis" class="form-control"  width="40"/></td>
                      <td ><input type="checkbox" {{ $check_no }}    size="30" hieght="40"  id="availability_certificate_analysis_no" name="availability_certificate_analysis" class="form-control"  width="40"/>
                     
                      </td>
                     
                      <td>  </td>
                      </tr>



   <tr>
                      <td>10. Sample Received Date :  </td>
                      <td colspan='4'> <input  type="date"  value ="{{ $check_lists->sample_received_date }}" size="30" hieght="40"  id="sample_received_date"   name="label_info" class="form-control"  width="40"/>
                      </td>
                      </tr>



                      <tr>
                      <td colspan='3'> Remark : <textarea  id="summernote_Remark_section_four"  name="remark" 
                      
                    value= "{{ $check_lists->summernote_Remark_section_four }}"    class="form-control"  auto/> 
                    {{ $check_lists->summernote_Remark_section_four }}

                  </textarea> </td>
                      <td>  </td>
                      <td>  </td>
                      </tr>


                  </tbody>
                </table>
                <br>
                <button style="display:block" id="update_section_four"  class="btn btn-info" >Save Changes</button>
                <br>
                <button title="Supervisor will Assess"   class="btn btn-warning"  id="submit_to_supervisor_section_four" > Submit Section Four (Sample details ) to supervisor </button> 

                </div>
               </div>
               </div>
@endif

 <!--Section 5: Payments Requirments  -->


  <div class="row">
          <div class="col-12">
            
               
        
              <div class="container-fluid">
        <!-- Section one product details  -->
        <div class="card card-outline  card-blue">
          <div class="card-header">
          <h3 class="card-title">Section 5: Payment details</h3>
             <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
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
                  @php if($inv_num->invoice_number == '') { $invoice_num='--'; } else { $invoice_num = $inv_num->invoice_number;}  @endphp
                  @endforeach 

 <td ><input type="text"  size="30" hieght="40" readonly  id="Generated_Invoice_Number"  value="{{ $invoice_num }}" placeholder="Invoice Number" name="Generated_Invoice_Number" class="form-control"  width="40"/></td>
                 </tr>
                 <tr>
        <td > Registration fee paid. <br></td>
       <!-- <td ><input type="text"  size="30" hieght="40"  id="checking_application_fee_no" name="checking_application_fee" class="form-control"  width="40"/></td> -->
       <td> </td> 
       @php if( $check_lists->is_application_payment_fee == 1) { $check_yes='checked';$check_no='';$check_not_app='';}  else if($check_lists->is_application_payment_fee == 0) {$check_no='checked';$check_yes=''; $check_not_app='';} else {$check_no='';$check_yes=''; $check_not_app='checked';}  @endphp 

       <td ><input type="checkbox"  {{ $check_yes }}  id="checking_application_fee_yes" name="checking_application_fee" class="form-control"  width="40"/></td>
       <td ><input type="checkbox"  {{ $check_no }} id="checking_application_fee_no" name="checking_application_fee" class="form-control"  width="40"/></td>
       </tr>
       <tr>
       <td >Receipt Number</td>
       <td >
       @foreach($receipts_number as $receipts) 
       @php if($receipts->receipt_number == '') {  $receipt = ''; } else { $receipt = $receipts->receipt_number;}  @endphp
       @endforeach

       
      
      <input type="text"  size="30" hieght="40"  readonly placeholder="Receipt  Number"   value="{{ $receipt }}"
      
       id="Application_Receipt_Number" name="Application_Receipt_Number"  class="form-control"  width="40"/>
      
      

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
                  @foreach($check_list as $remarkk ) 
                  @php if($remarkk->remark_section_five == '') { $remark_section_five='--'; } else { $remark_section_five = $remarkk->remark_section_five;}  @endphp
                  @endforeach

                  <td > Remark <br></td>
 <td ><textarea type="text"  size="30" hieght="40"    id="Remark_section_five" placeholder="" name="Remark" class="form-control"  width="40"/>{{ $remark_section_five }} </textarea> </td>
                 </tr>
                
                
       <tr>
       <td >Over All Comments</td>
       @foreach($check_list as $all_comment ) 
                  @php if($all_comment->over_all_comment == '') { $comment = '--'; } 
                  else { $comment = $all_comment->over_all_comment;}  
      @endphp
                  @endforeach


      <td ><textarea type="text"    placeholder="over_all_comment"  id="over_all_comment" name="Application_Receipt_Number"  class="form-control"  width="40"/>  {{   $comment}}  </textarea>
      </td>
      </tr>
      </tbody>
      </table>
  <!-- <a  class="btn btn-warning" target="_blank" href="{{ route('receipts') }}">Save Receipts</a> -->
                <br><br/>

                <button style="display:block" id="update_section_five"  class="btn btn-info" >Save</button> </td>
  
            
</div>
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
                    @php
               foreach($check_list as $dossier)
                    {}
                    @endphp
                    <label style="font-size:20px;"  for="exampleInputEmail1">Dossier Files [shared Path Name]</label>
                    <input type="path" name="path" width="50" class="form-control" id="path_dossier" readonly value="Dossier/{{$dossier->dossier_actual_path}}/">
                    </div>


              <!-- <button class="btn btn-primary"  id="save_processed_check_list" > Save  </button>  -->
              <br>
              <div class="row no-print">
              <div class="pull-right">
              <button class="btn btn-info"  id="print_check" > <i class="fas fa-print">   </i>Print</button> 


               <button class="btn btn-primary"  id="send_message_to_supervisor" > <i class="fas fa-message">  </i> Send comments</button> 

              <!-- <a href="{{ route('checklist.process_check_list', $dossier->application_id)}}" rel="noopener" target="_blank" class="btn btn-info"><i class="fas fa-print"></i> Print</a>
              <a target="_blank" href="{{ route('Acknowledgement_Letter_preliminary_screening',$application_id->application_id) }}" >

              <button class="btn btn-warning"   > <i class="fas fa-check"> </i>  Acknowledgement Letter   </button> 
               </a>               -->

         
            
     <button class="btn btn-warning"  id="check_steps"  >
     <i class="fas fa-circle-arrow-right"></i>   Proceed 
     </button>

               </div>
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
        



        @include('layouts.modal_check_completion_steps')


        @include('layouts.modal_mailing_messages')

@endsection