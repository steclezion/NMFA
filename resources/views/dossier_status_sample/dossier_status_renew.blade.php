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

             <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dossier Status</h3>
                <br>
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dossier_sample_status') }}" aria-selected="true"> <span class="badge bg-primary"> New Applications </span> </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active"  href="{{ route('dossier_sample_status_renew') }}"  role="tab"  aria-selected="false">  <span class="badge bg-primary"> Re-registration Applications  </span> </a>
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>ApplicationNumber</th>
                    <th>Re-Registration Number</th>
                    <th>Application Status</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Applicant contact Name</th>
                    <th>Dossier Submission</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody {{  $i=1 }}>
                  @foreach($applications as $application)

                @php   $explode = explode(',', $application->hold_progress_wizard);  @endphp

                  @if($application->dossier_url == '')
                   @php $tr="color:black";  @endphp
                  @else
                  @php $tr="color:black";  @endphp

                  @endif

        @if(in_array('7',$explode))

               <tr  style="{{ $tr }}">
                    <td>{{ $i++ }}</td> 

                    <td>{{ $application->application_number }}</td>

                    <td><span  style="font-size:15px" title="Re-registration number" class="badge bg-warning" <i   class='fas fa-ruler'>{{ $application->regnumber}}</i>  </span></td>
                    
                    <td>
@php 

if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; } 
elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; } 
elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; } 

 @endphp
                    <span class="{{  $badge }}"> {{ $application->application_status }} </span></td>
                    <td>{{ $application->med_name }}</td>
                    
                    <td>{{ $application->t_name }}</td>
                    <td>{{ $application->cs_tradename  }}</td>
                    <td>{{ $application->cfirst_name." ".$application->cmiddle_name." ".$application->clast_name }}</td>
                    <td>
                    @if($application->dossier_url == '')

<span  title="shows:Dossier needs to be completed" class="badge bg-warning" <i   class='fas fa-ruler'>Pending</i>  </span>


                  @else
                    <span  title="shows:Dossier is Completed" class="badge bg-success"
                    <i   class='fas fa-pen'> Completed    </i>
                    </button>
                    @endif
                   </a>
                  </td>

                   <td>
                    @if($application->dossier_url == '')
                    <a title="Submit Dossier" href="{{ route('application_set.dossier',$application->application_id)  }}"  class="btn btn-primary btn-sm">
                    <i  class='fas fa-plus'></i>
                    </a>
                  @else
                    <!-- <a title="Edit" href="{{ route('dossier_sample_status_edit',$application->application_id)  }}">
                    <i  style="color:green" class='fas fa-pen'></i>
                    </a> -->
<a  title="Edit"  class='btn btn-sm btn-warning'   href="{{ route('dossier_sample_status_edit',$application->application_id)  }}"> 
<i class='fas fa-edit'></i>
                    </a>


                    @endif
                   </a>
                  </td>
                  </tr>
                  @endif


                @endforeach

                  </tbody>
                  <tfoot>


                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>


          <script>
  $(function () {
    $("#example41").DataTable({
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
