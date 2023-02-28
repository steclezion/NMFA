@extends('layouts.app')
@section('stylesheets')




<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!--plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@endsection
@section('content')

<!-- <link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" > -->
    <!-- <link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
    <link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> -->
    
    <!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 -->

<meta name="csrf-token" content="{{ csrf_token() }}">

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Preliminary Screening List</h3>
                              <br>
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('check_list') }}" aria-selected="true"> <span class="badge bg-primary"> New  </span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active"  href="{{ url('checklist_renew') }}"  role="tab"  aria-selected="false"> <span class="badge bg-primary">  Re-register </span></a>
        </li> 
        <!-- <li class="nav-item">
            <a class="nav-link" id="custom-content-below-re-new-tab" data-toggle="pill" href="#custom-content-below-re-new-tab" role="tab" aria-controls="custom-content-below-re-new-tab" aria-selected="false">Rejected </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-defered-tab" data-toggle="pill" href="#custom-content-below-defered" role="tab" aria-controls="custom-content-below-defered" aria-selected="false">Defered</a>
        </li> -->
    </ul>
    <br>
                            </div>
                            <!-- /.card-header -->
                  <div class="card-body">
                  <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                  <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>Application Number</th>
                    <th>Application Re-Register Number</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Applicant Contact Person</th>
                    <th>Application Status</th>
                    <th>Action</th>
                 </tr>

                  </thead>
                  <tbody {{ $i=1 }}>
                  @foreach($applications as $application)
               <tr>
                    <td> {{ $i++ }}</td>
                    <td>  {{ $application->application_number }}                 
                    <td>  {{ $application->re_registration_number }} </td>
<!-- <i style="color:blue;cursor:pointer" id="modal_show_detail_information" title="Guidelines on Application Submission process"  class="fas fa-info-circle">  </i> -->


</td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->trade_name  }}</td>
                    <td>{{ $application->first_name." ".$application->middle_name." ".$application->last_name }}</td>
                    <td>
                    @php

if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }

 @endphp

    <span class="{{  $badge }}"> {{ $application->application_status }} </span></td>
        

         <td width="10%">
     @if( $application->application_status == 'processing' )
     <a   class="btn btn-info btn-sm"   id="sam" rel="tooltip"  title="Attest" style="cusror:pointer" 
     
      href=" {{ route('application.checklist_re',$application->application_id)  }}" >  <i class='fas fa-tasks'></i> </a>
     @elseif($application->application_status == 'Preliminary screening completed' || $application->application_status == 'Preliminary screening rejected' )
     
     <a class="btn btn-info btn-sm"   title="Show Assessor Progress"
     
     href="{{ route('supervisor_check_progress_of_assessor.checklist_progress',$application->application_id)  }}" >
      
     <i class="fas fa-list-check"> </i> 
     
 </a> 
 
     @elseif($application->check_app != '' && $application->application_status == 'processing' )
     <a class="btn btn-success btn-sm"  rel="tooltip"  title="Attest"  
     href="{{ route('application.checklist_update',$application->application_id)  }}">   <i class='fas fa-bars'></i> </a>   
     </a>
     @endif
  


@if($application->app_receipt_number == '')

<a  rel="tooltip"  class="btn btn-primary btn-sm" title="Acknowledgement of receipt of registration application"
  href="{{ route('Acknowledgement_of_Receipt_of_Registration_Application',$application->application_id)  }}">
  <i class='fas fa-list-dots'>
</i>

@elseif($application->app_receipt_number != '')
   <a href="javascript:void(0)" 
    data-id="{{ $application->application_id }}" 
    data-path="{{ $application->path }}"
    data-document_id="{{ $application->document_id  }}"
    data-toggle="tooltip"
    title="upload Receipt File" class="edit btn btn-success btn-sm upload_receipt_register">
    <i class="fas fa-file-upload">
    </i>
    </a>
    

@endif


</a>
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
  <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>




@include('layouts.modal_upload_acknowledgment_receipt_registration')
  @endsection
