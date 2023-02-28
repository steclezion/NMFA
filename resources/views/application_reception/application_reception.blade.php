@extends('layouts.app_app')
<!-- Toastr -->
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



<link rel="stylesheet" href="{{asset('asset/css/fontawesome.css')}}">
<link rel="stylesheet" href="{{asset('asset/css/fontawesome.min.css')}}">


<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
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



    
<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-md-5 col-md-7 col-lg-10 text-center p-0 mt-3 mb-2">
        <div  class="alert alert-success align-content-sm-center" id="app_id" ></div>
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3" id="Application_submission">
            <input type="hidden" name="stegnant_application_number"  value="0" id="stegnant_application_number"/>

                <h2>Application Submission Process </h2>
                
                <p>Fill all form fields to go to the next step 
                
                <i   style="color:blue;cursor:pointer" id="modal_show_detail_information" title="Guidelines on Application Submission process"  class="fas fa-info-circle">  </i>
           
                </p>
                <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li   class="active" id="Application_Type"><strong>Application Type</strong></li>
                                <li  id="supplier"><strong>Company Supplier</strong></li>
                                <li id="Agent"><strong>Agent</strong></li>
                                <li id="product_details" ><strong>Product Details</strong></li>
                                <li id="product_composition" ><strong>Product Composition</strong></li>
                                <li id="product_manufacturers" ><strong>Product Manufacturers</strong></li>
                                <li id="product_manufacturers_api" ><strong> API Manufacturers</strong></li>
                               <!-- <li id="dossier_sample" clas="fas fa-supple"><strong> Dossier Link and Sample</strong></li>-->
                                <li id="decleration" ><strong> Declaration</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                              
                            </ul> <!-- fieldsets -->
<input class="form-control" id="generated_application_id" type="hidden" name="Application_ID"  value="0" />
                                 
<!---------------------------------------------------------------------------------------------------------   -->

    <fieldset>
    <div class="form-card">
    <h2 class="fs-title">Application Type</h2>
    <div class="row">   
    <div class="col-sm-3">
    <!-- <label  >New Application </label>   -->
 
    </div>
    <div class="col-sm-9"> 
    <input class="form-control"   style="width:20px;position:float:left;"  id="app_new_application" type="hidden" name="Application_type"   />
    <label  style="display:block" id="new_application_mode_label" class="form control-label"  > Select Application Mode<i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>
    <div class="input-group mb-3"  style="width:20;position:float:left;" >
    <select  style="display:block"   class="form-control" name="new_application_mode" id="new_application_mode"  required  onchange="start_applications(this.value)">
    <option value="0" disable="false" selected="true"> </option>
    <option value="1"> Standard route of registration </option>
    @foreach ($fast_track_applications as $key => $value)
    <option value="2_{{ $value->name }}"> Fast track route of registration - {{ $value->name }}  </option>
    @endforeach
    </select>
    </div>
    </div>

    <div class="col-sm-3" hidden>
    <label >Renew Application </label>  
    </div>
    <div class="col-sm-9" hidden> 
    <input class="form-control"  style="width:20px;position:float:left;"  id="app_renewal_application" type="radio" name="Application_type"   />
    <div class="input-group mb-3"  style="position:float:left;" >
    <select    style="display:none" class="form-control" name="app_renew_new_application_mode" id="app_renew_new_application_mode"  required  onchange="start_applications(this.value)">
    <option value="0" disable="true" selected="true"> Select Application Mode<i style="color:red;cursor:pointer;font-size:20px;">*</i> </option>
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
 <label >Request fast track/Abridged registration </label>  
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

<button   type="button" class="btn btn-success" value="Save"    id="save_application_info"  >Save </button>

</span>
<span  id="applicaion_updatee"  style="display:none">
<button   type="button" class="btn btn-success" value="Update"    id="update_application_info"  >   Update </button> </span>
<button style="display:none" style="position:absolute;left: 35%; top:70%;cursor:pointer;padding:10px 5px;margin: 10px 5px;" style="display:block" type="button" name="next" class="btn btn-primary app_recep_next" value="Next Step"  id="next_button_application"   />Next Step </button>

<br><br><br>    
</fieldset>


 <!------------------------------------------------------------------------  -->
    <fieldset>
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
<label> Applicant Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>   
<select class="form-control select2bs4" style="width: 100%;background-color:#ff0000"  name="trade_names" id="trade_names"  required  onchange="check_trade_name(this.value)">
<option  selected="true" value="0"></option>
@foreach ($company_suppliers_template as $key => $value)
<option value="{{ $value->trade_name }}">{{  $value->trade_name   }}  </option>
@endforeach
<option value="Other"> Other </option>
</select>

</div>         
<div style="display:none;color:red"  id="cs_tradename_other" >
<label> Specify Applicant Name (If other) </label> 
 <input placeholder="" class="form-control"   value="0" id="trade_names_other" type="text" name="trade_names"    required>
 <input  type="hidden" id="company_supplier_template_id" />
 </div>
<br/>
<!-- <div id="">
<label> Supplier Name</label> 
 <input class="form-control" id="cs_city" type="text" name="city"    required>
</div> -->
<!--                                                               -->         
<div class="input-group mb-3"> 
<label> Country<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<select class="form-control" style="width: 100%;"  name="country_id" id="css_country"  onchange="fetch_tele(this.value,'cs_response_tele','{{url('/get_tele_code/tele_code')}}')"  required>
<option  selected="true" value="0"></option>
@foreach ($countries as $key => $value)
<option value="{{$value->id}}">{{ $value->country_name }}</option>
@endforeach
</select>
</div>

<label> City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
 <input class="form-control" onkeyup="AllowonlyText(event,'cs_city')"  id="cs_city" type="text" name="city"    required>
 <label>State</label>
<input class="form-control" id="cs_state"type="text" name="state" onkeyup="AllowonlyText(event,'cs_state')"  /> 
<label>Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
 <input class="form-control"  id="cs_address_line_one" type="text" name="address_line_one"  />
 <label>Address Line Two </label>
<input class="form-control" id="cs_address_line_two" type="text" name="address_line_two"  />
<div id="cs_response_email"></div>
<div id="cs_response_email_success" class="alert alert-success" style="display:none"></div>
<div id="cs_response_email_danger" class="alert alert-danger" style="display:none"></div>
<div id="cs_response_email_warning" class="alert alert-warning" style="display:none"></div>
<label> Institutional Email<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input class="form-control"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_supply')}}','cs_response_email','save_supplier_info','cs_email')" id="cs_email" type="email" name="email" placeholder="Email" />


<i class="fas fa-phone fa-2xs" id="cs_response_tele">     </i> 
<label>Phone Number <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input class="form-control" onkeyup="AllowonlyText_Tele(event,'cs_tele')"  min="0" id="cs_tele" type="number" name="telephone"  />

<label>Postal Address<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="postal_code" type="text" name="postal_code"   />

<!--Validate URL from the backend section -->

<div id="cs_response_website_url"></div>
<div id="cs_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
<div id="cs_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
<div id="cs_response_website_url_success" class="alert alert-success" style="display:none"></div>
<label>Web URL<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input class="form-control" id="cs_website_url" type="url" name="websete_url"   onkeyup="valdiate_url(this.value,'cs_response_website_url','{{url('/Validate/url/customer_supply')}}','save_supplier_infoo')" /> 


<label>Fax</label>
<input  class="form-control" id="cont_fax"  type="text" name="fax"  />

</div>



 <!--Contact Person Information />-->
 <div class="col-12 col-sm-6"> 
 <h2 class="fs-title">Applicant Contact Person  <i  style="color:blue;cursor:pointer;font-size:15px;" title="Please ensure that the contact person you have listed is an active employee of the company. The contact person will be responsible for all the communications between the National Medicines and Food Administration (NMFA) and the company."  class="fas fa-info-circle fa-0">  </i>
 </h2> 
 <label>First Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
 <input class="form-control" onkeyup="AllowonlyText(event,'cont_first_name')" id="cont_first_name" type="text" name="first_name"    required>
 <label>Middle Name</label>
<input class="form-control" id="cont_middle_name" onkeyup="AllowonlyText(event,'cont_middle_name')" type="text" name="middle_name"    required>
<label>Last Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
 <input class="form-control" id="cont_last_name" onkeyup="AllowonlyText(event,'cont_last_name')" type="text" name="last_name"    required>

<label>Position<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_position" onkeyup="AllowonlyText(event,'cont_position')" type="text" name="position"   />

<label> Country<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<div class="input-group mb-5">
<select class="form-control select2bs4" name="country_id" id="cont_country"  required  onchange="fetch_tele(this.value,'cont_response_tele','{{url('/get_tele_code/tele_code')}}')">
<option value="0" disable="true" selected="true">=== Select Country ===</option>
@foreach ($countries as $key => $value)
<option value="{{$value->id}}">{{ $value->country_name }}</option>
@endforeach
</select>
</div>

<label>City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>                            
<input class="form-control" id="cont_city"type="text" onkeyup="AllowonlyText(event,'cont_city')" name="city"  onkeyup="AllowonlyText(event,'cont_cityy')" /> 
<label>Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_address_line_one" type="text" name="address_line_one"  />
<label>Address Line Two</label> 
<input class="form-control" id="cont_address_line_two" type="text" name="address_line_two"  />
<div id="cont_response_email"></div>
     <div id="cont_response_email_success" class="alert alert-success" style="display:none"></div>
	 <div id="cont_response_email_danger" class="alert alert-danger" style="display:none"></div>
     <div id="cont_response_email_warning" class="alert alert-warning" style="display:none"></div>
<label>Institutional Email<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
<input class="form-control" id="cont_email" type="email" name="email"    onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_contact')}}','cont_response_email','save_supplier_info','cont_email')" />
<i class="fas fa-phone fa-2xs" id="cont_response_tele"></i> 
<label>Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>
<input  class="form-control" id="cont_tele" onkeyup="AllowonlyText_Tele(event,'cont_tele')"  min="0" type="number" name="telephone"  />
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

                                  

<input id="contact_id"   name="contact_id"    value=""   hidden/>
<input id="supplier_id"   name="supplier_id"    value=""  hidden/>

<!-- <span id="cs_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_supplier_info"       ></span> -->
<!-- <span id="cs_update"  style="display:none"> <input type="button" class="save action-update" value="Update"    id="update_supplier_info"      ></span> -->
<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
<!-- <input type="button" name="next" class="next action-button" id="cs_next_button" value="Next Step"   > -->

<button  style="position: absolute;left: 45%; top: 85%;"   type="button" class="btn btn-success _app" value="Save"    id="save_supplier_info"   >Save </button>
<button   style="display:none; position: absolute;left: 45%; top: 85%;"   type="button" class="btn btn-success _app" value="Save" value="Update" id="update_supplier_info" > Update </button>
<br><br><br><br><br><br><br> 
<button  style="display:block;" type="button" name="next" class="btn btn-secondary app_recep_previous  app_recep_previous_companyinfo" id="previous_button_application" />  Previous </button>
<button  style="display:none;" type="button" name="next" class="btn btn-primary  app_recep_next_companyinfo" value="Next Step"  id="next_application" /> Next Step </button>

        
   
   
          </fieldset>
                          <!------------------------------   -->
                            
                            <fieldset>
                        <div class="form-card">

                         <div class="container">   
    <div class="row">
                         <div class="col-12 col-sm-6"> 

        <h2 class="fs-title">Local Authorized Agent

   <i  style="color:blue;cursor:pointer;font-size:15px;" 
   title="Please visit Guidance for the Authorization of Local Agents for further guidance."
    class="fas fa-info-circle fa-0">  
    </i>
                                     
                                     </h2>
                                     
<label> Business Name<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
<input class="form-control" id="ag_trade_name" type="text" name="ag_trade_name" placeholder="Business Name"    required>
<label> Country </label> 


 <!--  Eritrea below the lable country  -->
<select class="form-control" name="country_id" id="ag_country"  required  onchange="fetch_tele(this.value,'age_response_tele','{{url('/get_tele_code/tele_code')}}')">
 <!-- <option value="0" disable="true" selected="true">Select Country</option>-->
  <option value="68"  selected="true">Eritrea</option>
  </select>
  <br>
  <label>Postal Address<i style="color:red;cursor:pointer;font-size:20px;">*</i></label> 
                                    <input class="form-control" id="ag_postal_code" type="text" name="postal_code"   required>
<label> City<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
<input onkeyup="AllowonlyText(event,'ag_city')" class="form-control" id="ag_city" type="text" name="city"     required>

   <label hidden> State </label> 
   <input  class="form-control" id="ag_state" type="hidden" name="state"  /> 
   <label> Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
                                    <input class="form-control" id="ag_address_line_one" type="text" name="address_line_one"  />
   <label> Address Line Two </label> 
                                    <input class="form-control" id="ag_address_line_two" type="text" name="address_line_two"  />
   <label> Email<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>      
                                    <div id="age_response_email"></div>
                                    <div id="ag_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="ag_response_email_dangerr" class="alert alert-danger" style="display:none"></div>
                                    <div id="ag_response_email_warning" class="alert alert-warning" style="display:none"></div>
                                    <input class="form-control" id="ag_email" type="email" name="email"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/local_agent')}}','ag_response_email','save_agent_info','ag_email')"/>
        <label> Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
        <i class="fas fa-phone fa-2xs" id="age_response_tele">+291</i> 
        <input min="0" onkeyup="AllowonlyText_Tele(event,'ag_tele')" class="form-control" id="ag_tele" type="number" min="0" name="telephone"  />
  <label> Website URL <i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>                                
                <div id="age_response_website_url"></div>
                <div id="age_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
                <div id="age_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
                <div id="age_response_website_url_success" class="alert alert-success" style="display:none"></div>
                      
                <input class="form-control" id="ag_website_url" type="url" name="websete_url"   onkeyup="valdiate_url(this.value,'age_response_website_url','{{url('/Validate/url/customer_supply')}}','save_agent_info')"/>
                
                <label>Fax</label>
<input  class="form-control" id="cont_ag_fax"  type="text" name="fax"  />
                </div>
                       <!--Contact Person Information />-->
                       <div class="col-12 col-sm-6"> 
                                   <h2 class="fs-title"> Contact  Person                                
 <i  style="color:blue;cursor:pointer;font-size:15px;" 
   title="Please ensure that the contact person you have listed is an active employee of the local agent company. The contact person will be responsible for all the communications between the National Medicines and Food Administration (NMFA) and the local agent. "
    class="fas fa-info-circle fa-0">  
    </i>
    
    </h2> 
                                   <label> First Name<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
                                    <input class="form-control" onkeyup="AllowonlyText(event,'cont_ag_first_name')" id="cont_ag_first_name" type="text" name="first_name"    required>
                                    <label> Middle Name </label> 
                                    <input class="form-control" onkeyup="AllowonlyText(event,'cont_ag_middle_name')"  id="cont_ag_middle_name" type="text" name="middle_name"    required>
                                    <label> Last Name<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
                                    <input class="form-control" onkeyup="AllowonlyText(event,'cont_ag_last_name')" id="cont_ag_last_name" type="text" name="last_name"    required>
                                    <label> Position<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
     <input class="form-control" id="cont_ag_position" type="text" onkeyup="AllowonlyText(event,'cont_ag_position')" name="position"  />
                                    <label> Country<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label> 
                                    <div class="input-group mb-3">
                                    <select class="form-control select2bs4" name="country_id" id="cont_ag_country_id"  required   onchange="fetch_tele(this.value,'cont_age_response_tele','{{url('/get_tele_code/tele_code')}}')">
                                    <option value="68" disable="true" selected="true">Eritrea</option>
                                    </select>  
                                    </div>
                                    <label> City<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
                                    <input class="form-control" onkeyup="AllowonlyText(event,'cont_ag_city')" id="cont_ag_city"type="text" name="city"  /> 
                                    <label> Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
                                    <input class="form-control"  id="cont_ag_address_line_one" type="text" name="address_line_one"  />
                                    <label> Address Line Two </label>
                                    <input class="form-control" id="cont_ag_address_line_two" type="text" name="address_line_two"  />
                                    <label> Email<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
                                    <div id="cont_ag_response_email"></div>
                                    <div id="cont_ag_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="cont_ag_response_email_danger" class="alert alert-danger" style="display:none"></div>
                                    <div id="cont_ag_response_email_warning"  class="alert alert-warning" style="display:none"></div>

                                    <input class="form-control" id="cont_ag_email" type="email" name="email"  onkeyup="Email_Validate(this.value,'{{url('/Validate/email/customer_contact')}}','cont_ag_response_email','save_agent_info','cont_ag_email')" />
                                
                                    <label> Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>
                                    <i class="fas fa-phone fa-2xs" id="cont_age_response_tele">+291</i> 
                                    <input class="form-control" id="cont_ag_tele"  name="telephone"  class="form-control" onkeyup="AllowonlyText_Tele(event,'cont_ag_tele')"   type="number" min="0"/>
     
                <div id="cont_age_response_website_url"></div>
                <div id="cont_age_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
                <div id="cont_age_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
                <div id="cont_age_response_website_url_success" class="alert alert-success" style="display:none"></div>
                      
         <!--<input class="form-control" id="cont_ag_webiste_url" type="url" name="webiste_url" placeholder="Website URL" onkeyup="valdiate_url(this.value,'cont_age_response_website_url','{{url('/Validate/url/customer_supply')}}','save_agent_info')" />-->
        </div> 
            </div>
            </div>
            </div>

<input id="agent_contact_id"   name="contact_name_agent"    value=""   hidden/>
<input id="agent_id"   name="agent_name"    value=""  hidden/>



<!-- <span  id="ag_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_agent_info"       ></span>
<span  id="ag_update"  style="display:none"> <input type="button" class="save action-update" value="Update"    id="update_agent_info"      ></span> -->
<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
<!-- <input   type="button" name="next" class="next action-button" value="Next Step"  id="agent_next_button" /> -->
<!-- <button  style="display:none" type="button" name="next" class="btn btn-primary agent" value="Next Step"  id="next_button_application"   />Next Step </button> -->


<br><br><br><br>
<button  style="position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save"    id="save_agent_info"   >Save </button>
<button   style="display:none; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"  id="update_agent_info" > Update </button>

<button  style="position: absolute;left: 35%; top: 95%;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button  style="display:none;position: absolute;left: 45%; top: 95%;"   type="button" name="next" class="btn btn-primary app_app" value="Next Step"     id="agent_next_button" /> Next Step </button>



                            </fieldset>

                  <!--------------------------------   -->





            <fieldset>
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
    <select  class="form-control select2bs4" style="display:block;width:350px;"  id="generic_approved_name"   name="Application_type" required  onchange="get_other_value(this.value,'generic_approved_name_other')">
    <option value="0" disable="true" selected="true">=== Generic Name ===</option>
    <option value="919" >Other</option>
     @foreach ($medicines as $key => $value)
     @if($value->id != 919)
     <option value="{{$value->id}}">{{ $value->product_name }}</option>
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
   <label>Brand Name<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
    &nbsp;&nbsp;
    <input class="form-control" id="product_trade_name"  type="text" name="product_trade_name"   />
        <label>Dosage Forms<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
             &nbsp;&nbsp;
            <select style="display:block;width:300px;" class="form-control select2bs4" id="dosage_form_id"  name="dosage_form_id"  required>
                                    <option value="0" disable="true" selected="true">=== Select Dosage Forms  ===</option>
                                    @foreach ($dosage_forms as $key => $value)
                                   <option value="{{$value->id}}">{{ $value->name }}</option>
                                    @endforeach
                                    </select>
                                    <br>  <br>  
            <label>Route of Administration<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  

            <select  style="display:block;width:200px;" class="form-control select2bs4" id="route_administration_id" name="route_administration_id"   required>
            <option value="0" disable="true" selected="true">=== Select Route of Administration  ===</option>
            @foreach ($route_administrations as $key => $value)
            <option value="{{$value->id}}">{{ $value->name }}</option>
            @endforeach
            </select>
<br><br/>
            <label hidden >Description<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
            <input class="form-control" id="description"  hidden type="text" name="description"  required />

            
            <span  style="display:none" id="strength_unit">
            <label hidden >Strength Amount and Strength unit  </label>  
            <textarea hidden value="1" class="form-control" id="strength_amount_unit"  name="strength_amount_unit" required />
            </textarea>
            </span>
     
            <br>
            <label>Storage Condition<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="storage_condition" type="text" name="storage_condition"  required/>
            
            <label>Shelf life (In months only)<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="shelf_life_amount" type="number" min='1'  onkeyup="AllowonlyText_Tele(event,'shelf_life_amount')"   placeholder=""   name="shelf_life_amount"  required/>
            
            <label hidden >Shelf life Unit</label>  
            <select hidden   class="form-control" id="shelf_life_unit" type="number" name="shelf_life_unit" required>
            <option value="Days" >Days</option>
            <option selected value="Months" selected="true" >Months</option>
            </select>
                     
            <br>
        <label>Pharmacotherapeutic Classification (Anatomic-Therapeutic Classification system)<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="pharmaco_therapeutic_classification" type="text" name="pharmaco_therapeutic_classification"  required/>


 <!--<input class="form-control" id="shelf_life_unit" type="number" name="shelf_life_unit"  required/>  -->


     </div>
     
   

             <div class="col-12 col-sm-6">
         
            <label>Proposed Shelf life (In months only)</label>  
<input onkeyup="AllowonlyText_Tele(event,'proposed_shelf_life_amount')" 
 class="form-control" id="proposed_shelf_life_amount" min="1" type="number" name="proposed_shelf_life_amount"   required/>


              

            
            <label hidden>Proposed Shelf life Unit </label>  
            <select  hidden style="display:block" class="form-control" id="proposed_shelf_life_unit"  name="proposed_shelf_life_unit"  required  onchange="proposed_shelf(this.value,)">
            <option selected value="Month" >Month</option>
            <option value="Years" selected="true" >Years</option>
            <option value="not_applicable"  >Not Applicable</option>
            </select>
          <!--  <input class="form-control" id="proposed_shelf_life_unit" type="text" name="proposed_shelf_life_unit"  placeholder="in-months" required/>-->
            <br>
            <label>Proposed Shelf Life After Reconstitution</label>  
            <input  class="form-control" id="proposed_shelf_life_after_reconstitution_amount" type="text" name="proposed_shelf_life_after_reconstitution_amount"  required/>
            
            <label hidden >Proposed Shelf Life After Reconstitution Unit</label>  
            <!--<input class="form-control" id="proposed_shelf_life_after_reconstitution_unit" type="text" name="proposed_shelf_life_after_reconstitution_unit"  required/> -->
            <select  style="display:none" class="form-control" id="proposed_shelf_life_after_reconstitution_unit"  name="proposed_shelf_life_after_reconstitution_unit"  required  onchange="proposed_shelf(this.value,)">
            <option selected value="Days" >Days</option>
            <option value="Months" selected="true" >Months</option>
            </select>
<br>
            <label>Visual Description<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="visual_description" type="text" name="visual_description" required />
            
            <label>Commercial Presentation<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="commercial_presentation" type="text" name="commercial_presentation"  required/>
            
            <label>Container, closure and administration devices<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="container" type="text" name="container" required />
            
            <label>Packaging and pack size <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
            <input class="form-control" id="packaging" type="text" name="packaging" required />
            
            <label>Category of Use<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
             &nbsp;&nbsp;
            <select  style="display:block" class="form-control" id="category_use"  name="category_use"  required>
            <option value="0" disable="true" selected="true">=== Select category Use  ===</option>
            <option  value="POM (Prescription only medicine)" >POM (Prescription only medicine) </option>
            <option value="P (Pharmacy Medicine)">   P (Pharmacy Medicine)  </option>
            <option value= "OTC (Over The Counter medicine)" >OTC (Over The Counter medicine)  </option>
            <option value= "Controlled Substances" >Controlled Substances </option>
            <option value= "Hosiptal/Health Facilities Only Medicines" >Hosiptal/Health Facilities Only Medicines  </option>
            </select>
     </div>
     </div>
     </div>
     </div>
            
<input id="product_detail_master_id"   name="product_detail_name"    value=""   hidden/>


<!-- <span  id="product_detail_save"  style="display:block"> <input type="button" class="save action-buttonn" value="Save"    id="save_product_detail_info"       ></span>
<span  id="product_detail_update"  style="display:none"> <input type="button" class="save action-update" value="Update"    id="update_product_detail_info"      ></span> -->
<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
<input type="button" name="next" class="next action-button" value="Next Step"  id="product_detail_next_button" /> -->


<br><br><br><br>
<button  style="position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save"    id="save_product_detail_info"   >Save </button>
<button  style="display:none; position: absolute;left: 40%; top: 90%;"   type="button" class="btn btn-success" value="Save" value="Update"   id="update_product_detail_info"  > Update </button>

<button  style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button   style="display:none;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_detail_next_button" /> Next Step </button>



</fieldset>

<!---------------  ------------------------------------------------------------>

<!-------------------                        ---->
<fieldset>
        
         <div class="form-card">
         <h2 class="fs-title">Product Composition

         <i  style="color:blue;cursor:pointer;font-size:15px;" 
             title="Please indicate the complete qualitative and quantitative composition of the product per unit dosage form (e.g. per tablet, capsule, 2ml etc.). Please replicate this section, as applicable, to indicate all constituents of the product"  
             class="fas fa-info-circle fa-0">  
        </i>

         </h2>
         <div class="container">
         <div style="display:block"><span style="display:none" id="id_update_compostion"> </span>
         <label>Constituents<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
         <div class="col-9"> 
         <select class="form-control" id="composition_type_composition" name="composition_type_composition">
         <option  value="">  </option>
         <option  value="API">API </option>
         <option value="Excipients" > Excipient</option>
         <option value="Solvents"> Solvents      </option>
         </select>
         </div>
         <label>Name (INN)<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
         <input class="form-control" id="name_inn_text_composition" type="text" name="name_inn_text_composition"  />   
         </div>   
        <label>Quantity<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
        <input class="form-control" id="quantity_composition" type="text" name="quantity_composition"  />
            
            <label>Reason for Inclusion<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
            <input class="form-control" id="reason_inclusion_composition" type="text" name="reason_inclusion_composition"  />

            <label>Reference standard<i style="color:red;cursor:pointer;font-size:20px;">*</i>  </label>  
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

     <!-- <span   id="add_new_composition"  class="btn btn-warning"> <i class="fas fa-minus"> clear </i> </span> -->
<br><br>
    <span  id="createNewCompostion_save"  style="display:block"> 
    <button type="button" class="btn btn-success btn-sm"    id="createNewCompostion_save" ><i class="fas fa-save" > </i>    Save </button></span>
    <span  id="createNewCompostion_update"  style="display:none"> 
    <!-- <button   id="add_new_composition"  class="btn btn-warning"> <i class="fas fa-minus"> clear </i> </button> -->

    <button type="button"   class="btn btn-success btn-sm" value="Update" title="Edit Composition" id="createNewCompostion_update" ><i class="fas fa-edit" > </i>  Update </button></span>
  
    <div class="container">
    <div class="table-responsive" style="display:block">          
     <table class="table"  id="table_product_compostion">
    
    <thead>
    

    <tr>
                 <th>ID </th>   
                 <!-- <th>ApplicationID</th> -->
                 <th>Constituents</th>
                <th>Name (INN)</th>
                <th>Quantity</th>
                <th>Reason for Inclusion</th>
                <th>Reference Standard</th>
               
                <th>Action</th>
             </tr>
        </thead>
        <tbody id='renderd_product_composition_table' >
        </tbody>
    </table>
</div>
</div>


</div>
      </div>
<br><br><br>

                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"   id="next_composition" /> -->

<button  style="position:absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button style="display:none;position:absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary app_app" value="Next Step"  id="next_composition" /> Next Step </button>

                            </fieldset>
                          

<!---------------  ------------------------------------------------------------>
        <fieldset>
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
<label>Manufacturer Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
<input class="form-control"    id="manufacturer_name" type="text" name="manufacturer_name"  />        
<label>Country<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
<select onchange="fetch_tele(this.value,'manu_response_tele','{{url('/get_tele_code/tele_code')}}')" class="form-control" style="width: 100%;" name="manufacturer_country_id" id="manufacturer_country"  required>
                       <option value="0" disable="true" selected="true">=== Select Country ===</option>
                        @foreach ($countries as $key => $value)
                        <option value="{{$value->id}}">{{ $value->country_name }}</option>
                        @endforeach
</select>
<br><br>
<label>State</label>  
<input class="form-control"  onkeyup="AllowonlyText(event,'manufacturer_state')"    id="manufacturer_state" type="text" name="manufacturer_state"  />

<label>City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
<input class="form-control"  onkeyup="AllowonlyText(event,'manufacturer_city')" id="manufacturer_city" type="text" name="manufacturer_city"  />
  
<i class="fas fa-phone fa-2xs" id="manu_response_tele"></i> &nbsp;&nbsp;
        <label>Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input min="0" onkeyup="AllowonlyText_Tele(event,'manufacturer_tele')"   class="form-control" id="manufacturer_tele"  type="number" min="0"  name="manufacturer_tele"  />

 </div>


<div class="col-12 col-sm-6">

<div style="display:block">
        <label>Postal Address <i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control"   id="manufacturer_postal_code" type="text" name="manufacturer_postal_code"  />
        </div>
     
      
        <label>Address line one<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_add_line_one" type="text" name="manufacturer_add_line_one"  />
      
        <label>Address line two</label>  
        <input class="form-control" id="manufacturer_add_line_two" type="text" name="manufacturer_add_line_two"  />
   

    <label>Block<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_block" type="text" name="manufacturer_block"  />
        <label>Unit<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_unit" type="text" name="manufacturer_unit"  />


  <label>Activity<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
  <textarea value="" class="form-control" id="manufacturer_activity" type="text" name="manufacturer_state"  > </textarea>  
       

    <div style="display:none">
     <label>Website URL<i style="color:red;cursor:pointer;font-size:20px;">*</i> </label>  
     <div id="manu_response_website_url"></div>
     <div id="manu_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
     <div id="manu_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
     <div id="manu_response_website_url_success" class="alert alert-success" style="display:none"></div>
    <input class="form-control" value="http://steclezion@gmail.com"  id="manufacturer_url" type="url" name="manufacturer_url"  onkeyup="valdiate_url(this.value,'manu_response_website_url','{{url('/Validate/url/customer_supply')}}','save_product_manufacturer_save')"/>
      </div>

        
        <div style="display:none">
        <label>Email<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
                                    <div id="manu_response_email"></div>
                                    <div id="manu_response_email_success" class="alert alert-success" style="display:none"></div>
		                            <div id="manu_response_email_danger" class="alert alert-danger" style="display:none"></div>
                                    <div id="manu_response_email_warning" class="alert alert-warning" style="display:none"></div>
        <input class="form-control"  value="Nmfa@gmail.com" onkeyup="Email_Validate(this.value,'{{url('/Validate/email/manufacturers')}}','manu_response_email','save_product_manufacturer_save','manufacturer_email')" id="manufacturer_email" type="email" name="manufacturer_email"  />
        </div>
      
        
    </div>
    </div>
    </div>
    </div>
    <br/>
    <span   id="add_new_manufacturer"  class="btn btn-warning btn-sm"> <i class="fas fa-minus">  </i>  <span style="font-style: normal; font-family: 'Times New Roman', Times, serif">   Clear  </span> </span>

    <!-- <span   id="add_new_manufacturer"  class="btn btn-warning"> <i class="fas fa-minus"> clear </i> </span> -->
    <br><br>
    <span  id="product_manufacturer_save"  style="display:block"> 
    <!-- <input type="button" class="btn btn-success" value="Save"    id="save_product_manufacturer_save"       ></span> -->

 <button  type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_save"  ><i class="fas fa-save"> </i>   Save </button></span>

    <span  id="product_manufacturer_update"  style="display:none"> 
    <!-- <input type="button" class="btn btn-success" value="Update"    id="updates_product_manufacturer_update"       > -->
    <button type="button" class="btn btn-success"    id="updates_product_manufacturer_update"> <i class="fas fa-edit"> </i>    Update </button></span>
    
    
    <br>
    <div class="container">
    <div class="table-responsive" style="display:block">          
    <table class="table" id="table_product_manufacturer_api"  >
    <thead>
    <tr>
                <th>ID</th>
                <!-- <th>Application ID</th> -->
                <th>Manufacturer Name</th>
                <th>Country</th>
                <th>Postal Address</th>
                <th>Phone Number</th>
                <th>State</th>
                <th>Address Ln1</th>
                <th>Address Ln2</th>
                <!--<th>Website URL</th>-->
                <th>Activity</th>
                <th>block</th>
                <th>Unit</th>
                <!--<th>Email</th>-->
                <th>City</th>
                <!-- <th>International Dailing Code</th> -->
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody id='renderd_manufacturer_table' >
        </tbody>
    </table>
</div>
</div>


      </div>

<br><br><br>
<button  style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button  style="display:none;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_next_button" /> Next Step </button>

                           
                            </fieldset>

<!-------------------                        ---->


        <fieldset>
        <div class="form-card">
        <h2 class="fs-title">API Manufacturer(s) 

           <i  style="color:blue;cursor:pointer;font-size:15px;" 
           title="Please indicate the name and complete address of each facility where manufacturing of the Active Pharmaceutical Ingredient (API) occurs, including contractors.  Please replicate this section, as applicable, to indicate all sites involved in manufacturing of the product."
           class="fas fa-info-circle fa-0">  
           </i>
        
        
        <span class="badge"  id="id_for_update_api" style="display:none"></span></h2>
        <!-- <button id="add_new_api_product_manufacturer"  class="btn btn-info"> <i class="fas fa-plus"> Add New   </i> </button> -->

    <div class="container">  
    <div class="form-group"> 
    <div class="row">
    
    <div class="col-12 col-sm-6">
    <label>Manufacturer Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
    <input class="form-control"   id="manufacturer_api_name" type="text" name="manufacturer_name"  />
    
    <label> API Name<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
    <input class="form-control"    id="manufacturer_api_name_api" type="text" name="manufacturer_name"  />
        

        <label>Country<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
       <select  onchange="fetch_tele(this.value,'manu_api_response_tele','{{url('/get_tele_code/tele_code')}}')"  class="form-control" style="width: 100%;" name="manufacturer_api_country_id" id="manufacturer_api_country"  required>
                       <option value="0" disable="true" selected="true">=== Select Country ===</option>
                        @foreach ($countries as $key => $value)
                        <option value="{{$value->id}}">{{ $value->country_name }}</option>
                        @endforeach
        </select>
        <br/><br/> 
           
        <label>Postal Address<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_api_postal_code" type="text" name="manufacturer_api_postal_code"  />
        <i class="fas fa-phone fa-2xs" id="manu_api_response_tele"></i> &nbsp;&nbsp;
        <label>Phone Number<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText_Tele(event,'manufacturer_api_tele')"  min="0" class="form-control" id="manufacturer_api_tele" type="number" name="manufacturer_tele"  />
        </div>
<div class="col-12 col-sm-6">
        <label>City<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" onkeyup="AllowonlyText(event,'manufacturer_api_city')"  id="manufacturer_api_city" type="text" name="manufacturer_api_city"  />
        <label>State</label>  
        <input class="form-control" onkeyup="AllowonlyText(event,'manufacturer_api_state')" id="manufacturer_api_state" type="text" name="manufacturer_api_state"  />
      
        <label>Address Line One<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input class="form-control" id="manufacturer_api_add_line_one" type="text" name="manufacturer_api_add_line_one"  />
      
        <label>Address Line Two</label>  
        <input class="form-control" id="manufacturer_api_add_line_two" type="text" name="manufacturer_api_add_line_two"  />
        
        <label>Block<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input value=""  class="form-control" id="manufacturer_api_block" type="text" name="manufacturer_api_block"  />
       

        <label>Unit<i style="color:red;cursor:pointer;font-size:20px;">*</i></label>  
        <input onkeyup="AllowonlyText_Tele(event,'manufacturer_api_unit')" min="0" value="" class="form-control" id="manufacturer_api_unit" type="text" name="manufacturer_unit"  />
        
     </div>
    </div>
    </div>
    </div> 
    
    <span   id="add_new_api_product_manufacturer"  class="btn btn-warning btn-sm"> <i class="fas fa-minus">  </i> clear</span>
    <br><br>
    
    <span  id="product_manufacturer_api_save"  style="display:block">
    <!-- <input type="button" class="save action-buttonn" value="Save"    id="save_product_manufacturer_api_save"       /> -->
    <button type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_api_save"   ><i class="fas fa-save"> </i>   Save </button></span>


    </span>

    <br/>
    
    <span  id="product_manufacturer_api_update"  style="display:none">
    <!-- <input type="button" class="save action-update" value="Update"    id="save_product_manufacturer_api_update"       /> -->
    <button type="button" class="btn btn-success btn-sm"    id="save_product_manufacturer_api_update"   >  <i class="fas fa-edit"> </i>  Update </button></span>

   </span>



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
        </thead>
        <tbody id='renderd_manufacturer_api_table' >
        </tbody>
    </table>
</div>
</div>


      </div>


                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"  id="product_manufacturer_api_next_button" /> -->
                    <br><br><br>
<button  style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button   style="display:none;position: absolute;left: 45%; top: 95%;"  type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="product_manufacturer_api_next_button" /> Next Step </button>

                    </fieldset>


<fieldset>
        
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
          <h3 class="card-title">Declaration</h3>
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
         
            <label class="form-check-label" for="exampleCheck1"><b> I agree  </b></label>
            <input type="checkbox" class="form-check-input" id="customCheckbox1">
                  </div>
                  </div>
            </div>
         
         <div class="col-sm-3 no-print">
<!-- <label>I agree <input style="width:20px;position:float-center;" class="form-control" id="customCheckbox1" type="checkbox" name="customCheckbox1"   /></label>   -->
        
        </div>

        

      
           </div>

             <div class="col-12 col-sm-6">
     
        <input class="form-control" id="decleration_name" type="text" name="decleration_name" placeholder="Name:"  />
        <input class="form-control" id="qualification" type="text" name="qualification"  placeholder="Qualification:" />
        <input class="form-control" id="position_in_the_company" type="text" name="Position_in_the_company" Placeholder="Position in the company"  />
        <!-- <input class="form-control" id="Signature" type="text" name="Signature"  Placeholder="Signature" /> -->
        <input class="form-control" id="Date_decleration" type="text" readonly  value='@php $t=time(); echo date("Y-m-d",$t); @endphp' name="Date"  />
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
           

    
    <span  id="decleration_id"  style="display:none"> 
    <!-- <input type="button" class="save action-buttonn" value="Save"    id="decleration_save"       ></span> -->
    <button type="button" class="btn btn-success"    id="decleration_save"   >   Save </button></span>

    <span  id="decleration_on__update"  style="display:none"> 
    <!-- <input type="button" class="save action-update" value="Update"    id="decleration_sample_update"       > -->
    
     <a  target="_blank"  href="" type="button" class="btn btn-warning float-right no-print" style="margin-right: 5px;"  id="print_decla">
                    <i class="fas fa-print"></i> Print
                  </a>
    </span>
    
 

</div>
      </div>


                    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next Step"   id="next_decleration" /> -->
<br><br>
<button  style="position: absolute;left: 35%; top: 95%;"   style="display:block;" type="button" name="next" class="btn btn-secondary  app_recep_previous " id="previous_button_application" />  Previous </button>
<button   style="display:none;position: absolute;left: 45%; top: 95%;" type="button" name="next" class="btn btn-primary  app_app" value="Next Step"  id="next_button_dec" /> Next Step </button>

                            </fieldset>
                          

<!---------------  ------------------------------------------------------------>

 <!------------------------------   
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Payment Information</h2>
                                    <div class="radio-group">
                                        <div class='radio' data-value="credit"><img src="https://i.imgur.com/XzOzVHZ.jpg" width="200px" height="100px"></div>
                                        <div class='radio' data-value="paypal"><img src="https://i.imgur.com/jXjwZlj.jpg" width="200px" height="100px"></div> <br>
                                    </div> <label class="pay">Card Holder Name*</label> <input type="text" name="holdername" placeholder="" />
                                    <div class="row">
                                        <div class="col-9"> <label class="pay">Card Number*</label> <input type="text" name="cardno" placeholder="" /> </div>
                                        <div class="col-3"> <label class="pay">CVC*</label> <input type="password" name="cvcpwd" placeholder="***" /> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3"> <label class="pay">Expiry Date*</label> </div>
                                        <div class="col-9"> 
                                        <select class="list-dt" id="month" name="expmonth">
                                                <option selected>Month</option>
                                                <option>January</option>
                                                <option>February</option>
                                                <option>March</option>
                                                <option>April</option>
                                                <option>May</option>
                                                <option>June</option>
                                                <option>July</option>
                                                <option>August</option>
                                                <option>September</option>
                                                <option>October</option>
                                                <option>November</option>
                                                <option>December</option>
                                            </select> <select class="list-dt" id="year" name="expyear">
                                                <option selected>Year</option>
                                            </select> </div>
                                    </div>
                                </div>
								<input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" class="next action-button" value="Confirm" />
                            </fieldset>
                            -->
                            
                            
                            
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="{{ asset('images/ok--v2.png') }}" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
            <h5>You have successfully finished  filling the Application Form!!</h5>
                                            </div> 
                                         </div>
                                    </div>

    <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />  -->
    <!-- <input type="button" name="make_payment" class="next action-button" value="Confirm" id="confirm_finish" /> -->

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

#msform .app_recep_next {
    /* width: 100px;
       background-color: #0275d8;
       border-color: #0275d8;
       font-weight: bold;
       color: white;
       border: 0 none;
       border-radius: 0px; 
    */


    /*display:none*/;


}



#msform ._app {
 
    cursor: pointer;
    padding: 10px 10px;
    margin: 10px 10px;
    /*display:none*/;


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


#msform .app_recep_previous_companyinfo {
    position: absolute;
    left: 40%;
    top: 90%;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
 


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

#msform .app_recep_nextn {
     width: 100px;
     background: rgba(12, 218, 12, 0.925);
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 2px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
    /*display:none*/;
}

#msform .app_recep_next:hover,
#msform .app_recep_next:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .app_recep_next-previous {
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

#msform .app_recep_next-previous:hover,
#msform .app_recep_next-previous:focus {
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


#progressbar li.active:before,#progressbar li.active:after {
    background:#337ab7;
}

#progressbar li.active:hover:before {
   color: #fff;
    background-color: #286090;
    border-color: #204d74
}

#progressbar #Application_Type:before {
    font-family: FontAwesome;
    content: "1";
    
}





#progressbar #supplier:before {
    font-family: FontAwesome;
    content: "2";
    
}

#progressbar #Agent:before {
    font-family: FontAwesome;
    content: "3"
}

#progressbar #product_details:before {
    font-family: FontAwesome;
    content: "4"
}

#progressbar #product_composition:before {
    font-family: FontAwesome;
    content: "5"
}

#progressbar #product_manufacturers:before {
    font-family: FontAwesome;
    content:"6"
}

#progressbar #product_manufacturers_api:before {
    font-family: FontAwesome;
    content: "7"
}

#progressbar #dossier_sample:before {
    font-family: FontAwesome;
    content: "8"
}


#progressbar #decleration:before {
    font-family: FontAwesome;
    content: "8"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
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

#progressbar li.active:before,
#progressbar li.active:after {
    /* background: #0275d8 */
    background:#337ab7;
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
var  app_new_application =     document.getElementById('app_new_application');
var  app_renewal_application = document.getElementById('app_renewal_application');
var  new_application_mode =    document.getElementById('new_application_mode');
var  app_renew_new_application_mode = document.getElementById('app_renew_new_application_mode');

var generated_value = document.getElementById('generated_application_id').value;
var  app_variations = document.getElementById('app_variations');
var  track_mode = document.getElementById('app_fast_track_mode');

//App New  Applications
 $("#app_new_application").click(function(){
    if((app_renewal_application.checked==true  ))
{  
    if(generated_value == 0){} else {return false;}
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
    document.getElementById('new_application_mode_label').style.display = 'block';
     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();

}
else
{
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
    document.getElementById('new_application_mode_label').style.display = 'block';

     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();


}
    
    });



 $("#app_renewal_application").click(function(){
    if((app_new_application.checked == true  ))
{if(generated_value == 0){} else {return false;}
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();

}
else  
{   if(generated_value == 0){} else {return false;}
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();
}
    
    });

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
var application_id = document.getElementById('app_id').innerHTML ;

var selected_value = document.getElementById('new_application_mode').value ;
if(application_id == '' &&  value != 0 )
 { $('#appicaiton_save').show(); 
    
 
 }
 else if(application_id != '' &&   value != 0 )
 {

     $('#applicaion_updatee').show();
 }
 else if(application_id != '' &&   value == 0 )
 {
    $('#appicaiton_save').hide('10'); 
     $('#applicaion_updatee').hide('10');
 }
 else if(application_id == '' &&   value == 0 )
 {
    $('#appicaiton_save').hide('10'); 
     $('#applicaion_updatee').hide('10');
 }
  
}
</script>


  

@include('layouts.custom_supplier_js')

@endsection