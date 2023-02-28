@extends('layouts.app_app')
@section('content')
    <?php
    use Carbon\Carbon;
    ?>


<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- start: Css -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

@foreach($applications as $app) @endforeach

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<div class="card">
        {{--<div class="card-header">
        <h3 class="card-title">Application</h3>
        <div class="card-tools">

            <div class="info-box bg-light">
                <div class="info-box-content">

                        @if($remaining_time_formatted == null)
                            <span class="info-box-text text-center text-red"> Re-registration Period Expired </span>
                        @else
                            <span class="info-box-text text-center text-muted"> {{$remaining_time_formatted}} </br>
                                Remaining</span>
                        @endif
            </div>
        </div>
    </div>
        </div>--}}
    <!-- /.card-header -->

    <div class="card-body">

            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->

                        <div class="small-box bg-success">
                            <div class="inner">
                                <h5>
                                    {{Carbon::create($certification->certified_date)->format('Y-m-d')}}
                                </h5>
                                <p> Certified Date</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-check"></i>
                            </div>
                            <a href="#" class="small-box-footer" data-toggle="tooltip" data-placement="bottom"
                               title="Date of Market Authorization Certification">
                                More info <i class="fas fa-circle-info"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h5>
                                    {{Carbon::create($certification->expiry_date)->format('Y-m-d')}}
                                </h5>

                                <p>Expiry Date</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-times"></i>
                            </div>
                            <a href="#" class="small-box-footer" data-toggle="tooltip" data-placement="bottom"
                               title="Expiry Date of Market Authorization Certification">
                                More info <i class="fas fa-circle-info"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->

                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h5>
                                    {{$reregistration_dashboard['remaining_time_formatted']}}
                                </h5>
                                <p> Countdown Timer</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clock"></i>
                            </div>
                            <a href="#" class="small-box-footer" data-toggle="tooltip" data-placement="bottom"
                               title="Time remaining till re-registration expiry.">
                                More info <i class="fas fa-circle-info"></i></a>
                        </div>
                    </div>

                        <div class="col-lg-3 col-6">
                            <!-- small box -->

                            <div class="{{$reregistration_dashboard['status_theme']}}">
                                <div class="inner">
                                    <h5>
                                        {{$reregistration_dashboard['status_text']}}
                                    </h5>
                                    <p> Registration Status</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-info-circle"></i>
                                </div>
                                <a href="#" class="small-box-footer" data-toggle="tooltip" data-placement="bottom"
                                   title="{{$reregistration_dashboard['tooltip_message']}}">
                                    More info <i class="fas fa-circle-info"></i></a>
                            </div>

                    </div>


                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->

            </div>


        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Application Detail</h1>
                    </div>

                </div>
            </div>
            <!-- /.container-fluid -->
        </section>



        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                    {{--<h3 class="card-title">Application Detail</h3>--}}

                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        @if($certification != null and $certification->status == 'reregistration_initiated')
                                            <button class="btn btn-primary btn-md" title="PSUR disabled because Re-registration has been initiated." disabled>
                                                <i class="fa fa-edit"></i>PSUR</button>
                                        @else
                                                <button class="btn btn-primary btn-md" title="Add New PSUR"
                                                        id="modal_upload_psur"><i class="fa fa-edit"></i> PSUR
                                                </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        @if($certification != null and $certification->status == 'reregistration_initiated')
                                            <button class="btn btn-primary btn-md" title="Variation disabled because Re-registration has been initiated." disabled>
                                                <i class="fa fa-plus-circle"></i>Variation</button>
                                        @else
                                            <a href="{{ route('variation_applicant_index', $certification->id) }}"
                                                   class="btn btn-primary btn-md" title="Submit New Variation"><i
                                                            class="fa fa-plus-circle"></i> Variation</a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        @if($certification != null and $certification->status == 'reregistration_initiated')
                                            <button class="btn btn-primary btn-md" title="Withdraw disabled because Re-registration has been initiated." disabled>
                                                <i class="fa fa-circle-left"></i> Withdraw</button>
                                        @else


                <!-- <a  id="modal_withdrawl"  type="button" href="{{route('withdrawals.withdrawn_index')}}" class="btn btn-primary btn-md">
                                                <i class="fa fa-circle-left"></i> Withdraw</a> -->

                                                <button id="modal_withdrawl" type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#modal-withdraw" title="Submit Withdrawal Request">
                                                    <i class="fa fa-circle-left"></i> Withdraw Product
                                                </button>


                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                            @if($certification != null and $certification->status == 'reregistration_open')
                                            <a
                                                    type="button"
                                                    href="{{ route('application_reception_re_registration', $app->application_id)}}"
                                                    class="btn btn-primary btn-md"
                                                        title="Renew Registration (Re-register Product)."> <i
                                                            class="fas fa-file"></i> Renew
                                            </a>

                                            @elseif($certification != null and $certification->status == 'renewal_request_accepted')
                                                <a
                                                        type="button"
                                                        href="{{ route('application_reception_re_registration', $app->application_id)}}"
                                                        class="btn btn-primary btn-md"
                                                        title="Registration renewal request has been accepted. Please renew registration."> <i class="fas fa-file"></i> Renew
                                                </a>
                                            @elseif($certification != null and $certification->status == 'reregistration_closed')
                                                <button href="#" class="btn btn-secondary btn-md"
                                                        title="Registration renewal will open six months prior to certificate expiry."
                                                        disabled> <i class="fas fa-file"></i> Renew
                                                </button>
                                            @elseif($certification != null and $certification->status == 'reregistration_expired')
                                                <?php
                                                //compute diff between expiry_date and now, if < 3 months, allow applicant
                                                // to send  renewal extension request, else disable renewal permanently
                                                /** @var TYPE_NAME $certification */
                                                $expiry_date = Carbon::create($certification->expiry_date);
                                                $diffInMonths_expiryDate_to_now = $expiry_date->diffInMonths(Carbon::now(), false);
                                                ?>
                                                @if($diffInMonths_expiryDate_to_now >=0 and $diffInMonths_expiryDate_to_now < 4)
                                                    <button href="#" class="btn btn-warning btn-md"
                                                            title="Renewal extension request can be sent within 3 months of renewal expiry."
                                                            data-toggle="modal"
                                                            data-target="#deadline_extension_request">
                                                        <i class="fa fa-clock"></i> Request Renewal Extension
                                                    </button>


                                                @else
                                                    <button href="#" class="btn btn-secondary btn-md"
                                                            title="Both Registration Renewal and Renewal Extension Request Period has expired.
                                                             Renewal Disabled Permanently."


                                                            disabled>Renew
                                                    </button>
                                                @endif
                                        @else
                                                <button href="#" class="btn btn-secondary btn-md"
                                                        title="Renewal Disabled."
                                                        disabled>Renew
                                                </button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-content-below-defered-tab" data-toggle="pill"
                                        href="#custom-content-below-defered" role="tab"
                                        aria-controls="custom-content-below-defered" aria-selected="false"><i
                                            class="fas fa-info-circle fa-2"></i><span class="badge badge-primary">
                                            Application Info </span></a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="custom-content-below-re-register-tab" data-toggle="pill"
                                        href="#custom-content-below-re-register" role="tab"
                                        aria-controls="custom-content-below-re-register" aria-selected="true">
                                        <span class="badge badge-primary"> Re-Register </span> </a>
                                </li>


                                <!-- <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Accepted </a>
        </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" id="custom-content-below-variation-tab" data-toggle="pill"
                                        href="#custom-content-below-variation" role="tab"
                                        aria-controls="custom-content-below-variation" aria-selected="false"><span
                                            class="badge badge-primary">Variation</span> </a>
                                </li> -->

                            </ul>

                        </div>




@include('layouts.modal_upload_psur')

@include('layouts.modal_withdraw')


                        <div class="tab-content" id="custom-content-below-tabContent">
                            <div class="tab-pane fade" id="custom-content-below-re-register" role="tabpanel"
                                aria-labelledby="custom-content-below-re-register-tab">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                    <th>Re-registerstration Num.</th>
                                            <th>Application Number</th>
                                            <th>Application Type</th>
                                            <th>Application Status</th>
                                            <th>Brand Name</th>
                                            <th>Applicant Name</th>
                                            <th>Applicant Contact person</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody {{ $i=1 }}>
                                        @foreach( $applications_re_register as $application_reg)

                                        @php $explode = explode(',',$application_reg->hold_progress_wizard); @endphp

                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $application_reg->re_registration_number }}</td>
                                            <td>{{ $application_reg->application_number }}</td>
                    <td>{{ $application_reg->registration_type }}</td>
                                            <td width="50px">
                                                @php if($application_reg->application_status == 'processing') { $badge =
                                                'badge bg-warning'; } 
                            elseif($application_reg->application_status != 'processing') { $badge = 'badge bg-success'; } @endphp
                                                <span class="{{  $badge }}"> {{ $application_reg->application_status }}
                                                </span>
                                            </td>
                                            <td>{{ $application_reg->product_trade_name }}</td>
                                            <td>{{ $application_reg->trade_name }}</td>
                                            <td>{{ $application_reg->first_name." ".$application_reg->middle_name." ".$application_reg->last_name }}
                                            </td>
                                            <td>
                                                @if(in_array('8',$explode))
                                                
<a style="color:white" class='btn btn-sm btn-info' title="View application details" 
href="{{ route('view_completed_application_re',$application_reg->appid)  }}">
<i class='fas fa-eye'></i>
</a>

  </a> 
                                                @else
    <a  title="Edit" class='btn btn-sm btn-warning' 
    href="{{ route('application_reception_re_registration_update',$application_reg->appid)  }}">
    <i class='fas fa-edit'></i>
    </a>



                                                @endif

   

                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>


                                    </tfoot>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="custom-content-below-variation" role="tabpanel"
                                aria-labelledby="custom-content-below-variation-tab">
<table id="example5" class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>ID</th>
                    <th>Application Number</th>
                    <th>Application Status</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Applicant Contact person</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody {{ $i=1 }}>
                @foreach($applications as $application) @php $explode = explode(',', $application->hold_progress_wizard); @endphp

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $application->application_number }}</td>
                    <td>
                    @php
              if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
              elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
              elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
              
                    @endphp 
                       
                       
                        <span class="{{  $badge }}"> {{ $application->application_status }} </span>
                    </td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->trade_name }}</td>
                    <td>{{ $application->first_name." ".$application->middle_name." ".$application->last_name }}</td>
                    <td>
                        @if(in_array('8',$explode))
                        <a style="color:white" class='btn btn-sm btn-info' title="View application details" href="{{ route('view_completed_application',$application->application_id)  }}">
                            <i class='fas fa-eye'></i>
                        </a>
                        @else
                        <a title="Edit" class='btn btn-sm btn-warning' href="{{ route('application.update',$application->application_id)  }}">
                            <i class='fas fa-edit'></i>
                        </a>

                        @endif

                        <!-- <a href="{{  route('application.invoice_generate',$application->application_id)  }}"
<i class='fas fa-file-invoice-dollar'></i> -->

                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>


            </tfoot>
        </table>
                            </div>

                          
                            <div class="tab-pane fade  show active" id="custom-content-below-defered" role="tabpanel"
                                aria-labelledby="custom-content-below-defered-tab">

                                <div class="col-row">
                                    <h3 class="text-primary text-2xl"><i class="fas fa-info-circle fa-2"></i>
                                        Application Info</h3>
                                    <p class="text-muted">
                                        <div class="container-fluid">
                                            <!-- Section one product details  -->
                                            <div class="card card-outline  card-primary  collapsed-card ">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 1: Application Type</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        value="{{ $application_id->application_id }}" id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <table class="table table-bordered  table-condensed table-striped">
                                                        <thead>
                                                            <!-- <tr>
                    <th>Lists</th>
                      <th> </th>

                    </tr> -->
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
                                                                <td>Application Number</td>
                                                                <td>{{ $font_product_name='' }}
                                                                    @foreach( $check_list as $product_name )
                                                                    {{  $product_name->application_number }}
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
                                                                <td>Application Type</td>
                                                                <td>
                                                                    @foreach($check_list as $product_trade_name )
                                                                    @if($product_trade_name->application_type == 1)
                                                                    Standard Mode @else Fast Track Mode /
                                                                    {{ $product_trade_name->fast_track_details }} @endif
                                                                    @break
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <style>
                                            th,
                                            td {
                                                padding: 15px;
                                                text-align: left;
                                                border: 0.2px solid grey;
                                                border-bottom: 1px solid #ddd;
                                            }

                                            tr {
                                                border: 1px dashed black;
                                            }
                                        </style>



                                        <!-- Section Tw o  Company Supplier information -->




                                        <div class="container-fluid">
                                            <!-- Section one product details  -->
                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 2: Company Supplier Information</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">
                                                            <thead>
                                                                <!-- <tr>
                    <th>Lists</th>
                      <th> </th>

                    </tr> -->
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
                                                                    <td>Application Trade Name</td>
                                                                    <td>{{ $font_product_name='' }}
                                                                        @foreach( $check_list as $product_name )
                                                                        {{  $product_name->tname}}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Country</td>
                                                                    <td>
                                                                        @foreach($company_supplier_info_country as
                                                                        $country_supplier )
                                                                        {{ $country_supplier->country_name }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>State</td>
                                                                    <td>
                                                                        @foreach($check_list as $customer_state )
                                                                        {{ $customer_state->company_supplier_state }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td>Address Line One</td>
                                                                    <td>
                                                                        @foreach($check_list as
                                                                        $customer_address_line_one )
                                                                        {{ $customer_address_line_one->company_supplier_address_line_one }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Address Line Two</td>
                                                                    <td>
                                                                        @foreach($check_list as
                                                                        $customer_address_line_one )
                                                                        {{ $customer_address_line_one->company_supplier_address_line_two }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Institutional Email</td>
                                                                    <td>
                                                                        @foreach($check_list as $product_trade_name )
                                                                        {{ $customer_address_line_one->cs_email }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>


                        <tr> <td>Postal Address</td>
                                                                    <td>
                                                                        @foreach($check_list as $product_trade_name )
                                                                        {{ $customer_address_line_one->cs_postal_code }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td>Web URL</td>
                                                                    <td>
                                                                        @foreach($check_list as $cs_web )
                                                                        {{ $cs_web->cs_webiste_url }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td>Contact First Name</td>
                                                                    <td>
                                                                        @foreach($check_list as $name)
                                                                        {{ $name->con_first_name }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>contact Middle Name</td>
                                                                    <td>
                                                                        @foreach($check_list as $name )
                                                                        {{ $name->con_middle_name }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact Last Name</td>
                                                                    <td>
                                                                        @foreach($check_list as $name )
                                                                        {{ $name->con_last_name }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact's Position</td>
                                                                    <td>
                                                                        @foreach($check_list as $position )
                                                                        {{ $position->con_position }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact's City</td>
                                                                    <td>
                                                                        @foreach($check_list as $city_ )
                                                                        {{ $city_->con_city }}

                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact's Address Line One</td>
                                                                    <td>
                                                                        @foreach($check_list as $contacts_address_line )
                                                                        {{ $contacts_address_line->contacts_address_line_one }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact's Address Line Two</td>
                                                                    <td>
                                                                        @foreach($check_list as $contacts_address_line )
                                                                        {{ $contacts_address_line->contacts_address_line_two }}
                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Contact's Telephone</td>
                                                                    <td>
                                                                        @foreach($check_list as $contacts )

                                                                        {{ $contacts->contacts_telephone}}

                                                                        @break
                                                                        @endforeach
                                                                    </td>
                                                                </tr>



                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>




                                            <!--       Section 3               -->

                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 3: Agent Information</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">
                                                            <thead>
                                                                <!-- <tr>
                    <th>Lists</th>
                      <th> </th>

                    </tr> -->
                                                            </thead>
                                                            <tbody>

                                                                @foreach( $agent_contact_info as
                                                                $agent_contact_info_list ) @endforeach

                                                                <tr>
                                                                    <td>Applicant Name</td>
                                                                    <td>{{ $agent_contact_info_list->ag_trade_name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>State</td>
                                                                    <td>{{ $agent_contact_info_list->ag_state }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Address Line One</td>
                                                                    <td>{{ $agent_contact_info_list->ag_address_line_one }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Address Line Two</td>
                                                                    <td>{{ $agent_contact_info_list->ag_address_line_two }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>City</td>
                                                                    <td>{{ $agent_contact_info_list->ag_city }}</td>
                                                                </tr>
            <tr><td>Postal Address</td><td >{{ $agent_contact_info_list->ag_postal_code  }}</td></tr>
                                                                <tr>
                                                                    <td>Telephone</td>
                                                                    <td>{{ $agent_contact_info_list->ag_country_code."".$agent_contact_info_list->ag_telephone }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Web Address</td>
                                                                    <td>{{ $agent_contact_info_list->ag_webiste_url }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                <td>Institutional Email</td>
                                                                    <td>{{ $agent_contact_info_list->ag_email }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contacts Full Name</td>
                                                                    <td>{{ $agent_contact_info_list->con_first_name ." ".$agent_contact_info_list->con_middle_name." ".$agent_contact_info_list->con_last_name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contact's City</td>
                                                                    <td>{{ $agent_contact_info_list->con_city }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contact's Position</td>
                                                                    <td>{{ $agent_contact_info_list->con_position }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contacts Address Line One</td>
                                                                    <td>{{ $agent_contact_info_list->con_address_line_one }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contacts Address Line Two</td>
                                                                    <td>{{ $agent_contact_info_list->con_address_line_two }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contacts Telephone</td>
                                                                    <td>{{ $agent_contact_info_list->con_telephone }}
                                                                    </td>
                                                                </tr>





                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>




                                            <!---------------------------------- Section 4 -->

                                            <!--       Section 4               -->

                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 4: Product Details</h3>

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>

                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">

                                                            <tbody>
                                                                @foreach($check_list as $application_id )
                                                                <input hidden type="text"
                                                                    value="{{ $application_id->application_id }}"
                                                                    id="app_id" />
                                                                @endforeach

                                                    </div>
                                                    <div class="card-body">

                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">
                                                            <thead>

                                                            </thead>
                                                            <tbody>

                                                                <tr>
                                                                    <td>Generic Name</td>
                                                                    <td> {{ $font_product_name='' }}@foreach(
                                                                        $check_list as $product_name )
                                                                        {{  $product_name->product_name }} @break
                                                                        @endforeach </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Brand Name</td>
                                                                    <td> @foreach($check_list as $product_trade_name )
                                                                        {{  $product_trade_name->product_trade_name     }}
                                                                        @break @endforeach</td>
                                                                </tr>
                                                      
                                                                <tr>
                                                                    <td {{ $i=1 }}>Pharmaceutical form </td>
                                                                    <td>@foreach ($dosage_forms as
                                                                        $dosage_formss)@endforeach{{  $dosage_formss->name   }}<br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td {{ $i=1 }}>Manufacturer/Market Authorization
                                                                        Holder </td>
                                                                    <td> @foreach ($applicant_contact_info as
                                                                        $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br>
                                                                        @endforeach </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>





                                            <!--       Section 5               -->
                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 5: Product Composition</h3>
                                                    @foreach($check_list as $application_id ) <input hidden type="text"
                                                        disabled disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" /> @endforeach
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i> </button></div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered table-responsive-sm  table-condensed table-striped">
                                                            <thead></thead>
                                                            <tbody>


                                                                <tr>
                                                                    <td>Composition Name</td>
                                                                    <td>Quantity</td>
                                                                    <td>Reason</td>
                                                                    <td>Reference Standard</td>
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
                                                    </div>
                                                </div>
                                            </div>








                                            <!--       Section 6               -->

                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 6: Product Manufacturers</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">

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

                                                                @foreach($check_list as $manu_info) @endforeach
                                                                <tr>
                                                                    <td> {{ $manu_info->manufacturer_name }} </td>
                                                                    <td> {{  $manu_info->manufacturer_city }} </td>
                                                                    <td> {{ $manu_info->manufacturer_state }} </td>
                                                                    <td> {{ $manu_info->manufacturer_address_line_one ." ". $manu_info->manufacturer_address_line_two }}
                                                                    </td>
                                                                    <td> {{ $manu_info->manufacturer_postal_code }}
                                                                    </td>
                                                                    <td> {{ $manu_info->manufacturer_telephone }} </td>
                                                                    <td> {{ $manu_info->manufacturer_activity }} </td>
                                                                    <td> {{ $manu_info->manufacturer_block }} </td>
                                                                    <td> {{ $manu_info->manufacturer_unit}} </td>
                                                                </tr>





                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>



                                            <!--       Section 7               -->

                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 7: API Product Manufacturers</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">


                                                            <tbody>

                                                                <tr>
                                                                    <td> API Product Manufatures</td>
                                                                    <td>City</td>
                                                                    <td>State</td>
                                                                    <td>Address</td>
<td>Postal Address</td>
                                                                    <td>Telephone</td>

                                                                </tr>

                                                                @foreach($api_manufacturers_info as $api_manu_info)
                                                                <tr>
                                                                    <td> {{ $api_manu_info->manufacturer_name }} </td>
                                                                    <td> {{ $api_manu_info->city }} </td>
                                                                    <td> {{ $api_manu_info->state }} </td>
                                                                    <td> {{ $api_manu_info->addressline_one."".$api_manu_info->addressline_two }}
                                                                    </td>
                                                                    <td> {{ $api_manu_info->postal_code }} </td>
                                                                    <td> {{ $api_manu_info->telephone }} </td>

                                                                </tr>

                                                                @endforeach


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>








                                            <!--       Section 6               -->

                                            <div class="card card-outline  card-primary  collapsed-card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Section 8: Declaration</h3>
                                                    @foreach($check_list as $application_id )
                                                    <input hidden type="text" disabled
                                                        disabledvalue="{{ $application_id->application_id }}"
                                                        id="app_id" />
                                                    @endforeach

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div style="overflow-x:auto;">
                                                        <table
                                                            class="table table-bordered  table-condensed table-striped">

                                                            <tbody>


                                                            </tbody>

                                                            <div class="card-body">
                                                                <p class="decleration">

                                                                    @foreach ( $product_details as $key => $value_name)
                                                                    @endforeach
                                                                    @foreach ($dosage_forms as $key => $value_dosage)
                                                                    @endforeach
                                                                    @foreach($company_suppliers as $value_company)
                                                                    @endforeach


                                                                    <p class="decleration">
                                                                        I, the undersigned certify that all the
                                                                        information in this form and all accompanying
                                                                        documentation submitted to Eritrea for the
                                                                        registration of
                                                                        ({{ $value_name->product_name }},
                                                                        {{ $value_name->strength_amount_strength_unit }}
                                                                        and {{ $value_dosage->name }})
                                                                        manufactured at
                                                                        ({{ $value_company->trade_name }} ,
                                                                        {{ $value_company->address_line_one }} and
                                                                        {{ $value_company->address_line_two }}) is true
                                                                        and correct. I further certify that I have
                                                                        examined the following statements and I attest
                                                                        to their correctness:-


                                                                    </P>

                                                                    <p class="decleration">
                                                                        1. The current edition of the WHO Guidelines on
                                                                        good manufacturing Practices (GMP) for
                                                                        pharmaceuticals products or equivalent guideline
                                                                        is applied in full in all premises involved in
                                                                        the manufacture of this medicine.
                                                                        <br />
                                                                        2. The formulation per dosage form correlates
                                                                        with the master formula and with the batch
                                                                        manufacturing record.
                                                                        <br />
                                                                        3. The manufacturing procedure is exactly as
                                                                        specified in the master formula and batch
                                                                        manufacturing record.
                                                                        <br />
                                                                        4. Each batch of all starting materials is
                                                                        either tested or certified (in accompanying
                                                                        certificate of analysis for that batch) against
                                                                        the full specifications in the accompanying
                                                                        documentation and must comply fully with those
                                                                        specifications before it is released for
                                                                        manufacturing purposes.
                                                                        <br />
                                                                        5. All batches of the active pharmaceutical
                                                                        ingredient(s) are obtained from the source(s)
                                                                        specified in the accompanying documentation.
                                                                        <br />
                                                                        6. No batch of active pharmaceutical
                                                                        ingredient(s) will be used unless a copy of the
                                                                        batch certificate established by the
                                                                        manufacturer is available.
                                                                        <br />
                                                                        7. Each batch of the container/closure system is
                                                                        tested or certified against the full
                                                                        specifications in the accompanying documentation
                                                                        and complies fully with those specifications
                                                                        before released for the manufacturing purposes.
                                                                        <br />
                                                                        8. Each batch of the finished product is either
                                                                        tested, or certified (in an accompanying
                                                                        certificate of analysis for that batch), against
                                                                        the full specifications in the accompanying
                                                                        documentation and complies fully with release
                                                                        specifications before released for sale.
                                                                        <br />
                                                                        9. The person releasing the product is an
                                                                        authorized person as defined by the WHO
                                                                        Guidelines on good manufacturing Practices (GMP)
                                                                        for pharmaceuticals products
                                                                        <br />
                                                                        10. The procedures for control of the finished
                                                                        product have been validated. The assay method
                                                                        has been validated for accuracy, precision,
                                                                        specificity and linearity.
                                                                        <br />
                                                                        11. All the documentation referred to in this
                                                                        application is available for review during GMP
                                                                        inspection.
                                                                        <br />
                                                                        12. Clinical trials (where applicable) were
                                                                        conducted in accordance with ICH, WHO or
                                                                        equivalent guidelines for Good Clinical
                                                                        Practice,
                                                                        <br />
                                                                        I also agree that:
                                                                        <br />
                                                                        13. As a holder of marketing
                                                                        authorization/registration of the product I will
                                                                        adhere to Eritrean National Pharmacovigilance
                                                                        Policy requirements for handling adverse
                                                                        reactions.
                                                                        <br />
                                                                        14. As holder of registration I will adhere to
                                                                        Eritrean requirements for handling batch recalls
                                                                        of the products.
                                                                        <br />
                                                                    </p>

                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                            <form>
                                                                                <div class="card-body">
                                                                                    <div class="form-check">

                                                                                        <label class="form-check-label"
                                                                                            for="exampleCheck1"><b> I
                                                                                                agree </b></label>
                                                                                        <input type="checkbox" checked
                                                                                            style="position:relative;left:18%"
                                                                                            class="form-check-input"
                                                                                            id="customCheckbox1">
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3 no-print">
                                                                        <!-- <label>I agree <input style="width:20px;position:float-center;" class="form-control" id="customCheckbox1" type="checkbox" name="customCheckbox1"   /></label>   -->

                                                                    </div>

                                                            </div>
                                                    </div>


                                                    @php

                                                    foreach($decleration_info as $decleration){}

                                                    $decleration_name = $decleration->decname;
                                                    $decleration_Qualification = $decleration->qualification;
                                                    $decleration_position = $decleration->position;
                                                    $decleration_date = $decleration->date;


                                                    @endphp


                                                    <div class="col-12 col-md-6" style="position:relative;left:0%">
                                                        <label> Declaration Name </label> : <input
                                                            value="{{ $decleration_name }}" disabled
                                                            class="form-control" id="decleration_name" type="text"
                                                            name="decleration_name" placeholder="Name:" />
                                                        <label> Qualification </label> : <input
                                                            value="{{ $decleration_Qualification }}" disabled
                                                            class="form-control" id="qualification" type="text"
                                                            name="qualification" placeholder="Qualification:" />
                                                        <label> Position </label> :<input
                                                            value="{{$decleration_position}}" disabled
                                                            class="form-control" id="position_in_the_company"
                                                            type="text" name="Position_in_the_company"
                                                            Placeholder="Position in the company" />
                                                        <!-- <input class="form-control" id="Signature" type="text" name="Signature"  Placeholder="Signature" /> -->
                                                        <label> Date </label> : <input value="{{ $decleration_date }}"
                                                            disabledclass="form-control" id="Date_decleration"
                                                            type="date" name="Date" />
                                                        <!-- <textarea class="form-control" id="OfficialSeal: " type="text" name=": " Placeholder="Officialstamp" /></textarea> -->
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->



                                                </table>
                                            </div>
                                        </div>

                                </div>


                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>


            <div class="modal fade" id="modal_reregistration_info" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modal_reregistration_info" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">


                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reregistration Procedure</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">


                        </div> {{--modal-body--}}
                    </div>
                    </form>
                </div>
            </div>
            {{-- MODAL: end reregistration info  --}}



            {{-- Start MODAL for Re-registration deadline extension Request--}}
            <div class="modal fade" id="deadline_extension_request" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="deadline_extension_request" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form action="{{ route('reregistration_deadline_extension_request')}}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Request For Deadline Extension</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label>Reason For Extension Request</label><input type="text" class="form-control"
                                                                                  name='extension_request_reason'
                                                                                  required><br>
                                <label>Requested Deadline</label><input type="date" class="form-control"
                                                                        name='requested_deadline' required><br>
                            </div>

                            <input type="hidden" value="{{$certification->id}}" name="certification_id"/>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Send Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End MODAL for Re-registration deadline extension Request--}}


    </div>
    </p>





    <!-- <ul class="list-unstyled">
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                            </li>
                        </ul> -->
    <!-- <div class="text-center mt-5 mb-3">
                            <a href="#" class="btn btn-sm btn-primary">Add files</a>
                            <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                        </div> -->
</div>
</div>
</div>
<!-- /.card-body -->
</div>
</div>
</div>




<!-- /.card -->





</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>


<script>
    $(function () {
        $("#example155").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2455').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });


        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
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