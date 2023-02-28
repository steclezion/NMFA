@extends('layouts.app_app')
@section('content')

<style>
#msform {
    font-family: "Arial", Times, serif;
}


</style>



<meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- start: Css -->

<!--   
plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>




<link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/css/w3.css')}}">

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
<script src="{{ asset('dist/js/demo.js')}}" ></script>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
    <!-- MultiStep Form -->
<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-md-5 col-md-7 col-lg-10 text-center p-0 mt-3 mb-2">
        <div  class="alert alert-success align-content-sm-center" id="app_id" >

@foreach($application_check_wizard as $app)  {{ $app->application_id  }}    @endforeach


</div>
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3"  id="app_color">
                <h2><strong>Application Submission Process
                
 </strong></h2>
                <p>Fill all form fields to go to the next step <i style="color:blue" id="modal_show_detail_information" title="Guidelines on Application Submission process"  class="fas fa-info-circle">  </i></p> 
                <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform">
                            <!-- progressbar -->
        <ul id="progressbar">
        <li  @php if(in_array('0',$explode)) { echo "class='active active-Application_Type'"; }  else  {}    @endphp   id="Application_Type" ><strong >Application Type</strong></li>
        <li @if(in_array('1',$explode))  class="active active-Application_Type"  @else    @endif    id="supplier" ><strong  >Company Supplier</strong></li>
        <li @if(in_array('2',$explode))  class="active "  @else    @endif   id="Agent"  ><strong>Agent</strong></li>
        <li @if(in_array('3',$explode))  class="active"  @else    @endif   id="product_details" ><strong>Product Details</strong></li>
        <li @if(in_array('4',$explode))  class="active"  @else    @endif   id="product_composition" ><strong>Product Composition</strong></li>
        <li  @if(in_array('5',$explode)) class="active"  @else    @endif   id="product_manufacturers" ><strong>Product Manufacturers</strong></li>
        <li @if(in_array('6',$explode))  class="active"  @else    @endif   id="product_manufacturers_api" ><strong>API Manufacturers</strong></li>
        <!-- <li @if(in_array('7',$explode))  class="active"  @else    @endif   id="dossier_sample" ><strong> Dossier Link and Sample</strong></li> -->
        <li @if(in_array('7',$explode))  class="active"  @else    @endif   id="decleration" ><strong> Declaration</strong></li>
        <li @if(in_array('8',$explode))  class="active"  @else    @endif   id="confirm"><strong>Finish</strong></li>
         </ul> <!-- fieldsets -->
                                 <!------------------------------   -->
                                 <!---------------------------------------------------------------------------------------------------------   -->

 @foreach($applications_status as $applications)  @endforeach  
 @foreach($country_contact_info as $country_contact_info)  @endforeach  

@foreach($country_contact_info_supplier_info as $country_contact_info_supplier_info)  @endforeach


 @php if($applications->trade_name == '')  $action_button ='save_supplier_info';   else  $action_button='update_supplier_info'  ;
 
$count = count($explode)-1;

 //Selection Sort
 for($c=0; $c < ( count($explode)-1 ); $c++)
 {
     $position = $c;
     for($d=$c+1 ; $d < count($explode);$d++ )
     {
         if($explode[$position] > $explode[$d]){ $position = $d;}
         if($position != $c) {$swap = $explode[$c]; $explode[$c] = $explode[$position]; $explode[$position] = $swap;}
     }
 }
sort($explode);
$Last_Element = $explode[$count];
$Last_Element;
 
 @endphp
 
<input class="form-control" id="generated_application_id" type="hidden" name="Application_ID"  value="{{ $applications->application_id}}" />


   
<fieldset   @php if($Last_Element==0) {  echo  "style='display:none'"; }  else  { echo "style='display:none'"; }    @endphp id="type_application">

<div class="form-card">
<h2 class="fs-title">Application Type</h2>
<div class="row">
<div class="col-sm-3">
<label hidden >New Application </label>  
</div>
<div class="col-sm-9"> 
<input class="form-control" checked disabled style="width:20px;position:float:left;"  id="app_new_application" type="hidden" name="Application_type"   />
<label  style="display:block" id="new_application_mode_label" class="form control-label"  > Select Application Mode 
<i style="color:red;cursor:pointer;font-size:20px;">*</i>
</label>

<div class="input-group mb-3"  style="width:20;position:float:left;" >
<select  style="display:block"   class="form-control" name="new_application_mode" id="new_application_mode"  required  onchange="start_applications(this.value)">
<!-- <option value="0" disable="true" selected="true">  </option> -->
@foreach($application_check_wizard as $app)

@if($app->application_type == 1)  
<option value="1" selected='true'>  Standard route of registration </option>
@elseif($app->application_type == 2 && $app->fast_track_details == 'MoH Tender' )
<option value="2_MoH Tender"  selected='true' >Fast track route of registration - MoH Tender</option>
@elseif($app->application_type == 2 && $app->fast_track_details == 'SRA product' )
<option value="2_SRA product"  selected='true' >Fast track route of registration - SRA product</option>
@elseif($app->application_type == 2 && $app->fast_track_details == 'WHO PQP' )
<option value="2_WHO PQP"  selected='true' >Fast track route of registration - WHO PQP</option>
@endif
@endforeach
@if($app->application_type != 1) 
<option value="1" > Standard route of registration </option>
@endif
@foreach ($fast_track_applications as $key => $value)
@if($value->name != $app->fast_track_details)
<option value="2_{{ $value->name }}">Fast track route of registration - {{ $value->name }} </option>
@endif
@endforeach

</select>
</div>
</div>


<div class="col-sm-3" hidden>
<!-- <label >Renew Application </label>   -->
</div>
<div class="col-sm-9" hidden> 
<input class="form-control"  style="width:20px;position:float:left;"  id="app_renewal_application" type="radio" name="Application_type"   />
<div class="input-group mb-3"  style="position:float:left;" >
<select    style="display:none" class="form-control" name="app_renew_new_application_mode" id="app_renew_new_application_mode"  required  onchange="start_applications(this.value)">
<option value="0" disable="true" selected="true"> </option>
<option value="1"> Standard Mode </option>
@foreach ($fast_track_applications as $key => $value)
<option value="2_{{ $value->name }}">Fast Track Mode {{ $value->name }}</option>
@endforeach
</select>

</select>
</div>
</div>   

<span style="display:none" ><label class="form-control">Applications for Variations </label>  <input class="form-control" id="app_variations" type="radio" name="Application_type"  /></span>


<div   style="display:none"class="col-sm-3">
<label >Request fast track/abridged registration </label>  
</div>
<div class="col-sm-9"  style="display:none">           
<input style="width:20px;position:float:left;"  class="form-control" id="app_fast_track_mode" type="radio" name="Application_type"  />

<div class="input-group mb-3"  style="width:200px;position:float:left;" >
<select  style="display:none" class="form-control" name="track_mode" id="app_select_mode"  required>
<option value="0" disable="true" selected="true"> Select Application Mode </option>
@foreach ($fast_track_applications as $key => $value)
<option value="{{$value->id}}">{{ $value->name }}</option>
@endforeach
</select>
</div>

</div>


</div>

       </div>
               

<input id="application_type_id"   name="application_type_name"    value=""   hidden/>
<input id="application_id"   name="application_name"    value=""  hidden/>
<span  id="appicaiton_save"  style="display:none">
<button   type="button" class="btn btn-success" value="Update"    id="update_application_info"  >   Update </button> </span>

</span>
<span  id="applicaion_updatee"  style="display:none">
<!-- <button   type="button" class="btn btn-success" value="Update"    id="update_application_info"  >   Update </button> -->
</span>
<!-- <button   type="button" class="btn btn-success" value="Save"    id="save_application_info"  >Save </button> -->

<button style="display:block; position: absolute;left: 45%; top: 85%;" style="display:block" type="button" name="next" class="btn btn-primary app_recep_next" value="Next Step"  id="next_button_application"   />Next Step </button>

<br><br><br> 
                       
</fieldset>


<!------------------------------------------------------------------------  -->

 <fieldset   @php if($Last_Element == 1) { echo "style='display:block'"; }  else  {}    @endphp   id="supplier_wizard">
 <div class="container">   
    <div class="row">

    <div class="col-sm-6" style="background-color:lightgrey;" >
    <lable>Applicant Registration Form </label> 
    <input id="old_applicant"  style="display:none" type="radio"   checked  name="check_trade"  /> 
    </div>

    <div class="col-sm-6" style="display:none">
    <lable> New Applicant  </label> 
    <input   id="new_applicant" type="radio"   name="check_trade"  > 
    </div>
 
   
    </div>
    </div>
                            
<div class="form-card"  style="font-family: "Times New Roman", Times, serif;">
<div class="row"  style="display:" id="General_Supplier">
<div class="col-12 col-sm-6">
<h2 class="fs-title" style="display:block"> Applicant Information </h2>
<input class="form-control" type="text" name="trade_name"  style="display:none"  placeholder="Applicant Name"  id="cs_trade_name"/>

<div style="display:block"   id="cs_tradename_exits">  
<label>Applicant Name <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>   
<select  class="form-control select2bs4" style="width: 100%;"  name="trade_names" id="trade_names"  required  onchange="check_trade_name(this.value)">
<option selected="true" value="{{ $applications->trade_name  }}"><i style="color:red" >  {{ $applications->trade_name   }}     </i></option>
@foreach ($company_suppliers_template as $key => $value)
<option value="{{ $value->trade_name }}">{{  $value->trade_name   }}  </option>
@endforeach
<option value="Other"> Other </option>
</select>

</div>         

<div style="display:none;color:red"  id="cs_tradename_other" >
<label> Specify Applicant Name (If other) </label> 
 <input class="form-control" id="trade_names_other" type="text" name="trade_names"    required>
 <input  type="hidden" id="company_supplier_template_id" />
 </div>
<br/>
<!-- <div id="">
<label> Supplier Name</label> 
 <input class="form-control" id="cs_city" type="text" name="city"    required>
</div> -->
<!--                                                               -->         
<div class="input-group mb-3"> 
<label> Country<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
<select class="form-control" style="width: 100%;"  name="country_id" id="css_country"  onchange="fetch_tele(this.value,'cs_response_tele','{{url('/get_tele_code/tele_code')}}')"  required>
<option selected="true" value="{{ $applications->countryid  }}"><i style="color:red" >  {{ $applications->country_name   }}     </i></option>
@foreach ($countries as $key => $value)
<option value="{{$value->id}}">{{ $value->country_name }}</option>
@endforeach
</select>
</div>

<label> City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
 <input  value="{{ $applications->customer_city   }}" class="form-control" id="cs_city" type="text" name="city"    required>
 <label>State</label>
<input value="{{ $applications->Supplier_state }}" class="form-control" id="cs_state"type="text" name="state"  /> 
<label>Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
 <input  value="{{ $applications->Supplier_line_one_address }}" class="form-control" id="cs_address_line_one" type="text" name="address_line_one"  />
 <label>Address Line Two </label>
<input value="{{ $applications->Supplier_line_two_address }}" class="form-control" id="cs_address_line_two" type="text" name="address_line_two"  />
<div id="cs_response_email"></div>
<div id="cs_response_email_success" class="alert alert-success" style="display:none"></div>
<div id="cs_response_email_danger" class="alert alert-danger" style="display:none"></div>
<div id="cs_response_email_warning" class="alert alert-warning" style="display:none"></div>
<label> Institutional Email<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>

<input value="{{ $applications->email_supplier }}" class="form-control"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_supply')}}','cs_response_email','save_supplier_infoo','cs_email')" 



id="cs_email" type="email" name="email" placeholder="Email" />
<i class="fas fa-phone fa-2xs" id="cs_response_tele"> {{   $applications->International_dialing }}    </i> 
<label>Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input  min="0" onkeyup="AllowonlyText_Tele(event,'cs_tele')"  value="{{ $applications->telephone_supplier }}"  class="form-control"  
id="cs_tele"  type="number"  name="telephone"  type="text" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask />

<label>Postal Address<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input value="{{ $applications->Supplier_postal_code }}" class="form-control" id="postal_code" type="text" name="postal_code"   />

                                  <!--
                                    <div id="cs_institutional_response_email"></div>
                                    <div id="cs_institutional_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="cs_institutional_response_email_danger" class="alert alert-danger" style="display:none"></div>
                                    <div id="cs_institutional_response_email_warning" class="alert alert-warning" style="display:none"></div>
                                    <label> Institutional Email </label>
                                    <input class="form-control" id="cs_institutional_email" type="email" name="institutional_email" placeholder="Institutional Email" onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_supply')}}','cs_institutional_response_email','save_supplier_info','cs_institutional_email')"/> 
                                    */-->
<!--Validate URL from the backend section -->

<div id="cs_response_website_url"></div>
<div id="cs_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
<div id="cs_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
<div id="cs_response_website_url_success" class="alert alert-success" style="display:none"></div>
<label>Web URL</label>
<input  value="{{ $applications->cs_webiste_url }}" class="form-control" id="cs_website_url" type="url" name="websete_url"   onkeyup="valdiate_url(this.value,'cs_response_website_url','{{url('/Validate/url/customer_supply')}}','save_supplier_info')" /> 
                                   
<label>Fax</label>
<input  class="form-control" id="cont_fax"  type="text" name="fax"  value="{{ @$applications->cont_fax}}"/>

                                    
                                    </div>

<!--Contact Person Information />-->
<div class="col-12 col-sm-6"> 
<h2 class="fs-title">Applicant Contact Person               
 <i  style="color:blue;cursor:pointer;font-size:15px;" title="Please ensure that the contact person you have listed is an active employee of the company. The contact person will be responsible for all the communications between the National Medicines and Food Administration (NMFA) and the company."  class="fas fa-info-circle fa-0">  </i>
</h2> 
<label>First Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input value="{{ $applications->cont_first_name }}" onkeyup="AllowonlyText(event,'cont_first_name')" class="form-control" id="cont_first_name" type="text" name="first_name"    required>
<label>Middle Name</label>
<input value="{{ $applications->cont_middle_name }}"  onkeyup="AllowonlyText(event,'cont_middle_name')"  class="form-control" id="cont_middle_name" type="text" name="middle_name"    required>
<label>Last Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input value="{{ $applications->cont_last_name   }}"  onkeyup="AllowonlyText(event,'cont_last_name')"class="form-control" id="cont_last_name" type="text" name="last_name"    required>
<label>Country<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<div class="input-group mb-3">
<select class="form-control select2bs4" name="country_id" id="cont_country"  required  onchange="fetch_tele(this.value,'cont_response_tele','{{url('/get_tele_code/tele_code')}}')">
<option selected="true" value="{{ $country_contact_info->countryid  }}"><i style="color:red" >   {{ $country_contact_info_supplier_info->contact_country_name   }}     </i></option>


@foreach ($countries as $key => $value)
@if($country_contact_info->country_name != $value->country_name  )
<option value="{{$value->id}}">{{ $value->country_name }}</option>
@endif
@endforeach


</select>
</div>


<label>Position<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_position" type="text" name="position" onkeyup="AllowonlyText(event,'cont_position')"  value="{{ $applications->cont_position }}"  />
<label>City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>                            
<input class="form-control" id="cont_city"type="text" name="city"  onkeyup="AllowonlyText(event,'cont_city')" value="{{ $applications->cont_city }}"  /> 
<label>Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_address_line_one" type="text" name="address_line_one" value="{{ $applications->cont_line_one_address}}"  />
<label>Address Line Two</label> 
<input class="form-control" id="cont_address_line_two" type="text" name="address_line_two" value="{{ $applications->cont_line_two_address }}"   />


<div id="cont_response_email"></div>
     <div id="cont_response_email_success" class="alert alert-success" style="display:none"></div>
	 <div id="cont_response_email_danger" class="alert alert-danger" style="display:none"></div>
     <div id="cont_response_email_warning" class="alert alert-warning" style="display:none"></div>
<label>Email<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_email" type="email" name="email"  value="{{ $applications->cont_email }}" 
  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_contact')}}','cont_response_email','save_supplier_info','cont_email')"
   />

<label><i class="fas fa-phone fa-2xs" id="cont_response_tele"> {{ $country_contact_info->International_dialing }}</i> Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input  class="form-control" id="cont_tele" type="number" name="telephone" min="0"  onkeyup="AllowonlyTex_Telet(event,'cont_tele')" value="{{ $applications->cont_telephone}}" />
    
 

<div id="cont_response_website_url"></div>
     <div id="cont_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
     <div id="cont_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
     <div id="cont_response_website_url_success" class="alert alert-success" style="display:none"></div>
     <!--<label>Website Url</label> 
     <input class="form-control" id="cont_webiste_url" type="url" name="webiste_url"   onkeyup="valdiate_url(this.value,'cont_response_website_url','{{url('/Validate/url/customer_supply')}}','save_supplier_info')" />
    -->
                                    </div>
                                    </div>
                                    </div> 
                         
                                    <input id="contact_id"   name="contact_id"    value="{{ $applications->cont_id}}"   hidden/>
<input id="supplier_id"   name="supplier_id"    value="{{ $applications->company_suppliers_id}} "  hidden/>
@if($applications->trade_name == '')
<!-- <span id="cs_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_supplier_info"       ></span> -->
<button  style="display:block;position: absolute;left: 45%; top: 85%;"   type="button" class="btn btn-success _app" value="Save"    id="save_supplier_info"   >Save </button>
@else
<!-- <span id="cs_update"  style="display:block"> <input type="button" class="save action-update" value="Update"    id="update_supplier_info"      ></span> -->
<button   style="display:block; position: absolute;left: 45%; top: 85%;"   type="button" class="btn btn-success _app" value="Save" value="Update" id="update_supplier_info" > Update </button>
<button  style="display:block;" type="button" name="next" class="btn btn-primary  app_recep_next_companyinfo" value="Next Step"  id="next_application" /> Next Step </button>

@endif
<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
<input type="button" name="next" class="next action-button" id="cs_next_button" value="Next Step"   > -->
<br><br><br><br><br><br><br> <br> <br> 
<button  style="display:block;" type="button" name="next" class="btn btn-secondary app_recep_previous  app_recep_previous_companyinfo" id="previous_button_application" />  Previous </button>
<button  style="display:none;" type="button" name="next" class="btn btn-primary  app_recep_next_companyinfo" value="Next Step"  id="next_application" /> Next Step </button>

<!--<button type="button" class="btn btn-danger toastrDefaultError">Launch Error Toast </button>  -->                      
                         
                          
                         
                          </fieldset>
                          <!------------------------------   -->
                          
<fieldset    @php if($Last_Element == 2) { echo "style='display:block'"; }  else  {}    @endphp    id="Agent_wizard">

@php
 if($agent_name == 1) 
 {
     foreach($agent_contact_info as $agent_contact_info) {} 


 $agent_trade_name= $agent_contact_info->business_name;
 $agent_state=$agent_contact_info->agent_state;
 $agent_address_line_one=$agent_contact_info->agent_address_line_one;
 $agent_address_line_two=$agent_contact_info->agent_address_line_two;
 $agent_telephone = $agent_contact_info->telephone;

 $agent_city=$agent_contact_info->agent_city;
 $agent_postal_code=$agent_contact_info->agent_postal_code;$agent_country_code=$agent_contact_info->agent_country_code;
 $agent_telephone=$agent_contact_info->agent_telephone;$agent_webiste_url=$agent_contact_info->agent_webiste_url;
 $agent_email = $agent_contact_info->agent_email;$agent_cont_id= $agent_contact_info->cont_id;$agent_id= $agent_contact_info->agent_id;

 $agent_contact_person_first_name=$agent_contact_info->cont_first;
 $agent_contact_person_middle_name=$agent_contact_info->cont_middle;
 $agent_contact_person_last_name=$agent_contact_info->cont_last;

 
 $agent_contact_person_position=$agent_contact_info->cont_position;

 $agent_contact_person_city=$agent_contact_info->cont_city;$agent_contact_person_address_line_one=$agent_contact_info->cont_line_one;
 $agent_contact_person_address_line_two=$agent_contact_info->cont_line_two;
 $agent_contact_person_postal_code=$agent_contact_info->cont_postal_code;
 $agent_contact_person_telephone=$agent_contact_info->cont_tele;
 $agent_contact_person_fax = $agent_contact_info->cont_fax;
 $agent_contact_person_email=$agent_contact_info->cont_email;

}
 else
 {
    $agent_contact_person_fax = $agent_cont_id= '';$agent_id= '';$agent_trade_name='';$agent_state="";$agent_address_line_one="";$agent_address_line_two="";$agent_city="";$agent_postal_code="";$agent_country_code="";$agent_telephone="";$agent_webiste_url="";$agent_email="";$agent_contact_person_first_name="";$agent_contact_person_middle_name="";$agent_contact_person_last_name="";$agent_contact_person_position="";$agent_contact_person_city="";$agent_contact_person_address_line_one="";$agent_contact_person_address_line_two="";$agent_contact_person_postal_code="";$agent_contact_person_telephone="";$agent_contact_person_webiste_url="";$agent_contact_person_email="";}
 @endphp
 
 @php if($agent_name == 1)  $action_button_ag ='update_agent_info';   else  $action_button_ag='save_agent_info';  @endphp


                        
 @php
 if($agent_name == 1) 
 {

foreach($agent_contact_info as $agent_contact_info) {} foreach($agent_contact_County_info as $agent_contact_County_info)  {}

 }

 @endphp


    <div class="form-card">

    <div class="container">   
    <div class="row">
    <div class="col-12 col-sm-6"> 
    <h2 class="fs-title">Local Authorized Agent

    <i  style="color:blue;cursor:pointer;font-size:15px;" 
   title="Please visit Guidance for the Authorization of Local Agents for further guidance."
    class="fas fa-info-circle fa-0">  </i>

    </h2> 

    <label>Business Name  <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>

  <input value="{{ $agent_trade_name }}"  class="form-control" id="ag_trade_name" type="text" name="ag_trade_name" placeholder=""    required>

                                
                                
                                    <label> Country <i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
                                    <select  class="form-control" name="country_id" id="ag_country"  required  onchange="fetch_tele(this.value,'age_response_tele','{{url('/get_tele_code/tele_code')}}')">
                                    <!--<option value="0" disable="true" selected="true">Select Country</option>-->
                                    <option value="68">Eritrea</option>
                                    </select>
                                    <br>
                                    <label> Postal Address <i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
                                    <input   class="form-control" value="{{ $agent_postal_code}}"  id="ag_postal_code" type="text" name="postal_code" value=""  required>
                                    <label> City <i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
                                    <input   class="form-control"  value="{{$agent_city}}" id="ag_city" type="text" name="city" value=" "    required>
                                    <label hidden> State  </label>
                                    <input   class="form-control"  value="{{$agent_state}}" id="ag_state" type="hidden" name="state" value="" /> 
                                    <label> Address line one <i style="color:red;cursor:pointer;font-size:20px;">*</i>   </label>
                                    <input  class="form-control" value="{{$agent_address_line_one}}" id="ag_address_line_one" type="text" name="address_line_one" value="" />
                                    <label> Address line two  </label>
                                    <input   class="form-control" value="{{$agent_address_line_two}}" id="ag_address_line_two" type="text" name="address_line_two"  value="" />
                                    <div id="age_response_email"></div>
                                    <div id="ag_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="ag_response_email_dangerr" class="alert alert-danger" style="display:none"></div>
                                    <div id="ag_response_email_warning" class="alert alert-warning" style="display:none"></div>
                                    <label> Email <i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
                                    <input   value="{{ $agent_email }}"class="form-control" id="ag_email" type="email" value="" name="email"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/local_agent')}}','ag_response_email','{{  $action_button_ag  }}','ag_email')"/>
                                    <i class="fas fa-phone fa-2xs" id="age_response_tele">+291</i> 
                                    <label> Phone Number <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
                <input   class="form-control"  onkeyup="AllowonlyText_Tele(event,'ag_tele')"  value="{{ $agent_telephone   }}" id="ag_tele" type="number" min="0" value="" name="telephone"  />
                <div id="age_response_website_url"></div>
                <div id="age_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
                <div id="age_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
                <div id="age_response_website_url_success" class="alert alert-success" style="display:none"></div>
                <label> Web site Url </label>
                <input   class="form-control" id="ag_website_url"  value="{{ $agent_webiste_url }}" placeceholder="Website URL"   onkeyup="valdiate_url(this.value,'age_response_website_url','{{url('/Validate/url/customer_supply')}}','{{  $action_button_ag  }}')"/>
                <label>Fax</label>
                <input  class="form-control" id="cont_ag_fax"   value="{{ $agent_contact_person_fax }}" type="text" name="fax"  />

                </div>

                       <div class="col-12 col-sm-6"> 
                       <h2 class="fs-title"> Contact  Person
                                   
                                   <i  style="color:blue;cursor:pointer;font-size:15px;" 
   title="Please ensure that the contact person you have listed is an active employee of the local agent company. The contact person will be responsible for all the communications between the National Medicines and Food Administration (NMFA) and the local agent. "
    class="fas fa-info-circle fa-0">  
    </i>

                                   </h2> 
                                   <label> First Name <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
    <input value="{{ $agent_contact_person_first_name }}" onkeyup="AllowonlyText(event,'cont_ag_first_name')" class="form-control"  id="cont_ag_first_name" type="text" name="first_name"    required>
                                    <label> Middle Name </label> 
    <input value="{{ $agent_contact_person_middle_name}}" class="form-control"  onkeyup="AllowonlyText(event,'cont_ag_middle_name')" id="cont_ag_middle_name" type="text" name="middle_name"    required>
                                    <label> Last Name <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
   <input value="{{ $agent_contact_person_last_name }}"  class="form-control" onkeyup="AllowonlyText(event,'cont_ag_last_name')" id="cont_ag_last_name" type="text" name="last_name"    required>
                                    <label> Position <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
    <input  value="{{ $agent_contact_person_position }}"  onkeyup="AllowonlyText(event,'cont_ag_position')" class="form-control" id="cont_ag_position" type="text" name="position"  />
                                    <label> Country <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
                                    <div class="input-group mb-3">
                                    <select class="form-control select2bs4" name="country_id" id="cont_ag_country_id"  required   onchange="fetch_tele(this.value,'cont_age_response_tele','{{url('/get_tele_code/tele_code')}}')">
                                    <option value="68" ="true" selected="true">Eritrea</option>
                                    </select>
                                    </div>
                                    
                                    <label> City <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
 <input value="{{ $agent_contact_person_city}}" class="form-control" id="cont_ag_city"type="text" name="city"  onkeyup="AllowonlyText(event,'cont_ag_city')"  /> 
                                    <label> Address Line One <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
    <input value="{{ $agent_contact_person_address_line_one }}"  class="form-control" id="cont_ag_address_line_one" type="text" name="address_line_one"  />
    <label> Address Line Two </label>
    <input value="{{ $agent_contact_person_address_line_two }}"  class="form-control" id="cont_ag_address_line_two" type="text" name="address_line_two"  />
    <label> Email <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
    <div id="cont_ag_response_email"></div>
    <div id="cont_ag_response_email_success" class="alert alert-success" style="display:none"></div>
	<div id="cont_ag_response_email_danger" class="alert alert-danger" style="display:none"></div>
    <div id="cont_ag_response_email_warning" class="alert alert-warning" style="display:none"></div>
    <input value="{{  $agent_contact_person_email}}" class="form-control" id="cont_ag_email" type="email" name="email"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_contact')}}','cont_ag_response_email','save_agent_info','cont_ag_email')" />
                                
   <label> Phone Number <i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
   <i class="fas fa-phone fa-2xs" id="cont_age_response_tele">+291</i> 
   <input onkeyup="AllowonlyText_Tele(event,'cont_ag_tele')"   value="{{ $agent_contact_person_telephone }}" class="form-control" id="cont_ag_tele"  name="telephone"    type="number" min="0"/>

   <div id="cont_age_response_website_url"></div>
   <div id="cont_age_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
   <div id="cont_age_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
   <div id="cont_age_response_website_url_success" class="alert alert-success" style="display:none"></div>
    <!--<input class="form-control" id="cont_ag_webiste_url" type="url" name="webiste_url" placeholder="Website URL" onkeyup="valdiate_url(this.value,'cont_age_response_website_url','{{url('/Validate/url/customer_supply')}}','save_agent_info')" />-->
    </div> 
    </div>
    </div>
    </div>

<input id="agent_contact_id"   name="contact_name_agent"    value="{{ $agent_cont_id }}"   hidden/>
<input id="agent_id"   name="agent_name"    value="{{ $agent_id }}"  hidden/>
<br><br><br><br>

@if($agent_name==0) 
<!-- <span  id="ag_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_agent_info"       ></span> -->
<button  style="position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save"    id="save_agent_info"   >Save </button>

<button   style="display:none; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"  id="update_agent_info" > Update </button>


@else
<!-- <span  id="ag_update"  style="display:block"> <input type="button" class="save action-update" value="Update"    id="update_agent_info"      ></span> -->
<button   style="display:block; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"  id="update_agent_info" > Update </button>
<button  style="display:block;position: absolute;left: 45%; top: 95%;"   type="button" name="next" class="btn btn-primary app_app next_button_agent" value="Next Step"     id="agent_next_button" /> Next Step </button>

@endif
                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input   type="button" name="next" class="next action-button" value="Next Step"  id="agent_next_button" /> -->
<button  style="position: absolute;left: 35%; top: 95%;"   type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button  style="display:none;position: absolute;left: 45%; top: 95%;"   type="button" name="next" class="btn btn-primary app_app next_button_agent" value="Next Step"     id="agent_next_button" /> Next Step </button>


                            </fieldset>

                  <!--------------------------------   -->

<fieldset   @php if($Last_Element == 3) { echo "style='display:block'"; }  else  {}    @endphp   id="product_details_wizard">

 @php
 
 if($med_id == 1)   
 {
     
     foreach($product_details_general as $product_details) {} 
     
        $product_trade_name =     $product_details->product_trade_namme;
       
        $medicine_product_name =  $product_details->medicine_product_name;
        $medicine_id  =        $product_details->medicine_id;
        $dosage_name  =      $product_details->dosage_name;
        $dosage_id  =        $product_details->dosage_id;
        $route_administration_id =   $product_details->route_id;
        $route_administration_name = $product_details->route_name;
        $description =               $product_details->description;
        $pharmaco_therapeutic_classification = $product_details->pharmaco_therapeutic_classification;
        $strength_amount_strength_unit =  $product_details->strength_amount_strength_unit;
        $storage_condition =         $product_details->storage_condition;
        $shelf_life_amount =         $product_details->shelf_life_amount;
        $shelf_life_unit =           $product_details->shelf_life_unit;
        $proposed_shelf_life_amount = $product_details->proposed_shelf_life_amount;
        $proposed_shelf_life_unit =   $product_details->proposed_shelf_life_unit;
        $proposed_shelf_life_after_reconstitution_amount = $product_details->proposed_shelf_life_after_reconstitution_amount;
        $proposed_shelf_life_after_reconstitution_unit =  $product_details->proposed_shelf_life_after_reconstitution_unit;
        $visual_description =        $product_details->visual_description;
        $commercial_presentation =   $product_details->commercial_presentation;;
        $container =                 $product_details->container;
        $packaging =                   $product_details->packaging;
        $category_use =              $product_details->category_use;
        $medicine_id = $product_details->medicine_id;
        $medicinal_id = $product_details->_id;

 }
else{
        $product_trade_name = '';
        $medicine_product_name =  '';
        $medicine_id = '';
        $dosage_name  = '';
        $dosage_id  = '';
        $medicine_id  =  '';
        $route_administration_id = '' ;
        $route_administration_name ='';
        $description = '';
       
      
        $pharmaco_therapeutic_classification = '';
        $strength_amount_strength_unit = '';
        $storage_condition ='';
        $shelf_life_amount = '';
        $shelf_life_unit = '';
        $proposed_shelf_life_amount = '';
        $proposed_shelf_life_unit = '';
        $proposed_shelf_life_after_reconstitution_amount = '';
        $proposed_shelf_life_after_reconstitution_unit = '';
        $visual_description = '';
        $commercial_presentation ='';
        $container = '';
        $packaging = '';
        $category_use =  '';
        $medicine_id = '';
        $medicinal_id = '';
}
@endphp
   
   
<div class="form-card">
    <h2 class="fs-title">Product Details </h2>
    <div class="container">   
    <div class="row">
    <input type="hidden" name="if_other" id="if_other" value=""/>
    <div class="col-12 col-sm-6">
    <label >Generic/Approved/International Non-proprietary Name(s) <i style="color:red;cursor:pointer;font-size:20px;">*</i>
   <i  style="color:blue;cursor:pointer;font-size:15px;" 
    title="If the product name is not found in the list of products below, please select the option ‘Other’ and specify the generic/approved/international non-proprietary name of the product in the space provided. "
    class="fas fa-info-circle fa-0">  
    </i>
     </label>  
    <select  style="display:block;width:350px;" class="form-control select2bs4"   id="generic_approved_name"   name="Application_type" required onchange="get_other_value(this.value,'generic_approved_name_other')">
   
    <option value="{{ $medicine_id }}" disable="true" selected='true' > {{ $medicine_product_name }}</option>
    <option value="919" >Other</option>
  @foreach ($medicines as $key => $value)
  
     @if($medicine_id != $value->id)
     @if($value->id != 919)
     <option value="{{$value->id}}">{{ $value->product_name }}</option>
     @endif
     @endif
  
     @endforeach
     </select>


     <br> <br>

 <div style="display:none;color:orange"  id="international_name" >
  <label>Generic/Approved/International Non-proprietary Name(s) (if Other): </label>  
    &nbsp;&nbsp;
    <input class="form-control" id="generic_approved_name_other"   type="text" name="generic_approved_name"   />
</div>
<br><br>


   <label>Brand Name <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
    &nbsp;&nbsp;
    <input value="{{  $product_trade_name  }}"  class="form-control" id="product_trade_name"  type="text" name="product_trade_name"   />
        <label>Dosage Forms <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
             &nbsp;&nbsp;
            <select style="display:block;width:300px;" class="form-control select2bs4" id="dosage_form_id"  name="dosage_form_id"  required>
            <option value="{{$dosage_id}}" disable="true" selected="true">{{$dosage_name}}</option>
            @foreach ($dosage_forms as $key => $value)
            @if($dosage_id != $value->id)
            <option value="{{$value->id}}">{{ $value->name }}</option>
             @endif
                                    @endforeach
                                    </select>
                                    <br>  <br>  <br>
            <label>Route of Administration <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
            <select  style="display:block;width:200px;" class="form-control select2bs4" id="route_administration_id" name="route_administration_id"   required>
<option value="{{ $route_administration_id }}"  disable="true" selected="true">{{$route_administration_name}}</option>
           
@foreach ($route_administrations as $key => $value)
@if($route_administration_id != $value->id)
<option value="{{$value->id}}">{{ $value->name }}</option>
@endif
@endforeach
            </select>
<br><br/>
            <label hidden >Description <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
            <input  value="{{ $description }}"  hidden class="form-control" id="description" type="text" name="description"  required/>


            <span  style="display:none" id="strength_unit">
            <label hidden >Strength Amount and Strength unit  </label>  
            <textarea hidden value="1" class="form-control" id="strength_amount_unit"  name="strength_amount_unit" required />
            </textarea>
            </span>
         
            <br>
            <label>Storage Condition <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input value="{{ $storage_condition  }}"class="form-control" id="storage_condition" type="text" name="storage_condition"  required/>
            
            <label >Shelf life (In months only)  <i style="color:red;cursor:pointer;font-size:20px;">*</i> <label>  
            <input  value="{{ $shelf_life_amount }}"  onkeyup="AllowonlyText_Tele(event,'shelf_life_amount')" 
             class="form-control" id="shelf_life_amount" type="number" min="1"  placeholder=""   name="shelf_life_amount"  required/>
            
            <label hidden >Shelf life Unit</label>  
            <select hidden  class="form-control" id="shelf_life_unit" type="number" name="shelf_life_unit" required>
            <option value="{{ $shelf_life_unit }}" selected="true" >{{ $shelf_life_unit}} </option>
            @if($shelf_life_unit != 'Days')
            <option value="Days" >Days</option>
            @endif
            @if($shelf_life_unit != 'Months')
            <option  selected value="Months"  >Months</option>
            @endif
            </select>
            <br>

             <label>Pharmacotherapeutic Classification (Anatomic-Therapeutic Classification system) <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
             <input  value = "{{ $pharmaco_therapeutic_classification }}"class="form-control" id="pharmaco_therapeutic_classification" type="text" name="pharmaco_therapeutic_classification"  required/>
        <!--<input class="form-control" id="shelf_life_unit" type="number" name="shelf_life_unit"  required/>  -->


     </div>
     
   

             <div class="col-12 col-sm-6">
             <label>Proposed Shelf life (In months only) </label>  
             <input onkeyup="AllowonlyText_Tele(event,'proposed_shelf_life_amount')"  value="{{  $proposed_shelf_life_amount}}"class="form-control" min="1" id="proposed_shelf_life_amount" type="number" name="proposed_shelf_life_amount"  required/>
           
            <label hidden >Proposed Shelf life Unit</label>  
            <select hidden  style="display:block" class="form-control" id="proposed_shelf_life_unit"  name="proposed_shelf_life_unit"  required  onchange="proposed_shelf(this.value,)">
           <option  selected="true" value="{{ $proposed_shelf_life_unit}}"> {{ $proposed_shelf_life_unit}} </option>
           @if($proposed_shelf_life_unit!= 'Month')
            <option selected  value="Month" >Month</option>
            @endif
            @if($proposed_shelf_life_unit != 'Years')
            <option value="Years"  >Years</option>
            @endif
            @if($proposed_shelf_life_unit != 'not_applicable')
            <option value="not_applicable"  >Not Applicable</option>
            @endif
            </select>

          <!--  <input class="form-control" id="proposed_shelf_life_unit" type="text" name="proposed_shelf_life_unit"  placeholder="in-months" required/>-->
            <br>
            <label>Proposed Shelf Life After Reconstitution </label>  
            <input  value="{{$proposed_shelf_life_after_reconstitution_amount}}"   class="form-control" id="proposed_shelf_life_after_reconstitution_amount" type="text" name="proposed_shelf_life_after_reconstitution_amount"  required/>
            
            <label hidden >Proposed Shelf Life After Reconstitution Unit <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <select  style="display:none" class="form-control" id="proposed_shelf_life_after_reconstitution_unit"  name="proposed_shelf_life_after_reconstitution_unit"  required  onchange="proposed_shelf(this.value,)">
           <option selected="true" disabled="true"  value="{{ $proposed_shelf_life_after_reconstitution_unit }}"> {{ $proposed_shelf_life_after_reconstitution_unit }}  </option>
           @if($proposed_shelf_life_after_reconstitution_unit != 'Days')
            <option selected value="Days" >Days</option>
            @endif
            @if($proposed_shelf_life_after_reconstitution_unit != 'Months')
            <option selected value="Months"  >Months</option>
            @endif
            </select>
            <br>
            <label>Visual Description  <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input value="{{ $visual_description }}"class="form-control" id="visual_description" type="text" name="visual_description" required />
            
            <label>Commercial Presentation <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
            <input value="{{ $commercial_presentation }}" class="form-control" id="commercial_presentation" type="text" name="commercial_presentation"  required/>
            
            <label>Container, closure and administration devices <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input value="{{$container }}"  class="form-control" id="container" type="text" name="container" required />
            
            <label>Packaging and pack size <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input  value="{{$packaging }}"class="form-control" id="packaging" type="text" name="packaging" required />
            
            <label>Category of Use <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
             &nbsp;&nbsp;
            <select  style="display:block" class="form-control" id="category_use"  name="category_use"  required>
            <option value="{{ $category_use }}" disable="true" selected="true">{{ $category_use }}</option>
            @if($category_use != 'POM (Prescription only medicine)')
            <option  value="POM (Prescription only medicine)" >POM (Prescription only medicine) </option>
            @endif
            @if($category_use != 'P (Pharmacy Medicine)')
           <option value="P (Pharmacy Medicine)">   P (Pharmacy Medicine)  </option>
            @endif
            @if($category_use != 'OTC (Over The Counter medicine)')
            <option value= "OTC (Over The Counter medicine)" >OTC (Over The Counter medicine)  </option>
            @endif
            @if($category_use != 'Controlled Substances')
            <option value= "Controlled Substances" >Controlled Substances </option>
            @endif
 @if($category_use != 'Hosiptal/Health Facilities Only Medicines')
            <option value= "Hosiptal/Health Facilities Only Medicines" >Hosiptal/Health Facilities Only Medicines  </option>
    @endif
            </select>
     </div>
     </div>
     </div>
     </div>
            
<input id="product_detail_master_id"  name="product_detail_name" value="{{  $medicinal_id }}"   hidden/>
<br><br><br><br>

@if($med_id == '')   
<!-- <span  id="product_detail_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_product_detail_info"       ></span> -->
<button  style="position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save"    id="save_product_detail_info"   >Save </button>
<button  style="display:none; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"   id="update_product_detail_info"  > Update </button>

@else
<!-- <span  id="product_detail_update"  style="display:block"> <input type="button" class="save action-update" value="Update"    id="update_product_detail_info"      ></span> -->
<button  style="display:block; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"   id="update_product_detail_info"  > Update </button>
<button   style="display:block;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_detail_next_button" /> Next Step </button>

@endif

<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
<input type="button" name="next" class="next action-button" value="Next Step"  id="product_detail_next_button" />
     -->
<button   style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button   style="display:none;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_detail_next_button" /> Next Step </button>

</fieldset>



<!------------------------------------------------------------------------------------------>


<fieldset   @php if($Last_Element == 4 ) { echo "style='display:block'"; }  else  {}    @endphp  id="composition_pro">
        
        
        @php
        if($product_composition_name == 1)   
        {
       
            foreach($product_composition_info as $composition_info) {}
           $composition_name=$composition_info->composition_name;
           $medical_product_id = $composition_info->medical_product_id;
           $quantity=$composition_info->quantity;
           $reason=$composition_info->reason;
           $reference_standard=$composition_info->reference_standard;
           $type=$composition_info->type;
           $id= $composition_info->id;
        }
       
       
       else{
           $composition_name='';
           $medical_product_id = '';
           $quantity='';
           $reason='';
           $reference_standard='';
           $type='';
           $id='';
       
       }
        @endphp
        <div class="form-card">
         <h2 class="fs-title">Product Composition 
         <i  style="color:blue;cursor:pointer;font-size:15px;" 
             title="Please indicate the complete qualitative and quantitative composition of the product per unit dosage form (e.g. per tablet, capsule, 2ml etc.). Please replicate this section, as applicable, to indicate all constituents of the product"  
             class="fas fa-info-circle fa-0">  
        </i>

         </h2>

    <div class="container">
    <div style="display:block"><span style="display:none" id="id_update_compostion"> </span>
    <label>Constituents <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
    <div class="col-9"> 
    <select class="form-control" id="composition_type_composition" name="composition_type_composition">
    <option   value="" >  </option>
    <option  value="API">API  </option>
    <option value="Excipients" > Excipient </option>
    <option value="Solvents"> Solvents      </option>
    </select>
    </div>
            <label>Name (INN) <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input  value="" class="form-control" onkeyup="AllowonlyText(event,'name_inn_text_composition')"  id="name_inn_text_composition" type="text" name="name_inn_text_composition"  />   
            </div>   
            <label>Quantity <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input value="" class="form-control" id="quantity_composition" type="text" name="quantity_composition"  />
            
            <label>Reason for Inclusion <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input  value="" class="form-control" id="reason_inclusion_composition" type="text" name="reason_inclusion_composition"  />
          
            <label>Reference standard <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
    <!--<input class="form-control" id="reference_standard_composition" type="text" name="reference_standard_composition"  />-->
    <input list="browsers"  class="form-control" id="reference_standard_composition"  name="reference_standard_composition">
    <datalist id="browsers" >
    <option   value="" selecetd="true">  </option>
    <option  value="United States Pharmacopeia (USP)"> United States Pharmacopeia (USP)</option>
    <option  value="International pharmacopoeia (Ph.Int)">International pharmacopoeia (Ph.Int)</option>
    <option value="British Pharmacopoeia (BP)" >British Pharmacopoeia (BP)</option>
    <option value="European Pharmacopoeia (Ph.Eur.)"> European Pharmacopoeia (Ph.Eur.) </option>
    </datalist>
          
  
    <br/>
 

<span   id="add_new_composition"  title="Clear fields" class="btn btn-warning btn-sm"> <i class="fas fa-minus" > </i> <span style="font-style: normal; font-family: 'Times New Roman', Times, serif"> Clear </span> </span>
<br><br>
<span  id="createNewCompostion_save"  style="display:block"> 
    <span type="button" class="btn btn-success btn-sm" title="Save entries for Product Compostion" title="Save composition"  id="createNewCompostion_save" ><i class="fas fa-save" > </i>   Save </span></span>
    <span  id="createNewCompostion_update"  style="display:none"> 
    <!-- <button   id="add_new_composition"  class="btn btn-warning"> <i class="fas fa-minus"> clear </i> </button> -->

    <span type="button" class="btn btn-success btn-sm" value="Update" title="Edit Composition" id="createNewCompostion_update" ><i class="fas fa-edit" > </i> Update </span></span>
  
    <div class="container">
    <div class="table-responsive" style="display:block">          
     <table class="table"  id="table_product_compostion">
    
    <thead>
    

    <tr>
                 <th>ID </th>   
                 <!-- <th>ApplicationID</th> -->
                <th>Composition Type</th>
                <th>Name (INN)</th>
                <th>Quantity</th>
                <th>Reason for Inclusion</th>
                <th>Reference Standard</th>
                <th>Action</th>
                </tr>
               </thead>
               @if($product_composition_name == 1) 
               <tbody id='renderd_product_composition_table' {{ $i=1 }}>
               @foreach($product_composition_info as $product_composition)
               <tr>
                        <td>{{ $i++ }}</td>   
                        <!-- <th>{{ $product_composition->application_id  }}</th> -->
                       <td>{{ $product_composition->type}}</td>
                       <td>{{ $product_composition->composition_name}}</td>
                       <td>{{ $product_composition->quantity }}</td>
                       <td>{{ $product_composition->reason }}</td>
                       <td>{{ $product_composition->reference_standard }}</td>
                      
                       <td> 
    
    <button  class="btn btn-warning btn-sm editcomposition"    value='{{ $product_composition->application_id }} '  id=''  data-di="{{ $product_composition->id }}"  ><i class='fas fa-pencil-alt'></i></button>
    <br/> <br/>
    <button class='btn btn-danger btn-sm'  onclick='Delete_composition({{ $product_composition->id }})' ><i class='fas fa-trash'></i></button> 
            </td>
               </tr>
            @endforeach
               </tbody>
               @else
               <tbody id='renderd_product_composition_table' >
               </tbody>
               @endif
           </table>
</div>
</div>


</div>
      </div>


                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"   id="next_composition" /> -->

<button  style="position:absolute;left: 35%; top: 94%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
@if(@$i >=1)
<button style="display:block;position:absolute;left: 45%; top: 94%;width:auto"  type="button" name="next" class="btn btn-primary app_app" value="Next Step"  id="next_composition" /> Next Step </button>
@else
<button style="display:none;position:absolute;left: 45%; top: 94%;width:auto"  type="button" name="next" class="btn btn-primary app_app" value="Next Step"  id="next_composition" /> Next Step </button>
@endif
<br><br>
                            </fieldset>
                          



                                   </fieldset>

<!---------------  ------------------------------------------------------------>
 <fieldset   @php if($Last_Element == 5) { echo "style='display:block'"; }  else  {}    @endphp   id="product_manufacturers_wizard">


 @php
 if($manufacturers_name == 1)   
 {

     foreach($product_manufacturers_info as $product_manufacturers) {}
    $manufacturer_name=   $product_manufacturers->manufac_name;
  //$medical_product_id = $product_manufacturers->medical_product_id;
    $city=                $product_manufacturers->city;
    $country_id=                $product_manufacturers->country_id;
    $country_name=                $product_manufacturers->country_name;
    $state=              $product_manufacturers->state;
    $addressline_one  =  $product_manufacturers->addressline_one;
    $addressline_two  =  $product_manufacturers->addressline_two ;
    $telephone=        $product_manufacturers->telephone;
    $country_code=        $product_manufacturers->International_dialing	;
  

   
    $activity=        $product_manufacturers->activity;
    $block=        $product_manufacturers->block;
    $unit=        $product_manufacturers->unit;
    $postal_code= $product_manufacturers->postal_code;
    $manuf_id = $product_manufacturers->manufac_id;
 }


else{
    
    $manufacturer_name=  "";
    $medical_product_id ="";
    $country_id="";
    $country_name= "";
    $city="";
    $state= "";
    $addressline_one  =  "";
    $addressline_two  = "";
    $telephone="";
    $country_code= "";
    $webiste_url=  "";
    $email= "";
    $activity= "";
    $block= "";
    $unit="";
    $postal_code="";
    $manuf_id = "";

}
 @endphp


<div class="form-card">
<div class="container">
  <h2 class="fs-title">Product Manufacturer(s)
  <i  style="color:blue;cursor:pointer;font-size:15px;" 
        title="When different activities of manufacturing of the given product are carried out at different manufacturing sites, please provide the below particulars for each site involved in manufacturing of the product (including final product release if different from the manufacturer). Please replicate this section, as applicable, to indicate all sites involved in manufacturing of the product."
        class="fas fa-info-circle fa-0">  
    </i>
 <span class="badge"  id="id_for_update" style="display:none"></span> 
  </h2>



  </div>

    <div class="container">  
    <div class="form-group"> 
    <div class="row">
    
    <div class="col-12 col-sm-6">
    <label>Manufacturer Name <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value="" class="form-control"   id="manufacturer_name" type="text" name="manufacturer_name"  />
    
        <label>Country <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <select onchange="fetch_tele(this.value,'manu_response_tele','{{url('/get_tele_code/tele_code')}}')" 
        class="form-control" style="width: 100%;" name="manufacturer_country_id" id="manufacturer_country"  required>
        <option value="0" disable="true" selected="true">=== Select Country ===</option>

                        @foreach ($countries as $key => $value)
                        <!-- @if($country_id != $value->id) -->
                        <option value="{{$value->id}}">{{ $value->country_name }}</option>
                        <!-- @endif -->
                        @endforeach
        </select>
        <br><br>

        <label>State</label>  
        <input value=""class="form-control"  onkeyup="AllowonlyText(event,'manufacturer_state')" id="manufacturer_state" type="text" name="manufacturer_state"  />
      

           <label>City <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText(event,'manufacturer_city')" value="" class="form-control" id="manufacturer_city" type="text" name="manufacturer_city"  />


    <i class="fas fa-phone fa-2xs" id="manu_response_tele"></i> &nbsp;&nbsp;
        <label>Phone Number <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText_Tele(event,'manufacturer_tele')"  value="" class="form-control" id="manufacturer_tele"  type="number" min="0"  name="manufacturer_tele"  />

  </div>
       
       <div class="col-12 col-sm-6">
       <div style="display:block">
        <label>Postal Address <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value="" class="form-control"   id="manufacturer_postal_code" type="text" name="manufacturer_postal_code"  />
        </div>

        <label>Address line one <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value="" class="form-control" id="manufacturer_add_line_one" type="text" name="manufacturer_add_line_one"  />
      
        <label>Address line two</label>  
        <input value="" class="form-control" id="manufacturer_add_line_two" type="text" name="manufacturer_add_line_two"  />
    <div style="display:none">
     <label>Website URL</label>  
     <div id="manu_response_website_url"></div>
     <div id="manu_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
     <div id="manu_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
     <div id="manu_response_website_url_success" class="alert alert-success" style="display:none"></div>
    <input class="form-control" value="http://steclezion@gmail.com"  id="manufacturer_url" type="url" name="manufacturer_url"  onkeyup="valdiate_url(this.value,'manu_response_website_url','{{url('/Validate/url/customer_supply')}}','save_product_manufacturer_save')"/>
      </div>

        
        <div style="display:none">
        <label>Email <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
                                    <div id="manu_response_email"></div>
                                    <div id="manu_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="manu_response_email_danger" class="alert alert-danger" style="display:none"></div>
                                    <div id="manu_response_email_warning" class="alert alert-warning" style="display:none"></div>
        <input class="form-control"  value="Nmfa@gmail.com" onkeyup="Email_Validate(this.value,'{{url('/Validate/email/manufacturers')}}','manu_response_email','save_product_manufacturer_save','manufacturer_email')" id="manufacturer_email" type="email" name="manufacturer_email"  />
        </div>
        <label>Block <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value=""  class="form-control" id="manufacturer_block" type="text" name="manufacturer_block"  />
       

        <label>Unit <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText_Tele(event,'manufacturer_unit')" min="0" value="" class="form-control" id="manufacturer_unit" type="text" name="manufacturer_unit"  />
        

        <label>Activity <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <textarea value="" class="form-control" id="manufacturer_activity" type="text" name="manufacturer_activity"  > </textarea>  
       
    </div>
    </div>
    </div>
    </div>

    <br/>
    <span   id="add_new_manufacturer"  class="btn btn-warning btn-sm"> <i class="fas fa-minus">  </i>  <span style="font-style: normal; font-family: 'Times New Roman', Times, serif">   Clear  </span> </span>
    <br><br>
    <span  id="product_manufacturer_save"  style="display:block"> 
    <!-- <input type="button" class="btn btn-success" value="Save"    id="save_product_manufacturer_save"       ></span> -->
    <button  type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_save"  ><i class="fas fa-save"> </i>   Save </button></span>
    <span  id="product_manufacturer_update"  style="display:none"> 
    <!-- <input type="button" class="btn btn-success" value="Update"    id="updates_product_manufacturer_update"       > -->
    <button type="button" class="btn btn-success btn-sm"    id="updates_product_manufacturer_update"  ><i class="fas fa-edit"> </i>  Update </button></span>
    
    <br>    <br>
    <div class="container">
    <div class="table-responsive" style="display:block">          
     <table class="table" id="table_product_manufacturer_api"  >
    
    <thead>
    <tr>
              
                <th>ID</th>
                <th>Manufacturer Name</th>
                <th>Country</th>
                <th>Postal Address</th>
                <th>Phone Number</th>
                <th>State</th>
                <th>Address Ln1</th>
                <th>Address Ln2</th>
                <th>Activity</th>
                <th>block</th>
                <th>Unit</th>
                 <th>City</th>
                <!-- <th>International Dailing Code</th> -->
                <th width="300px">Action</th>
                </tr>
        </thead>
        @if($manufacturers_name == 1)   
        <tbody id='renderd_manufacturer_table' {{ $i=1 }} >
        @foreach($product_manufacturers_info as $products) 
        <tr  @if($i== 1) style="color:skyblue" title="This is selected information that changes the effect of declaration" @endif >
                <td>{{$i++}} </td>
                <!-- <td>{{$products->application_id}} </td> -->
                <td>{{$products->manufac_name}} </td>
                <td>{{$products->country_name}} </td>
                <td>{{$products->postal_code}} </td>
                <td>{{$products->telephone}} </td>
                <td>{{$products->state}} </td>
                <td>{{$products->addressline_one}} </td>
                <td>{{$products->addressline_two}} </td>
                <td>{{$products->activity}} </td>
                <td>{{$products->block}} </td>
                <td>{{$products->unit}} </td>
                <td>{{$products->city}} </td>
                <!-- <td>{{$products->International_dialing	}} </td> -->
                <td width="300px">
              
<button class='btn btn-warning btn-sm editmanufacturer' data-di='{{$products->mmid}}'  ><i class='fas fa-pencil-alt'></i></button>
           <br/> <br/>
<button class='btn btn-danger btn-sm' onclick='Delete_Manufacturer({{ $products->manu_id }})' ><i class='fas fa-trash'></i></button>
  
</th>
        </tr>


        @endforeach
         </tbody>
         @else
         <tbody id='renderd_manufacturer_table' >
         </tbody>
         @endif
    </table>
</div>
</div>


      </div>


                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"  id="product_manufacturer_next_button" /> -->
<br><br><br>
<button  style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
@if(@$products->manu_id >= 1 )
<button  style="display:block;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_next_button" /> Next Step </button>
@else
<button  style="display:none;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_next_button" /> Next Step </button>
@endif
                          
                            </fieldset>

<!-------------------                        ---->


<fieldset  @php if($Last_Element == 6) { echo "style='display:block'"; }  else  {}    @endphp     id="product_manufacturers_api_wizard">
    @php
    if($api_name  == 1)
    {
   foreach($api_manufacturers_info as  $api_manufacturers) {}
    $api_manufacturer_name_api =   $api_manufacturers->api_name;
}
else
{
    $api_manufacturer_name_api="";

}


@endphp
        <div class="form-card">
        <h2 class="fs-title">API Manufacturer(s) 
        
           <i  style="color:blue;cursor:pointer;font-size:15px;" 
           title="Please indicate the name and complete address of each facility where manufacturing of the Active Pharmaceutical Ingredient (API) occurs, including contractors.  Please replicate this section, as applicable, to indicate all sites involved in manufacturing of the product."
           class="fas fa-info-circle fa-0">  
           </i>
        
        <span class="badge"  id="id_for_update_api" style="display:none"></span></h2>
      
    <div class="container">  
    <div class="form-group"> 
    <div class="row">
    
    <div class="col-12 col-sm-6">
    <label> Manufacturer Name <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control"    id="manufacturer_api_name" type="text" name="manufacturer_name"  />
        

        <label> API Name <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control"    id="manufacturer_api_name_api" type="text" name="manufacturer_name"  />
        

        <label>Country <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
       <select  onchange="fetch_tele(this.value,'manu_api_response_tele','{{url('/get_tele_code/tele_code')}}')"  class="form-control" style="width: 100%;" name="manufacturer_api_country_id" id="manufacturer_api_country"  required>
                       <option value="0" disable="true" selected="true">=== Select Country ===</option>
                        @foreach ($countries as $key => $value)
                        <option value="{{$value->id}}">{{ $value->country_name }}</option>
                        @endforeach
        </select>
        <br><br>
           
        <label>Postal Address <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_api_postal_code" type="text" name="manufacturer_api_postal_code"  />
        <i class="fas fa-phone fa-2xs" id="manu_api_response_tele"></i> &nbsp;&nbsp;
        <label>Phone Number <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" onkeyup="AllowonlyText_Tele(event,'manufacturer_api_tele')"  min="0" id="manufacturer_api_tele" type="number" name="manufacturer_tele"  />
        </div>
<div class="col-12 col-sm-6">
        <label>City <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control"  onkeyup="AllowonlyText(event,'manufacturer_api_city')"  id="manufacturer_api_city" type="text" name="manufacturer_api_city"  />
        <label>State</label>  
        <input class="form-control"  onkeyup="AllowonlyText(event,'manufacturer_api_state')" id="manufacturer_api_state" type="text" name="manufacturer_api_state"  />
      
        <label>Address Line One <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control"  id="manufacturer_api_add_line_one" type="text" name="manufacturer_api_add_line_one"  />
      
        <label>Address Line Two</label>  
        <input class="form-control" id="manufacturer_api_add_line_two" type="text" name="manufacturer_api_add_line_two"  />
 
        <label>Block <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value=""  class="form-control" id="manufacturer_api_block" type="text" name="manufacturer_api_block"  />
       

        <label>Unit <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText_Tele(event,'manufacturer_api_unit')" min="0" value="" class="form-control" id="manufacturer_api_unit" type="text" name="manufacturer_unit"  />
        

    </div>
    </div>
    </div>
    </div>

    <span   id="add_new_api_product_manufacturer"   class="btn btn-warning btn-sm"> <i class="fas fa-minus">  </i>  <span title="Clear All fileds are refill it again" style="font-style: normal; font-family: 'Times New Roman', Times, serif">   Clear  </span> </span>
    <br><br>

    <span  id="product_manufacturer_api_save"  style="display:block">
    <!-- <input type="button" class="save action-buttonn" value="Save"    id="save_product_manufacturer_api_save"       /> -->
    <button type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_api_save" title="Save Button"> <i class="fas fa-save"> </i>    Save </button></span>


    </span>

    <br/>
    
    <span  id="product_manufacturer_api_update"  style="display:none">
    <!-- <input type="button" class="save action-update" value="Update"    id="save_product_manufacturer_api_update"       /> -->
    <button type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_api_update" title="Edit Buttom"> <i class="fas fa-edit"> </i>    Update </button></span>

   </span>

<bre><br>
    <div class="container">
    <div class="table-responsive" style="display:block" >          
     <table class="table"   id="table_manufacturer_api">
    
    <thead>
    <tr>
                <th>ID</th>
                <!-- <th>ApplicationID</th> -->
                <th>Manufacturer Name</th>
                <th>API Name</th>
                <th>Country</th>
                <th>Postal Address</th>
                <th>Phone Number</th>
                <th>City</th>
                <th>State</th>
                <th>Address Ln1</th>
                <th>Address Ln2</th>
                <th>Unit</th>
                <th>Block</th>
                <th width="300px">Action</th>
            </tr>
            @if($api_name == 1)   
            <tbody id='renderd_manufacturer_api_table'  {{$i=1}}>
        @foreach($api_manufacturers_info as $api_manufacturers) 
        <tr >
                <td>{{$i++}}</th>
                <!-- <th>{{$api_manufacturers->application_id}}</th> -->
                <td>{{$api_manufacturers->manufacturer_name}}</td>
                <td>{{$api_manufacturers->api_name}}</td>
                <td>{{$api_manufacturers->country_name}}</td>
                <td>{{$api_manufacturers->postal_code}}</td>
                <td>{{$api_manufacturers->telephone}}</td>
                <td>{{$api_manufacturers->city}}</td>
                <td>{{$api_manufacturers->state}}</td>
                <td>{{$api_manufacturers->addressline_one}}</td>
                <td>{{$api_manufacturers->addressline_two}}</td>
                <td>{{$api_manufacturers->unit}}</td>
                <td>{{$api_manufacturers->block}}</td>
            
                <td width="300px">
              
<span class='btn btn-warning btn-sm editmanufacturer_api'   data-di='{{$api_manufacturers->api_id }}' ><i class='fas fa-pencil-alt'></i></span>
           <br/> <br/>
<span class='btn btn-danger btn-sm' onclick='Delete_manufacture_api({{$api_manufacturers->api_id }})' ><i class='fas fa-trash'></i></span>
</td>
</tr>
@endforeach
</tbody>

         @else
         <tbody id='renderd_manufacturer_api_table' >
         </tbody>
         @endif
    </table>
</div>
</div>


      </div>

<br><br>
                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"  id="product_manufacturer_api_next_button" /> -->
<button  style="position: absolute;left: 35%; top: 93%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
@if(@$api_manufacturers->api_id >=1)
<button   style="display:block;position: absolute;left: 45%; top: 93%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_api_next_button" /> Next Step </button>
@else

<button   style="display:none;position: absolute;left: 45%; top: 93%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_api_next_button" /> Next Step </button>
@endif






                            </fieldset>

<!-------------------                        ---->
                          




<!-------------------                        ---->
<fieldset   @php if($Last_Element == 7) { echo "style='display:block'"; }  else  {}    @endphp   id="decleration_wizard" >
@php
if($decleration_present == 1)
{
foreach($decleration_info as $decleration){}
$decleration_name = $decleration->decname;
$decleration_Qualification = $decleration->qualification;
$decleration_position = $decleration->position;
$decleration_date = $decleration->date;
}
else
{
$decleration_name = '';
$decleration_Qualification = '';
$decleration_position ='';
$decleration_date = '';

}
@endphp

<div class="form-card">
         <h2 class="fs-title">Declaration </h2>
             
<div class="container">
                             
<style>
p.decleration 
{
    padding: 10px;
    text-align: justify;
    font-weight: bold;
    font-family: "Times New Roman", Times, serif;
}
</style>
              <!-- Default box -->
      <div class="card" id="print_decleration">
        <div class="card-header">
          <h3 class="card-title">Declaration  </h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!--<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>-->
          </div>
        </div>
        <div class="card-body">
        
<p class="decleration"> 
I, the undersigned certify that all the information in this form and all accompanying documentation submitted to Eritrea for the
registration of 
 
    
    (
    <span id="d_p_name">  {{ @$get_details_declarations->medicine_product_name }}   </span>  ,
    <span id="d_dname">   {{ @$get_details_declarations->dosage_name      }}   </span>
    )

manufactured at 

  (
    <span id="d_manu_state"> {{ @$get_details_declarations->manufacturer_name }} </span> and 
    <span id="address_manufacture"> {{ @$get_details_declarations->manufacturer_addressline_one }}</span> {{ @$get_details_declarations->manufacturer_addressline_two}}
 ) 
is true and correct. I further certify that I have examined the following statements and I attest to their correctness:- 
</P>


<p class="decleration">
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
          
            <div class="row">
            <div class="col-sm-3">
            <form>
                <div class="card-body">
            <div class="form-check">
         @php
            if( $decleration_present == 1) { $checked='checked disabled '; } else { $checked=''; }     @endphp
            <label class="form-check-label" for="exampleCheck1"><b> I agree  </b></label>
            <input type="checkbox" class="form-check-input"  {{ $checked}} id="customCheckbox1">

        
                  </div>
                  </div>
            </div>
         
         <div class="col-sm-3 no-print">

<!-- <label>I agree <input style="width:20px;position:float-center;" class="form-control" id="customCheckbox1" type="checkbox" name="customCheckbox1"   /></label>   -->
        
        </div>

        
        
      
           </div>
       <div class="col-12 col-sm-6">
        <input value="{{ $decleration_name }}" class="form-control" id="decleration_name" type="text" name="decleration_name" placeholder="Name:"  />
        <input value="{{ $decleration_Qualification }}" class="form-control" id="qualification" type="text" name="qualification"  placeholder="Qualification:" />
        <input value="{{$decleration_position}}"class="form-control" id="position_in_the_company" type="text" name="Position_in_the_company" Placeholder="Position in the company"  />
        <!-- <input class="form-control" id="Signature" type="text" name="Signature"  Placeholder="Signature" /> -->
       @if( $decleration_date =='')
        <input value="{{ $decleration_date }}" class="form-control" id="Date_decleration" type="date" name="Date"  />
        @else
        <input  readonly value='@php $t=time(); echo date("Y-m-d",$t); @endphp' class="form-control" id="Date_decleration" type="text" name="Date"  />
   @endif

        <!-- <textarea class="form-control" id="OfficialSeal: " type="text" name=": " Placeholder="Officialstamp" /></textarea> -->
    </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          NMFA 
          @php
          $t=time();
          echo date("Y-m-d",$t);
      @endphp

          
        </div>
        <!-- /.card-footer-->
      </div>
           

    
   
    @if( $decleration_present == 1)
    <span  id="decleration_id"  style="display:block"> 
    <button type="button" class="btn btn-success"    id="decleration_save"   >  Update </button>
    
    
    @php
foreach($docu_id as $document){}
$path = @$document->path;
@endphp
 </span>
<br>
 <a target="_blank" href="{{ route('completeApplicationStatus',$applications->application_id)}}" type="button" class="btn btn-warning left-1"   id="print_decla"> <i class="fas fa-print"></i> Print </a>

    
    @else
    <span  id="decleration_id"  style="display:none"> 
    <button type="button" class="btn btn-success"    id="decleration_save"   >  Save </button></span>
</span>
<br>
<a  target="_blank" href="{{ route('completeApplicationStatus',$applications->application_id)}}" type="button" class="btn btn-warning left-14"   id="print_decla"> <i class="fas fa-print"></i> Print </a>

    <span  id="decleration_on__update"  style="display:none"> 
    <!-- <input type="button" class="save action-update" value="Update"    id="decleration_sample_update"       > -->
</span>
    @endif
  


 </div>
</div>


                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"   id="next_decleration" /> -->
 <br><br>
<button  style="position: absolute;left: 35%; top: 96%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>

@if(@$decleration_name !='')

<button   style="display:block;position: absolute;left: 45%; top: 96%;" type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="next_button_dec" /> Next Step </button>

@else

<button   style="display:none;position: absolute;left: 45%; top: 96%;" type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="next_button_dec" /> Next Step </button>
@endif

        
                                   </fieldset>
                          

<!---------------  ------------------------------------------------------------>


<fieldset   @php if($Last_Element == 8) { echo "style='display:block'"; }  else  {}    @endphp   id="success_wizard" >
<div class="form-card">
<h2 class="fs-title text-center">Success !</h2> <br><br>
<div class="row justify-content-center">
<div class="col-3"> <img src="{{ asset('images/ok--v2.png') }}" class="fit-image"> </div>
</div> <br><br>
                                   
                              <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You have successfully finished filling the application form!!</h5>
                                            </div> 
                                         </div>
                                    </div>

    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
    <input type="button" name="make_payment" class="next action-button" value="Confirm" id="confirm_finish" /> -->
<br>
<button  style="position: absolute;left: 35%; top: 90%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button  style="position: absolute;left: 45%; top: 90%;" style="display:block;" type="button" name="next" class="btn btn-primary  app_recep_next" value="Next Step"  value="Confirm" id="confirm_finish" /> Confirm Finish </button>
<br><br>

                                </div>

                            </fieldset>
 
         
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@include('layouts.modal_show_detail_information')




<style>
* {
    margin: 0;
    padding: 0
}

html {
    height: 100%
}

#grad1 {
    background-color: : #9C27B0;
    /* background-color: rgba(240, 226, 227, 0.815); */
   /*background-image: linear-gradient(120deg, #FF4081, #81D4FA)*/
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}

#msform input,
#msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: blue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 2px;
    /* cursor: pointer;*/
    padding: 10px 5px;
    margin: 10px 5px;
    /*display:none*/;
}

#msform .action-update {
    width: 100px;
    background: rgba(250, 171, 0, 0.979);
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    /* cursor: pointer;*/
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-buttonn {
    width: 100px;
    background: rgba(12, 218, 12, 0.925);
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
     cursor: pointer
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .app_recep_previous_companyinfo {
    position: absolute;
    left: 40%;
    top: 90%;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
 


}

#msform .app_recep_next_companyinfo {
    /* width: 100px;
       background-color: #0275d8;
       border-color: #0275d8;
       font-weight: bold;
       color: white;
       border: 0 none;
       border-radius: 0px; 
    */

    position: absolute;
    left: 50%;
    top: 90%;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
    /*display:none*/;


}


#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width:10%;
    float: left;
    position: relative
}

#progressbar #Application_Type:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "1"
}

#progressbar #supplier:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "2";
    
}

#progressbar #Agent:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "3"
}

#progressbar #product_details:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "4"
}

#progressbar #product_composition:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "5"
}

#progressbar #product_manufacturers:before {
    font-family: FontAwesome;
    cursor:pointer;
    content:"6"
}

#progressbar #product_manufacturers_api:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "7"
}

#progressbar #dossier_sample:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "8"
}


#progressbar #decleration:before {
    font-family: FontAwesome;
    cursor:pointer;
    content: "8"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    cursor:pointer;
    
    content: "9"
}


#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,#progressbar li.active:after {
    background:#337ab7;
}

#progressbar li.active:hover:before {
   color: #fff;
    background-color: #286090;
    border-color: #204d74
}


.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>


<script> 
$(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;





$(".app_recep_next").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});


next_fs.css({'opacity': opacity});
},
duration: 600
});


});

//app_recep_next_companyinfo
$(".app_recep_next_companyinfo").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});


});


// app_app
$(".app_app").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});


});

$(".app_recep_previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

$(".submit").click(function(){
return false;
})

});

</script>


<script>

$(document).ready(function(){
var  app_new_application = document.getElementById('app_new_application');
var  app_renewal_application = document.getElementById('app_renewal_application');

var  new_application_mode = document.getElementById('new_application_mode');

var  app_renew_new_application_mode = document.getElementById('app_renew_new_application_mode');



var  app_variations = document.getElementById('app_variations');
var  track_mode = document.getElementById('app_fast_track_mode');

//App New  Applications
 $("#app_new_application").click(function(){
    if((app_renewal_application.checked==true  ))
{
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();

}
else
{
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();


}
    
    });



 $("#app_renewal_application").click(function(){
    if((app_new_application.checked == true  ))
{
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();

}
else  
{
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();

}
    
    });

/*

//check Track mode from the Application new track mode Select Box
    $("#app_fast_track_mode").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
   
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

    ////check Track mode from the Application new track mode Select Box
    $("#app_variations").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
 
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

       ////check Track mode from the Application new track mode Select Box
       $("#app_renewal_application ").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
    //$('#appicaiton_save').show();
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
    //$('#appicaiton_save').show();
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

          ////check Track mode from the Application new track mode Select Box
          $("#app_new_application ").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
   // $('#save_application_info').show();
     generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    });



*/
});



</script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();




    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });


 //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask();

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    });
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    );

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    });

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox();

    //Colorpicker
    $('.my-colorpicker1').colorpicker();
    //color picker with addon
    $('.my-colorpicker2').colorpicker();

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  });
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  });

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false;

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template");
  previewNode.id = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  });

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  });

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  });

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  });

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  });

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  };
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End



function start_applications(value){
console.log(value);
 $('#appicaiton_save').show()

  
}
</script>


  @include('layouts.custom_supplier_js')



@endsection