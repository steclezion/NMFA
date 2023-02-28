
<div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
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
                aria-sort="ascending" width="12%"> Application Num.
            </th>
            <th class="sorting sorting_asc" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Reference Number: activate to sort column descending"
                aria-sort="ascending" width="10%"> Name
            </th>
            <th class="sorting" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Title: activate to sort column ascending"
                width="14%"> Status
            </th>
            <th class="sorting" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Title: activate to sort column ascending"
                width="14%"> Applicant Name
            </th>
            <th class="sorting" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Title: activate to sort column ascending"
                width="14%">  Generic Name
            </th>
            <th class="sorting" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Title: activate to sort column ascending"
                width="14%">  Brand Name
            </th>
            <th class="sorting" tabindex="0"
                aria-controls="example1" rowspan="1" colspan="1"
                aria-label="Title: activate to sort column ascending"
                width="14%"> Deadline
            </th>
            <th rowspan="1" colspan="1" width="20%">Actions</th>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @foreach($issue_query_documents as $query)
            <tr role="row" class="odd" id="description">
                <td>{{$i++}}</td>
                <td>{{$query->application_number}}</td>
                <?php
                /** @var TYPE_NAME $query */
                $query_name_by_sequence = Illuminate\Support\Str::of($query->name)->explode(' ');
                    $query_string = $query_name_by_sequence[0];
                    $query_number = $query_name_by_sequence[1];
                    $modified_query_name = $query_string. ' Exchange '. $query_number;

                ?>
                <td>{{$modified_query_name}}</td>
                @if($query->status == 'Query Issued')
                    <td><span class="badge badge-warning">{{$query->status}}</span></td>
                @elseif($query->status == 'Response Received')
                    <td><span class="badge badge-success">Response Sent</span></td>
                @elseif($query->status == 'Locked')
                    <td><span class="badge badge-danger">{{$query->status}}</span></td>
                @else
                    <td><span class="badge badge-primary">{{$query->status}}</span></td>
                @endif
                <td>{{$query->company_name}}</td>
                <td>{{$query->product_name}}</td>
                <td>{{$query->product_trade_name}}</td>
                <td>{{$query->query_deadline}}</td>
                <td>


                <!-- Yemane Extension -->


                <button type="button" class="btn btn-primary btn-sm"
                                                                    title="Request for Deadline Extension"
                                                                    data-toggle="modal" data-target="#dedline_extension"
                                                                    onclick="extend_deadline({{$query->id}})"
                                                                    value="">
                                                                <i class='fas fa-clock'></i>
                                                            </button>

                <!-- End Yemane Extension-->
                    @can('application-list')
                        @if($query->task_status=='Locked' || $query->task_status=='Decided')
                        @else
                            @if($query->query_received_date != null)   {{--response is sent--}}

                            <?php
                            /** @var TYPE_NAME $query */
                            $now = \Carbon\Carbon::now();
                            $query_received_date = \Carbon\Carbon::create($query->query_received_date);
                            $diffInHours = $query_received_date->diffInHours($now, false);

                            ?>
                                 @if($diffInHours >= 0 and $diffInHours < 24)
                                <button
                                        data-toggle="modal"
                                        data-target="#editResponseModal"
                                        class="btn btn-warning btn-sm"
                                        title="Edit/Reupload details. Editing will be disabled in {{24-$diffInHours}} hours."
                                        onclick="edit_response_details(this, {{ $query }})"
                                        value="{{ $query->name }}">
                                    <i class="fas fa-edit"></i></button>
                            @else
                                <button
                                        class="btn btn-secondary btn-sm"
                                        title="Editing Disabled. The Editing Time (24 hrs after sending response) expired."
                                        disabled>
                                    <i class="fas fa-edit"></i></button>
                            @endif

                            {{--<button
                                    class="btn btn-secondary btn-sm"
                                    title="Query Response Already Uploaded"
                                    disabled>
                                <i class="fas fa-upload"></i>
                            </button>
                            <button
                                    class="btn btn-secondary btn-sm"
                                    title="Query Response Already Uploaded"
                                    disabled>
                                <i class="fas fa-edit"></i>
                            </button>--}}

                            @elseif ($query->query_received_date==null)  {{--response NOT sent yet--}}
                        <!-- check deadline status if it reaches disable the button  -->
                            @if ($query->status=='Locked')
                                <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-secondary btn-sm"
                                        title="Response Deadline Expired! "
                                        onclick="get_query_name(this, {{ $query->id}})"
                                        value="{{ $query->name }}" disabled>
                                    <i class="fas fa-upload"></i></button>
                            @else
                                <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-success btn-sm"
                                        title="Upload response for this query"
                                        onclick="get_query_name(this, {{ $query }})"
                                        value="{{ $query->name }}">
                                    <i class="fas fa-upload"></i></button>
                            @endif

                            <button
                                    data-toggle="modal"
                                    data-target="#editResponseModal"
                                    class="btn btn-secondary btn-sm"
                                    title="Edit details, re-upload file"
                                    onclick="edit_response_details(this, {{ $query }})"
                                    value="{{ $query->name }}" disabled>
                                <i class="fas fa-edit"></i></button>

                            @else
                                <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-secondary btn-sm"
                                        title="Response has been Uploaded"
                                        value="{{ $query->name }}" disabled>
                                    <i class="fas fa-upload" disabled></i></button>



                                <button
                                        data-toggle="modal"
                                        data-target="#editResponseModal"
                                        class="btn btn-warning btn-sm"
                                        title="Edit details, re-upload file"
                                        onclick="edit_response_details(this, {{ $query }})"
                                        value="{{ $query->name }}">
                                    <i class="fas fa-edit"></i></button>
                            @endif
                        @endif

                    @endcan
                    <button type="button" class="btn btn-info btn-sm"
                            data-toggle="modal"
                            data-target="#modal_query_details"
                            onclick="details_query(this)"
                            value='{{ $query->id }}'>
                        <i class="fas fa-list"></i>
                    </button>

                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div> {{-- end div: example1_wrapper--}}


{{-- MODAL: start upload response of issue --}}
<div class="modal fade" id="uploadResponseModal" data-backdrop="static" tabindex="-1"
     role="dialog"
     aria-labelledby="uploadResponseModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Response</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form name="upload_response" method="POST"
                      action="{{route('upload_query_response') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="description">Response for Query</label>
                        <input name="query_name_placeholder" type="text"
                               class="form-control"
                               id="query_name_placeholder" disabled>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input name="description" type="text" class="form-control"
                               id="description">
                    </div>

                    <div class="form-group">
                        <label for="query_response_cover_letter">Query Response Cover
                            Letter</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="query_response_cover_letter"
                                       id="query_response_cover_letter"
                                       class="custom-file-input"
                                       onchange="filevalidiator('uploaded_document_id','query_response_cover_letter','upload_query',['pdf'])"
                                       required>
                                <label class="custom-file-label"
                                       for="query_response_cover_letter">Choose
                                    file</label>
                            </div>

                        </div>
                        <span class="text text-danger" id="uploaded_document_id"></span>
                    </div>

                    <div class="form-group">
                        <label for="query_response_file">Query Response</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="query_response_file"
                                       id="query_response_file"
                                       class="custom-file-input"
                                       onchange="filevalidiator('uploaded_zip_document_id','query_response_file','upload_query',['rar', 'zip'])"
                                       required>
                                <label class="custom-file-label"
                                       for="query_response_file">Choose .zip, .rar
                                    file</label>
                            </div>

                        </div>
                        <span class="text text-danger"
                              id="uploaded_zip_document_id"></span>
                    </div>
                    <input type="hidden" name="dossier_assignment_id" id="dossier_assignment_id" value="">

                    <input type="hidden" name="hidden_query_id" id="hidden_query_id"
                           value=""/>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="upload_query"
                                onsubmit="CheckAllDocuments()">Upload
                        </button>
                    </div>
                </form>
            </div> {{--modal-body--}}
        </div>
    </div>
</div>
{{-- MODAL: end upload response of issue --}}

{{-- MODAL: start edit response --}}
<div class="modal fade" id="editResponseModal" data-backdrop="static" tabindex="-1"
     role="dialog"
     aria-labelledby="editResponseModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">

        <form name="edit_response" method="POST"
              action="{{route('edit_query_response')}}"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Response</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="query_name1">Response for Query:</label>
                        <input name="query_name1" type="text" class="form-control"
                               id="query_name1" disabled>
                    </div>
                    <div class="form-group">
                        <label for="description1">Description</label>
                        <input name="description1" type="text" class="form-control"
                               id="description1">
                    </div>

                    <div class="form-group">
                        <label for="query_response_cover_letter1">Query Response Cover
                            Letter</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="query_response_cover_letter1"
                                       id="query_response_cover_letter1"
                                       class="custom-file-input"
                                       onchange="filevalidiator('edit_document_id','query_response_cover_letter1','edit_query_response',['pdf'])"
                                       required>
                                <label class="custom-file-label"
                                       for="query_response_cover_letter1">Choose
                                    file</label>
                            </div>

                        </div>
                        <span class="text text-danger" id="edit_document_id"></span>
                    </div>

                    <div class="form-group">
                        <label for="query_response_file1">Query Response (ZIP file)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="query_response_file1"
                                       id="query_response_file1"
                                       class="custom-file-input"
                                       onchange="filevalidiator('edit_zip_document_id','query_response_file1','edit_query_response',['zip'])" required>
                                <label class="custom-file-label"
                                       for="query_response_file1">Choose .zip
                                    file</label>
                            </div>

                        </div>
                        <span class="text text-danger" id="edit_zip_document_id"></span>
                    </div>

                    <input type="hidden" name="hidden_query_id1" id="hidden_query_id1"
                           value=""/>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id="edit_query_response"
                                class="btn btn-success">Edit
                        </button>
                    </div>

                    <input type="hidden" name="dossier_assignment_id" id="dossier_assignment_id_edit"
                           value="">

                </div> {{--modal-body--}}
            </div>
        </form>
    </div>
</div>
{{-- MODAL: end edit response  --}}

{{--  Modal for Qc details  --}}
<div class="modal fade" id="modal_query_details" data-backdrop="static" tabindex="-1"
     role="dialog"
     aria-labelledby="modalextend" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">


        <form action="{{ route('update_deadline') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Query Details</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{------------------------Start Issued Query Details-------------------}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Issued Query</strong></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <div id="example_wrapper_eval_queries" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                        <table class="table table-responsive">
                            <tbody>
                            <tr>
                                <td class="text-muted" width="23%">Name</td>
                                <td class="text-left"><span id="query_name"></span></td>
                            </tr>
                          {{--  <tr>
                                <td class="text-muted">From</td>
                                <td class="text-left"><span
                                            id="query_send_from_id"></span></td>
                            </tr>--}}
                            <tr>
                                <td class="text-muted">To</td>
                                <td class="text-left"><span
                                            id="query_send_to_id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sent Date</td>
                                <td class="text-left"><span
                                            id="query_send_date_id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Deadline</td>
                                <td class="text-left"><span
                                            id="query_send_deadline_id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td class="text-left"><span class="badge bg-success"
                                                            id="query_status"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sent Document</td>
                                <td class="text-left"><a id="query_sent_document_view"
                                                         href="" target="_blank"
                                                         data-toggle="tooltip"
                                                         class="btn btn-info btn-sm"
                                                         data-placement="top"
                                                         title="View the file"><i
                                                class="fas fa-book-open"></i> View </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                    </div> {{--end card-info --}}
                    {{------------------------End Issued Query Details-------------------}}

                    {{------------------------Start Query Response Details-------------------}}
                    <div class="card card-info" id="query_received_view_id" hidden>
                        <div class="card-header">
                            <h3 class="card-title"><strong>Query Response</strong></h3>
                        </div>
                        <!-- /.card-header -->

                        <table class="table table-condensed">
                            <tbody>
                            <tr>
                                <td class="text-muted" width="23%">From</td>
                                <td class="text-left"><span
                                            id="query_receive_from_id"></span></td>
                            </tr>
                        {{--    <tr>
                                <td class="text-muted">To</td>
                                <td class="text-left"><span
                                            id="query_receive_to_id"></span></td>
                            </tr>--}}
                            <tr>
                                <td class="text-muted">Response Description</td>
                                <td class="text-left"><span
                                            id="query_response_description_id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Received On</td>
                                <td class="text-left"><span
                                            id="query_receive_date_id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Received Document</td>
                                <td class="text-left"><a id="received_view" href=""
                                                         target="_blank"
                                                         data-toggle="tooltip"
                                                         class="btn btn-info btn-sm"
                                                         data-placement="top"
                                                         title="View the file"><i
                                                class="fas fa-book-open"></i> View</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Attachment</td>
                                <td class="text-left"><a
                                            id="query_attached_document_view" href=""
                                            target="_blank" data-toggle="tooltip"
                                            class="btn btn-info btn-sm"
                                            data-placement="top"
                                            title="Download the Attachment"><i
                                                class="fas fa-paperclip"></i>
                                        Download</a>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div> {{--end card-info --}}
                    {{------------------------End Query Response Details-------------------}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Back
                    </button>
                    <button type="button" class="btn btn-success" data-dismiss="modal">
                        Ok
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
{{--  end of Modal for query details  --}}

