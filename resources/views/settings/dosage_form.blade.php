@extends('layouts.app')
@section('content')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">


@endsection
<div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-list-alt"></i>
    List of Dosage Forms
            </h3>
            </div> 
        
          <br>
          <div class="card-body">
            
            
                
<!--<a class="btn btn-success" href="javascript:void(0)" id="createNewEml"> Create New Book</a>-->
<div class="table-responsive">          
<!-- <table class="table table-responsive table-condensed data-table"> -->
<a class="btn btn-success" href="javascript:void(0)" id="createNewDosage"><i class="fas fa-add" > </i>  Add New Dosage Form </a>
<br><br><br>
<table class="table table-bordered data-table">
<thead>
            <tr>
                <th>No</th>
                <th>Dosage Name</th>
                
                <th>Description</th>
             
                <th width="200px">Action</th>
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





@include('layouts.modal_dosage_forms')





          

@endsection