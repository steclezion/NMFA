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



    <div class="card-body">

<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
<li class="nav-item"> 
    <a class="nav-link active " href="{{route('psur_acknowledgment_list') }}" role="tab" aria-controls="custom-content-below-profile" 
    aria-selected="false">PSUR Acknowledgment</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{url('un_assigned_psur') }}" role="tab" aria-controls="custom-content-below-profile" 
    aria-selected="false">PSUR Assignment</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{route('psur_reviewed_report') }}" role="tab" aria-controls="custom-content-below-profile"
     aria-selected="false">PSUR Assessment Report</a>
  </li>

</ul>
</div> 

<div class="card-body">
    <h4>Acknowledgement letter for the receipt of PSUR </h4>
    
    <div class="tab-content" id="custom-content-below-tabContent">
        <div class="tab-pane fade show active" id="custom-content-below-accepted" role="tabpanel" aria-labelledby="custom-content-below-accepted-tab">

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="5%">Registration Number</th>
                    <th width="5%"  style="background-color: lightblue;" >Psur Refrence Number</th>
                    <th width="5%" > Generic Name</th>
                    <th width="5%" > Brand Name</th>
                    <th  width="5%" >Applicant Name</th>
                    <th  width="5%">Applicant Contact person</th>
                    <th width="2%">Action</th>
                </tr>
            </thead>
            <tbody {{ $i=1 }}>
                @foreach($applications as $application) @php $explode = explode(',', $application->hold_progress_wizard);
                
                $array_application_content = $application->app_id."--".$application->psurid
                 @endphp
                       
                <tr>
                    <td>{{ $i++ }} </td>
                    <td><span class="badge-info btn-group-sm" > {{ $application->regnumber }} </span></td>
                    <td style="background-color: lightblue;">{{ $application->psur_refrence_number }}</td>
                    <td>{{ $application->med_name }}</td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->trade_name }}</td>
                    <td>{{ $application->first_name." ".$application->middle_name." ".$application->last_name }}</td>
                    <td>
                   
@if(($application->document_id != null))

<a href="javascript:void(0)" 
data-toggle="tooltip" 
data-contact_person_name="{{ $application->first_name." ".$application->middle_name." ".$application->last_name }}"
data-psur_refrence_number ="{{ $application->psur_refrence_number }}"
data-application_number = "{{$application->app_number}}"
data-application_id = "{{ $application->app_id }}"
 
id="upload_acknowledgment_letter_psur"
title="upload Acknowledgment Letter"  
data-original-title="Edit" 
class="edit btn btn-primary btn-sm upload_acknowledgment_letter_psur">
<i class='fas fa-upload'></i> 
</a>
<a href="{{ $application->path }}" 
data-toggle="tooltip" 
id="query" title="Download PSUR Generated From Applicant"  
data-original-title="Edit"  class="edit btn btn-success btn-sm">
<i class='fas fa-download'></i> 
</a>
@else
<a href="{{ route('Acknowledgement_Letter_post_marketing',$array_application_content) }}" 
data-toggle="tooltip" 
id="query" title="Acknowledgement letter Receipt of PSUR"  
data-original-title="Edit"  class="edit btn btn-primary btn-sm">
<i class='fas fa-list'></i> 
</a>


<a href="{{ $application->path }}" 
data-toggle="tooltip" 
id="query" title="Download PSUR Generated From Applicant"  
data-original-title="Edit"  class="edit btn btn-success btn-sm">
<i class='fas fa-download'></i> 
</a>



    @endif
                      
                    </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>


            </tfoot>
        </table>
        </div>
        
       

<div class="tab-pane fade" id="custom-content-below-rejected" role="tabpanel" aria-labelledby="custom-content-below-rejected-tab">
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
                @foreach($applications as $application)
                
                 @php $explode = explode(',', $application->hold_progress_wizard); @endphp

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
          

                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>


            </tfoot>
        </table>  
</div>
        <div class="tab-pane fade" id="custom-content-below-defered" role="tabpanel" aria-labelledby="custom-content-below-defered-tab">
        <table id="example3" class="table table-bordered table-striped">
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

 @include('layouts.modal_upload_alert_nmfa_director_info')
</div>
 @include('layouts.modal_upload_acknowledgment_letter_psur_front_list')
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