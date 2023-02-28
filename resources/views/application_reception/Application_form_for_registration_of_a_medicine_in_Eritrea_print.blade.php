@extends('layouts.app_app') @section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- start: Css -->

<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>


<div class="card-body">
    <h4>Applications </h4>
    <br>
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <!-- <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-accepted-tab" data-toggle="pill" href="#custom-content-below-accepted" role="tab" aria-controls="custom-content-below-accepted" aria-selected="true">Accepted </a>
        </li> -->

    </ul>
    <br>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>Application form for registration of a medicine in Eritrea
                </h1>
            </div>
            <div class="col-sm-6">
                <!-- <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Advanced Form</li>
            </ol> -->
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->


    <div class="tab-content" id="custom-content-below-tabContent">
        <div class="tab-pane fade show active" id="custom-content-below-accepted" role="tabpanel" aria-labelledby="custom-content-below-accepted-tab">
            <div class="row">
                <div class="col-md-12">



            


                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">1. Details of Applicant </h3>
                        </div>
                        <div class="card-body">
                            <!-- Date dd/mm/yyyy -->
                            <h5 class="card card-blockquote"> 1.1 Name and address of applicant </h5>
                            <div class="form-group">
                                <label>Name:</label> @foreach( $check_list as $product_name ) @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>
                                    <input readonly value="{{ $product_name->tname }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Name" data-mask>

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- Date mm/dd/yyyy -->
                            <div class="form-group">
                                <label> Business Address:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                    </div>
                                    @foreach($check_list as $customer_address_line_one ) @endforeach

                                    <input readonly value="{{$customer_address_line_one->company_supplier_address_line_one }}, {{ $customer_address_line_one->company_supplier_address_line_two}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Bussiness Address" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- phone mask -->
                            <div class="form-group">
                                <label>Postal Address:</label> @foreach($check_list as $product_trade_name ) @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input readonly value=" {{ $customer_address_line_one->cs_postal_code }}" type="text" class="form-control" data-inputmask-alias="text" placeholder=" Postal Address" data-mask>

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- phone mask -->
                            <div class="form-group">
                                <label> Country:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                    </div>
                                    @foreach($company_supplier_info_country as $country_supplier ) @endforeach
                                    <input readonly value=" {{ $country_supplier->country_name }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Country" data-mask>
                                </div>
                                <br>
                                <label> Phone number({{ $country_supplier->International_dialing }}):</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-square"></i></span>
                                    </div>
    @foreach($check_list as $phone_number ) @endforeach
 <input readonly type="text"  value="{{ $phone_number->cs_telephone }}"class="form-control" data-inputmask-alias="text" placeholder="phone" data-mask>
                                </div>
                                <br>
                                <label>Fax:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fax"></i></span>
                                    </div>
    @foreach($check_list as $fax ) @endforeach
<input readonly type="text" value="{{$fax->contacts_fax}}" class="form-control" data-inputmask-alias="text" placeholder="Fax" data-mask>
                                </div>



                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- IP mask -->
                            <div class="form-group">
                                <label> Institutional Email Website:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                    </div>
                                    @foreach($check_list as $product_trade_name ) @endforeach
                                    <input readonly value="{{ $customer_address_line_one->cs_email }} " type="email" class="form-control" data-inputmask-alias="text" placeholder="Institutional email" data-mask>

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <br>
                            <span class="card card-blockquote" style="font-size:25px"> Contact person Information </span>




                            <label>Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-fax"></i></span>
                                </div>
                                @foreach($check_list as $name) @endforeach

                                <input readonly value="{{ $name->con_first_name.' '.$name->con_middle_name.' '.$name->con_last_name }} " type="text" class="form-control" data-inputmask-alias="text" placeholder="Name" data-mask>
                            </div>

                            <br>

                            <label>Position:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
                                </div>
                                @foreach($check_list as $position ) @endforeach
                                <input readonly value=" {{ $position->con_position }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
                            </div>

                                   <br>

<label>Country:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    @foreach($company_supplier_cont_country_country as $contact_cs_supplier_country_name) @endforeach
    <input readonly value=" {{ $contact_cs_supplier_country_name->country_name }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

   <br>

<label>City:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    @foreach($check_list as $con_city) @endforeach
    <input readonly value=" {{ $con_city->con_city}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

       <br>

<label>Address line one:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    @foreach($check_list as $contacts_address_line_one ) @endforeach
    <input readonly value=" {{ $contacts_address_line_one->contacts_address_line_one}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>


       <br>

<label>Address line two:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    @foreach($check_list as $contacts_address_line_two) @endforeach
    <input readonly value=" {{ $contacts_address_line_two->contacts_address_line_two }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>


                            <br>
                            @foreach($company_supplier_cont_country_country as $code) @endforeach
                            <label>Phone number({{ $code->International_dialing }}):</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                @foreach($check_list as $contacts ) @endforeach
                                <input readonly value="{{ $contacts->contacts_telephone}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Phone number" data-mask>
                            </div>
                            <br>

                            <label>Institutional Email:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-mail-reply"></i></span>
                                </div>
                                @foreach($check_list as $contacts ) @endforeach
                                <input readonly value="{{ $contacts->email}}" type="email" class="form-control" data-inputmask-alias="text" placeholder="Institutional Email" data-mask>
                            </div>
                            <br>
                            <h5 class="card card-blockquote">1.2 Name and address of the local agent </h5>

                            @foreach( $agent_contact_info as $agent_contact_info_list ) @endforeach

                            <div class="form-group">
                                <label>Name:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>

     <input 
     readonly 
     value="{{ $agent_contact_info_list->con_first_name." ".$agent_contact_info_list->con_middle_name." ".$agent_contact_info_list->con_last_name }}" 
     type="text" 
     class="form-control" 
     data-inputmask-alias="text"
     placeholder="Name" 
     data-mask >

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

    <div class="form-group">
                            <label>City:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                    </div>


                                    <input readonly value="{{ $agent_contact_info_list->ag_city}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Bussiness Address" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>



                            <!-- Date mm/dd/yyyy -->
                            <div class="form-group">
                            <label>Address:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                    </div>


                                    <input readonly value="{{ $agent_contact_info_list->ag_address_line_one.','.$agent_contact_info_list->ag_address_line_two }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Bussiness Address" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- phone mask -->
                            <div class="form-group">
                                <label>Postal Address:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>

                                    <input readonly value="{{ $agent_contact_info_list->ag_postal_code}}" type="text" class="form-control" data-inputmask-alias="text" placeholder=" Postal Address" data-mask>

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                                 

                            <!-- phone mask -->
                            <div class="form-group">
                                <label> Country:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                    </div>
                                    <input readonly value="Eritrea" type="text" class="form-control" data-inputmask-alias="text" placeholder="Country" data-mask>
                                </div>


                                <br>
                                <label> Phone number(+291):</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-square"></i></span>
                                    </div>
<input readonly value="{{ $agent_contact_info_list->ag_telephone }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="phone" data-mask>
                                </div>

   <br>
                                <label> Web Site url:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-square"></i></span>
                                    </div>
<input readonly value="{{ $agent_contact_info_list->ag_webiste_url }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="phone" data-mask>
                                </div>



                                <br>
                                <label>Fax:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fax"></i></span>
                                    </div>
 <input readonly type="text" value="{{ $agent_contact_info_list->contacts_fax_ag }}" class="form-control" data-inputmask-alias="text" placeholder="Fax" data-mask>
                                </div>



                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- IP mask -->
                            <div class="form-group">
                                <label> Institutional Email Website:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                    </div>
                                    <input readonly value="{{ $agent_contact_info_list->ag_email }}" type="email" class="form-control" data-inputmask-alias="text" placeholder="Institutional email" data-mask>

                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <br>
                            <span class="card card-blockquote" style="font-size:25px">Local Agent Contact person Information </span>




                            <label>Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-fax"></i></span>
                                </div>
                                <input readonly value="{{ $agent_contact_info_list->con_first_name ." ".$agent_contact_info_list->con_middle_name." ".$agent_contact_info_list->con_last_name }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Name" data-mask>
                            </div>

                            <br>

                            <label>Position:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
                                </div>
                                <input readonly value="{{ $agent_contact_info_list->con_position }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
                            </div>


       <br>

<label>Country:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    <input readonly value="Eritrea" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

                            <br>

                            <label>City:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    <input readonly value="{{ $agent_contact_info_list->con_position }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

                            <br>


<label>Address line one:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    <input readonly value="{{ $agent_contact_info_list->con_address_line_one}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

                            <br>

                            <label>Address line two:</label>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-person-walking-with-cane"></i></span>
    </div>
    <input readonly value="{{ $agent_contact_info_list->con_address_line_two }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Position" data-mask>
</div>

                            <br>

                            <label>Phone number(+291):</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input readonly value="{{ $agent_contact_info_list->con_telephone }}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Phone number" data-mask>
                            </div>
                            <br>

                            <label>Institutional Email:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                </div>
                                <input readonly value="{{ $agent_contact_info_list->con_email}}" type="text" class="form-control" data-inputmask-alias="text" placeholder="Institutional Email" data-mask>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


        <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">2. Type of Application</h3>
                        </div>


                        @foreach( $check_list as $application_type ) @endforeach @php if( $application_type->application_type == 1 ) { 
                            $new_application = 'checked'; $renew_application_moh = ''; 
                            $renew_application_SRA = ''; $renew_application_WHO_PQP=''; 
                            $fast_track_application
                        = ''; } if( $application_type->application_type == 2 && $application_type->fast_track_details=='MoH Tender' ) { $fast_track_application = 'checked'; $new_application = ''; $renew_application_moh = 'checked'; $renew_application_SRA
                        = ''; $renew_application_WHO_PQP=''; } if( $application_type->application_type == 2 && $application_type->fast_track_details=='SRA product' ) { $new_application = ''; $fast_track_application = 'checked'; $renew_application_moh
                        = ''; $renew_application_SRA = 'checked'; $renew_application_WHO_PQP=''; } if( $application_type->application_type == 2 && $application_type->fast_track_details=='WHO PQP' ) { $new_application = ''; $fast_track_application = 'checked';
                        $renew_application_moh = ''; $renew_application_SRA = ''; $renew_application_WHO_PQP = 'checked'; } @endphp


                        <div class="card-body">
                            <!-- Minimal style -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input readonly {{ @$new_application}} type="checkbox" id="checkboxPrimary1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> New Application </label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- Minimal red style -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-danger d-inline">
                                            <input readonly type="checkbox" id="checkboxDanger1">
                                            <label for="checkboxDanger1">
                        </label>
                                        </div>

                                        <div class="icheck-danger d-inline">

                                            <label for="checkboxDanger3">
                        Applications for renewal 
                        </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Minimal red style -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$fast_track_application }} type="checkbox" id="checkboxSuccess1">
                                            <label for="checkboxSuccess1">
                        </label>
                                        </div>

                                        <div class="icheck-success d-inline">

                                            <label for="checkboxSuccess3">
                        Request fast track
                        </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$renew_application_WHO_PQP}} type="radio" id="checkboxSuccess1">
                                            <label for="checkboxSuccess1">
                        </label>
                                        </div>

                                        <div class="icheck-success d-inline">

                                            <label for="checkboxSuccess3">
                        WHO PQP
                        </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-4 pull-right">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$renew_application_SRA }} type="radio" id="checkboxSuccess1">
                                            <label for="checkboxSuccess1">
                        </label>
                                        </div>

                                        <div class="icheck-success d-inline">

                                            <label for="checkboxSuccess3">
                        SRA product
                        </label>
                                        </div>
                                    </div>
                                </div>

                            </div>




                            <div class="row">
                                <div class="col-md-4">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$renew_application_moh }} type="radio" id="checkboxSuccess1">
                                            <label for="checkboxSuccess1">
                        </label>
                                        </div>

                                        <div class="icheck-success d-inline">

                                            <label for="checkboxSuccess3">
                        MoH Tender
                        </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    
                </div>



                <!-- /.col (left) -->
                <div class="col-md-12">


                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">3. Details of the Product</h3>
                        </div>
                        <div class="card-body">
                            <!-- Color Picker -->
                            <span class="card card-blockquote" style="font-size:20px">3.1 Product Name and Description  </span>

                            <div class="form-group">


       <div class="form-group">
                                <label>Generic/Approved/International Non-proprietary Name(s):</label>

                                <div class="input-group my-colorpicker2">
                                    {{ $font_product_name='' }}@foreach( $check_list as $product_name ) @endforeach
                                    <input readonly value="{{  $product_name->product_name }}" type="text" class="form-control" placeholder="Generic/Approved/International Non-proprietary Name(s)">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>

            <label>Proprietary/Trade name of the product::</label> @foreach( $check_list as $product_name ) @endforeach

                       <div class="input-group my-colorpicker2">
            <input readonly value=" {{  $product_name->product_trade_name}}" type="text" class="form-control my-colorpicker1" placeholder="Proprietary/Trade name of the product:">


                                   <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>

 </div>
                            </div>
                            <!-- /.form group -->

                            <div class="form-group">
                                <label>Dosage form:</label>
                             
                                <div class="input-group my-colorpicker2">
                                    @foreach ($dosage_forms as $dosage_formss)@endforeach

                                    <input readonly value="{{  $dosage_formss->name   }}" type="text" class="form-control" placeholder="Dosage form:">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                             </div>
                                <!-- /.input group -->
                            </div>

                                    <div class="form-group">
                                <label>Route of Administraion:</label>
                             
                                <div class="input-group my-colorpicker2">
                                    @foreach ($dosage_forms as $dosage_formss)@endforeach

                                    <input readonly value="{{  $dosage_formss->route_name  }}" type="text" class="form-control" placeholder="Dosage form:">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                             </div>
                                <!-- /.input group -->
                            </div>

                     


                            <!-- <div class="form-group">
                                @foreach( $check_list as $product_name ) @endforeach
                                <label>Strength(s) per dosage unit:</label>

                                <div class="input-group my-colorpicker2">
                                    <input readonly value="{{$product_name->product_name }}" type="text" class="form-control" placeholder="Strength(s) per dosage unit">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group --
                            </div> -->


                            <div class="form-group">
                                <label>Pharmacotherapeutic Classification (Anatomic-Therapeutic Classification system):</label>

                                <div class="input-group my-colorpicker2">

                                    @foreach ($check_list  as $pharmaco_therapeutic_classification ) @endforeach
                                    <input readonly value="{{  $pharmaco_therapeutic_classification->pharmaco_therapeutic_classification   }}" type="text" class="form-control" placeholder="Pharmacotherapeutic Classification (Anatomic-Therapeutic Classification system)">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>Storage conditions:</label>

                                <div class="input-group my-colorpicker2">


                                    @foreach( $check_list as $storage_condition ) @endforeach
                                    <input readonly value="{{ $storage_condition->storage_condition  }}" type="text" class="form-control" placeholder="Storage conditions:">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->

                            </div>




                            <div class="form-group">
                                <label>Proposed shelf life:                 </label>

                                <div class="input-group my-colorpicker2">



                                    @foreach( $check_list as $Proposed_shelf_life ) @endforeach


                                    <input readonly value="{{ $Proposed_shelf_life->proposed_shelf_life_amount }}" type="text" class="form-control" placeholder="Proposed shelf life">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



                            <div class="form-group">
                                <label>Proposed shelf life (after reconstitution or dilution): </label>

                                <div class="input-group my-colorpicker2">

                                    @foreach( $check_list as $proposed_shelf_life_after_reconstitution_amount ) @endforeach
                                    <input readonly value="{{  $proposed_shelf_life_after_reconstitution_amount->proposed_shelf_life_after_reconstitution_amount  }}" type="text" class="form-control" placeholder="Proposed shelf life (after reconstitution or dilution)">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->


                            <div class="form-group">
                                <label>Shelf Life (in months)</label>

                                <div class="input-group my-colorpicker2">

                                    @foreach( $check_list as $shelf_life_amount ) @endforeach

                                    <input readonly value=" {{ $shelf_life_amount->shelf_life_amount  }}" type="text" class="form-control" placeholder="Shelf Life (in months)">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <div class="form-group">
                                <label>Visual Description:</label>

                                <div class="input-group my-colorpicker2">

                                    @foreach( $check_list as $visual_description ) @endforeach
                                    <input readonly value="{{ $visual_description->visual_description}}" type="text" class="form-control" placeholder="Visual Description">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <div class="form-group">
                                <label>Commercial presentation of the product:</label>

                                <div class="input-group my-colorpicker2">
                                    @foreach( $check_list as $commercial_presentation ) @endforeach
                                    <input readonly value="{{ $commercial_presentation->commercial_presentation}}" type="text" class="form-control" placeholder="Commercial presentation of the product">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



                            <div class="form-group">
                                <label>Container, closure and administration devices:</label>

                                <div class="input-group my-colorpicker2">
                                    @foreach( $check_list as $container ) @endforeach
                                    <input readonly value="{{ $container->container}}" type="text" class="form-control" placeholder="Container, closure and administration devices">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->


                            <div class="form-group">
                                <label>Packaging and pack size:</label>

                                <div class="input-group my-colorpicker2">
                                    @foreach( $check_list as $packaging ) @endforeach
                                    <input readonly value="{{ $packaging->packaging }}" type="text" class="form-control" placeholder="Packaging and pack size">

                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-square"></i></span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->


                            <div class="form-group">
                                <label>Category of use:</label>

                                <!-- <div class="input-group my-colorpicker2">
                  <input readonly type="checkbox" id="checkboxSuccess1"><label> POM (Prescription only medicine </label><br>
                  
                  <input readonly type="checkbox" id="checkboxSuccess1"><label> P (Pharmacy Medicine) </label><br>
                  
                  <input readonly type="checkbox" id="checkboxSuccess1"><label> OTC (Over The Counter medicine)  </label><br>
                 </div> -->
                            </div>

                            @foreach( $check_list as $category_use ) @endforeach @php 
                     if( $category_use->category_use == 'P (Pharmacy Medicine)')
                             { $checked_pharmacy = 'checked'; $checked_otc = ''; @$checked_pom = '';  @$checked_controlled = ''; @$checked_health = ''; } 
                     if( $category_use->category_use == 'OTC (Over The Counter medicine)')
                            { $checked_otc = 'checked'; $checked_pharmacy = ''; @$checked_pom = '';   @$checked_controlled = ''; @$checked_health = ''; } 
                    if( $category_use->category_use == 'POM (Prescription only medicine)' ) 
                             { $checked_pom = 'checked'; $checked_pharmacy = ''; $checked_otc = '';  @$checked_controlled = ''; @$checked_health = ''; }
                    
                             if( $category_use->category_use == 'Controlled Substances' ) 
                             { $checked_pom = ''; $checked_pharmacy = ''; $checked_otc = '';  @$checked_controlled = 'checked'; @$checked_health = ''; }
                    

                      if( $category_use->category_use == 'Hosiptal/Health Facilities Only Medicines' ) 
                             { $checked_pom = ''; $checked_pharmacy = ''; $checked_otc = '';  @$checked_controlled = ''; @$checked_health = 'checked'; }
                    
                     @endphp

                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input readonly {{ $checked_pom }} type="checkbox" id="checkboxPrimary1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> POM (Prescription only medicine </label>
                                        </div>
                                    </div>

                                </div>

                            </div>



                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-danger d-inline">
                                            <input readonly {{ $checked_pharmacy }} type="checkbox" id="checkboxDanger1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> P (Pharmacy Medicine) </label>
                                        </div>
                                    </div>

                                </div>

                            </div>



                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ $checked_otc }} type="checkbox" id="checkboxSuccess1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> OTC (Over The Counter medicine) </label>
                                        </div>
                                    </div>

                                </div>

                            </div>



                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$checked_controlled }} type="checkbox" id="checkboxSuccess1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> Controlled Substances </label>
                                        </div>
                                    </div>

                                </div>

                            </div>




                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                            <input readonly {{ @$checked_health }} type="checkbox" id="checkboxSuccess1">
                                            <label for="checkboxPrimary1">
                        </label>
                                        </div>

                                        <div class="icheck-primary d-inline">
                                            <label for="checkboxPrimary3"> Hosiptal/Health Facilities Only Medicines </label>
                                        </div>
                                    </div>

                                </div>

                            </div>





                            <span class="card card-blockquote" style="font-size:25px">3.2 Product Composition  </span>
                            <h5 style="font-size:15px"> Indicate per unit dosage form (Tablet, Capsule, 2ml) the Complete qualitative and quantitative composition of the product </h5>
                            <table class="table table-bordered  table-condensed table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td>Name (INN)</td>
                                        <td>Quantity</td>
                                        <td>Reason for inclusion</td>
                                        <td>Reference standards</td>
                                        <td>Type</td>
                                    </tr>

                                    @foreach($product_composition_info as $compose)
                                    <tr>
                                        <td>{{ $compose->composition_name }}</td>
                                        <td>{{ $compose->quantity }}</td>
                                        <td> {{ $compose->reason }}</td>
                                        <td> {{ $compose->reference_standard}}</td>
                                        <td> {{ $compose->type}}</td>

                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>

                            <br>
                            <span class="card card-blockquote" style="font-size:25px">3.3 Product Manufacturer(s)   </span>
                            <h5 style="font-size:15px">3.3.1 Name(s) and complete address(es) of the manufacturer(s) of the finished pharmaceutical product (FPP), including the final product release if different from the manufacturer. </h5>

                            <div style="">

                                <table class="table table-bordered  table-condensed table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Manufactures Name</td>
                                            <td>City</td>
                                            <td>State</td>
                                            <td>Address</td>
                                            <td>Postal Address</td>
                                            <td>Telephone</td>
                                            <td>Activity </td>
                                            <td>Block </td>
                                            <td> Unit </td>
                                        </tr>
                              

@foreach($manufacturers_info as $manu_info) 
<tr>
<td >  {{ $manu_info->manufacturer_name }}    </td>
<td>   {{  $manu_info->manufacturer_city }}    </td>
<td>   {{ $manu_info->manufacturer_state }}   </td>
<td>   {{ $manu_info->manufacturer_address_line_one ." ". $manu_info->manufacturer_address_line_two }}</td>
<td>   {{ $manu_info->manufacturer_postal_code }} </td>
<td>   {{ $manu_info->manufacturer_telephone }} </td>
<td>   {{ $manu_info->manufacturer_activity }}  </td>
<td>   {{ $manu_info->manufacturer_block }}  </td>
<td>   {{ $manu_info->manufacturer_unit}}  </td>
</tr>

@endforeach

                                    </tbody>
                                </table>
                            </div>
                            <br>

                            <h5 style="font-size:15px">3.3.2 Name(s) and complete address(es) of the manufacturer(s) of the API(s) </h5>


                            <!--  <div style="overflow-x:auto;" > -->

                            <div style="" >
                            
                            <table class="table table-bordered  table-condensed table-striped">
                                <tbody>

                                    <tr>
                                        <td> API Product Manufatures</td>
                                        <td> API Name</td>
                                        <td>City</td>
                                        <td>State</td>
                                        <td>Address</td>
                                        <td>Postal Address</td>
                                        <td>Telephone</td>
                                        <td>Block</td>
                                        <td>Unit</td>

                                    </tr>

                                    @foreach($api_manufacturers_info as $api_manu_info)
                                    <tr>
                                        <td> {{ $api_manu_info->manufacturer_name }} </td>
                                        <td> {{ $api_manu_info->api_name}} </td>
                                        <td> {{ $api_manu_info->city }} </td>
                                        <td> {{ $api_manu_info->state }} </td>
                                        <td> {{ $api_manu_info->addressline_one."".$api_manu_info->addressline_two }} </td>
                                        <td> {{ $api_manu_info->postal_code }} </td>
                                        <td> {{ $api_manu_info->telephone }} </td>
                                        <td> {{ $api_manu_info->block }} </td>
                                        <td> {{ $api_manu_info->unit }} </td>
                                       

                                    </tr>
                                    @endforeach

                                </tbody>
                                </table>
                            </div>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->





                    <!-- iCheck -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"> 4. Declaration </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered  table-condensed table-striped">

                                <tbody>


                                </tbody>

                                <div class="card-body">
                                    <p class="decleration">

                                        @foreach ( $product_details as $key => $value_name) @endforeach @foreach ($dosage_forms as $key => $value_dosage) @endforeach @foreach($company_suppliers as $value_company) @endforeach


                                        <p class="decleration">
                                            I, the undersigned certify that all the information in this form and all accompanying documentation submitted to Eritrea for the 
                                            registration of 
                                            ({{ $value_name->product_name }}, 
                  
                                            {{ $value_dosage->name
                                            }}) manufactured at 
                                            
                        ({{ $manufacturers_info_for_declaration->manufacturer_name }} , 
                        {{ $manufacturers_info_for_declaration->manufacturer_address_line_one}} 
                        {{ $manufacturers_info_for_declaration->manufacturer_address_line_two}}) is true and correct. I further certify that I have examined the following
                        statements and I attest to their correctness:-


                                        </P>

                                        <p class="decleration">
                                            1. The current edition of the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products or equivalent guideline is applied in full in all premises involved in the manufacture of this medicine.
                                            <br/> 2. The formulation per dosage form correlates with the master formula and with the batch manufacturing record.
                                            <br/> 3. The manufacturing procedure is exactly as specified in the master formula and batch manufacturing record.
                                            <br/> 4. Each batch of all starting materials is either tested or certified (in accompanying certificate of analysis for that batch) against the full specifications in the accompanying documentation and must
                                            comply fully with those specifications before it is released for manufacturing purposes.
                                            <br/> 5. All batches of the active pharmaceutical ingredient(s) are obtained from the source(s) specified in the accompanying documentation.
                                            <br/> 6. No batch of active pharmaceutical ingredient(s) will be used unless a copy of the batch certificate established by the manufacturer is available.
                                            <br/> 7. Each batch of the container/closure system is tested or certified against the full specifications in the accompanying documentation and complies fully with those specifications before released for the
                                            manufacturing purposes.
                                            <br/> 8. Each batch of the finished product is either tested, or certified (in an accompanying certificate of analysis for that batch), against the full specifications in the accompanying documentation and complies
                                            fully with release specifications before released for sale.
                                            <br/> 9. The person releasing the product is an authorized person as defined by the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products
                                            <br/> 10. The procedures for control of the finished product have been validated. The assay method has been validated for accuracy, precision, specificity and linearity.
                                            <br/> 11. All the documentation referred to in this application is available for review during GMP inspection.
                                            <br/> 12. Clinical trials (where applicable) were conducted in accordance with ICH, WHO or equivalent guidelines for Good Clinical Practice,
                                            <br/> I also agree that:
                                            <br/> 13. As a holder of marketing authorization/registration of the product I will adhere to Eritrean National Pharmacovigilance Policy requirements for handling adverse reactions.
                                            <br/> 14. As holder of registration I will adhere to Eritrean requirements for handling batch recalls of the products.
                                            <br/>
                                        </p>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <form>
                                                    <div class="card-body">
                                                        <div class="form-check">

                                                            <label class="form-check-label" for="exampleCheck1"><b> I agree  </b></label>
                                                            <input disabled type="checkbox" checked style="position:relative;left:18%" class="form-check-input" id="customCheckbox1">
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-sm-3 no-print">
                                                <!-- <label>I agree <input readonly style="width:20px;position:float-center;" class="form-control" id="customCheckbox1" type="checkbox" name="customCheckbox1"   /></label>   -->

                                            </div>

                                        </div>
                                </div>


                                @php foreach($decleration_info as $decleration){} $decleration_name = $decleration->decname; $decleration_Qualification = $decleration->qualification; $decleration_position = $decleration->position; $decleration_date = $decleration->date; @endphp


                                <div class="col-12 col-md-12" style="position:relative;left:0%">
                                    <label> Declaration Name </label> : <input readonly value="{{ $decleration_name }}" disabled class="form-control" id="decleration_name" type="text" name="decleration_name" placeholder="Name:" />
                                    <label> Qualification </label> : <input readonly value="{{ $decleration_Qualification }}" disabled class="form-control" id="qualification" type="text" name="qualification" placeholder="Qualification:" />
                                    <label> Position </label> :<input readonly value="{{$decleration_position}}" disabled class="form-control" id="position_in_the_company" type="text" name="Position_in_the_company" Placeholder="Position in the company" />
                                    <!-- <input readonly class="form-control" id="Signature" type="text" name="Signature"  Placeholder="Signature" /> -->
                                    <label>  Date </label> : <input readonly value="{{ $decleration_date }}" disabled class="form-control" id="Date_decleration" type="date" name="Date" />
                                    <!-- <textarea class="form-control" id="OfficialSeal: " type="text" name=": " Placeholder="Officialstamp" /></textarea> -->
                                </div>
                        </div>
                        <!-- /.card-body -->



                        </table>
                    </div>

                </div>
                <!-- /.card -->

                <!-- /.card -->
            </div>
            <!-- /.col (right) -->
        </div>

    </div>

    @foreach( $check_list as $app ) @endforeach

<div class="row no-print">
<a href="{{ route('completeApplicationStatus',$app->application_id)}}" rel="noopener" target="_blank" class="btn btn-info"><i class="fas fa-print"></i>  Print </a>
</div>

<div class="tab-pane fade" id="custom-content-below-rejected" role="tabpanel" aria-labelledby="custom-content-below-rejected-tab">

</div>
<div class="tab-pane fade" id="custom-content-below-defered" role="tabpanel" aria-labelledby="custom-content-below-defered-tab">

</div>
</div>
</div>
</div>
















<script>
if (confirm("This page is about to print")) {
window.addEventListener("load", window.print());
//window.location="{{ url()->previous() }}";
           } 
else {  window.location="{{ url()->previous() }}";
  
  }

</script>


<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

@endsection