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
                    <h4 class="text-center display-5">Assessor Tasks Report</h4>
                </div>
                <!-- /.container-fluid -->
            </section>

            <!-- Main content -->
                    <form action="{{route('assessor_report')}}" method="post">
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

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Assessor/PERC</label>
                                                    <select class="form-control select2 select2-hidden-accessible" name="user"
                                                            style="width: 100%;"
                                                            tabindex="-1" aria-hidden="true">
                                                        @if(isset($search_history))
                                                            <option value="{{$search_history['user_id']}}">{{$search_history['assessor_name']}}</option>
                                                           @else
                                                            <option value=""></option>
                                                        @endif
                                                            <option value="All">All</option>
                                                        @foreach($assessors as $assessor)
                                                            <option  value="{{$assessor->id}}">{{$assessor->first_name}} {{$assessor->middle_name}}</option>
                                                        @endforeach
                                                        @foreach($perc_members as $perc_member)
                                                            <option value="{{$perc_member->id}}">{{$perc_member->first_name}}  {{$perc_member->middle_name}} (PERC)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>Task From Date:</label>
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
                                                    <label>Task To Date:</label>
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

                                        </div> {{--end of row1--}}


                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Task Type</label>
                                                    <select class="form-control select2 select2-hidden-accessible" multiple="" style="width: 100%;" name="task_type[]" tabindex="-1" aria-hidden="true">

                                                        <option value="all" @if(isset($search_history)) @if(  in_array("all", $search_history['task_type'])) selected @endif @endif >All</option>
                                                        <option value="prelimunary" @if(isset($search_history)) @if(  in_array("prelimunary", $search_history['task_type'])) selected @endif @endif >Prelimunary Screening</option>
                                                        <option value='dossier' @if(isset($search_history)) @if(  in_array("dossier", $search_history['task_type'])) selected @endif @endif >Dossier Evaluation</option>
                                                        <option value='dossier_section' @if(isset($search_history)) @if(  in_array("dossier_section", $search_history['task_type'])) selected @endif @endif >Dossier Section Assignment</option>
                                                        <option value='variation' @if(isset($search_history)) @if(  in_array("variation", $search_history['task_type'])) selected @endif  @endif  >Variation Evaluation</option>
                                                        <option value="psur" @if(isset($search_history)) @if(  in_array("psur", $search_history['task_type'])) selected @endif @endif >PSUR</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Task Status:</label>
                                                    <div class="input-group">
                                                        <select class="select2 select2-hidden-accessible"   multiple="" style="width: 94%;" name="task_status[]" tabindex="-1" aria-hidden="true">
                                                            <option value="All" @if(isset($search_history)) @if(  in_array("All", $search_history['task_status'])) selected @endif @endif >All</option>
                                                            <option value="pause"  @if(isset($search_history)) @if(  in_array("pause", $search_history['task_status'])) selected @endif @endif>Paused</option>
                                                            <option value="Inprogress" @if(isset($search_history))  @if(  in_array("Inprogress", $search_history['task_status'])) selected @endif @endif>In-progress</option>
                                                            <option value="Completed"  @if(isset($search_history)) @if(  in_array("Completed", $search_history['task_status'])) selected @endif @endif>Completed</option>
                                                            <option value="Decision"  @if(isset($search_history)) @if(  in_array("Decision", $search_history['task_status'])) selected @endif @endif>Decided</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" name="submit_btn" value="search" class="btn btn-primary btn-lg">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                      {{--  <button type="submit" name="submit_btn" value="print" class="btn btn-warning btn-lg">
                                                            <i class="fas fa-print"></i>
                                                        </button>--}}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        @if(isset($data))
                                            <hr>
                                            <br/>
                                            <h4 class="text-primary">Assessor Tasks between @if(isset($search_history)) {{$search_history['start_date']}} and {{$search_history['end_date']}} @endif</h4>


                                            <table  class="table table-bordered  dataTable no-footer dtr-inline "
                                                    style="width: 50%" role="grid" aria-describedby="example1_info">

                                                <thead>
                                                <tr role="row">
                                                    <th class="sorting sorting_asc" tabindex="0"
                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                        aria-label="Serial Number: activate to sort column descending"
                                                        aria-sort="ascending" width="60%">Task Type
                                                    </th>
                                                    <th class="sorting sorting_asc" tabindex="0"
                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                        aria-label="Reference Number: activate to sort column descending"
                                                        aria-sort="ascending" width="40%">Total
                                                    </th>
                                                </thead>
                                                <tbody>


                                                @foreach($counter as $task_type=>$count)
                                                    <tr>
                                                        <td>{{$task_type}}</td>
                                                        <td>{{$count}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>


                                                <br/>
                                            <h4 class="text-primary">List of Assessor Tasks</h4>

                                            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                <table id="example"
                                                       class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                       role="grid" aria-describedby="example1_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Serial Number: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>
                                                       <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Reference Number: activate to sort column descending" aria-sort="ascending" width="10%">Ref. Num</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" width="10%">Task</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="10%">Assessor</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="10%">Product Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="20%">Generic Name</th>
                                                         <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Location: activate to sort column ascending" width="10%">Applicant Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" width="12%">Start Date - End Date</th>
                                                        <th rowspan="1" colspan="1">Status</th></tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($i=1)
                                                    @foreach( $data as $assessor_tasks)
                                                    @foreach($assessor_tasks as $report)
                                                        <tr role="row" class="odd">
                                                            <td>{{$i++}}</td>

                                                            <td>{{$report['reference_number']}}</td>
                                                            @if($report['related_task'] == 'Application')
                                                                <td>Preliminary Screening  </td>
                                                            @else
                                                                <td>{{$report['related_task']}} </td>
                                                            @endif
                                                            <td>{{$report['full_name']}}</td>
                                                            <td>{{$report['product_name']}}</td>
                                                            <td>{{$report['generic_name']}}</td>
                                                            <td>{{$report['company_name']}}</td>
                                                            <td>{{ $report['start_date']}} to {{$report['end_date']}}</td>

                                                            @if(strtolower($report['status']) == 'pause')
                                                                <td><span class='badge badge-warning'>Paused</span></td>
                                                            @elseif(strtolower($report['status']) == 'inprogress')
                                                                <td><span class='badge badge-primary'>In-progress</span></td>
                                                            @elseif(strtolower($report['status']) == 'completed')
                                                                <td><span class='badge badge-success'>Completed</span></td>
                                                            @elseif(strtolower($report['status']) == 'decision')
                                                                <td><span class='badge badge-secondary'>Decided</span></td>
                                                            @endif

                                                        </tr>
                                                    @endforeach
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
