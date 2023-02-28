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
            Assigned Applications            </h3>
          </div>
          <div class="card-body">

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item"> 
                <a class="nav-link" href="{{route('un-assignment.index') }}" role="tab" aria-controls="custom-content-below-profile" 
                aria-selected="false">Unassigned</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="{{route('assignment.index') }}" role="tab" aria-controls="custom-content-below-profile" 
                aria-selected="false">Assigned</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="{{route('assignment.all_assigned_unassigned') }}" role="tab" aria-controls="custom-content-below-profile"
                 aria-selected="false">All</a>
              </li> -->
            
            </ul>
    </div> 
          
<!--             
                <h1>Assigned Applications</h1> -->
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->
    <div class="table-responsive">      
    <table style="overflow-x:auto;" class="table  table-responsive table-bordered data-table">
        <thead>
            <tr>
                    <th  >ID</th>
                    <th >Application Number</th>
                    <th >Generic Name</th>
                    <th >Applicant Name</th>
                    <th >Applicant Contact Person</th>
                    <th >Application Type</th>
                    <th >Assigned To</th>
                    <th >Assigned By</th>
                    <th >Assigned Date</th>
                    <th >Application Status</th>
                    <th >Action</th>
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

@include('layouts.modal_assign_unassign_assgined')
@endsection