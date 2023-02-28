{{----------- start list of deferred assessment reports ------------}}
<?php
use App\Http\Controllers\UtilsController as Utils;

?>


{{--show this panel if , there is deferred application for this dossier assignment --}}

@if(count($submitted_deferred_assessment_reports_array) > 0)
    <div class="card card-outline card-warning collapsed-card">
        <div class="card-header">
            <h3 class="card-title"><strong>Deferred Application: Submitted and Commented Assessment Reports</strong>
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
        <div class="card-body">

            <div id="example3_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="example3" class="table table-bordered table-hover dataTable dtr-inline"
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
                            aria-sort="ascending" width="30%"> Report
                        </th>
                        <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Reference Number: activate to sort column descending"
                            aria-sort="ascending" width="30%"> Filename
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="20%"> Submitted at
                        </th>
                        <th rowspan="1" colspan="1" width="30%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)

                    @foreach($submitted_deferred_assessment_reports_array as $item)
                        @foreach($item['uploaded_document'] as $uploaded_document)
                            <tr role="row" class="odd">
                                <?php
                                /** @var TYPE_NAME $uploaded_document */
                                list($main_title, $report_sequence) = Utils::split_report_title($uploaded_document->name);

                                ?>

                                <td>{{$i++}}</td>
                                @if($report_sequence == '(Deferment_First)')
                                    <td>{{$main_title}} <span class="badge badge-warning">{{$report_sequence}}</span>
                                    </td>
                                @elseif($report_sequence == '(Deferment_Final)')
                                    <td>{{$main_title}} <span class="badge badge-primary">{{$report_sequence}}</span>
                                    </td>
                                @elseif($report_sequence == '(Deferment_Final_revised)')
                                    <td>{{$main_title}} <span class="badge badge-success">{{$report_sequence}}</span>
                                    </td>
                                @endif

                                <td>{{basename($uploaded_document->path)}}</td>
                                <td>{{$uploaded_document->updated_at}}</td>
                                <td>
                                    <a href="{{asset($uploaded_document->path)}}" type="button" target="_blank"
                                       title="Download the document" class="btn btn-success btn-sm"><i
                                                class="fas fa-download"></i></a>

                                    @if(! ($main_task->task_status=='Locked' | $main_task->task_status=='Decided'))

                                        {{-- Edit or reupload of the previous report should not be allowed if the next report has already been submitted.
                                        OR  if evaluation is finalized(submitted to supervisor)--}}

                                        @if(($item['assessment_report_name'] == 'Assessment Report Submission (Deferment_First)' and $item['assessment_received_date'] != null) or
                                            ($item['assessment_report_name'] == 'Assessment Report Submission (Deferment_Final)' and $item['assessment_received_date'] != null) or
                                            ($item['assessment_report_name'] == 'Assessment Report Submission (Deferment_Final_revised)' and $item['assessment_submitted_to_supervisor'] == 1)
                                            )

                                            <button
                                                    class="btn btn-secondary btn-sm"
                                                    title="Edit/Re-upload not Allowed" disabled>
                                                <i class="fas fa-edit"></i></button>
                                        @else
                                            <button
                                                    data-toggle="modal"
                                                    data-target="#editAssessmentReportModal"
                                                    class="btn btn-warning btn-sm"
                                                    title="Re-upload the document"
                                                    onclick="get_report_details(`{{$uploaded_document->name}}`, `{{$uploaded_document->id}}`, `{{$uploaded_document->path}}`)"
                                                    value="">
                                                <i class="fas fa-edit"></i></button>
                                        @endif

                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endforeach




                    @foreach($commented_deferred_evaluation_reports as $assess_rep_doc)
                        <tr role="row" class="odd">

                            <?php

                            /** @var TYPE_NAME $assess_rep_doc */
                            list($main_title, $report_sequence) = Utils::split_report_title($assess_rep_doc->name);
                            ?>

                            <td>{{$i++}}</td>

                            @if($report_sequence == '(Deferment_First)')
                                    <td><span class="text-danger">{{$main_title}}</span> <span class="badge badge-warning">{{$report_sequence}}</span></td>
                            @elseif($report_sequence == '(Deferment_Final)')
                                    <td><span class="text-danger">{{$main_title}}</span> <span class="badge badge-primary">{{$report_sequence}}</span></td>
                            @elseif($report_sequence == '(Deferment_Final_revised)')
                                    <td><span class="text-danger">{{$main_title}}</span> <span class="badge badge-success">{{$report_sequence}}</span></td>
                            @endif
                            {{--   <td>{{$assess_rep_doc->name}}</td>--}}
                            <td>{{basename($assess_rep_doc->path)}}</td>
                            <td>{{$assess_rep_doc->updated_at}}</td>

                            <td>

                                <a href="{{asset($assess_rep_doc->path)}}" type="button" target="_blank"
                                   title="View the document" class="btn btn-success btn-sm"><i
                                            class="fas fa-download "></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>


        </div>
        <!-- /.card-body -->
    </div>
@endif

{{----------- end list of assessment reports ------------}}
