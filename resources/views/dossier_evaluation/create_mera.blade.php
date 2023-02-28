@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            Dossier Evaluation
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-three-overview-tab"
                                                   data-toggle="pill" href="#custom-tabs-three-overview" role="tab"
                                                   aria-controls="custom-tabs-three-overview"
                                                   aria-selected="true">Overview</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-qc-tab" data-toggle="pill"
                                                   href="#custom-tabs-three-qc" role="tab"
                                                   aria-controls="custom-tabs-three-qc" aria-selected="false"> Quality
                                                    Control Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-issue-tab" data-toggle="pill"
                                                   href="#custom-tabs-three-issue" role="tab"
                                                   aria-controls="custom-tabs-three-issue" aria-selected="false">Issue
                                                    Query</a>
                                            </li>
                                            <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-three-assessment-tab"
                                                       data-toggle="pill"
                                                       href="#custom-tabs-three-assessment" role="tab"
                                                       aria-controls="custom-tabs-three-assessment"
                                                       aria-selected="false">Assessment Report</a>
                                                </li>
                                                <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-three-dossier-tab" data-toggle="pill"
                                                           href="#custom-tabs-three-dossier" role="tab"
                                                           aria-controls="custom-tabs-dossier-report" aria-selected="false">Dossier
                                                        </a>
                                                </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-report-tab" data-toggle="pill"
                                                   href="#custom-tabs-three-report" role="tab"
                                                   aria-controls="custom-tabs-three-report" aria-selected="false">Timeline
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-notification-tab"
                                                   data-toggle="pill" href="#custom-tabs-three-notification" role="tab"
                                                   aria-controls="custom-tabs-three-notification"
                                                   aria-selected="false">Notificaton</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <!-- content overview tab data -->
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <!-- this code below is for over view -->
                                            <div class="tab-pane fade active show" id="custom-tabs-three-overview"
                                                 role="tabpanel" aria-labelledby="custom-tabs-three-overview-tab">
                                                <!-- this is overview tab -->
                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <div class="card card-info col-md-10 offset-1">
                                                            <div class="card-header">
                                                                <h3 class="card-title">General Info</h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <label>Task Assigned to : </label>
                                                                {{ $dossier_evaluation_details->assessor_id }}<br>
                                                                <label>Assigned Date : </label>
                                                                {{ $dossier_evaluation_details->assigned_datetime }}<br>
                                                                <label>Supervisor : </label>
                                                                {{ $dossier_evaluation_details->supervisor_id }}<br>
                                                                <label>Dossier Ref.Num : </label>
                                                                {{ $dossier_evaluation_details->dossier_ref_num }}<br>
                                                                <label>Evaluation Mode : </label> STD/FS
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <br>
                                                        <div class="card card-green col-md-10 offset-1">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Company Info </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <label>Company Name : </label><br>
                                                                <label>Address : </label><br>
                                                                <label>Phone.Num : </label><br>
                                                                <center>
                                                                    <h5><u> Contact Perosn</u></h5>
                                                                </center>
                                                                <label>Contact Person Name : </label><br>
                                                                <label>Address : </label><br>
                                                                <label>Phone.Num : </label><br>
                                                                <label>Email : </label><br>


                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-primary col-md-10 offset-1">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Drug Info</h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <label>Generic name of the product : </label><br>
                                                                <label>Dosage Form : </label><br>
                                                                <label>Strength : </label><br>
                                                                <label>Manufacturer : </label>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <br>
                                                        <div class="card card-lightblue col-md-10 offset-1">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Task/Progress</h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <label>Current Task :
                                                                </label>{{ $dossier_evaluation_details->task_tracker_title }}
                                                                <br>
                                                                <label>Task Status :
                                                                </label>{{ $dossier_evaluation_details->content_detail }}
                                                                <br>
                                                                <label>Task Start Date :
                                                                </label>{{ $dossier_evaluation_details->task_started }}
                                                                <br>
                                                                <label>Progress Status</label><br>
                                                                <div class="progress">
                                                                    <div
                                                                        class="progress-bar bg-gradient-green progress-bar-striped"
                                                                        role="progressbar" aria-valuenow="40"
                                                                        aria-valuemin="0" aria-valuemax="100"
                                                                        style="width: 40%">
                                                                        <span class="sr-only">40% Complete </span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <!-- end of overview code -->

                                            {{--this code below is for assessment report  --}}
                                            <div class="tab-pane fade" id="custom-tabs-three-assessment" role="tabpanel"
                                                 aria-labelledby="custom-tabs-three-assessment-tab">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-primary">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Standard Evaluation</h3>
                                                            </div>
                                                            <form method="get" action="{{ route('download_pdf') }}">
                                                                <div class="card-body">

                                                                    <label>Choose File</label>
                                                                    <select class="form-control" name='std_type'>
                                                                        @foreach ($standard as $std)
                                                                            <option
                                                                                value="{{ $std->id }}">{{ $std->name }}
                                                                            </option>

                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                                <div class="card-footer" style="float:right">
                                                                    <button class="btn btn-success"
                                                                            role="button">Download
                                                                    </button>
                                                                </div>
                                                            </form>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-primary">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Fast Track</h3>
                                                            </div>
                                                            <form method="get" action="{{ route('download_pdf') }}">
                                                                <div class="card-body">
                                                                    <label>Choose File</label>
                                                                    <select class="form-control" name='std_type'>
                                                                        @foreach ($fast as $fas)
                                                                            <option
                                                                                value="{{ $fas->id }}">{{ $fas->name }}
                                                                            </option>

                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                                <div class="card-footer" style="float:right">
                                                                    <button class="btn btn-success"
                                                                            role="button">Download
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                                <br>
                                                {{--                                            Upload Assessment Report--}}

                                                <br><br>


                                                <div class="card card-outline card-success collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Upload history</strong></h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;">


                                                        <div id="example1_wrapper"
                                                        class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                       <table id="example1"
                                                              class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                              role="grid" aria-describedby="example1_info">

                                                           <thead>
                                                           <tr role="row">
                                                               <th class="sorting sorting_asc" tabindex="0"
                                                                   aria-controls="example1" rowspan="1" colspan="1"
                                                                   aria-label="Serial Number: activate to sort column descending"
                                                                   aria-sort="ascending" width="5%">S.N
                                                               </th>
                                                               <th class="sorting sorting_asc" tabindex="0"
                                                                   aria-controls="example1" rowspan="1" colspan="1"
                                                                   aria-label="Reference Number: activate to sort column descending"
                                                                   aria-sort="ascending" width="20%"> Name
                                                               </th>
                                                               <th class="sorting" tabindex="0"
                                                                   aria-controls="example1" rowspan="1" colspan="1"
                                                                   aria-label="Title: activate to sort column ascending"
                                                                   width="40%"> Description
                                                               </th>
                                                               <th class="sorting" tabindex="0"
                                                                   aria-controls="example1" rowspan="1" colspan="1"
                                                                   aria-label="Title: activate to sort column ascending"
                                                                   width="20%"> The
                                                               </th>
                                                               <th rowspan="1" colspan="1" width="15%">Actions</th>
                                                           </tr>
                                                           </thead>
                                                           <tbody>
                                                           {{--@php($i=1)
                                                           @foreach($issue_query_details as $query)
                                                               <tr role="row" class="odd">
                                                                   <td>{{$i++}}</td>
                                                                   <td>{{$query->name}}</td>
                                                                   <td>{{$query->description}}</td>
                                                                   <td>{{$query->document_type}}</td>

                                                                   <td>

                                                                       <a href="{{route('view_html_template',['id'=>$template->id,'dossier_asg_id'=>$dossier_evaluation_details->id])}}"
                                                                          data-toggle="tooltip"
                                                                          class="btn btn-info btn-sm"
                                                                          data-placement="top"
                                                                          title="view the Template"><i
                                                                               class="fas fa-eye "></i></a>
                                                                   </td>
                                                               </tr>
                                                           @endforeach--}}
                                                           </tbody>

                                                       </table>
                                                   </div>


                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                                <!-- /.card -->



                                                <div class="card card-outline card-success collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Submit Assessment Report: Dossier
                                                                Ref. Num. - {{$dossier_evaluation_details->dossier_ref_num}}</strong>
                                                        </h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;">

                                                        <form method="post"
                                                              action="{{ route('upload_assessment_report') }}"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="assessment_report_file">Assessment
                                                                    Report</label>
                                                                <div class="form-group">
                                                                    <label> Reference Number</label>
                                                                    <input type="text" class="form-control"
                                                                           name="ref_num">
                                                                </div>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="assessment_report_file"
                                                                               id="assessment_report_file"
                                                                               class="custom-file-input" required>
                                                                        <label class="custom-file-label"
                                                                               for="assessment_report_file">Choose
                                                                            file</label>
                                                                        {{--                                                                        @error('$file_required_error')--}}
                                                                        {{--                                                                        <span class="danger"> {{ $file_required_message }}</span>--}}
                                                                        {{--                                                                        @enderror--}}
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="assessment_report_smpc_file">Assessment
                                                                    Report SmPC</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file"
                                                                               name="assessment_report_smpc_file"
                                                                               id="assessment_report_smpc_file"
                                                                               class="custom-file-input" required>
                                                                        <label class="custom-file-label"
                                                                               for="assessment_report_smpc_file">Choose
                                                                            file</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="assessment_report_pils_file">Assessment
                                                                    Report PILs (Optional)</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file"
                                                                               name="assessment_report_pils_file"
                                                                               id="assessment_report_pils_file"
                                                                               class="custom-file-input">
                                                                        <label class="custom-file-label"
                                                                               for="assessment_report_pils_file">Choose
                                                                            file</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                    </div>

                                                    <input type="hidden" name="dossier_assignment_id"
                                                           value="{{$dossier_evaluation_details->id}}"/>
                                                    <div class="card-footer" style="float:right">
                                                        <button class="btn btn-success" role="button">Submit
                                                        </button>

                                                        </form>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- end of assessment Report --}}


                                            {{--this code below is for  qc report  --}}
                                            <div class="tab-pane fade" id="custom-tabs-three-qc" role="tabpanel"
                                                 aria-labelledby="custom-tabs-three-qc-tab">
                                                {{--start download qc report templates--}}
                                                <div class="card card-blue">
                                                    <div class="card-header">
                                                        <h3 class="card-title">QC Report Templates</h3>

                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div id="example1_wrapper"
                                                             class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                            <table id="example1"
                                                                   class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                                   role="grid" aria-describedby="example1_info">

                                                                <thead>
                                                                <tr role="row">
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Serial Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="5%">S.N
                                                                    </th>
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Reference Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="20%">Template Name
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="40%">Descripiton
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="20%">Template Type
                                                                    </th>
                                                                    <th rowspan="1" colspan="1" width="15%">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php($i=1)
                                                                @foreach($qc_report_templates as $template)
                                                                    <tr role="row" class="odd">
                                                                        <td>{{$i++}}</td>
                                                                        <td>{{$template->name}}</td>
                                                                        <td>{{$template->description}}</td>
                                                                        <td>{{$template->document_type}}</td>

                                                                        <td>


                                                                            <a href="{{route('view_html_template',['id'=>$template->id,'dossier_asg_id'=>$dossier_evaluation_details->id])}}"
                                                                               data-toggle="tooltip"
                                                                               class="btn btn-info btn-sm"
                                                                               data-placement="top"
                                                                               title="view the Template"><i
                                                                                    class="fas fa-eye "></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--end download qc report templates--}}

                                                {{--start upload qc reports--}}


                                                <div class="card card-outline card-success collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Submit QC</strong></h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;">

                                                        <form method="post" action="{{ route('upload_qc_report') }}"
                                                              enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="form-group">
                                                                <label for="qc_report_file">QC Report</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="qc_report_file"
                                                                               id="qc_report_file"
                                                                               class="custom-file-input">
                                                                        <label class="custom-file-label"
                                                                               for="qc_report_file">Choose file</label>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="form-group">
                                                                <label for="qc_report_attachments">Attachments</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="qc_report_attachments"
                                                                               id="qc_report_attachments"
                                                                               class="custom-file-input">
                                                                        <label class="custom-file-label"
                                                                               for="qc_report_attachments">Choose
                                                                            file</label>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                    </div>

                                                    <input type="hidden" name="dossier_assignment_id"
                                                           value="{{$dossier_evaluation_details->id}}"/>
                                                    <div class="card-footer" style="float:right">
                                                        <button class="btn btn-success" role="button">Submit
                                                        </button>

                                                        </form>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>

                                            </div>
                                            <!-- end of qc report -->

                                            {{---------------- START OF ISSUE QUERY------------}}

                                            <div class="tab-pane fade" id="custom-tabs-three-issue" role="tabpanel"
                                                 aria-labelledby="custom-tabs-three-issue-tab">
                                                <div class="card card-blue">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Query Issue Templates</h3>

                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div id="example1_wrapper"
                                                             class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                            <table id="example1"
                                                                   class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                                   role="grid" aria-describedby="example1_info">

                                                                <thead>
                                                                <tr role="row">
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Serial Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="5%">S.N
                                                                    </th>
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Reference Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="20%">Template Name
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="40%">Descripiton
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="20%">Template Type
                                                                    </th>
                                                                    <th rowspan="1" colspan="1" width="15%">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php($i=1)
                                                                @foreach($issue_query_report_templates as $template)
                                                                    <tr role="row" class="odd">
                                                                        <td>{{$i++}}</td>
                                                                        <td>{{$template->name}}</td>
                                                                        <td>{{$template->description}}</td>
                                                                        <td>{{$template->document_type}}</td>

                                                                        <td>

                                                                            <a href="{{route('view_html_template',['id'=>$template->id,'dossier_asg_id'=>$dossier_evaluation_details->id])}}"
                                                                               data-toggle="tooltip" class="btn btn-info btn-sm" data-placement="top" title="view the issue Query"><i class="fas fa-eye "></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>

                                                            </table>
                                                        </div>

                                                    </div>


                                                </div>

                                                {{---------- start list of uploaded documents -------------}}

                                                <div class="card card-outline card-success collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Issued Query History</strong></h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;">


                                                        <div id="example1_wrapper"
                                                             class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                                            <table id="example1"
                                                                   class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                                                   role="grid" aria-describedby="example1_info">

                                                                <thead>
                                                                <tr role="row">
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Serial Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="5%">S.N
                                                                    </th>
                                                                    <th class="sorting sorting_asc" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Reference Number: activate to sort column descending"
                                                                        aria-sort="ascending" width="20%"> Name
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="40%"> Description
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="example1" rowspan="1" colspan="1"
                                                                        aria-label="Title: activate to sort column ascending"
                                                                        width="20%"> The
                                                                    </th>
                                                                    <th rowspan="1" colspan="1" width="15%">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                {{--@php($i=1)
                                                                @foreach($issue_query_details as $query)
                                                                    <tr role="row" class="odd">
                                                                        <td>{{$i++}}</td>
                                                                        <td>{{$query->name}}</td>
                                                                        <td>{{$query->description}}</td>
                                                                        <td>{{$query->document_type}}</td>

                                                                        <td>

                                                                            <a href="{{route('view_html_template',['id'=>$template->id,'dossier_asg_id'=>$dossier_evaluation_details->id])}}"
                                                                               data-toggle="tooltip"
                                                                               class="btn btn-info btn-sm"
                                                                               data-placement="top"
                                                                               title="view the Template"><i
                                                                                    class="fas fa-eye "></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach--}}
                                                                </tbody>

                                                            </table>
                                                        </div>


                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                                <!-- /.card -->


                                                {{---------- end list of uploaded documents -------------}}

                                                {{---------- start upload_query_response -------------}}

                                                <div class="card card-outline card-success collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Submit Query Response</strong>
                                                        </h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;">
                                                        <form method="POST"
                                                              action="{{ route('upload_query_response') }}"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                <input type="text" class="form-control"
                                                                       name="description" id="description">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="query_response_file">Query Response</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="query_response_file"
                                                                               id="query_response_file"
                                                                               class="custom-file-input">
                                                                        <label class="custom-file-label"
                                                                               for="query_response_file">Choose
                                                                            file</label>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="dossier_assignment_id"
                                                                   value="{{$dossier_evaluation_details->id}}"/>
                                                            <div class="card-footer" style="float:right">
                                                                <button class="btn btn-success" role="button">
                                                                    Submit
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>

                                                {{-------- end upload_query_response -------}}
                                                {{---------------- END OF ISSUE QUERY------------}}

                                                <div class="tab-pane fade" id="custom-tabs-three-report" role="tabpanel"
                                                     aria-labelledby="custom-tabs-three-report-tab">
                                                    <!-- this is upload -->
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-three-settings"
                                                     role="tabpanel"
                                                     aria-labelledby="custom-tabs-three-settings-tab">
                                                    <!-- this is settings -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        function show_upload() {
            if (document.getElementById('assessment_report_submit').hidden == false) {
                document.getElementById('assessment_report_submit').hidden = true;

            } else {
                document.getElementById('assessment_report_submit').hidden = false;
            }
        }

        $(function () {
            bsCustomFileInput.init();
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(function () {
            bsCustomFileInput.init();
        });
        // function download_pdf(o){
        //     value=o.value;
        //     $.ajax({

        // type:'GET',

        // url:"{{ route('download_pdf') }}",

        // data:{value:value},

        // success:function(data){


        // }
        // });
        // }


    </script>
@endsection
