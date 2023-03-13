@extends('layouts.app')
@section('content')
    <meta name="csrf-tokenn" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<script rel="stylesheet" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
  
   
    
<div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
           Received Documents
            </h3>
          </div>
          <div class="card-body">

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a href="{{route('doc.index') }}" class="nav-link active" id="custom-content-below-home-tab"  role="tab" aria-controls="custom-content-below-home" aria-selected="true">Acknowledgment letters </a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link" href="{{route('documents_invoice.invoice_received') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Invoices  </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="{{route('documents_financial_notification') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Financial Notifications</a>
              </li>

             <li class="nav-item">
                <a class="nav-link" href="{{route('documents_psurs') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">PSURS</a>
              </li>


                <li class="nav-item">
                <a class="nav-link" href="{{route('documents_nmfa_alerts') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Directors Alert Notification</a>
              </li>
             
            
            </ul>
          
                
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->
    <table class="table table-bordered data-table">
        <thead>
                    <tr>
                    <th>ID</th>
                    <th>Application Number</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant  Name</th>
                    <th>Application Status</th>
                    <th>Application Mode </th>
                    <th>Application Type </th>
                    <th>Uploaded Date </th>
                    <th width="10%">Action</th>
                    </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>
</div>
            

    
    
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
<script rel="stylesheet" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>


@include('layouts.modal_acknowledgement_uploaded')


@endsection