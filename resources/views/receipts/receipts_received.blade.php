@extends('layouts.app')
@section('stylesheets')

@endsection
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

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
            <a class="nav-link"  href="{{route('receipts') }}" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pending Receipts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="{{ route('receipts.received')   }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Received</a>
              </li>

           <!-- <li class="nav-item">
                 <a class="nav-link" href="{{route('receipts.received.all') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">All</a>
              </li> -->
            
            </ul>
            </div>
            </div>
            
               
    <div class="card-header">

<div class="container">
<div class="table-responsive">
 <!--   <a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Generate Invoice Number </a>-->
 <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th style="width:10%">No</th>
                <th style="width:10%">ID</th>
                <th style="width:10%">Application ID</th>
                <th style="width:10%">Generic Name</th>
                <th style="width:10%">Brand Name</th>
                <th style="width:10%">Applicant Name</th>
                <th style="color:green;width:10%">Receipt ID</th>
                <th style="width:10%">Invoice Number</th>
                <th style="width:10%">Receipt Amount</th>
                <th style="width:10%">Invoice Date</th>
                <th style="width:10%">Receipt Date</th>
                <th style="width:10%">Invoice Document</th>
                <th style="width:10%">Receipt Document</th>
                <th style="width:10%">Description</th>
               
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
   </div>
   </div>
   </div>
   </div>

  @endsection
  @section('scripts')


@include('layouts.modal_generate_receipts_received')



@include('layouts.modal_generate_receipts_received_applications')

          

@endsection