@extends('layouts.app')
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

    <h4> PSUR Assessment </h4>
    <br>
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-accepted-tab" data-toggle="pill" href="#custom-content-below-accepted" role="tab" aria-controls="custom-content-below-accepted" aria-selected="true">Applications </a>
        </li>
        </ul>
    <br>
    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
    <div class="container">
<div class="table-responsive">
    <table id="example1" class="table table-bordered table-striped">
                    <thead>
                <tr>
                    <th>ID</th>
                    <th  >Registration Number</th>
                    <th>PSUR Ref. No.</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th hidden >Applicant Contact person</th>
                    <th hidden>Application Status</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody >
            @php echo $return_data @endphp
            </tbody>
            <tfoot>


            </tfoot>
        </table>
        </div>
        
       

    </div>

  
</div>



</div>



 @include('layouts.modal_upload_psur_review_report')




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

@endsection