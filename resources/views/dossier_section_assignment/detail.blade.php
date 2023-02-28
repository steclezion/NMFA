@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <strong>Dossier Section Assignment Details</strong>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-6 offset-1">

                                    {{------------------------Start dossier section assessement details-------------------}}
                                    <div class="card card-outline card-gray" style="width: 70%;">
                                        <div class="card-header">
                                            <h3 class="card-title"><strong>Assessment Request</strong>
                                            </h3>
                                        </div>

                                        <table class="table table-condensed table-borderless">
                                            <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Assigned by</td>
                                                <td class="text-left">{{ $assigned_section->first_name }} {{$assigned_section->middle_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Description</td>
                                                <td class="text-left">{{ $assigned_section->assignment_description}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Assigned Date</td>
                                                <td class="text-left">{{$assigned_section->section_sent_date }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Deadline</td>
                                                <td class="text-left">{{ $assigned_section->section_deadline }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Remaining Days</td>
                                                <td class="text-left">{{$diff_in_days}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Dossier Section</td>
                                                <td class="text-left"><a
                                                            href="{{ asset($assigned_section->sent_document_path)}}"
                                                            target="_blank" data-toggle="tooltip"
                                                            class="btn btn-info btn-sm"
                                                            data-placement="top"
                                                            title="View the file"><i class="fas fa-book-open"></i> View</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{------------------------End dossier section assessement details--------------------}}


                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-5">
                                    @if ($assigned_section->section_received_date!=null)

                                        {{------------------------Start dossier section response details-------------------}}
                                        <div class="card card-outline card-gray" style="width: 70%;">
                                            <div class="card-header">
                                                <h3 class="card-title"><strong>Assessment Response</strong>
                                                </h3>
                                            </div>

                                            <table class="table table-condensed table-borderless">
                                                <tbody>
                                                <tr>
                                                    <td class="text-muted" width="40%">Subject</td>
                                                    <td class="text-left">{{ $assigned_section->response_description}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Response Date</td>
                                                    <td class="text-left">{{$assigned_section->section_received_date}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Assessed Section</td>
                                                    <td class="text-left"><a
                                                                href="{{ asset($assigned_section->received_document_path)}}"
                                                                target="_blank" data-toggle="tooltip"
                                                                class="btn btn-info btn-sm"
                                                                data-placement="top"
                                                                title="View the file"><i class="fas fa-book-open"></i>
                                                            View</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{------------------------End dossier section response details--------------------}}


                                </div>
                                @else

                                        <div class="alert alert-default-warning" style="width: 80%;">

                                            <h5><i class="icon fas fa-exclamation-circle"></i>

                                                Evaluation Report has not been Submitted.</h5>

                                        </div>

                                @endif
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <a type="button" class="btn btn-default"
                               href="{{route('dossier_section_assign_index')}}">Back</a>
                            @if ($assigned_section->section_received_date==null)
                                <button type="button" class="btn btn-warning" title="Request for Deadline Extension"
                                        data-toggle="modal" data-target="#dedline_extension" onclick="" value="">
                                    <i class="fas fa-clock"></i> Request Deadline Extension
                                </button>

                                @if ($assigned_section->status!='Locked')
                                    <button type="button" class="btn btn-success"
                                            title="Submit the evaluation to Assessor"
                                            data-toggle="modal" data-target="#submit_evaluation_to_assessor" onclick=""
                                            value=""><i class="fas fa-upload"></i> Submit Evaluation Report
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger"
                                            title="Submission Expired. Request Deadline Extension."
                                             disabled><i class="fas fa-upload"></i> Submit Evaluation Report
                                    </button>
                                @endif
                            @else
                                <button data-toggle="modal" data-target="#editSectionResponseModal"
                                        class="btn btn-success btn-sm"
                                        title="edit details, re-upload file"
                                        onclick="edit_section_response_details({{$assigned_section }})">
                                    <i class="fas fa-upload"></i> Re-upload Response
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

        </div>

        {{-- MODAL: start edit response --}}
        <div class="modal fade" id="editSectionResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="editResponseModal" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">

                <form name="edit_response" method="POST" action="{{route('edit_section_assignment_response')}}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Response</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="description1">Description</label>
                                <input name="section_description" type="text" class="form-control"
                                       id="section_description"
                                       value="" required>
                            </div>

                            <div class="form-group">
                                <label for="query_response_file1">Section
                                    Assignment Response</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="section_response_file1" id="section_response_file1"
                                               class="custom-file-input" required>
                                        <label class="custom-file-label" for="section_response_file1">Choose
                                            file</label>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" name="dossier_assignment_id"
                                   value="{{$assigned_section->section_related_id}}"/>
                            <input type="hidden" name="section_edit_id" id="section_edit_id" value=""/>
                            <div class="modal-footer
                                    justify-content-between">
                                <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn
                                        btn-success">Reupload
                                </button>
                            </div>

                        </div> {{--modal-body--}}
                    </div>
                </form>
            </div>
        </div>
        {{-- MODAL: end edit response --}}

        {{-- MODAL for deadline extension Request--}}

        <div class="modal fade" id="dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="deleteRecordModal" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">

                <form action="{{ route('dossier_section_deadline_extension')
                        }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Request For Deadline Extension</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label>Reason For Extension Request</label><input type="text" class="form-control"
                                                                      name='extension_reason' required><br>
                            <label>Requested Deadline</label><input type="date" class="form-control"
                                                                    name='extended_deadline' required><br>


                        </div>

                        <input type="hidden" value="{{$assigned_section->id}}" name="dossier_section_id"/>
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
        {{-- MODAL for Section Evaluation Response --}}

        <div class="modal fade" id="submit_evaluation_to_assessor" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="deleteRecordModal" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">

                <form action="{{ route('dossier_section_upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Evaluation Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="description1">Description</label>
                                <input name="section_description" type="text" class="form-control"
                                       id="section_description"
                                       value="" required>
                            </div>
                            <div class="form-group">
                                <label for="query_response_file1">Evaluation Report </label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="section_response_file1" id="section_response_file1"
                                               class="custom-file-input" required>
                                        <label class="custom-file-label" for="section_response_file1">Choose
                                            file</label>
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" value="{{
                                    $assigned_section->id }}"
                                   name="hidden_section_id">


                            <div class="modal-footer
                                    justify-content-between">
                                <button type="button" class="btn bg-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn
                                        btn-success">Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
        {{-- End of Modal Section Evaluation Response --}}


    </section>


@endsection

@section('scripts')
    <script src="{{
                asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')
                }}"></script>

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


        // Delete Record Modal to confirm before deletion
        $('#deleteRecordModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var action = button.data('action');
            var modal = $(this);
            modal.find('form').attr('action', action);
        });

    </script>
@endsection
