@extends('layouts.app')


@section('content')


<meta name="csrf-token" content="{{ csrf_token() }}">

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Supervisor : Applications List </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                              <table id="example" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">
                  <thead>
                  <tr>
                    <th width="5%" {{ $i=0 }}>ID</th>
                    <th width="10%" >Application Number</th>
                    <th width="10%" >Application Status</th>
                    <th width="10%">Product Name</th>
                    <th width="10%">Applicant Name</th>
                    <th width="10%"> Assigned To</th>
                    <th width="10%" >Action</th>
                  </tr>
                  </thead>
                  <tbody {{ $i=1 }}>
                  @foreach($applications as $application)
               <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $application->application_number }}</td>
                    <td>
                    
                    @php
              if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
              elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
              elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
              
                    @endphp 
                    <span class="{{  $badge }}">
                    
                    
                     {{ $application->application_status }}
                    
                    
                     </span>
</td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->trade_name  }}</td>
                    <td>
                    @php if($application->fullname == '') {  $badge = 'badge bg-warning'; } elseif($application->fullname != '')
                     { $badge = 'badge bg-success'; }  @endphp
                    <span class="{{  $badge }}"> @if($application->fullname == '')  Un Assigned  @else 
                     {{ $application->fullname }} @endif  </span>
</td>
                    <td>   
                    @if($application->check_app == '' )

  <button title="checklist in  progress"  disabled  class="btn btn-secondary btn-sm" > <span class="fas fa-list-check"> </span> </button> 
           
  <a class="btn btn-info btn-sm" 
  title="Application Description"
   href="{{ route('supervisor_track_application_status.application',$application->application_id)  }}" >
  <i class="fas fa-table-list"> </i> 
 </a> 

<!-- <a  href="{{ route('application.checklist',$application->application_id)  }}" <i class='fas fa-battery-empty'>Validate </i> </a> -->
                  
                    @else
                    
<a class="btn btn-info btn-sm"   title="Show Assessor Progress"
 href="{{ route('supervisor_check_progress_of_assessor.checklist_progress',$application->application_id)  }}" >
<i class="fas fa-list-check"> </i> 
 </a> 
 
 <a class="btn btn-info btn-sm" title="Application Description" href="{{ route('supervisor_track_application_status.application',$application->application_id)  }}" >
   <i class="fas fa-table-list"> </i> 
</a>
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

  @endsection
  @section('scripts')
  <!-- DataTables  & Plugins -->
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
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

  @endsection
