@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>  
<style>
.m-0 {
    font-size :20px;
    padding-right: 30px;
    position: relative;
}


</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">PERU</li>
@foreach ($roles_names as $r)
                    <li class="breadcrumb-item active">{{ $r}}</li>
@endforeach



                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->


@if(in_array('Applicant',$roles_names))  
<div class="container-fluid">
       
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Applicant</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_applicant')

@endif



@if(in_array('Supervisor',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" >Supervisor</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_supervisor')

@endif





@if(in_array('Nmfa director',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Nmfa Director</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_nmfa_director')

@endif




@if(in_array('PERC',$roles_names) and count($roles_names)==1)
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">PERC</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_perc')

@endif



@if(in_array('Inspection',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Inspection</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_inspection')

@endif



@if(in_array('Quality Control',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quality Control</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_quality_control')

@endif


@if(in_array('Assessor',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Assessor</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_assessor')

@endif


@if(in_array('Admin',$roles_names))  
<div class="container-fluid">
        
<div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Administration</h1>
            </div><!-- /.col -->
</div>
</div>
@include('dashboards.dashboard_admin')

@endif











@endsection

