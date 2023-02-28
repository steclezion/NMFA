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
                <h4 class="text-center display-5">Regulatory Time-Taken Report for Certified Applications</h4>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <form action="{{route('get_regulatory_time_taken')}}" method="post">
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
                                        <label>Assessor</label>
                                        <select class="form-control select2 select2-hidden-accessible" name="user[]"
                                                multiple
                                                style="width: 100%;"
                                                tabindex="-1" aria-hidden="true">
                                            @if(isset($search_history))
                                                <option value="All">All</option>
                                                @foreach( $assessors as $assessor)
                                                    @if(in_array($assessor->id, $search_history['assessor_ids']))
                                                        <option selected
                                                                value="{{$assessor->id}}">{{$assessor->first_name}} {{$assessor->middle_name}}</option>
                                                    @else
                                                        <option value="{{$assessor->id}}">{{$assessor->first_name}} {{$assessor->middle_name}}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option value=""></option>
                                                <option value="All">All</option>
                                                @foreach($assessors as $assessor)
                                                    <option value="{{$assessor->id}}">{{$assessor->first_name}} {{$assessor->middle_name}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Task From Date</label>
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
                                        <label>Task To Date</label>
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

                            </div> {{--end of row1--}}


                            <div class="row">

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Route of Registration</label>
                                        <select class="form-control select2 select2-hidden-accessible" multiple=""
                                                style="width: 100%;" name="registration_route[]" tabindex="-1"
                                                aria-hidden="true">

                                            <option value="all"
                                                    @if(isset($search_history)) @if(  in_array("all", $search_history['registration_route'])) selected @endif @endif >
                                                All
                                            </option>
                                            <option value="2"
                                                    @if(isset($search_history)) @if(  in_array("2", $search_history['registration_route'])) selected @endif @endif >
                                                Fast Track
                                            </option>
                                            <option value='1'
                                                    @if(isset($search_history)) @if(  in_array("1", $search_history['registration_route'])) selected @endif @endif >
                                                Standard Mode
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <select class="form-control select2 select2-hidden-accessible" multiple=""
                                                style="width: 100%;" name="time[]" tabindex="-1"
                                                aria-hidden="true">
                                            <option value="all"
                                                    @if(isset($search_history)) @if(  in_array("all", $search_history['time'])) selected @endif @endif >
                                                All
                                            </option>
                                            <option value="in_time"
                                                    @if(isset($search_history)) @if(  in_array("in_time", $search_history['time'])) selected @endif @endif >
                                                In Time
                                            </option>
                                            <option value='post_time'
                                                    @if(isset($search_history)) @if(  in_array("post_time", $search_history['time'])) selected @endif @endif >
                                                Post Time
                                            </option>

                                        </select>
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
                                            {{--   <button type="submit" name="submit_btn" value="print" class="btn btn-warning btn-lg">
                                                   <i class="fas fa-print"></i>
                                               </button>--}}


                                        </div>
                                    </div>
                                </div>

                            </div>


                            @if(isset($data))

                                <hr>
                                <br/>
                                <h4 class="text-primary">Regulatory Time-Taken for Certified Applications
                                    between @if(isset($search_history)) {{$search_history['start_date']}}
                                    and {{$search_history['end_date']}} @endif</h4>
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
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Reference Number: activate to sort column descending"
                                                aria-sort="ascending" width="15%">Application Number
                                            </th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Reference Number: activate to sort column descending"
                                                aria-sort="ascending" width="15%">Assessor
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Generic Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Brand Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Applicant Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Route of Registration
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Time
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="20%">Non Regulatory Time-Taken (Days)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="15%">Regulatory Time-Taken (Days)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Title: activate to sort column ascending"
                                                width="15%">Total Time-Taken (Days)
                                            </th>
                                        </thead>
                                        <tbody>
                                        @php($i=1)

                                        @foreach($data as $report)
                                            <tr role="row" class="odd">
                                                <td>{{$i++}}</td>

                                                <td>{{$report['application_number']}}</td>
                                                <td>{{$report['assessor_full_name']}}</td>

                                                <td>{{$report['generic_name']}}</td>
                                                <td>{{$report['product_name']}}</td>
                                                <td>{{$report['company_name']}}</td>
                                                @if($report['application_type'] == 1)

                                                    <td>Standard Mode</td>
                                                @elseif($report['application_type'] == 2)

                                                    <td>Fast Track</td>
                                                @endif

                                                @if($report['end_time_status'] == 'In time')
                                                    <td>
                                                        <span class="badge badge-success">{{$report['end_time_status']}}</span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge badge-danger">{{$report['end_time_status']}}</span>
                                                    </td>
                                                @endif
                                                <td>{{$report['non_regulatory_days_sum']}}</td>
                                                <td>{{$report['regulatory_time_taken']}}</td>
                                                <td>{{$report['total_time_taken']}}</td>

                                            </tr>
                                        @endforeach

                                        </tbody>

                                    </table>


                                    @endif
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>


    </section>

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
