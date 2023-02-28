{{--------------------START QC REPORT ------------------}}
<div class="tab-pane fade" id="custom-tabs-three-qc" role="tabpanel"
     aria-labelledby="custom-tabs-three-qc-tab">


    <div class="modal fade" id="modalsend_qc_request" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalsend_qc_request" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Sample Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form name="upload_response" method="POST"
                          action="{{route('send_to_inspection') }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Subject:</label>
                            <input class="form-control" placeholder="Enter Subject Here" name='subject' required>
                        </div>
                        <div class="form-group">
                            <label>To:</label>
                            <select class="form-control" name='to_user' required>
                                <option></option>
                                @foreach ($inspection_users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->first_name }} {{ $user->middle_name }}</option>

                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="query_response_cover_letter">Sealed Document</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="qc_latter"
                                           id="qc_latter"
                                           class="custom-file-input"
                                           title="Attach both the cover letter and other documents as a zip file."
                                           onchange="filevalidiator('send_qc_document_id','qc_latter','send_qc_btn',['pdf'])"
                                           required>
                                    <label class="custom-file-label"
                                           for="query_response_cover_letter">Choose
                                        file (pdf Only)</label>
                                </div>

                            </div>
                            <p class="text text-danger" id="send_qc_document_id"></p>
                        </div>


                        <input type="hidden" name="dossier_assignment_id"
                               value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="send_qc_btn" class="btn btn-success">Send Request</button>
                        </div>
                    </form>
                </div> {{--modal-body--}}
            </div>
        </div>
    </div>


    {{-- start list of QC reports--}}

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><strong>QC Sample Testing</strong>
            </h3>

            <div class="card-tools">
                @can('assessor_roles')
                    @if($current_user_id==$dossier_evaluation_details->assessor_id)
                        <a type="button" href="{{route('view_html_template',['id'=>$qc_report_template->id,'dossier_asg_id'=>$dossier_evaluation_details->dossier_ass_id])}}"
                           data-toggle="tooltip"
                           class="btn btn-info btn-sm"
                           data-placement="top"
                           target="_blank"
                           title="Send Request to Inspection"> <i class="fas fa-eye"></i> View Templates </a>

                        @if($main_task->task_status =='Locked' || $main_task->task_status =='Decision')
                            <button class="btn btn-secondary btn-sm" title="Evaluation Locked" disabled>Send Request
                                <i class="fas fa-arrow-alt-circle-right"></i></button>
                        @else
                            <button class="btn btn-warning btn-sm"
                                    title="Send Request to Inspection"
                                    data-toggle="modal"
                                    data-target="#modalsend_qc_request">
                                <i class="fas fa-arrow-alt-circle-right"> <span
                                            style="font-family: sans-serif; font-weight: normal ;"> Send Request </span></i>
                            </button>




                        @endif

                    @endif

                @endcan

            </div>
            <!-- /.card-tools -->
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
                            aria-sort="ascending" width="15%"> Status
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="20%" id="subject"> Description
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="15%"> Request Sent On
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="15%" id="received"> Result Received On
                        </th>
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
                            {{--request received from assessor--}}
                            <td>{{$qc_doc->request_subject}}</td>
                            <td>{{$qc_doc->inspection_sent_date}}</td>
                            <td>{{$qc_doc->qc_received_date}}</td>
                            <td>{{$qc_doc->qc_deadline}}</td>

                            <td>

                            @if ($qc_doc->qc_received_date==null)
                                <!-- <button
                                            data-toggle="modal"
                                            data-target="#uploadQCResponseModal"
                                            class="btn btn-info btn-sm"
                                            title="Submit response (reply)"
                                            onclick="upload_qc_report(this)" value="{{ $qc_doc->id }}">
                                        <i class="fas fa-upload"></i></button>

                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#modalextend" onclick="deadline_modal(this)"
                                            value='{{ $qc_doc->id }}'>
                                        <i class="fas fa-clock "></i>
                                    </button> -->

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
            <div class="modal fade" id="modal_qc_details" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">


                    <form action="{{ route('update_deadline') }}"
                          method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Sample Testing Details</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                {{------------------------Start Assessort-to-inspection Details-------------------}}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Details</strong></h3>
                                    </div>
                                    <!-- /.card-header -->
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
                                            <td class="text-muted">Deadline</td>
                                            <td class="text-left"><span id="send_deadline_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Document</td>
                                            <td class="text-left"><a id="qc_sent_document_view" href="" target="_blank"
                                                                     data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm"
                                                                     data-placement="top" title="View the file"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div> {{--end card-info --}}
                                {{------------------------End Assessort-to-inspection Details-------------------}}

                                {{------------------------Start Inspection-to-QC Details-------------------}}
                                <div class="card card-info" id="inspection_qc_view_id" hidden>
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Inspection to QC Details</strong></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <table class="table table-condensed">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">From</td>
                                            <td class="text-left"><span id="inspection_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="qc_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Date</td>
                                            <td class="text-left"><span id="inspection_send_date_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Document</td>
                                            <td class="text-left"><a id="inspection_sent_document_view" href=""
                                                                     target="_blank" data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm" data-placement="top"
                                                                     title="View the file"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div> {{--end card-info --}}
                                {{------------------------End Inspection-to-QC Details-------------------}}

                                {{------------------------Start QC Response Details-------------------}}
                                <div class="card card-info" id="received_view_id" hidden>
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>QC Response Details</strong></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <table class="table table-condensed">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">From</td>
                                            <td class="text-left"><span id="receive_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="receive_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Received Date</td>
                                            <td class="text-left"><span id="receive_date_id"></span></td>
                                        </tr>
                                        <tr style="border-bottom: none">
                                            <td class="text-muted">Received Document</td>
                                            <td class="text-left"><a id="qc_receive_document_view" href=""
                                                                     target="_blank" data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm" data-placement="top"
                                                                     title="View the file"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        <tr id="attachment_row">
                                            <td class="text-muted">Attachment</td>
                                            <td class="text-left"><a id="qc_attached_document_view" href=""
                                                                     target="_blank" data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm" data-placement="top"
                                                                     title="Download the Attachment"><i
                                                            class="fas fa-paperclip"></i> Download</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div> {{--end card-info --}}
                                {{------------------------End QC Response  Details-------------------}}

                            </div> {{--end modal--}}
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
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
                                <h5 class="modal-title">Extend Deadline.</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name='type' value='qc' hidden/>
                                    <input type="text" name='qc_id' id='qc_id' value="" hidden/>
                                    <input type="text" name='hidden_dossier_asg_id'
                                           value='{{$dossier_evaluation_details->dossier_ass_id}}' hidden/>

                                </div>
                                <div class="form-group">
                                    <label> Reason for Extension :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="extend_reason">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Extened New Deadline :</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="date" class="form-control" name="new_deadline">

                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Extend</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{--  end of Modal for extend deadline  --}}

            {{-- MODAL: start upload qc response  --}}
            <div class="modal fade" id="uploadQCResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="uploadQCResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload QC Response</h5>
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

                                    {{--@error('description')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror--}}

                                </div>

                                <div class="form-group">
                                    <label for="qc_report_file">QC Report</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="qc_report_file"
                                                   id="qc_report_file"
                                                   class="custom-file-input"
                                                   onchange="CheckPDFFile(this,'upload_qc_document','uploaded_qc_id')"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="qc_report_file">Choose file</label>
                                            {{--
                                                                                        @error('qc_report_file')
                                                                                        <span class="text-danger"> {{ $message }}</span>
                                                                                        @enderror--}}

                                        </div>
                                    </div>
                                    <span class="text text-danger" id="uploaded_qc_id"></span>
                                </div>

                                <div class="form-group">
                                    <label for="qc_report_attachments">Attachments (Zip file format)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="qc_report_attachments"
                                                   id="qc_report_attachments"
                                                   class="custom-file-input" multiple="multiple"
                                                   onchange="CheckZIPFile(this,'upload_qc_document','uploaded_qc_zip_id')"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="qc_report_attachments">Choose file</label>
                                        </div>
                                        {{--@error('qc_report_attachments')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror--}}

                                    </div>
                                    <span class="text text-danger" id="uploaded_qc_zip_id"></span>
                                </div>

                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                <input type="hidden" name="hidden_qc_id" id="hidden_qc_id" value=""/>
                                <div class="card-footer" style="float:right">
                                    <button class="btn btn-success" id="upload_qc_document" role="button">Submit
                                    </button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end upload of qc response  --}}

            {{-- MODAL: start edit QC response --}}
            <div class="modal fade" id="editQCResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="editQCResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form name="edit_qc_response" method="POST"
                          action="{{route('edit_qc_response')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit QC Response</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                                   onchange="CheckPDFFile(this,'upload_query','edit_uploaded_qc_id')"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="qc_report_file1">Choose file</label>

                                        </div>
                                    </div>
                                    <span class="text text-danger" id="edit_uploaded_qc_id"></span>
                                </div>


                                <div class="form-group">
                                    <label for="qc_report_attachments1">Attachments (Zip file format)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="qc_report_attachments1"
                                                   id="qc_report_attachments1"
                                                   class="custom-file-input" multiple="multiple"
                                                   onchange="CheckZIPFile(this,'edit_qc_document_id','edit_uploaded_qc_zip_id')"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="qc_report_attachments1">Choose file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="edit_uploaded_qc_zip_id"></span>
                                </div>

                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                <input type="hidden" name="hidden_qc_id1" id="hidden_qc_id1" value=""/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="edit_qc_document_id" class="btn btn-success">Edit</button>
                                </div>

                            </div> {{--modal-body--}}
                        </div>
                    </form>
                </div>
            </div>
            {{-- MODAL: end edit response  --}}

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    {{--end list of QC reports--}}


    {{--start upload qc reports--}}

    {{--  <div class="card card-outline card-success collapsed-card">
          <div class="card-header">
              <h3 class="card-title"><strong>Submit QC Report</strong></h3>

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
                      <label> Description</label>
                      <input type="text" class="form-control"
                             name="description">
                  </div>

                  <div class="form-group">
                      <label for="qc_report_file">QC Report</label>
                      <div class="input-group">
                          <div class="custom-file">
                              <input type="file" name="qc_report_file"
                                     id="qc_report_file"
                                     class="custom-file-input" onchange="CheckPDFFile(this,'upload_query','uploaded_document_id')" required>
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
                                     class="custom-file-input" onchange="CheckZIPFile(this,'upload_query','uploaded_zip_document_id')" required>
                              <label class="custom-file-label"
                                     for="qc_report_attachments">Choose
                                  file</label>
                          </div>

                      </div>
                  </div>


                  <input type="hidden" name="dossier_assignment_id"
                         value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                  <div class="card-footer" style="float:right">
                      <button class="btn btn-success" role="button">Submit
                      </button>
                  </div>
              </form>
          </div>
          <!-- /.card-body -->
      </div>--}}

</div>
{{--------------------END QC REPORT ------------------}}
<script>
    function deadline_modal(o) {
        document.getElementById('qc_id').value = o.value;
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
                    document.getElementById('receive_to_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_middle_name'] + ' (PERU) and ' + data.data['inspection_first_name'] + ' ' + data.data['inspection_middle_name'] + 'Inspeciton unit';
                    ;
                    document.getElementById('receive_date_id').innerText = data.data['qc_received_date'];

                    if (data.qc_document != null) {
                        var document_path = data.qc_document.path;

                        document.getElementById('qc_receive_document_view').href = server_ip + document_path;
                        if (data.attachments.length !== 0) {
                            var document_path = data.attachments.path;
                            document.getElementById('qc_attached_document_view').href = server_ip + document_path;
                        }else{ //hide attached document from the details list if no attachment
                            document.getElementById('attachment_row').hidden = true;
                        }
                    }// alert(data.qc['qc_deadline']);
                    //   alert(data.qc.qc_deadline);
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

</script>
