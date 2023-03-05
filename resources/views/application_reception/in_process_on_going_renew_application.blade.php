@extends('layouts.app_app')
 @section('content')
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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

   <div class="card card-primary">
                    <div class="card-header">
                       Applications
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('application_in_processing') }}" aria-selected="true"> <span class="badge bg-primary"> New  </span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active"  href="{{ route('in_process_on_going_renew_application') }}"  role="tab"  aria-selected="false"> <span class="badge bg-primary">  Re-register </span></a>
        </li> 
        <!-- <li class="nav-item">
            <a class="nav-link" id="custom-content-below-re-new-tab" data-toggle="pill" href="#custom-content-below-re-new-tab" role="tab" aria-controls="custom-content-below-re-new-tab" aria-selected="false">Rejected </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-defered-tab" data-toggle="pill" href="#custom-content-below-defered" role="tab" aria-controls="custom-content-below-defered" aria-selected="false">Defered</a>
        </li> -->
    </ul>
    <br>
    <div class="tab-content" id="custom-content-below-tabContent">
    <div class="tab-pane fade show active" id="custom-content-below-accepted" role="tabpanel" aria-labelledby = "custom-content-below-accepted-tab" >

    
	
	                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Application Number</th>
                    <th hidden >Re-registration Number</th>
                    <th>Application Status</th>
                    <th>Application Type</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th hidden >Applicant Contact Person</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody {{  $i=1 }}>
                  @foreach($applications as $application)
              @php $explode = explode(',', $application->hold_progress_wizard); @endphp

               <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $application->application_number }}</td>
                    <td hidden><span class="badge bg-warning">{{ $application->re_registration_number }}</span>
                    </td>
                    <td> 
@php

if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }

 @endphp
                    <span class="{{  $badge }}"> {{ $application->application_status }} </span>
                    </td>
                    <td> 
                  @php if($application->registration_type == 'New') {  $badge = 'badge bg-success'; }elseif($application->registration_type == 'Re-new') { $badge = 'badge bg-success'; }  @endphp
                    <span class="{{  $badge }}"> {{ $application->registration_type }} </span>
                    </td>
                    <td>{{ $application->product_name }}</td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->trade_name  }}</td>
                    <td hidden>{{ $application->first_name." ".$application->middle_name." ".$application->last_name }}</td>
                    <td>
                    @if(in_array('8',$explode))
                    <a  style="color:white"  class='btn btn-sm btn-info' title="View application details" href="{{ route('view_completed_application_re',$application->application_id)  }}">
                    <i class='fas fa-eye'></i>
                    </a> 
                    @else
                    <a  title="Edit"  class='btn btn-sm btn-warning' 
                     href="{{ route('application_reception_re_registration_update',$application->application_id)  }}"> 
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
    </div>

  
</div>



</div>
</section>

<script>
//     $(function() {
//         $("#example1").DataTable({
//             "responsive": true,
//             "lengthChange": false,
//             "autoWidth": false,
//             "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
//         }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
//         $('#example2').DataTable({
//             "paging": true,
//             "lengthChange": false,
//             "searching": false,
//             "ordering": true,
//             "info": true,
//             "autoWidth": false,
//             "responsive": true,
//         });
//     });
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