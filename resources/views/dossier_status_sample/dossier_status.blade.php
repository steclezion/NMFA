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
                <h3 class="card-title">Dossier Submission
<i  style="color:blue;cursor:pointer;font-size:15px;" 
 title="Dossier submission
Instruction: You can submit your electronic dossiers by one of the following ways: 
1. Online submission
For dossiers you intend to submit online, please provide the link indicating where the dossier is located. 
2. Email
For dossiers you intend to send via Email, you can submit the dossiers to er.peru.nmfa@gmail.com. 
3. Electronic media delivery
Alternatively, you can send an electronic copy of your dossier using one of the following media:
•	CD or DVD
•	USB flash drive
•	USB external hard drive
The label for each unit of media should include the following minimum information:
•	Applicant name
•	Product name
•	Name of the dossier
•	Total number of CDs/DVDs, USB flash drives or USB hard drives
4. Paper submissions
If you cannot submit your dossier by online submission, email or electronic media delivery, you may submit a single hard copy of the dossier. 
Please visit the Eritrean Medicines Registration Guideline for information on the documentation to be submitted for the registration of pharmaceutical products in Eritrea. 
"
           class="fas fa-info-circle fa-0">  
           </i>
                </h3>
                <br>
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dossier_sample_status') }}" aria-selected="true"> <span class="badge bg-primary"> New Applications</span> </a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('dossier_sample_status_renew') }}"  role="tab"  aria-selected="false">  <span class="badge bg-primary"> Re-registration  Applications</span> </a>
        </li> 

    </ul>
    <br>

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Application Number</th>
                    <th>Application Status</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Applicant contact person</th>
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
<a  title="Edit dossier"  class='btn btn-sm btn-warning'   href="{{ route('dossier_sample_status_edit',$application->application_id)  }}"> 
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
