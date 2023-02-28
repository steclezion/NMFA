@extends('layouts.app')
@section('stylesheets')

@endsection
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
    
<div class="container" >
       <!-- /.card -->
       <div class="card card-primary card-outline" style="overflow-x:auto;" >
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
            Assigned PSUR           </h3>
          </div>
          <div class="card-body">

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item"> 
                <a class="nav-link" href="{{route('psur_acknowledgment_list') }}" role="tab" aria-controls="custom-content-below-profile" 
                aria-selected="false">PSUR Acknowledgment</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="{{route('un_assigned_psur') }}" role="tab" aria-controls="custom-content-below-profile" 
                aria-selected="false">PSUR Assignment</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('psur_reviewed_report') }}" role="tab" aria-controls="custom-content-below-profile"
                 aria-selected="false">PSUR Reviewed Report</a>
              </li>
            
            </ul>
    </div> 
          
<!--             
                <h1>Assigned Applications</h1> -->
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->

        <div class="table-responsive">   
    <table style="overflow-x:auto;" class="table  table-responsive table-bordered data-table">
        <thead>
            <tr>
            <th width="10%" >ID</th>
                    <th width="10%">Application Number</th>
                    <th width="10%">Psur Ref No.</th>
                    <th width="10%">Product Name</th>
                    <th width="10%">Applicant Name</th>
                    <th width="10%">Applicant Contact Person</th>
                    <th >Application Type</th>
                    <th width="10%">Assigned To</th>
                    <th width="10%">Assigned By</th>
                    <th width="10%">Deadline</th>
                    <th width="10%">Application Status</th>
                    <th width="10%">PSUR File</th>
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

  @endsection
  @section('scripts')


@endsection