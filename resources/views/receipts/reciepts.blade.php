@extends('layouts.app')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

@endsection
@section('content')


<meta name="csrf-token" content="{{ csrf_token() }}">
  
    <div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
            Receipt Status
            </h3>
          </div>
          <div class="card-body">

              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
              <a class="nav-link active "  href="{{route('receipts') }}" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pending Receipts</a>
              </li>

              <li class="nav-item">
              <a class="nav-link" href="{{ route('receipts.received')   }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Received</a>
              </li>


                  <!-- <li class="nav-item">
                  <a  class="nav-link" href="{{route('receipts.received.all') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">All</a>
                  </li> -->
            
            </ul>
            </div>
            </div>

<div class="container">
<div class="table-responsive">
 <!--   <a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Generate Invoice Number </a>-->
 <table class="table table-bordered data-table">       
  <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Application ID</th>
                <th style="width:10%">Generic Name</th>
                <th style="width:10%">Brand Name</th>
                <th style="width:10%">Applicant Name</th>
                <th style="color:green">Receipt ID</th>
                <th>Invoice Number</th>
                <th>Receipt Amount</th>
                <th>Invoice Date</th>
                <th>Receipt Date</th>
                <th>Invoice Document</th>
                <th>Receipt Document</th>
                <th>Description</th>
               
                <th width="10px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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

@include('layouts.modal_generate_receipts')

@endsection