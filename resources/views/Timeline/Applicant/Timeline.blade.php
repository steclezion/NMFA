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
                <h3 class="card-title">Timeline</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>ApplicationNumber</th>
                    <th>Application Status</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Applicant Contact Person</th>
                    <th>Application Status</th>
                    <th width="5%">Action</th>
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
                    <td>{{ $application->application_status }}</td>
                    <td>{{ $application->t_name }}</td>
                    <td>{{ $application->cs_tradename  }}</td>
                    <td>{{ $application->cfirst_name." ".$application->cmiddle_name." ".$application->clast_name }}</td>
                    <td>
                    @if($application->dossier_url == '')
                    <button  title="shows:Dossier needs to be completed" class="btn btn-warning btn-xs" <i   class='fas fa-ruler'>Pending</i>  </button>
                    @else
                    <span  title="shows:Dossier is Completed" class="badge bg-success"
                    <i   class='fas fa-pen'> Completed    </i>
                    </button>
                    @endif
                   </a>
                  </td>

                   <td>
                    
<a  title="Edit"  class='btn btn-sm btn-warning'   href="{{ route('Timeline_show_applicant',$application->appid)  }}"> 
<i class='fa fa-timeline'></i>
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
