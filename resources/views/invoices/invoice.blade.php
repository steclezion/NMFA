@extends('layouts.app')
@section('content')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- <script src="{{ asset('asset/js/jquery3_5_1.min.js') }}"></script> -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">


<script>
  $(function () {
    // Summernote
    $('#summernote').summernote();
    // $('#summernotee').summernote();
    // $('#summernote_Remark_section_four').summernote();
    // $('#Remark_section_five').summernote();
    // $('#over_all_comment').summernote();

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("summernote"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>


@endsection
<div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-file-invoice"></i>
           Invoice
            </h3>
            </div> 
        
          <br>
       <div class="card-body">
            
            
                
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->
    <div class="table-responsive">          

    <table class="table table-responsive table-condensed data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th >Application Number </th>
                <th >Invoice Number</th>
                <th>UserId</th>
                <th >Generic Name</th>
                <th  >Brand Name </th>
                <th >Applicant Name</th>
                <th  >Application Type</th>
                <th>Remark</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
</div>
   
</div>
</div>
    
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





@include('layouts.modal_generate_invoice')



@include('layouts.modal_upload_invoice')

          

@endsection