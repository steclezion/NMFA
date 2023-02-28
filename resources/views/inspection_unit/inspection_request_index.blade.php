@extends('layouts.app')

@section('content')

<?php
use Carbon\Carbon;
?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong> Sample Test Requests</strong>
                            </h3>

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
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="21%"> Sample Testing Status
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Description
                                        </th>

                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="13%"> Request Received On
                                        </th>

                                        @can(('inspection_roles'))
                                            <th class="sorting" tabindex="0"
                                                aria-controls="example1" rowspan="1" colspan="1"
                                                aria-label="Title: activate to sort column ascending"
                                                width="13%" id="received">Results Received On
                                            </th>
                                        @endcan
                                        @can(('qc_roles'))
                                            <th class="sorting" tabindex="0"
                                                aria-controls="example1" rowspan="1" colspan="1"
                                                aria-label="Title: activate to sort column ascending"
                                                width="13%" id="received">Results Sent On
                                            </th>
                                        @endcan
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Deadline
                                        </th>
                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($qc_documents as $qc_doc)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>
                                                @if($qc_doc->status=='Response Received')
                                                    <span class="badge bg-success">{{$qc_doc->status}}</span>
                                                @elseif($qc_doc->status=='Locked')
                                                    <span class="badge bg-danger">{{$qc_doc->status}}</span>
                                                @else
                                                    <span class="badge bg-warning">{{$qc_doc->status}}</span>
                                                @endif
                                            </td>

                                            @can('inspection_roles')
                                                <td>{{$qc_doc->request_subject}}</td>
                                                <td>{{$qc_doc->inspection_sent_date}}</td>
                                                {{--request sent to inspection date (inspection received request on this date)  --}}
                                            @endcan

                                            @can('qc_roles')

                                                {{--request sent by inspection but result from QC is NOT sent yet--}}
                                                @if($qc_doc->to_qc_sent_date!=null and $qc_doc->qc_received_date == null)
                                                    <td>{{$qc_doc->to_qc_lab_subject}}</td>
                                                    <td>{{$qc_doc->to_qc_sent_date}}</td>  {{--null--}}

                                                @else
                                                    {{--Result from QC is sent --}}
                                                    <td>{{$qc_doc->response_description}}</td>
                                                    <td>{{$qc_doc->to_qc_sent_date}}</td>
                                                @endif
                                            @endcan

                                            <td>{{$qc_doc->qc_received_date}}</td>

                                            <td>{{$qc_doc->qc_deadline}}</td>

                                            <td>
                                            @if($qc_doc->task_status =='Locked' | $qc_doc->task_status == 'Decided')


                                            @else
                                                @if ($qc_doc->qc_received_date==null)
                                                    <!--
                                                    if the user is from inspection unit he/she is given privilage to extend or send request to qc -->
                                                        @can('inspection_roles')
                                                            @if($qc_doc->to_qc_sent_date == null)


                                                                <button type="button" class="btn btn-success btn-sm"
                                                                        data-toggle="modal"
                                                                        title="Send Sample Test Request to QC"
                                                                        data-target="#modalsendrequest"
                                                                        onclick="send_request_modal_modal(this,{{$qc_doc->dossier_assign_id}})"
                                                                        value='{{ $qc_doc->id }}'>
                                                                    <i class="fas fa-upload "></i>
                                                                </button>

                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                        data-toggle="modal"
                                                                        title="Sample Test Request to QC not Sent"
                                                                        disabled>
                                                                    <i class="fas fa-clock "></i>
                                                                </button>
                                                            @elseif($qc_doc->qc_received_date==null)
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                        data-toggle="modal"
                                                                        title="Send Sample Test Request to QC "
                                                                        data-target="#modalsendrequest"
                                                                        onclick="send_request_modal_modal(this,{{$qc_doc->dossier_assign_id}})"
                                                                        value='{{ $qc_doc->id }}'>
                                                                    <i class="fas fa-upload "></i>
                                                                </button>

                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                        data-toggle="modal"
                                                                        title="Extend Deadline for QC"
                                                                        data-target="#modalextend"
                                                                        onclick="deadline_modal(this,{{$qc_doc->dossier_assign_id}})"
                                                                        value='{{ $qc_doc->id }}'>
                                                                    <i class="fas fa-clock "></i>
                                                                </button>

                                                            @else
                                                                <button class="btn btn-secondary btn-sm"
                                                                        title="Sample test request already sent to QC"
                                                                        disabled>
                                                                    <i class="fas fa-upload"></i></button>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                        data-toggle="modal" title="Extend Deadline."
                                                                        data-target="#modalextend"
                                                                        onclick="deadline_modal(this,{{$qc_doc->dossier_assign_id}})"
                                                                        value='{{ $qc_doc->id }}'>
                                                                    <i class="fas fa-clock "></i>
                                                                </button>
                                                            @endif
                                                        @endcan
                                                    @else
                                                        @can('inspection_roles')
                                                            <button class="btn btn-secondary btn-sm"
                                                                    title="Sample request already sent to QC" disabled>
                                                                <i class="fas fa-upload"></i>
                                                            </button>


                                                            <button type="button" class="btn btn-secondary btn-sm"
                                                                    title="Sample Response Received from QC"
                                                                    disabled>
                                                                <i class="fas fa-clock "></i>
                                                            </button>
                                                        @endcan
                                                    @endif

                                                <!--                                               if the user is from qc control he/she has the previlage to upload or reupload sample test result -->
                                                    @can('qc_roles')

                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                title="Request for Deadline Extension"
                                                                data-toggle="modal" data-target="#qc_dedline_extension"
                                                                data-tooltip="tooltip"
                                                                data-placement="bottom"
                                                                onclick="qc_extend_deadline({{$qc_doc->id}})"
                                                                value="">
                                                            <i class='fas fa-clock'></i>
                                                        </button>
                                                        @if ($qc_doc->qc_received_date==null)

                                                        <!-- check if status or deadline is reached if yes lock the upload button -->
                                                            @if($qc_doc->status=='Locked')
                                                                <button
                                                                        class="btn btn-secondary btn-sm"
                                                                        title="Upload Sample Report"
                                                                        disabled>
                                                                    <i class="fas fa-upload"></i></button>
                                                            @else
                                                                <button
                                                                        data-toggle="modal"
                                                                        data-target="#uploadQCResponseModal"
                                                                        data-tooltip="tooltip"
                                                                        class="btn btn-success btn-sm"
                                                                        title="Upload QC lab report"
                                                                        onclick="upload_qc_report(this)"
                                                                        value="{{ $qc_doc->id }}">
                                                                    <i class="fas fa-upload"></i></button>
                                                            @endif
                                                            <button
                                                                    class="btn btn-secondary btn-sm"
                                                                    title="QC lab report not uploaded."
                                                                    disabled>
                                                                <i class="fas fa-edit"></i></button>

                                                        @else  {{--qc report has been uploaded already--}}

                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                                              title="QC lab report has been uploaded.">
                                                        <button
                                                                class="btn btn-secondary btn-sm"
                                                                style="pointer-events: none;"
                                                              disabled>
                                                            <i class="fas fa-upload"></i></button>
                                                        </span>


                                                        <?php
                                                        /** @var TYPE_NAME $qc_doc */
                                                        $now = Carbon::now();
                                                        $received_date = Carbon::create($qc_doc->qc_received_date);
                                                        $diffInHours = $received_date->diffInHours($now, false);
                                                        $diffInHoursMins = $received_date->diff($now, false)->format('%hhr:%Im');
                                                        ?>
                                                        @if($diffInHours >= 0 and $diffInHours < 24)
                                                            <button
                                                                    data-toggle="modal"
                                                                    data-target="#editQCResponseModal"
                                                                    data-tooltip="tooltip"
                                                                    data-placement="bottom"
                                                                    class="btn btn-warning btn-sm"
                                                                    title="Re-upload report. ({{$diffInHoursMins}} Elapsed. Editing will be disabled in 24 hrs. since last report submission)"
                                                                    onclick="edit_qc_report(this, {{ $qc_doc }})"
                                                                    value="{{ $qc_doc->id }}">
                                                                <i class="fas fa-edit"></i></button>


                                                        @else
                                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                                                  title="Editing Disabled. 24 hrs. Elapsed since last report submission">
                                                            <button
                                                                    class="btn btn-secondary btn-sm"
                                                                    style="pointer-events: none;"
                                                                    disabled>
                                                                <i class="fas fa-edit"></i></button>
                                                            </span>
                                                        @endif

                                                        @endif
                                                    @endcan

                                                @endif


                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#modal_qc_details" onclick="qc_detail(this)"
                                                        value='{{ $qc_doc->id }}'>
                                                    <i class="fas fa-list "></i>
                                                </button>


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div> {{-- end div: example1_wrapper--}}

                            {{--  Modal for Qc details  --}}
                            <div class="modal fade" id="modal_qc_details" data-backdrop="static" tabindex="-1"
                                 role="dialog"
                                 aria-labelledby="modalextend" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{{ route('update_deadline') }}"
                                          method="POST">  {{--todo check with mera if route is ok--}}
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Sample Test Request and Response Details</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                {{------------------------Start Assessor-to-inspection Details-------------------}}
                                                <div class="card card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Request: Assessor to
                                                                Inspection</strong>
                                                        </h3>
                                                    </div>
                                                    <table class="table table-condensed">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-muted" width="23%">From</td>
                                                            <td class="text-left"><span id="send_from_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">To</td>
                                                            <td class="text-left"><span id="send_to_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Sent Date</td>
                                                            <td class="text-left"><span id="send_date_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Sent Document</td>
                                                            <td class="text-left"><a id="qc_sent_document_view" href=""
                                                                                     target="_blank"
                                                                                     data-toggle="tooltip"
                                                                                     class="btn btn-info btn-sm"
                                                                                     data-placement="top"
                                                                                     title="View the file"><i
                                                                            class="fas fa-book-open"></i> View</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{------------------------END Assessor-to-inspection Details-------------------}}


                                                {{------------------------Start Inspection to QC Details-------------------}}
                                                <div class="card card-info" id="inspection_qc_view_id" hidden>
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Request: Inspection to
                                                                QC</strong>
                                                        </h3>
                                                    </div>

                                                    <table class="table table-condensed">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-muted" width="23%">From</td>
                                                            <td class="text-left"><span id="inspection_from_id"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">To</td>
                                                            <td class="text-left"><span id="qc_to_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Sent Date</td>
                                                            <td class="text-left"><span
                                                                        id="inspection_send_date_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Deadline</td>
                                                            <td class="text-left"><span id="send_deadline_id"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Sent Document</td>
                                                            <td class="text-left"><a id="inspection_sent_document_view"
                                                                                     href="" target="_blank"
                                                                                     data-toggle="tooltip"
                                                                                     class="btn btn-info btn-sm"
                                                                                     data-placement="top"
                                                                                     title="View the file"><i
                                                                            class="fas fa-book-open"></i> View</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{------------------------END Inspection to QC Details-------------------}}


                                                {{------------------------START Qc Response Details-------------------}}
                                                <div class="card card-info" id="received_view_id" hidden>
                                                    <div class="card-header">
                                                        <h3 class="card-title"><strong>Response: QC Response
                                                                Details</strong>
                                                        </h3>
                                                    </div>
                                                    <table class="table table-condensed">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-muted" width="23%">From</td>
                                                            <td class="text-left"><span id="receive_from_id"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">To</td>
                                                            <td class="text-left"><span id="receive_to_id"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Received Date</td>
                                                            <td class="text-left"><span id="receive_date_id"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Received Document</td>
                                                            <td class="text-left"><a id="qc_receive_document_view"
                                                                                     href="" target="_blank"
                                                                                     data-toggle="tooltip"
                                                                                     class="btn btn-info btn-sm"
                                                                                     data-placement="top"
                                                                                     title="View the file"><i
                                                                            class="fas fa-book-open"></i> View</a>
                                                            </td>
                                                        </tr>
                                                        <tr id="attachment_row">
                                                            <td class="text-muted">Attached Document</td>
                                                            <td class="text-left"><a id="qc_attached_document_view"
                                                                                     href="" target="_blank"
                                                                                     data-toggle="tooltip"
                                                                                     class="btn btn-info btn-sm"
                                                                                     data-placement="top"
                                                                                     title="Open the file"><i
                                                                            class="fas fa-paperclip"></i> Download</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{------------------------END QC Response Details-------------------}}


                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Back
                                                </button>
                                                <button type="button" class="btn btn-success" data-dismiss="modal">Ok
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{--  end of Modal for qc details  --}}
                            {{--  Modal for Extend deadline  --}}
                            <div class="modal fade" id="modalextend" data-backdrop="static" tabindex="-1" role="dialog"
                                 aria-labelledby="modalextend" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">

                                    <form action="{{ route('update_deadline') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Extend Deadline</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="text" name='type' value='qc' hidden/>
                                                    <input type="hidden" name="hidden_dossier_asg_id"
                                                           id="hidden_dossier_asg_id" value=""/>

                                                    <input type="text" name='qc_id' id='qc_id' value="" hidden/>


                                                </div>
                                                <div class="form-group">
                                                    <label> Reason for Extension</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="extend_reason">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>New Deadline</label>
                                                    <div class="input-group date" id="reservationdate"
                                                         data-target-input="nearest">
                                                        <input type="date" class="form-control" name="new_deadline">

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">Extend</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{--  end of Modal for extend deadline  --}}

                            {{-- MODAL: start upload qc response  --}}
                            <div class="modal fade" id="uploadQCResponseModal" data-backdrop="static" tabindex="-1"
                                 role="dialog"
                                 aria-labelledby="uploadQCResponseModal" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Sample Testing Report</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">


                                            <form method="post" action="{{ route('upload_qc_report') }}"
                                                  enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label> Description</label>
                                                    <input type="text" class="form-control"
                                                           name="description" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qc_report_file">QC Report</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="qc_report_file"
                                                                   id="qc_report_file"
                                                                   class="custom-file-input"
                                                                   onchange="filevalidiator('qc_report_file_error','qc_report_file','upload_qc_document',['pdf'])"
                                                                   required>
                                                            <label class="custom-file-label"
                                                                   for="qc_report_file">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <!-- <span class="text text-danger" id="uploaded_qc_id"></span> -->
                                                    <p id="qc_report_file_error"
                                                       style="display:none; color:#FF0000;"></p>

                                                </div>

                                                <div class="form-group">
                                                    <label for="qc_report_attachments">Attachments (ZIP file
                                                        format)</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="qc_report_attachments"
                                                                   id="qc_report_attachments"
                                                                   multiple="multiple"
                                                                   onchange="filevalidiator('uploaded_qc_zip_id','qc_report_attachments','upload_qc_document',['zip','rar','tar'])">
                                                            <label class="custom-file-label"
                                                                   for="qc_report_attachments">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <p id="uploaded_qc_zip_id" style="display:none; color:#FF0000;"></p>
                                                    <span class="text text-danger" id=""></span>
                                                </div>

                                                <input type="hidden" name="hidden_qc_id" id="hidden_qc_id" value=""/>
                                                <div class="card-footer" style="float:right">
                                                    <button class="btn btn-success" id="upload_qc_document"
                                                            role="button">Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div> {{--modal-body--}}
                                    </div>
                                </div>
                            </div>
                            {{-- MODAL: end upload of qc response  --}}

                            {{-- MODAL: start edit QC response --}}
                            <div class="modal fade" id="editQCResponseModal" data-backdrop="static" tabindex="-1"
                                 role="dialog"
                                 aria-labelledby="editQCResponseModal" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">

                                    <form name="edit_qc_response" method="POST"
                                          action="{{route('edit_qc_response')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit QC Lab Analysis Report</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="qc_subject1">Response for:</label>
                                                    <input name="qc_subject1" type="text" class="form-control"
                                                           id="qc_subject1" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qc_description1">Description</label>
                                                    <input name="qc_description1" type="text" class="form-control"
                                                           id="qc_description1">
                                                </div>

                                                <div class="form-group">
                                                    <label for="qc_report_file1">QC Report</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="qc_report_file1"
                                                                   id="qc_report_file1"
                                                                   class="custom-file-input"
                                                                   onchange="filevalidiator('edit_uploaded_qc_id','qc_report_file1', 'edit_qc_document_id', ['pdf'])"
                                                                   required>
                                                            <label class="custom-file-label"
                                                                   for="qc_report_file1">Choose file</label>

                                                        </div>
                                                    </div>
                                                    <p style="display:none; color:#FF0000;"
                                                       id="edit_uploaded_qc_id"></p>
                                                </div>


                                                <div class="form-group">
                                                    <label for="qc_report_attachments1">Attachments (ZIP/RAR)</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="qc_report_attachments1"
                                                                   id="qc_report_attachments1"
                                                                   class="custom-file-input" multiple="multiple"
                                                                   onchange="filevalidiator('edit_uploaded_qc_zip_id','qc_report_attachments1', 'edit_qc_document_id', ['zip', 'rar'])">
                                                            <label class="custom-file-label"
                                                                   for="qc_report_attachments1">Choose file</label>
                                                        </div>

                                                    </div>
                                                    <p style="display:none; color:#FF0000;"
                                                       id="edit_uploaded_qc_zip_id"></p>
                                                </div>

                                                <input type="hidden" name="hidden_qc_id1" id="hidden_qc_id1" value=""/>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn bg-white" data-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" id="edit_qc_document_id"
                                                            class="btn btn-success">Edit
                                                    </button>
                                                </div>

                                            </div> {{--modal-body--}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- MODAL: end edit response  --}}



                            {{-- MODAL: start send qc request  --}}
                            <div class="modal fade" id="modalsendrequest" data-backdrop="static" tabindex="-1"
                                 role="dialog"
                                 aria-labelledby="uploadQCResponseModal" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Sample Testing Report</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">


                                            <form method="post" action="{{ route('send_to_qc_from_inspection') }}"
                                                  enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label>To:</label>
                                                    <select class="form-control" name="to_user" required>
                                                        <option></option>
                                                        @foreach ($users as $user)
                                                            <option value='{{ $user->id }}'>{{ $user->first_name }} {{ $user->middle_name }}</option>

                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Subject:</label>
                                                    <input class="form-control" placeholder="Enter Subject Here"
                                                           name='subject' required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deadline :</label>
                                                    <div class="input-group date" id="reservationdate"
                                                         data-target-input="nearest">
                                                        <input type="date" class="form-control" name="deadline"
                                                               required>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="qc_report_file">Request Form</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="sample_request_file"
                                                                   id="sample_request_file"
                                                                   class="custom-file-input"
                                                                   onchange="filevalidiator('sample_request_file_error','sample_request_file','request_qc_document',['pdf'])"
                                                                   required>
                                                            <label class="custom-file-label"
                                                                   for="qc_report_file">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <!-- <span class="text text-danger" id="uploaded_qc_id"></span> -->
                                                    <p id="sample_request_file_error"
                                                       style="display:none; color:#FF0000;"></p>

                                                </div>


                                                <input type="hidden" name="request_qc_id" id="request_qc_id" value=""/>
                                                <input type="hidden" name="request_hidden_dossier_asg_id"
                                                       id="request_hidden_dossier_asg_id" value=""/>
                                                <div class="card-footer" style="float:right">
                                                    <button class="btn btn-success" id="request_qc_document"
                                                            role="button">Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div> {{--modal-body--}}
                                    </div>
                                </div>
                            </div>
                            {{-- MODAL: end of send request  --}}

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                {{--end list of QC reports--}}

                <!-- Yemane Extension   -->

                    {{-- MODAL for deadline extension Request--}}

                    <div class="modal fade" id="qc_dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="deleteRecordModal" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">

                            <form action="{{ route('sample_deadline_extension')}}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request For Sample Testing Deadline Extension</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Reason For Extension Request</label><input type="text"
                                                                                          class="form-control"
                                                                                          name='extension_reason'><br>
                                        <label>Required Deadline</label><input type="date" class="form-control"
                                                                               name='extended_deadline'><br>


                                    </div>

                                    <input type="hidden" id="extension_qc_id" name="extension_qc_id"/>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Send
                                            Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                {{-- End of modal deadline extension Request--}}

                <!-- End Yemane Extension -->
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(function () {
            bsCustomFileInput.init();
        });

        function deadline_modal(o, dossier_assing_id) {
            document.getElementById('qc_id').value = o.value;
            document.getElementById('hidden_dossier_asg_id').value = dossier_assing_id;
        }

        function send_request_modal_modal(o, dossier_assing_id) {
            document.getElementById('request_qc_id').value = o.value;
            document.getElementById('request_hidden_dossier_asg_id').value = dossier_assing_id;
        }

        function qc_detail(o) {

            let qc_id = o.value
            var server_ip = document.getElementById('server_ip').value;
            document.getElementById('received_view_id').hidden = true;
            document.getElementById('inspection_qc_view_id').hidden = true;

            $.ajax({

                type: 'GET',

                url: "{{ route('retrieve_details') }}",

                data: {id: qc_id, typ: 'qc'},

                success: function (data) {
                    //for sending part
                    document.getElementById('send_from_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_middle_name'];
                    document.getElementById('send_to_id').innerText = data.data['inspection_first_name'] + ' ' + data.data['inspection_middle_name'];
                    document.getElementById('send_date_id').innerText = data.data['inspection_sent_date'];
                    document.getElementById('send_deadline_id').innerText = data.data['qc_deadline'];

                    if (data.assessor_document != null) {

                        var document_path = data.assessor_document.path;

                        document.getElementById('qc_sent_document_view').href = server_ip + document_path;


                    }


                    //this code shows the infromation of data sent from inspection to qc
                    if (data.data.to_qc_sent_date == null) {

                    } else {
                        document.getElementById('inspection_qc_view_id').hidden = false;
                        document.getElementById('inspection_from_id').innerText = data.data['inspection_first_name'] + ' ' + data.data['inspection_middle_name'];
                        document.getElementById('qc_to_id').innerText = data.data['qc_first_name'] + ' ' + data.data['qc_middle_name'];
                        document.getElementById('inspection_send_date_id').innerText = data.data['to_qc_sent_date'];

                        if (data.inspection_document != null) {
                            var document_path = data.inspection_document.path;

                            document.getElementById('inspection_sent_document_view').href = server_ip + document_path;

                        }
                    }


//this shows the information of sample test from qc staff to assessor
                    if (data.data.qc_received_date == null) {

                    } else {

//for receiving part

                        document.getElementById('received_view_id').hidden = false;
                        document.getElementById('receive_from_id').innerText = data.data['qc_first_name'] + ' ' + data.data['qc_middle_name'];
                        document.getElementById('receive_to_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_middle_name'] + ' (PERU) and ' + data.data['inspection_first_name'] + ' ' + data.data['inspection_middle_name'] + ' (Inspeciton unit)'
                        document.getElementById('receive_date_id').innerText = data.data['qc_received_date'];

                        if (data.qc_document != null) {

                            var document_path = data.qc_document.path;

                            document.getElementById('qc_receive_document_view').href = server_ip + document_path;
                            if (data.attachments.length !== 0) {

                                var document_path = data.attachments.path;
                                document.getElementById('qc_attached_document_view').href = server_ip + document_path;

                            } else { //hide attached document from the details list if no attachment
                                document.getElementById('attachment_row').hidden = true;
                            }
                        }
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }


        function upload_qc_report(o) {
            document.getElementById('hidden_qc_id').value = o.value;

        }

        function edit_qc_report(o, qc) {

            document.getElementById('hidden_qc_id1').value = qc.id;
            document.getElementById('qc_subject1').value = qc.request_subject;
            document.getElementById('qc_description1').value = qc.response_description;

        }

        // Yemane Extension

        function qc_extend_deadline(qc_id) {
            document.getElementById('extension_qc_id').value = qc_id;
        }

        // End Yemane Extension


        // Regular tooltip initializer for tooltip of DISABLED buttons ( workaround: button needs to be wrapped in span for the tooltip)
        // see blow - code for elapsed time counter after qc report is uploaded)

      /*  <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="bottom"
        title="Editing Disabled. 24 hrs. Elapsed since last report submission">                  <--- ADD THE SPAN
            <button
        class="btn btn-secondary btn-sm"
        style="pointer-events: none;"                         <---- ADD THIS STYLE
        onclick="edit_qc_report(this, {{ $qc_doc }})"
        value="---" disabled>
        <i class="fas fa-edit"></i></button>
        </span>
        */
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });



       // Special initializer for one buttons with both MODAL and TOOLTIP as data-toggles
        // on hover tooltip fires, on click modal fires

      /*  <button
        data-toggle="modal"
        data-target="#editQCResponseModal"
        data-tooltip="tooltip"                       <---- ADD THIS
        class="btn btn-warning btn-sm"
        title="Re-upload report. ( --- Elapsed. Editing will be disabled in 24 hrs. since last report submission)"
        onclick="edit_qc_report(this, ---)"
        value="{{ $qc_doc->id }}">
            <i class="fas fa-edit"></i></button>*/

       $('[data-tooltip="tooltip"]').tooltip();

    </script>

@endsection
