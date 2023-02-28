{{-----------  START ASSESSMENT REPORT----------------------}}
<?php
use App\Http\Controllers\UtilsController as Utils;

?>

{{-----------start show Assessment Report templates----------------------}}
<div class="tab-pane fade" id="custom-tabs-three-assessment" role="tabpanel"
     aria-labelledby="custom-tabs-three-assessment-tab">

    @if($dossier_evaluation_details->application_type==1)
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Standard Evaluation</h3>
            </div>
            <form method="get" action="{{ route('download_pdf') }}">
                <div class="card-body">

                    <label>Choose Report Template</label>
                    <select class="form-control" name='std_type'>
                        @foreach ($standard as $std)
                            <option
                                    value="{{ $std->id }}">{{ $std->name }}
                            </option>

                        @endforeach
                    </select>

                    <input type="hidden" name="dossier_assignment_id"
                           value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                </div>
                <div class="card-footer" style="float:right">
                    <button class="btn btn-success"
                            role="button"><i class="fas fa-download"></i> Download
                    </button>
                </div>
            </form>

            <!-- /.card-body -->
        </div>
    @else
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Fast Track</h3>
            </div>
            <form method="get" action="{{ route('download_pdf') }}">
                <div class="card-body">
                    <label>Choose Report Template</label>
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
                            role="button"><i class="fas fa-download"></i> Download
                    </button>
                </div>
                <input type="hidden" name="dossier_assignment_id"
                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
            </form>
        </div>
        <!-- /.card-body -->
    @endif
    <br>
    {{-----------end show Assessment Report templates----------------------}}



    {{----------- start upload assessment report------------}}

    @can('assessor_roles')
        @if($current_user_id==$dossier_evaluation_details->assessor_id)

            <div class="card card-primary card-outline collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Submit Assessment Report</strong>
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





                {{--display upload section only for 3 upload times--}}
                @if($evaluation_document_progress->assessment_submitted < 3 or
                ($decision != null and $decision->decision_status=='Deferred' and
                $decision->locked == 1 and $evaluation_document_progress->deferred_assessment_submitted < 3))
                    @if($dossier_evaluation_details->application_type==1)  {{--upload form for standard mode --}}
                        <div class="card-body" style="display: none;">
                            <form method="post"
                                  action="{{ route('upload_assessment_report') }}"
                                  enctype="multipart/form-data" id="assessment_report_form"
                                  name="assessment_report_form">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="">Report Type</label></div>
                                    <select class="form-control" name="report_type" required>
                                        @if($evaluation_document_progress->assessment_submitted < 3)
                                            <option value="initial_report">Initial Assessment Report</option>
                                            <option value="deferment_report" disabled>Deferral Assessment Report</option>
                                        @elseif($evaluation_document_progress->deferred_assessment_submitted < 3)
                                            <option value="initial_report" disabled>Initial Assessment Report</option>
                                            <option value="deferment_report">Deferral Assessment Report</option>
                                        @else
                                            <option value="initial_report" disabled>Initial Assessment Report</option>
                                            <option value="deferment_report" disabled>Deferral Assessment Report</option>
                                        @endif

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="assessment_report_file">Assessment
                                        Report</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="assessment_report_file"
                                                   id="assessment_report_file"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('assessment_report_file_error','assessment_report_file','submit_assessment_report_id',['doc', 'docx'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="assessment_report_file">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <p class="text text-danger" id="assessment_report_file_error"></p>

                                </div>

                                <div class="form-group">
                                    <label for="assessment_report_smpc_file">Assessment
                                        Report SmPC</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   name="assessment_report_smpc_file"
                                                   id="assessment_report_smpc_file"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('assessment_report_smpc_file_error','assessment_report_smpc_file','submit_assessment_report_id',['doc', 'docx'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="assessment_report_smpc_file">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <p class="text text-danger" id="assessment_report_smpc_file_error"></p>
                                </div>
                                <div class="form-group">
                                    <label for="assessment_report_pils_file">Assessment
                                        Report PILs/Package Insert</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   name="assessment_report_pils_file"
                                                   id="assessment_report_pils_file"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('assessment_report_pils_file_error','assessment_report_pils_file','submit_assessment_report_id',['doc', 'docx'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="assessment_report_pils_file">Choose
                                                file</label>
                                        </div>
                                    </div>
                                    <p class="text text-danger" id="assessment_report_pils_file_error"></p>
                                </div>


                                <div class="form-group">
                                    <label for="report_desc">Description/Remark</label>
                                    <input type="text" name="report_desc"
                                           id="report_desc"
                                           class="form-control">
                                </div>

                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                @if(! ($main_task->task_status=='Locked' | $main_task->task_status=='Decision'))

                                    <div class="card-footer" style="float:right">


                                        {{--  disable submission if evaluation is LOCKED for any reason--}}
                                        @if($dossier_evaluation_details->locked == 1)
                                            <p class="text-danger">Submission Locked.</p>
                                            <button type="button" class="btn btn-secondary" title="Dossier Evaluation is Locked." disabled>
                                                Submit for Comments
                                            </button>
                                            {{--  disable submission if comment of previous report is NOT received yet--}}
                                        @elseif($comment_received == false)
                                            <p class="text-danger">Submission Locked.</p>
                                            <button type="button" class="btn btn-secondary" title="Comment of previous report is NOT sent from supervisor." disabled>
                                                Submit for Comments
                                            </button>
                                        @else
                                            <button class="btn btn-success" id="submit_assessment_report_id" role="button">
                                                Submit for Comments
                                            </button>

                                        @endif


                                    </div>
                                @endif

                            </form>
                        </div> <!-- /.card-body -->
                    @else    {{--upload form for fast track --}}
                    <div class="card-body" style="display: none;">
                        <form method="post"
                              action="{{ route('upload_assessment_report') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <div class="form-group">
                                    <label for="">Report Type</label></div>
                                <select class="form-control" name="report_type" required>
                                    @if($evaluation_document_progress->assessment_submitted < 3)
                                        <option value="initial_report">Initial Assessment Report</option>
                                        <option value="deferment_report" disabled>Deferral Assessment Report</option>
                                    @elseif($evaluation_document_progress->deferred_assessment_submitted < 3)
                                        <option value="initial_report" disabled>Initial Assessment Report</option>
                                        <option value="deferment_report">Deferral Assessment Report</option>
                                    @else
                                        <option value="initial_report" disabled>Initial Assessment Report</option>
                                        <option value="deferment_report" disabled>Deferral Assessment Report</option>
                                    @endif

                                </select>

                            </div>

                            <div class="form-group">
                                <label for="assessment_report_file">Assessment Report</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="assessment_report_file"
                                               id="assessment_report_file"
                                               class="custom-file-input"
                                               onchange="filevalidiator('assessment_report_fs_error','assessment_report_file','fs_submit_btn_id',['doc', 'docx'])"
                                               required>
                                        <label class="custom-file-label"
                                               for="assessment_report_file">Choose file</label>
                                    </div>

                                </div>
                                <p class="text text-danger" id="assessment_report_fs_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="report_desc">Description/Remark</label>
                                <input type="text" name="report_desc"
                                       id="report_desc"
                                       class="form-control" required>
                            </div>
                            <input type="hidden" name="dossier_assignment_id"
                                   value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                            <div class="card-footer" style="float:right">



                                {{--  disable submission if evaluation is LOCKED for any reason--}}
                                @if($dossier_evaluation_details->locked == 1)
                                    <p class="text-danger">Submission Locked.</p>
                                    <button type="button" class="btn btn-secondary" title="Dossier Evaluation is Locked." disabled>
                                        Submit for Comments
                                    </button>
                                    {{--  disable submission if comment of previous report is NOT received yet--}}
                                @elseif($comment_received == false)
                                    <p class="text-danger">Submission Locked.</p>
                                    <button type="button" class="btn btn-secondary" title="Comment of previous report is NOT sent from supervisor." disabled>
                                        Submit for Comments
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success" id="fs_submit_btn_id" role="button">
                                        Submit for Comments
                                    </button>

                                @endif


                            </div>

                        </form>
                    </div><!-- /.card-body -->
                    @endif

                @else  {{-- evaluation_document_progress->assessment_submitted > 2--}}
                <div class="card-body">

                        <div class="alert alert-default-danger">

                            <h5><i class="icon fas fa-exclamation-circle"></i>Assessment Report Submission closed.</h5>
                            Each of the following Assessment Reports have been submitted three times (Initial, Final,
                            Final_revised).
                            <ul>
                                @if($evaluation_document_progress->assessment_submitted > 2 )
                                    @if($dossier_evaluation_details->application_type==1)
                                        <li>Standard Evaluation</li>
                                    @elseif($dossier_evaluation_details->application_type==2)
                                        <li>Fast Track Evaluation</li>
                                    @endif
                                @endif
                                @if($evaluation_document_progress->deferred_assessment_submitted > 2)
                                    <li>Deferral Evaluation</li>
                                @endif

                            </ul>
                        </div>
                    </div>
                @endif

            </div>

            {{--@endif--}}
            @if(!($main_task->task_status == 'Locked' | $main_task->task_status =='Decided'))
                <form method="post"
                      action="{{ route('submit_to_supervisor') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-footer justify-content-between">

                        @if($evaluation_document_progress->assessment_submitted_to_supervisor == 0)
                            <button type="button" class="btn btn-danger" title="Finalizing Entire Evaluation Process"
                                    data-toggle="modal"
                                    data-target="#submit_to_supervisor"
                                    onclick="submit_to_supervisor_js(this,[{{$evaluation_document_progress}},{{$dossier_evaluation_details->application_type}}])"
                                    value="{{$dossier_evaluation_details->dossier_ass_id}}"><i class="fas fa-check"></i>
                                Finalize Evaluation Process
                            </button>

                            @elseif($evaluation_document_progress->deferred_assessment_submitted_to_supervisor == 0 and ($decision != null and $decision->locked == 1))
                                <button type="button" class="btn btn-danger " title="Finalizing Entire Evaluation Process"
                                        data-toggle="modal"
                                        data-target="#submit_to_supervisor"
                                        onclick="submit_to_supervisor_js(this,[{{$evaluation_document_progress}},{{$dossier_evaluation_details->application_type}}])"
                                        value="{{$dossier_evaluation_details->dossier_ass_id}}"><i class="fas fa-check"></i>
                                    Finalize Evaluation Process
                                </button>

                        @endif

                    </div>
                </form>
            @endif
        @endif
    @endcan

    {{-- end of upload assessment Report --}}


    {{----------- start list of assessment reports ------------}}


    <div class="card card-outline card-success collapsed-card">
        <div class="card-header">
            <h3 class="card-title"><strong>Submitted and Commented Assessment Reports</strong></h3>

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

            <div id="example10_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="example10" class="table table-bordered table-hover dataTable dtr-inline"
                       role="grid" aria-describedby="example10_info">

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
                            width="20%"> Submitted On
                        </th>
                        <th tabindex="0" rowspan="1" colspan="1" width="30%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)

                    @foreach($submitted_assessment_reports_array as $item)
                        @foreach($item['uploaded_document'] as $uploaded_document)
                            <tr role="row" class="odd">
                                <?php
                                /** @var TYPE_NAME $uploaded_document */
                                list($main_title, $report_sequence) = Utils::split_report_title($uploaded_document->name);

                                ?>

                                <td>{{$i++}}</td>
                                    @if($report_sequence == '(First)')
                                    <td>{{$main_title}} <span class="badge badge-warning">{{$report_sequence}}</span></td>
                                        @elseif($report_sequence == '(Final)')
                                            <td>{{$main_title}} <span class="badge badge-primary">{{$report_sequence}}</span></td>
                                    @elseif($report_sequence == '(Final_revised)')
                                        <td>{{$main_title}} <span class="badge badge-success">{{$report_sequence}}</span></td>
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

                                        @if(($item['assessment_report_name'] == 'Assessment Report Submission (First)' and $item['assessment_received_date'] != null) or
                                            ($item['assessment_report_name'] == 'Assessment Report Submission (Final)' and $item['assessment_received_date'] != null) or
                                            ($item['assessment_report_name'] == 'Assessment Report Submission (Final_revised)' and $item['assessment_submitted_to_supervisor'] == 1)
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

                    @foreach($commented_assessment_report_documents as $assess_rep_doc)
                        <tr role="row">
                            <td>{{$i++}}</td>


                            <?php
                            /** @var TYPE_NAME $assess_rep_doc */
                            list($main_title, $report_sequence) = Utils::split_report_title($assess_rep_doc->name);
                            ?>
                            @if($report_sequence == '(First)')
                                <td><span class="text-danger"> {{$main_title}} </span> <span class="badge badge-warning">{{$report_sequence}}</span></td>
                            @elseif($report_sequence == '(Final)')
                                <td><span class="text-danger"> {{$main_title}} </span>  <span class="badge badge-primary">{{$report_sequence}}</span></td>
                            @elseif($report_sequence == '(Final_revised)')
                                <td><span class="text-danger"> {{$main_title}} </span>  <span class="badge badge-success">{{$report_sequence}}</span></td>
                            @endif
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

    {{----------- end list of assessment reports ------------}}

    {{--------------------START DEFERRED EVALUATION REPORTS LIST  ------------------}}

        @if(isset($decision))
            @if($decision->sealed_document_id!=null and $decision->decision_status=="Deferred")
                @include('dossier_evaluation.tab_assessment_report_deferred')
            @endif
        @endif
    {{--------------------END DEFERRED EVALUATIONREPORTS LIST  ------------------}}

</div>

{{--  MODAL: start edit assessment report  --}}

<div class="modal fade" id="editAssessmentReportModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="edit_assessment_report" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">

        <form action="{{ route('edit_assessment_report') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Assessment Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="hidden_report_name">Report Name</label>
                        <input type="text" name="hidden_report_name" value="" class="form-control"
                               id="hidden_report_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="assess_report_file">File</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="assess_report_file"
                                       id="assess_report_file" value=""
                                       class="custom-file-input">
                                <label class="custom-file-label"
                                       for="assess_report_file">Choose File</label>
                            </div>

                        </div>
                    </div>

                </div>
                <input type="hidden" id="hidden_path" name="hidden_path"
                       value=""/>
                <input type="hidden" id="hidden_document_id" name="hidden_document_id"
                       value=""/>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"> Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{--MODAL: end edit assessment report--}}


{{--------------------END ASSESSEMENT REPORT ------------------}}

{{-- Modal for Submit to supervisor --}}
<div class="modal fade" id="submit_to_supervisor" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="submit_to_supervisor" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">

        <form action="{{ route('submit_to_supervisor') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Completed Evaluation to Supervisor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @include('dossier_evaluation.progress_status')
                </div>
                <input type="hidden" name='dossier_assignment_id'
                       value="{{ $evaluation_document_progress->dossier_assignment_id}}">
                <div class="modal-footer justify-content-between">
                    @if(($evaluation_document_progress->assessment_submitted_to_supervisor!=1 or
                    $evaluation_document_progress->deferred_assessment_submitted_to_supervisor!=1) and $comment_received)
                        <button type="submit" id="submit_to_supvisor_btn" name="submit_to_supvisor_btn"
                                class="btn btn-danger">Finalize Evaluation Process
                        </button>
                    @else
                        <button type="submit" title="Assessment Already Submitted" class="btn btn-secondary" disabled>
                            Finalize Evaluation Process
                        </button>


                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
{{-- end of Modal for Submit to Supervisor --}}


{{-- MODAL TO POP-UP CONFIRM DIALOG--}}

{{--<div class="modal fade" id="modal_confirm_submission" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="modal_confirm_submission" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
<!--    ?php
//    if (isset($assess_rep_doc)) {
//        $assess_report_doc_id = $assess_rep_doc->id;
//    } else {
//        $assess_report_doc_id = "";
//    }
//
//    ?> -->
    <form action="{{ route('upload_assessment_report') }}" method="POST">
            @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title1"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body1">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>--}}


<script>
    function submit_to_supervisor_js(o, evaluation_progress) {

        const progress = evaluation_progress[0];
        const mode = evaluation_progress[1];
        if (mode == 1) {
            if (progress.QOS_is_done == 1 &&
                progress.issue_query_is_done == 1 &&
                progress.qc_sample_is_done == 1 &&
                progress.assessment_submitted == 3) {
                document.getElementById('submit_to_supvisor_btn').disabled = false
            } else {
                document.getElementById('submit_to_supvisor_btn').disabled = true
            }
        }
        if (mode == 2) {
            if (
                progress.issue_query_is_done == 1 &&
                progress.assessment_submitted == 3) {
                document.getElementById('submit_to_supvisor_btn').disabled = false
            } else {
                document.getElementById('submit_to_supvisor_btn').disabled = true
            }
        }


    }

    function get_report_details(uploaded_document_name, uploaded_document_id, uploaded_document_path) {

        document.getElementById('hidden_report_name').value = uploaded_document_name;
        document.getElementById('hidden_document_id').value = uploaded_document_id;
        document.getElementById('hidden_path').value = uploaded_document_path;

    }


    /* function validate_report_submission(){

         alert('validate')

        /!* var constraints = {
             assessment_report_file: {
                 presence: true
         },

     }
 *!/


         var validator = new FormValidator('assessment_report_form', [
             /!*{
             name: 'req',
             display: 'required',
             rules: 'required'
         }, {
             name: 'alphanumeric',
             rules: 'alpha_numeric'
         }, {
             name: 'password',
             rules: 'required'
         },*!/ {
             name: 'assessment_report_file',
             display: 'Report File',
             rules: 'required'
         }
             /!*{
             name: 'email',
             rules: 'valid_email',
             depends: function() {
                 return Math.random() > .5;
             }
         }, {
             name: 'minlength',
             display: 'min length',
             rules: 'min_length[8]'
         }*!/
         ], function(errors, event) {
             if (errors.length > 0) {
                 // Show the errors
                 document.getElementById('assessment_report_file_span').innerText = 'This field is required.'
             }
         }
         );


         // if (document.getElementById('assessment_report_file').value = "")
         //     document.getElementById('assessment_report_file_span').innerText = 'This field is required.'


     }*/
    /*function confirm_report_submission(o, evaluation_progress_status) {

        alert(evaluation_progress_status.deferred_assessment_submitted);

        // step1/3 first check if all fields are filled using validation




        // step 2/3 - if submitting for the 1st or, 2nd times, ask to confirm (edit is possible afterwards)
        if(evaluation_progress_status.assessment_submitted < 2  ||  evaluation_progress_status.deferred_assessment_submitted < 2){

            document.getElementById('modal-title1').innerText = 'Submission for Comments';
            document.getElementById('modal-body1').innerText = 'Submit the report ?';
            $('#modal_confirm_submission').modal('show');
        }
        //step 2/3 - if submitting for 3rd time alert - that editing is not possible
        else if(evaluation_progress_status.assessment_submitted == 2  ||  evaluation_progress_status.deferred_assessment_submitted == 2) {

            document.getElementById('modal-title1').innerText = 'Final Revised Submission';
            document.getElementById('modal-body1').innerText = 'Further Edit/Re-upload is not possible. Submit ?';
            $('#modal_confirm_submission').modal('show');
        }
    }*/
</script>
