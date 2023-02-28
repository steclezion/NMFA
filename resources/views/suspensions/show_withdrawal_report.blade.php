@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Suspended, Ceased or Withdrawn Report</h3>

                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form role='form' method='post' action="{{route('suspensions.debug_report')}}">
                                @csrf

                                <div class="report-selector row">
                                    <div class="form-group col-md-1">
                                        <label> Report : </label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select class="form-control" required name="report_type">
                                            @if($report_type=="Suspended")
												<option value=""></option>
												<option value="Suspended" selected>Suspended</option>
												<option value="Ceased">Ceased</option>
												<option value="Withdrawn">Withdrawn</option>
                                        </select>
                                        @elseif($report_type=="Ceased")
											<option value=""></option>
											<option value="Suspended">Suspended</option>
											<option value="Ceased" selected>Ceased</option>
											<option value="Withdrawn">Withdrawn</option>
                                            </select>
                                        @elseif($report_type=="Withdrawn")
											<option value=""></option>
											<option value="Suspended">Suspended</option>
											<option value="Ceased">Ceased</option>
											<option value="Withdrawn" selected>Withdrawn</option>
                                            </select>
                                        @else
											<option value=""></option>
											<option value="Suspended">Suspended</option>
											<option value="Ceased">Ceased</option>
											<option value="Withdrawn">Withdrawn</option>
                                            </select>
                                        @endif

                                    </div>
                                </div>

                                <div class="report-selector row">

                                    <div class="form-group col-md-2">
                                        <label>From :</label>
                                        <input type="date" required value="{{$from}}" class="form-control" name="from">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>To :</label>
                                        <input type="date" required value="{{$to}}" class="form-control" name="to">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Applicant :</label>
                                        <select class="form-control" name="company">
											<option value=""></option>
                                            @foreach($company_suppliers as $company)
												<option value="{{$company->id}}">{{$company->trade_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Country :</label>
                                        <select class="form-control" name="country">
											<option value=""></option>
                                            @foreach($countries as $country)
												<option value="{{$country->id}}">{{$country->country_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label>Route:</label>
                                        <select class="form-control" name="route">
                                            @if($route==1)
												<option value="1" selected>Standard</option>
												<option value="2">Fast Track</option>
												<option value=""></option>
                                            @elseif($route==2)
												<option value="2" selected>Fast Track</option>
												<option value="1">Standard</option>
												<option value=""></option>
                                            @else
												<option value=""></option>
												<option value="2">Fast Track</option>
												<option value="1">Standard</option>
                                            @endif


                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Local Agent :</label>
                                        <input type="text" value="{{$local_agent}}" class="form-control"
                                               name="local_agent">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label><br/></label>
                                        <br/>
                                        <button type="submit" class="form-control btn-sm btn-primary"/>
                                        <i class="nav-icon fas fa-search"></i> Search </button>
                                    </div>

                                </div>

                                <hr/>


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
                                                    aria-label="Trade Name: activate to sort column ascending">Company
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Trade Name: activate to sort column ascending">Trade
                                                    Name
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Date: activate to sort column ascending">Date
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Taken: activate to sort column ascending">StatusSS
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Action Status: activate to sort column ascending">
                                                    Decision
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php($i=1)
                                            @php($withdraw_accepted=0)
                                            @php($withdraw_rejected=0)
                                            @php($withdraw_not_decided=0)
                                            @foreach($list as $item)
                                                <tr role="row">
                                                    <td>{{$i++}}</td>
                                                    <td tabindex="0">{{$item->registration_number}}</td>
                                                    <td>{{$item->trade_name}}</td>
                                                    <td>{{$item->product_trade_name}}</td>
                                                    <td>{{$item->action_date}}</td>
                                                    <td style="font-weight:bold;">{{$item->action_taken}}</td>
                                                    <td style="font-weight:bold;">{{$item->withdrawal_decision}}</td>
                                                </tr>

                                                @if($item->withdrawal_decision=='Accepted')
                                                    @php($withdraw_accepted=$withdraw_accepted+1)
                                                @elseif($item->withdrawal_decision=='Rejected')
                                                    @php($withdraw_rejected=$withdraw_rejected+1)
                                                @elseif($item->withdrawal_decision==NULL || $item->withdrawal_decision=='')
                                                    @php($withdraw_not_decided=$withdraw_not_decided+1)
                                                @endif

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>


                                    <div class="card col-md-5 ml-5">
                                        <div class="card-header">
                                            <h3 class="card-title"><B> <u> Report Summary </B> </u></h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-0">
                                            <table class="table table-sm">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Withdrawals Application Status</th>
                                                    <th>Qty</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Withdraw Accepted</td>
                                                    <td>
                                                        <span class="badge badge-btn btn-lg bg-success"> {{$withdraw_accepted}} </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Withdraw Rejected</td>
                                                    <td>
                                                        <span class="badge badge-btn btn-lg bg-danger"> {{$withdraw_rejected}} </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Withdraw Not Decided</td>
                                                    <td>
                                                        <span class="badge badge-btn btn-lg bg-warning"> {{$withdraw_not_decided}} </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><B>Total</B></td>
                                                    <td><span class="badge badge-btn btn-lg bg-secondary">
															<b>{{$withdraw_accepted +$withdraw_rejected+ $withdraw_not_decided}} </b>
														</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
							</form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


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

