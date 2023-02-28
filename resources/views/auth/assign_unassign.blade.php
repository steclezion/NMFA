@extends('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
    <link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
    <link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
  

    
<div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
            Assign / Un-Assign 
            </h3>
          </div>
          <div class="card-body">

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">UnAssigned</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('un-assignment.index') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Assigned</a>
              </li>


                 <li class="nav-item">
                <a class="nav-link" href="{{route('un-assignment.index') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">All</a>
              </li>
            
            </ul>
            </div>
            </div>
            
                <h1>Assign/UnAssign</h1>
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                    <th>ID</th>
                    <th>Application Number</th>
                    <th>Application Status</th>
                    <th>Product Name</th>
                    <th> Applicant Contact Name</th>
                    <th>Applicant Business Name</th>
                    <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   

    
<script rel="stylesheet"  src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script rel="stylesheet"  src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="stylesheet"  src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="stylesheet"  src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="stylesheet"  src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>


@include('layouts.modal_assign_unassign')
@endsection