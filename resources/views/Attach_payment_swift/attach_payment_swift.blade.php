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
            
              <i class="fas fa-edit"></i>
           Upload Payment Swift 
         
          </div>
          <div class="card-body">

              <!-- <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
              <a class="nav-link active "  href="{{route('receipts') }}" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pending Receipts</a>
              </li> -->

           


                  <!-- <li class="nav-item">
                  <a  class="nav-link" href="{{route('receipts.received.all') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">All</a>
                  </li> -->
            
            </ul>
            </div>
            </div>

<div class="container">
<div class="table-responsive">
 <!--   <a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Generate Invoice Number </a>-->
 <table class="table table-bordered data-table" id='example'>       
  <thead>
            <tr>
                <th style="width:5%" >ID</th>
                <th style="width:5%">Application No</th>
                <th style="width:5%">Generic Name</th>
                <th style="width:5%">Brand Name</th>
                <th style="width:5%">Applicant Name</th>
                <th style="width:5%">Application Status</th>
                <th width="5%">Action</th>

            </tr>
        </thead>
        <tbody {{ $i=1 }} >

                @foreach($attach_payment as $application) 

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $application->application_number }}</td>
                    <td>{{ $application->product_trade_name }}</td>
                    <td>{{ $application->product_name }}</td>
                    <td>{{ $application->cs_tradename}}</td>
         <td width="10px">
         @php
              if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
              elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
              elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
              
                    @endphp 
                       <span class="{{  $badge }}"> {{ $application->application_status }} </span>
                  <td>
                    
@if($application->uploaded_swift_document_id == 0 )
<a href="javascript:void(0)" data-toggle="tooltip" id="swift_payment"
              data-application_id= "{{ $application->application_id  }}"
              data-app_number= "{{ $application->application_number }}"
              title="upload"
              data-original-title="Edit" class="edit btn btn-primary btn-sm swift_payment">
<i class="fas fa-upload"></i>  

</a>

@else

<a href="javascript:void(0)" data-toggle="tooltip" id="swift_payment"
              data-application_id= "{{ $application->application_id  }}"
              data-app_number= "{{ $application->application_number }}"
              title="View uploaded Swift payment"
              data-original-title="File Aleady uploaded" class="edit btn btn-success btn-sm swift_payment">
<i class="fas fa-upload"></i> 


@endif


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

@include('layouts.modal_upload_swift_payment')

@endsection