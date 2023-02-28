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
    <a class="nav-link" href="{{route('psur_acknowledgment_list') }}" role="tab" aria-controls="custom-content-below-profile" 
    aria-selected="false">PSUR Acknowledgment</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{url('un_assigned_psur') }}" role="tab" aria-controls="custom-content-below-profile" 
    aria-selected="false">PSUR Assignment</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active " href="{{route('psur_reviewed_report') }}" role="tab" aria-controls="custom-content-below-profile"
     aria-selected="false">PSUR Assessment Report</a>
  </li>

</ul>
</div> 


<div class="card-body">
    <h4> PSUR Assessment Reports </h4>
    
        <table id="example3" class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>ID</th>
                    <th>Registration Num</th>
                    <th>PSUR Ref Num</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Assessor Name</th>
                    <th>PSUR Uploaded File</th>
                    <th>Assessment Reports</th>
                  
                </tr>
            </thead>
            <tbody {{ $i=1 }}>
         
@php echo $return_data @endphp
            </tbody>
    

        </table>
      </div>
    </div>

  
</div>



</div>


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