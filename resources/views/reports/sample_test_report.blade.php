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
                <h3 class="text-center text-bold display-5">Sample Test Report</h3>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <form action="{{route('get_sample_test_report')}}" method="post">
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
                                        <label>Task From Date:</label>
                                        <div class="input-group date" id="task_start_date"
                                             data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                   data-target="#task_start_date" name="task_start_date"
                                                   value="@if(isset($search_history)) {{$search_history['start_date']}} @endif"
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
                                        <label>Task To Date:</label>
                                        <div class="input-group date" id="task_end_date"
                                             data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                   data-target="#task_end_date" name="task_end_date"
                                                   value="@if(isset($search_history)) {{$search_history['end_date']}} @endif"
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
                                            <button type="submit" name="submit_btn" value="search"
                                                    class="btn btn-primary btn-lg">
                                                <i class="fas fa-search"></i>
                                            </button>
                                           {{-- <button type="submit" name="submit_btn" value="print"
                                                    class="btn btn-warning btn-lg">
                                                <i class="fas fa-print"></i>
                                            </button>--}}
                                        </div>
                                    </div>
                                </div>
                            </div> {{--end of row1--}}


                            @if(isset($sample_tests_count_by_year))
                                <br/>
                                <h4 class="text-primary">Requests Sent to QC
                                    between @if(isset($search_history)) {{$search_history['start_date']}}
                                    and {{$search_history['end_date']}} @endif</h4>
                                <br/>
                                <table class="table table-bordered  dataTable no-footer dtr-inline "
                                       style="width: 50%" role="grid" aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="5%">Year
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="10%">Sample Test Requests
                                        </th>
                                    </thead>
                                    <tbody>

                                    @foreach($sample_tests_count_by_year as $sample_tests)
                                        <tr>
                                            <td>{{$sample_tests['year']}}</td>
                                            <td>{{$sample_tests['count']}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @endif

                            @if(isset($data))
                                <br/>
                                <br/>
                                <h4 class="text-primary">Completed Sample Test Requests between @if(isset($search_history)) {{$search_history['start_date']}}
                                    and {{$search_history['end_date']}} @endif</h4>
                                <br/>

                                <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                    <table id="example"
                                           class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                           role="grid" aria-describedby="example1_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Serial Number: activate to sort column descending"
                                                aria-sort="ascending" width="5%">S.N
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Title: activate to sort column ascending"
                                                width="15%">Application Number
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Title: activate to sort column ascending"
                                                width="15%">Applicant Name
                                            </th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Reference Number: activate to sort column descending"
                                                aria-sort="ascending" width="20%">Product Generic Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="10%"> Product Trade Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Request Date - Response Date
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="10%">Time-lapse
                                            </th>
                                        </thead>
                                        <tbody>
                                        @php($i=1)
                                        @foreach( $data as $details)
                                            <tr role="row" class="odd">

                                                <td>{{$i++}}</td>
                                                <td>{{$details->application_number}}</td>
                                                <td>{{$details->company_name}}</td>
                                                <td>{{$details->product_name}}</td>
                                                <td>{{$details->product_trade_name}}</td>
                                                <td>{{$details->inspection_sent_date}}
                                                    - {{$details->qc_received_date}} </td>

                                                <td>
                                                    <?php
                                                    /** @var TYPE_NAME $details */

                                                    $time_lapse = \Carbon\Carbon::create($details->inspection_sent_date)->diff(\Carbon\Carbon::create($details->qc_received_date), false);
                                                    ?>
                                                    <span class="text-primary text-bold text-2xl">{{ $time_lapse->format('%mm:%dd:%HHr')}}</span>
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
