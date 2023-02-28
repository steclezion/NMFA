@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

{{--<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">--}}

<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

@section('content')
@php
use Carbon\Carbon as Carbon;
@endphp

    <section class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <h4 class="text-center display-5"
                    data-toggle="tooltip" data-placement="top" data-html="true"
                    title="Meeting Report filters all meetings where meeting decision has been updated to the system.">
                    Meetings Report <i class="fa fa-info-circle"></i></h4>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <form action="{{route('get_meetings')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header ">
                            <h3 class="card-title">Meetings Report</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row"> {{--row1--}}

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Meeting Type</label>
                                        <select class="form-control select2 select2-hidden-accessible" multiple=""
                                                style="width: 100%;" name="meeting_type[]" tabindex="-1"
                                                aria-hidden="true">

                                            <option value="All"
                                                    @if(isset($search_history)) @if(  in_array("All", $search_history['meeting_type'])) selected @endif @endif >
                                                All
                                            </option>
                                            <option value="Decision_Meeting"
                                                    @if(isset($search_history)) @if(  in_array("Decision_Meeting", $search_history['meeting_type'])) selected @endif @endif >
                                                Registration Meetings
                                            </option>
                                            <option value='Other_Meeting'
                                                    @if(isset($search_history)) @if(  in_array("Other_Meeting", $search_history['meeting_type'])) selected @endif @endif >
                                                Other Meetings
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-2">
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


                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Decision</label>
                                        <select class="form-control select2 select2-hidden-accessible" multiple=""
                                                style="width: 100%;" name="decision_type[]" tabindex="-1"
                                                aria-hidden="true">

                                            <option value="All"
                                                    @if(isset($search_history)) @if(  in_array("All", $search_history['decision_type'])) selected @endif @endif >
                                                All
                                            </option>
                                            <option value="Accepted"
                                                    @if(isset($search_history)) @if(  in_array("Accepted", $search_history['decision_type'])) selected @endif @endif >
                                                Accepted
                                            </option>
                                            <option value='Rejected'
                                                    @if(isset($search_history)) @if(  in_array("Rejected", $search_history['decision_type'])) selected @endif @endif >
                                                Rejected
                                            </option>
                                            <option value='Deferred'
                                                    @if(isset($search_history)) @if(  in_array("Deferred", $search_history['decision_type'])) selected @endif @endif >
                                                Deferred
                                            </option>

                                        </select>
                                    </div>
                                </div>


                                <div class="col-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="input-group">
                                            <button type="submit" name="submit_btn" value="search"
                                                    class="btn btn-primary btn-lg">
                                                <i class="fas fa-search"></i>
                                            </button>
                                           {{-- <button type="submit" name="submit_btn" value="print"
                                                    class="btn btn-warning">
                                                <i class="fas fa-print"></i>
                                            </button>--}}
                                        </div>
                                    </div>
                                </div>
                            </div> {{--end of row1--}}


                            @if(isset($data))

                                <hr>
                                <br/>
                                <h4 class="text-primary">PERC Meetings Between @if(isset($search_history)) {{$search_history['start_date']}} and {{$search_history['end_date']}} @endif</h4>

                                <table  class="table table-bordered  dataTable no-footer dtr-inline "
                                        style="width: 50%" role="grid" aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="60%">Year
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="40%">Number of Meetings
                                        </th>
                                    </thead>
                                    <tbody>

                                    @foreach($number_of_perc_meetings as $meetings)
                                        <tr>
                                            <td>{{$meetings['year']}}</td>
                                            <td>{{$meetings['count']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <br/>
                                <br/>
                                <h4 class="text-primary">PERC Meetings</h4>

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
                                            aria-sort="ascending" width="10%">Meeting Type
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Title: activate to sort column ascending"
                                            width="20%">Venue
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Location: activate to sort column ascending"
                                            width="20%">Meeting Date
                                        </th>
                                        @if(isset($search_history)) @if(  in_array("Accepted", $search_history['decision_type']))
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="5%">Accepted
                                            </th>
                                        @endif
                                        @endif
                                        @if(isset($search_history)) @if(  in_array("Rejected", $search_history['decision_type']))
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="5%">Rejected
                                            </th>
                                        @endif
                                        @endif

                                        @if(isset($search_history)) @if(  in_array("Deferred", $search_history['decision_type']))
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Type: activate to sort column ascending"
                                                width="5%">Deferred
                                            </th>
                                        @endif
                                        @endif

                                        <th rowspan="1" colspan="1">Total Products</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($data as $report)
                                    <?php

                                    $flag = TRUE;
                                    /** @var TYPE_NAME $report */
                                    if($report['accepted_count']==0 && $report['rejected_count']==0 )
                                     {
                                        $flag=FALSE;

                                     }
                                    if($report['type']=='Decision_Meeting')
                                    {
                                     if($report['accepted_count']==0 && $report['rejected_count']==0 && $report['deferred_count']==0  )
                                     {
                                        $flag=FALSE;

                                     }
                                     else{
                                        $flag = TRUE;
                                     }
                                    }

                                     ?>
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>

                                            <td>{{$report['type']}}</td>
                                            <td>{{$report['venue']}}</td>
                                            <td>{{Carbon::create($report['meeting_date'])->format('d-m-Y')}} {{$report['time']}}</td>
                                            @if(isset($search_history)) @if(  in_array("Accepted", $search_history['decision_type']))
                                            @if($flag)
                                                <td style="background-color: rgba(75, 192, 192, 0.2)">{{$report['accepted_count']}} </td>
                                                @else
                                                <td style="background-color: rgba(75, 192, 192, 0.2)">N/A</td>
                                            @endif
                                            @endif
                                            @endif
                                            @if(isset($search_history)) @if(  in_array("Rejected", $search_history['decision_type']))
                                            @if($flag)
                                                <td style="background-color: rgba(255, 99, 132, 0.2)">{{$report['rejected_count']}}</td>
                                                @else
                                                <td style="background-color: rgba(255, 99, 132, 0.2)">N/A</td>
                                            @endif

                                            @endif
                                            @endif
                                            @if(isset($search_history))
                                                @if(  in_array("Deferred", $search_history['decision_type']))
                                                    @if($report['type']=='Decision_Meeting')
                                                    @if($flag)
                                                        <td style="background-color: rgba(255, 206, 86, 0.2)">{{$report['deferred_count']}} </td>

                                                    @else

                                                        <td style="background-color: rgba(255, 206, 86, 0.2)">N/A</td>

                                            @endif

                                                    @else

                                                        <td style="background-color: rgba(255, 206, 86, 0.2)">N/A</td>
                                                    @endif
                                                @endif
                                            @endif
                                           {{-- <td style="background-color: rgba(54, 162, 235, 0.2)">{{$report['not_decided_count']}} </td>--}}

                                            </td>
                                            <td>
                                                {{$report['total']}}

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

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
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

@endsection  {{--end scripts--}}
