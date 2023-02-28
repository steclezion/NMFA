@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

{{--<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">--}}

<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

@section('content')


    <section class="content">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <h3 class="text-center display-5">Decided Variations Time-Taken Report</h3>
                </div>
                <!-- /.container-fluid -->
            </section>

            <!-- Main content -->
                    <form action="{{route('get_variation_assessment_time_taken')}}" method="post">
                    @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary">
                                    <div class="card-header ">
                                        <h3 class="card-title">Search Criteria</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row"> {{--row1--}}
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>Task From Date</label>
                                                    <div class="input-group date" id="task_start_date"
                                                         data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input"
                                                               data-target="#task_start_date" name="task_start_date" value="@if(isset($search_history)) {{$search_history['start_date']}} @endif"
                                                               required>
                                                        <div class="input-group-append" data-target="#task_start_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>Task To Date</label>
                                                    <div class="input-group date" id="task_end_date"
                                                         data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input"
                                                               data-target="#task_end_date" name="task_end_date" value="@if(isset($search_history)) {{$search_history['end_date']}} @endif"
                                                               required>
                                                        <div class="input-group-append" data-target="#task_end_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" name="submit_btn" value="search" class="btn btn-primary btn-lg">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                     {{--   <button type="submit" name="submit_btn" value="print" class="btn btn-warning btn-lg">
                                                            <i class="fas fa-print"></i>
                                                        </button>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{--end of row1--}}



                                        @if(isset($data))
                                            <br/>
                                            <br/>
                                            <h4 class="text-primary">Time-Taken for Variations between @if(isset($search_history)) and {{$search_history['start_date']}}
                                                to {{$search_history['end_date']}} @endif</h4>

                                                <br/>
                                            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                <table id="example"
                                                       class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                       role="grid" aria-describedby="example1_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Serial Number: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>
                                                       <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Reference Number: activate to sort column descending" aria-sort="ascending" width="10%">Registration Number </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Variation Ref. Num.</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Generic Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Brand Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Applicant Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Assigned Date</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Decided Date</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="15%"> Decision</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="20%">Time-Taken (Days)</th>

                                                    </thead>
                                                    <tbody>
                                                    @php($i=1)
                                                    @foreach($data as $variation )
                                                        <tr role="row" class="odd">
                                                            <td>{{$i++}}</td>
                                                            <td>{{$variation->registration_number}}</td>
                                                            <td>{{$variation->variation_reference_number}}</td>
                                                            <td>{{$variation->product_name}}</td>
                                                            <td>{{$variation->product_trade_name}}</td>
                                                            <td>{{$variation->company_name}}</td>
                                                            <td>{{$variation->assigned_datetime}}</td>
                                                            <td>{{$variation->decision_date}}</td>
                                                            @if(strtolower($variation->decision_status) == 'accepted')
                                                            <td><span class="badge badge-success">{{$variation->decision_status}}</span></td>
                                                            @elseif(strtolower($variation->decision_status) == 'rejected')
                                                                <td><span class='badge badge-danger'>{{$variation->decision_status}}</span></td>
                                                            @endif
                                                            <td>
                                                                {{\Carbon\Carbon::create($variation->assigned_datetime)->diffInDays(\Carbon\Carbon::create($variation->decision_date))}}
                                                            </td>

                                                        </tr>
                                                    @endforeach

                                                    </tbody>

                                                </table>


                                    </div>

                                        @endif

                                 </div>
                                </div>
                            </div>
                        </div>


        </form>


    </section>
    </div>

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

@endsection  {{--end scripts--}}
