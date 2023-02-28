@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>


<meta name="csrf-token" content="{{ csrf_token() }}">

<section class="content">
  


                <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Financial Notifications  </h3>
                            </div>
                            <!-- /.card-header -->
<div class="card-body">
<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
<table id="example" class="table table-bordered table-striped example1">
<thead>
         <tr>
                    <th width="5%" {{ $i=0 }}>ID</th>
                    <th width="5%">Application Number</th>
                    <th width="5%">Generic Name</th>
                    <th width="5%">Brand  Name</th>
                    <th width="5%">Applicant Name</th>
                    <th width="5%">MOF receipt number</th>
                    <th width="15%">Date of order</th>
                    <th width="5%">Application Status</th>
                    <th width="5%">Action</th>
                   </tr>
                  </thead>
                  <tbody {{ $i=1 }}>
                  @foreach($data as $application)
          <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $application->application_number }}</td>
                    <td>{{ $application->product_name }}</td>
                    <td>{{ $application->product_trade_name }}</td>
                   
                    <td>{{ $application->cs_tradename }}</td>
                    <td>{{ $application->receipt_number }}</td>
                    <td>  {{ $application->date_of_order }}  </td>
                    <td> 
                  @php
                  
                  if($application->application_status == 'processing') { $title='processing'; $badge = 'badge bg-warning' ;$application_stat="processing"; }
                  elseif($application->application_status == 'Preliminary screening completed') { $title='Preliminary screening completed';  $badge = 'badge bg-success';$application_stat="PSC"; }
                  elseif($application->application_status == 'Preliminary screening rejected') { $title='Preliminary screening rejected'; $badge = 'badge bg-danger';$application_stat="PSR"; }
          $btn = "<span class='$badge'  title='$title'>  $application_stat  </span>";
                  @endphp
                   
                  <span class='{{ $badge }}'  title='{{ $title}} '>   {{  $application_stat  }}  </span>
                    </td>                   
                    
                    
                     <td> 

@if($application->financial_notification_flag == 0 )
<a   class="btn btn-info btn-sm"   id="sam" rel="tooltip"  title="Financial Notification letter" style="cusror:pointer" 
 href=" {{ route('financial_notification.application',$application->application_id)  }}"  data-di={{ $application->application_id  }} >  
    <i class='fas fa-tasks'></i> </a>

@else
<button class="btn btn-success btn-sm upload_finance_notify"  data-id="{{ $application->application_id  }}" data-path = '{{ $application->path  }}'  rel="tooltip"  title="Upload To Applicant"  href="javascript:void(0)" > <i class='fas fa-upload'></i> </button> </a>

@endif

                  </button>
                  </td>
                  </tr>
                @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                  </table>
                            </div>
                           </div>
                         </div>
                            <!-- /.card-body -->
                      </div>
                 </div>
           </div>
      </div>
  </section>



@include('layouts.modal_upload_finincial_notification_applicant')


@endsection






