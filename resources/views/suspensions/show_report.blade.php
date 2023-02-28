@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

@section('content')

    <section class="content">
	
	        <section class="content-header">
            <div class="container-fluid">
                <h4 class="text-center display-5">MA Status Report</h4>
            </div>
            <!-- /.container-fluid -->
        </section>

		
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">MA Status Report</h3>

                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">


                            <form method='post' action="{{route('suspensions.debug_report')}}">
                                @csrf

                                <div class="report-selector row">

                                    <div class="col-2">
									<div class="form-group">
                                        <label> Report : </label>
                                    <div class="input-group">
                                        <select class="form-control" required name="report_type">
                                            @if($report_type=="Suspended")
                                                <option value=""></option>
                                                <option value="Suspended" selected>Suspended</option>
                                                <option value="Ceased">Ceased</option>
                                                <option value="Withdrawn">Withdrawn</option>
                                            @elseif($report_type=="Ceased")
                                                <option value=""></option>
                                                <option value="Suspended">Suspended</option>
                                                <option value="Ceased" selected>Ceased</option>
                                                <option value="Withdrawn">Withdrawn</option>
                                            @elseif($report_type=="Withdrawn")
                                                <option value=""></option>
                                                <option value="Suspended">Suspended</option>
                                                <option value="Ceased">Ceased</option>
                                                <option value="Withdrawn" selected>Withdrawn</option>
                                            @else
                                                <option value=""></option>
                                                <option value="Suspended">Suspended</option>
                                                <option value="Ceased">Ceased</option>
                                                <option value="Withdrawn">Withdrawn</option>
                                            @endif
                                        </select>
</div>
</div>

                                    </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>From:</label>
                                                    <div class="input-group date" id="task_start_date"
                                                         data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input"
                                                               data-target="#task_start_date" name="from_test" value="@if(isset($search_history)) {{$search_history['start_date']}} @endif"
                                                               required>
                                                        <div class="input-group-append" data-target="#task_start_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>To:</label>
                                                    <div class="input-group date" id="task_end_date"
                                                         data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input"
                                                               data-target="#task_end_date" name="to_test" value="@if(isset($search_history)) {{$search_history['end_date']}} @endif"
                                                               required>
                                                        <div class="input-group-append" data-target="#task_end_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


								<div class="col-6">
                                                <div class="form-group">
                                                    <label>Applicant</label>
                                                    <select class="form-control select2 select2-hidden-accessible" multiple="" name="company_test[]"
                                                            style="width: 100%;"
                                                            tabindex="-1" aria-hidden="true">
                                                        @if(isset($search_history))
															@if(isset($search_history['company_test']))
																<?php 
															if(in_array("All",$search_history['company_test']))
                                                            {echo '<option value="All" selected>All</option>';
															foreach($company_suppliers as $company){
																echo '<option  value="'.$company->trade_name.'">$company->trade_name</option>';
															}
															}
														else{
															echo '<option value="All">All</option>';
															foreach($company_suppliers as $company){
																echo '<option  value="'.$company->trade_name.'"';
																if(in_array($company->trade_name,$search_history['company_test'])){echo "selected";}
																echo ">$company->trade_name</option>";
															}
														}																
															?>															
                                                           @else
                                                            <option value=""></option>
                                                            <option value="All">All</option>
		                                                        @foreach($company_suppliers as $company)
																	<option  value="{{$company->trade_name}}">{{$company->trade_name}}</option>
																@endforeach
                                                        @endif
                                                           @else
                                                            <option value=""></option>
                                                            <option value="All">All</option>
		                                                        @foreach($company_suppliers as $company)
																	<option  value="{{$company->trade_name}}">{{$company->trade_name}}</option>
																@endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

											
							               <div class="col-3">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <select class="form-control select2 select2-hidden-accessible" multiple="" name="country_test[]"
                                                            style="width: 100%;"
                                                            tabindex="-1" aria-hidden="true">
                                                        @if(isset($search_history))
															@if(isset($search_history['country_test']))
																<?php 
															if(in_array("All",$search_history['country_test']))
                                                            {echo '<option value="All" selected>All</option>';
																	foreach($countries as $country){
																echo '<option  value="'.$country->id.'">'.$country->country_name.'</option>';
															}
														}
														else{
															echo '<option value="All">All</option>';
															foreach($countries as $country){
																echo '<option  value="'.$country->id.'"';
																if(in_array($country->id,$search_history['country_test'])){echo "selected";}
																echo ">$country->country_name</option>";
															}
														}																
															?>
															
                                                          @else
                                                            <option value=""></option>
                                                            <option value="All">All</option>
															@foreach($countries as $country)
																<option value="{{$country->id}}">{{$country->country_name}}</option>
															@endforeach

															@endif
                                                           @else
                                                            <option value=""></option>
                                                            <option value="All">All</option>
															@foreach($countries as $country)
																<option value="{{$country->id}}">{{$country->country_name}}</option>
															@endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                       		               <div class="col-3">
                                                <div class="form-group">
                                                    <label>Route</label>
                                                    <select class="form-control select2 select2-hidden-accessible" multiple="" name="route_test[]"
                                                            style="width: 100%;"
                                                            tabindex="-1" aria-hidden="true">
                                                        @if(isset($search_history))
															@if(isset($search_history['route_test']))
																<option value="All" <?php if(in_array('All',$search_history['route_test'])){echo "selected";} ?>>All</option>
																<option value="2" <?php if(in_array('2',$search_history['route_test'])){echo "selected";} ?>>Fast Track</option>
																<option value="1" <?php if(in_array('1',$search_history['route_test'])){echo "selected";} ?>>Standard</option>														
															@endif
                                                           @else
                                                            <option value=""></option>
                                                            <option value="All">All</option>
															<option value="2">Fast Track</option>
															<option value="1">Standard</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
											
											
                                    <div class="form-group col-md-4">
                                        <label>Local Agent:</label>
                                        <input type="text" value=
										<?php 
										if(isset($search_history)){
												if(isset($search_history['local_agent'])){
													$onlyconsonants = str_replace("\t", "", $search_history['local_agent']);
															echo ("'".trim($onlyconsonants)."'");
														}else{echo "''";}
														}else{echo "''";}
														?>
														 class="form-control"
                                               name="local_agent">
                                    </div>
									
                                    <div class="form-group ml-2 col-md-1">
                                        <label><br/></label>
                                        <div class="row">
                                            <button type="submit" name="submit_value" value="searching"
                                                    class="btn btn-md btn-primary"/>
                                            <i class="nav-icon fas fa-search"></i></button> &nbsp;
                                        </div>

                                    </div>

                                </div>

                                <hr/>

                                                @php($suspended_active=0)
                                                @php($suspended_void=0)
                                                @php($suspended_unsuspended=0)
                                                @php($suspended_ceased=0)
                                                @php($ceased_active=0)
                                                @php($ceased_void=0)
												@php($ceased_unceased=0)

                                @if($list_count>0)
                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                        <table id="example"
                                               class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                               role="grid" aria-describedby="example1_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Rendering engine: activate to sort column descending">
                                                    S.N
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Reg Number: activate to sort column ascending">Reg
                                                    Number
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Trade Name: activate to sort column ascending">Trade
                                                    Name
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Trade Name: activate to sort column ascending">Applicant Name
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Date: activate to sort column ascending">
													                @if($report_type=="Withdrawn")
																		Withdrawal Request Date
																	@else
																		Action Date
																	@endif
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Taken: activate to sort column ascending">
Action Taken
                                                    
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Status: activate to sort column ascending">
													                @if($report_type=="Withdrawn")
																		Decision Date
																	@else
																	Action Status
																	@endif
                                                </th>
                                            </tr>
                                            </thead>

                                            @if($report_type=="Suspended")
                                                <tbody>
                                                @php($i=1)
                                                @foreach($list as $item)
                                                    <tr role="row">
                                                        <td>{{$i++}}</td>
                                                        <td tabindex="0">{{$item->registration_number}}</td>
                                                        <td>{{$item->product_trade_name}}</td>
                                                        <td>{{$item->trade_name}}</td>
                                                        <td>{{$item->action_date}}</td>
                                                        <td style="font-weight:bold;">{{$item->action_taken}}</td>
                                                        <td style="font-weight:bold;">{{$item->suspension_status}}</td>
                                                    </tr>

                                                    @if($item->action_taken=='Suspended' && $item->suspension_status=='active')
                                                        @php($suspended_active=$suspended_active+1)
                                                    @elseif($item->action_taken=='Suspended' && $item->suspension_status=='Unsuspended')
                                                        @php($suspended_unsuspended=$suspended_unsuspended+1)
                                                    @elseif($item->action_taken=='Suspended' && $item->suspension_status=='Void')
                                                        @php($suspended_void=$suspended_void+1)
                                                    @elseif($item->action_taken=='Suspended' && $item->suspension_status=='Ceased')
                                                        @php($suspended_ceased=$suspended_ceased+1)
                                                    @endif

                                                @endforeach
                                                </tbody>


                                        </table>
                                    </div>


                @elseif($report_type=="Ceased")


                    <tbody>
                    @php($i=1)
                    @foreach($list as $item)
                        <tr role="row">
                            <td>{{$i++}}</td>
                            <td tabindex="0">{{$item->registration_number}}</td>
                            <td>{{$item->product_trade_name}}</td>
                            <td>{{$item->trade_name}}</td>
                            <td>{{$item->action_date}}</td>
                            <td style="font-weight:bold;">{{$item->action_taken}}</td>
                            <td style="font-weight:bold;">{{$item->suspension_status}}</td>
                        </tr>

                        @if($item->action_taken=='Ceased' && $item->suspension_status=='active')
                            @php($ceased_active=$ceased_active+1)
                        @elseif($item->action_taken=='Ceased' && $item->suspension_status=='Unceased')
                            @php($ceased_unceased=$ceased_unceased+1)
                        @elseif($item->action_taken=='Ceased' && $item->suspension_status=='Void')
                            @php($ceased_void=$ceased_void+1)
                        @endif

                    @endforeach
                    </tbody>


                    </table>
            </div>
        </div>

        </div>
        </div>
                @elseif($report_type=="Withdrawn")
				
				@php($total_withdrawals = 0)


                    <tbody>
                    @php($i=1)
                    @foreach($list as $item)
                        <tr role="row">
                            <td>{{$i++}}</td>
                            <td tabindex="0">{{$item->registration_number}}</td>
                            <td>{{$item->product_trade_name}}</td>
                            <td>{{$item->trade_name}}</td>
                            <td>{{$item->action_date}}</td>
                            <td style="font-weight:bold;">{{$item->action_taken}}</td>
                            <td style="font-weight:bold;">{{$item->withdrawal_decision_date}}</td>
                        </tr>

                    @endforeach
				@php($total_withdrawals = $i-1)
                    </tbody>


                    </table>

        </div>
        </div>
        @endif
        @endif
		     @if($report_type=="Suspended")
		                 <div class="card col-md-5 ml-5">
                    <div class="card-header">
                        <h3 class="card-title"><b>Report Summary</b></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Suspended Application Status</th>
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Active</td>
                                <td><span class="badge badge-btn btn-lg bg-success"> {{$suspended_active}} </span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Ceased</td>
                                <td><span class="badge badge-btn btn-lg bg-danger"> {{$suspended_ceased}} </span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Unsuspended</td>
                                <td><span class="badge badge-btn btn-lg bg-primary"> {{$suspended_unsuspended}} </span>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Void</td>
                                <td><span class="badge badge-btn btn-lg bg-warning"> {{$suspended_void}} </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><B>Total</B></td>
                                <td>
                                    <span class="badge badge-btn btn-lg bg-secondary"> <b>{{$suspended_unsuspended +$suspended_ceased +$suspended_active+$suspended_void}}</b> </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
       @elseif($report_type=="Ceased")

        <div class="card col-md-5 ml-5">
            <div class="card-header">
                <h3 class="card-title"><b> Report Summary </b> </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ceased Applications Status</th>
                        <th>Qty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Active</td>
                        <td><span class="badge badge-btn btn-lg bg-success"> {{$ceased_active}} </span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Unceased</td>
                        <td><span class="badge badge-btn btn-lg bg-primary"> {{$ceased_unceased}} </span></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Void</td>
                        <td><span class="badge badge-btn btn-lg bg-warning"> {{$ceased_void}} </span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><B>Total</B></td>
                        <td>
                            <span class="badge badge-btn btn-lg bg-secondary"> <b>{{$ceased_active +$ceased_unceased +$ceased_void}} </b></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        @endif

		     @if($report_type=="Withdrawn")
                 <div class="card col-md-5 ml-5">
                    <div class="card-header">
                        <h3 class="card-title"><b>Report Summary</b></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                            </thead>
                            <tbody>

                            <tr>
                                <td></td>
                                <td><B>Total Withdrawn Certifications</B></td>
                                <td>
                                    <span class="badge badge-btn btn-lg bg-secondary"> <b>{{$total_withdrawals}}</b> </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
            </div>
            <!-- /.card-body -->
        </div>
				@endif

        </div>
        </div>
    </section>


    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
		

        //Initialize Select2 Elements
        $(function () {
            $('.select2').select2()
        });

        //Date picker
        $('#task_start_date').datetimepicker({
            format: 'L'
        });

        //Date picker
        $('#task_end_date').datetimepicker({
            format: 'L'
        });


    </script>

@endsection


@section('scripts')

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script>


        //Initialize Select2 Elements
        $(function () {
            $('.select2').select2()
        });

        //Date picker
        $('#task_start_date').datetimepicker({
            format: 'L'
        });

        //Date picker
        $('#task_end_date').datetimepicker({
            format: 'L'
        });


    </script>

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
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>


@endsection 