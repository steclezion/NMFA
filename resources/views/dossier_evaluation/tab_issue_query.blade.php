{{---------------- START OF ISSUE QUERY------------}}


<div class="tab-pane fade" id="custom-tabs-three-issue" role="tabpanel"
     aria-labelledby="custom-tabs-three-issue-tab">



{{--    Send Query modal--}}

    <div class="modal fade" id="modalsend_query" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalsend_query" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Query</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form name="upload_response" method="POST"
                          action="{{route('send_query_issue') }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="description">Subject</label>
                            <input name="query_subject" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Deadline</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="date" class="form-control" name="query_deadline">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="query_response_cover_letter">Query Letter</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="query_latter"
                                           id="query_latter"
                                           class="custom-file-input" title="Attach both the cover letter and other documents as a zip file."
                                           onchange="filevalidiator('send_document_id','query_latter','send_query_btn',['zip', 'rar'])" required>
                                    <label class="custom-file-label"
                                           for="query_response_cover_letter">Choose
                                        file (zip, rar)</label>
                                </div>

                            </div>
                            <p class="text text-danger" id="send_document_id"></p>
                        </div>



                        <input type="hidden" name="dossier_assignment_id" value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="send_query_btn" class="btn btn-success">Send Query</button>
                        </div>
                    </form>
                </div> {{--modal-body--}}
            </div>
        </div>
    </div>

    {{--  End of Send Query Modal--}}


    {{---------- start list of uploaded documents -------------}}

    <div class="card card-outline card-success ">
        <div class="card-header">
            <h3 class="card-title"><strong>Evaluation Queries</strong>
            </h3>

            <div class="card-tools bootstrap4">
            @can('assessor_roles')
                            <!-- to prevent other assessors and users with assessor roles from accessing these features -->
                            @if($current_user_id==$dossier_evaluation_details->assessor_id)

                                <a href="{{route('view_html_template',['id'=>$query_cover_letter->id,'dossier_asg_id'=>$dossier_evaluation_details->dossier_ass_id])}}"
                                   data-toggle="tooltip"
                                   class="btn btn-info btn-sm"
                                   data-placement="top"
                                   title="View and Fill out the Template"><i class="fas fa-eye "> <span style="font-family: sans-serif; font-weight: normal ;">View & Edit Cover Letter </span></i></a>
                                    <a href="{{route('view_html_template',['id'=>$query_details->id,'dossier_asg_id'=>$dossier_evaluation_details->dossier_ass_id])}}"
                                       data-toggle="tooltip"
                                       class="btn btn-info btn-sm"
                                       data-placement="top"
                                       title="View and Fill out the Template"><i class="fas fa-eye "> <span style="font-family: sans-serif; font-weight: normal ;">View & Edit Query </span></i></a>

                                    @if($main_task->task_status=='pause')
                                    <button  class="btn btn-secondary btn-sm"
                                            title="You Cann't send another query until applicant responds"
                                            data-toggle="modal"
                                            data-target="#modalsend_query" disabled>
                                            <i class="fas fa-plus"> <span style="font-family: sans-serif; font-weight: normal ;"> New Query </span></i>
                                    </button>
                                    @endif
                                @if($main_task->task_status!='pause')
                                    @if($main_task->task_status=='Locked' || $main_task->task_status=='Decision')
                                            <button class="btn btn-secondary btn-sm"
                                                    title="Evaluation Locked"
                                                    disabled>
                                                <i class="fas fa-plus"> <span style="font-family: sans-serif; font-weight: normal ;"> New Query </span></i>
                                            </button>
                                    @else
                                    <button class="btn btn-warning btn-sm"
                                            title="Send Query to Applicant"
                                            data-toggle="modal"
                                            data-target="#modalsend_query">
                                        <i class="fas fa-plus"> <span style="font-family: sans-serif; font-weight: normal ;"> New Query </span></i>
                                    </button>
                                    @endif

                                    @endif
                                    @endif
                                    @endcan
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" >


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
                            width="14%"> Generic Name
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="14%"> Brand Name
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

                            <?php
                            /** @var TYPE_NAME $query */
                            $query_name_by_sequence = Illuminate\Support\Str::of($query->name)->explode(' ');
                            $query_string = $query_name_by_sequence[0];
                            $query_number = $query_name_by_sequence[1];
                            $modified_query_name = $query_string. ' Exchange '. $query_number;

                            ?>

                            <td>{{$modified_query_name}}</td>
                            <td>
                                @if($query->status=='Response Received')
                                    <span class="badge bg-success">{{$query->status}}</span>
                                @elseif($query->status=='Locked')
                                    <span class="badge bg-danger">{{$query->status}}</span>
                                @else
                                    <span class="badge bg-warning">{{$query->status}}</span>
                                @endif
                            </td>
                            {{--<td>{{$query->query_sent_date}}</td>
                            <td>{{$query->query_received_date}}</td>--}}
                            <td>{{$query->company_name}}</td>
                            <td>{{$query->product_name}}</td>
                            <td>{{$query->product_trade_name}}</td>
                            <td>{{$query->query_deadline}}</td>

                            <td>
                            @can('assessor_roles')
                              <!-- to prevent other assessors and users with assessor roles from accessing these features -->
                              @if($current_user_id==$dossier_evaluation_details->assessor_id)
                            @if($main_task->task_status=='Locked' || $main_task->task_status=='Decided')
                                @else
                                @if ($query->query_received_date==null)
                                <!-- check deadline status if it reaches disable the button  -->
                                  @if ($query->status=='Locked')
                                    <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-secondary btn-sm"
                                        title="Response Deadline Expired!"
                                        onclick="get_query_name(this, {{ $query->id}})" value="{{ $query->name }}" disabled>
                                        <i class="fas fa-upload"></i></button>
                                        @else
                                        <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-success btn-sm"
                                        title="Upload response for this query"
                                        onclick="get_query_name(this, {{ $query->id}}); passid({datatarget: 'uploadResponseModal'})" value="{{ $query->name }}">
                                        <i class="fas fa-upload"></i></button>
                                        @endif
                                    @if ($query->query_extend_count >= 2)
                                        <button type="button" class="btn btn-secondary btn-sm"
                                                title="Extension Denied. Extending deadline more than two times not allowed."
                                                data-toggle="modal"
                                                data-target="#modalextend_query"
                                                onclick="deadline_modal_query(this)" value="{{ $query->id }}" disabled>
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary btn-sm" title="Extend Deadline"
                                                data-toggle="modal"
                                                data-target="#modalextend_query"
                                                onclick="deadline_modal_query(this)" value="{{ $query->id }}">
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    @endif
                                    <button
                                            data-toggle="modal"
                                            data-target="#editResponseModal"
                                            class="btn btn-secondary btn-sm"
                                            title="Edit details, re-upload file"
                                            onclick="edit_response_details(this, {{ $query }})" value="{{ $query->name }}" disabled>
                                        <i class="fas fa-edit"></i></button>

                                    @else {{-- query response is received--}}
                                    <button
                                        data-toggle="modal"
                                        data-target="#uploadResponseModal"
                                        class="btn btn-secondary btn-sm"
                                        title="Response has been Uploaded"
                                        onclick="get_query_name(this, {{ $query->id}})" value="{{ $query->name }}"  disabled>
                                        <i class="fas fa-upload" disabled></i></button>

                                        <button type="button" class="btn btn-secondary btn-sm"
                                                title="Response has been Uploaded"
                                                data-toggle="modal"
                                                data-target="#modalextend_query"
                                                onclick="deadline_modal_query(this)" value="{{ $query->id }}" disabled>
                                            <i class="fas fa-clock"></i></button>

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
                                                    title="Editing Disabled. The Editing Time (24 hrs after sending response) expired." disabled>
                                            <i class="fas fa-edit"></i></button>
                                        @endif
                                @endif
                                @endif
                                @endif

                                @endcan
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#modal_query_details" onclick="details_query(this)"
                                        value='{{ $query->id }}'>
                                    <i class="fas fa-list"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div> {{-- end div: example1_wrapper--}}

            {{--  Modal for Qc details  --}}
            <div class="modal fade" id="modal_query_details" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">


                    <form action="{{ route('update_deadline') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Query Details</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

                                    <table class="table table-condensed responsive-table">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">Name</td>
                                            <td class="text-left"><span id="query_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">From</td>
                                            <td class="text-left"><span id="query_send_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="query_send_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Date</td>
                                            <td class="text-left"><span id="query_send_date_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Deadline</td>
                                            <td class="text-left"><span id="query_send_deadline_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td class="text-left"><span  class="badge bg-success" id="query_status"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Document</td>
                                            <td class="text-left"> <a id="query_sent_document_view" href="" target="_blank" data-toggle="tooltip"
                                                                      class="btn btn-info btn-sm"
                                                                      data-placement="top" title="View the file"><i
                                                        class="fas fa-download"></i> Download </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>  {{--end card-info --}}
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
                                            <td class="text-left"><span id="query_receive_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="query_receive_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Response Description</td>
                                            <td class="text-left"><span
                                                        id="query_response_description_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Response Received On</td>
                                            <td class="text-left"><span id="query_receive_date_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Received Document</td>
                                            <td class="text-left"> <a id="received_view" href="" target="_blank" data-toggle="tooltip"
                                                                      class="btn btn-info btn-sm"
                                                                      data-placement="top" title="View the file"><i
                                                        class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Attachment</td>
                                            <td class="text-left"><a id="query_attached_document_view" href="" target="_blank" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm" data-placement="top" title="Download the Attachment"><i
                                                        class="fas fa-paperclip"></i> Download</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>  {{--end card-info --}}
                                {{------------------------End Query Response Details-------------------}}
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{--  end of Modal for query details  --}}

            {{--  Modal for Extend deadline  --}}
            <div class="modal fade" id="modalextend_query" data-backdrop="static" tabindex="-1" role="dialog"
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
                                    <input type="text" name='query_id' id='query_id' hidden/>
                                    <input type="text" name='type' value='query' hidden/>
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
                                    <label>Extend New Deadline :</label>
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


            {{-- MODAL: start upload response of issue --}}
            <div class="modal fade" id="uploadResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="uploadResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Response</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" id="upload_response_form" method="POST"
                                  action="{{route('upload_query_response') }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="description">Response for Query:</label>
                                    <input name="query_name_placeholder" type="text" class="form-control"
                                           id="query_name_placeholder" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input name="description" type="text" class="form-control"
                                           id="description">
                                </div>

                                <div class="form-group">
                                    <label for="query_response_cover_letter">Query Response Cover Letter</label>
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
                                                   onchange="filevalidiator('uploaded_zip_document_id','query_response_file','upload_query',['rar','zip'])"
                                                   required>
                                             <label class="custom-file-label"
                                                   for="query_response_file">Choose Files (zip,rar) </label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="uploaded_zip_document_id"></span>
                                </div>


                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                <input type="hidden" name="hidden_query_id" id="hidden_query_id" value=""/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="upload_query" onclick="passid(this)" onsubmit="CheckAllDocuments()">Upload</button>
                                </div>

                                {{------------------------start progress bar for file upload-------------------------}}

                                <div class="form-group">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                             role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                </div>
                                {{------------------------end progress bar for file upload-------------------------}}
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end upload response of issue --}}

            {{-- MODAL: start edit response --}}
            <div class="modal fade" id="editResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="editResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form name="edit_response" method="POST"
                          action="{{route('edit_query_response')}}"
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
                                    <label for="query_name1">Response for Query</label>
                                    <input name="query_name1" type="text" class="form-control"
                                           id="query_name1" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="description1">Description</label>
                                    <input name="description1" type="text" class="form-control"
                                           id="description1">
                                </div>

                                <div class="form-group">
                                    <label for="query_response_cover_letter1">Query Response Cover Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="query_response_cover_letter1"
                                                   id="query_response_cover_letter1"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('edit_document_id','query_response_cover_letter1','edit_query_response',['pdf'])" required>
                                            <label class="custom-file-label"
                                                   for="query_response_cover_letter1">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="edit_document_id"></span>
                                </div>

                                <div class="form-group">
                                    <label for="query_response_file1">Query Response (Zip file)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="query_response_file1"
                                                   id="query_response_file1"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('edit_zip_document_id','query_response_file1','edit_query_response',['doc','docx'])" required >
                                            <label class="custom-file-label"
                                                   for="query_response_file1">Choose .zip
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="edit_zip_document_id"></span>
                                </div>

                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                <input type="hidden" name="hidden_query_id1" id="hidden_query_id1" value=""/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit"  id="edit_query_response" class="btn btn-success">Edit</button>
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


    {{---------- end list of uploaded documents -------------}}

@if(isset($decision))
@if( $decision->locked == 1  and $decision->decision_status=="Deferred")
<div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><strong>Deferral Queries</strong>
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
        <div class="card-body" >
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
<table id="example1"
                       class="table table-bordered table-striped dataTable no-footer dtr-inline"
                       role="grid" aria-describedby="example1_info">

                    <thead>
                    <tr role="row">
                    <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Serial Number: activate to sort column descending"
                            aria-sort="ascending" width="5%"> S.N.
                        </th>
                        <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Serial Number: activate to sort column descending"
                            aria-sort="ascending" width="20%"> Subject
                        </th>
                        <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Reference Number: activate to sort column descending"
                            aria-sort="ascending" width="10%">Status
                        </th>
                        <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Reference Number: activate to sort column descending"
                            aria-sort="ascending" width="20%">Query Sent On
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="20%">Response Received On
                        </th>
                        <th rowspan="1" colspan="1" width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($deferment_queries as $deferment_query)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$deferment_query->sent_subject}}</td>
                        <td><span class="badge badge-success">{{$deferment_query->status}}</span></td>
                        <td>{{$deferment_query->sent_date}}</td>
                        <td>{{$deferment_query->received_date}}</td>
                        <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#modal_deferment_query_details" onclick="deferment_details_query(this)"
                                        value='{{ $deferment_query->id }}'>
                                    <i class="fas fa-list"></i>
                                </button>

                       </td>
</tr>
@endforeach
                    </tbody>

                </table>
            </div>
            </div>
            </div>





            {{--  Modal for Qc details  --}}
            <div class="modal fade" id="modal_deferment_query_details" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">


                    <form action="{{ route('update_deadline') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Query Details</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

                                    <table id="example2" class="table table-condensed responsive-table">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">Status</td>
                                            <td class="text-left"><span  class="badge bg-success" id="status"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted" width="23%">Supervisor Subject</td>
                                            <td class="text-left"><span id="superviosr_subject"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">From</td>
                                            <td class="text-left"><span id="supervisor_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="applicant_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Query Sent On</td>
                                            <td class="text-left"><span id="query_send_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Response Received On</td>
                                            <td class="text-left"><span id="query_received_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Documents</td>
                                            <td class="text-left"> <a id="query_sent_document_view" href="" target="_blank" data-toggle="tooltip"
                                                                      class="btn btn-info btn-sm"
                                                                      data-placement="top" title="View the file"><i
                                                        class="fas fa-book-open"></i> View </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Response Document</td>
                                            <td class="text-left"> <a id="query_received_document_view" href="" target="_blank" data-toggle="tooltip"
                                                                      class="btn btn-info btn-sm"
                                                                      data-placement="top" title="View the file"><i
                                                        class="fas fa-book-open"></i> View </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>  {{--end card-info --}}
                                {{------------------------End Issued Query Details-------------------}}
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{--  end of Modal for query details  --}}
@endif
    @endif



</div>


{{---------------- END OF ISSUE QUERY------------}}
<script>









    function deadline_modal_query(o) {

        document.getElementById('query_id').value = o.value;
    }

    function get_query_name(o, queryid) {
        document.getElementById('hidden_query_id').value = queryid;
        var words = o.value.split(" ");
        var query_name = words[0] + ' Exchange ' + words[1];
        document.getElementById('query_name_placeholder').value = query_name;
    }

    function edit_response_details(o, query) {


        document.getElementById('hidden_query_id1').value = query.id;
        document.getElementById('query_name1').value = query.name;
        document.getElementById('description1').value = query.response_description;

    }


    function details_query(o) {
        let id = o.value;

        var server_ip=document.getElementById('server_ip').value;
        document.getElementById('query_received_view_id').hidden = true;


        $.ajax({

            type: 'GET',

            url: "{{ route('retrieve_details') }}",

            data: {id: id, typ: 'query'},

            success: function (data) {

                //for sending part
                var document_path = data.sent_document.path;

                if(data.data['name'] == 'Query 1'){
                    document.getElementById('query_name').innerText = "Query Exchange 1"
                }else if(data.data['name'] == 'Query 2'){
                    document.getElementById('query_name').innerText = "Query Exchange 2"
                }
                //document.getElementById('query_name').innerText = data.data['name'];
                document.getElementById('query_send_from_id').innerText =data.data['assessor_first_name']+' '+data.data['assessor_last_name'];
                document.getElementById('query_send_to_id').innerText = data.data['applicant_first_name']+' '+data.data['applicant_last_name'];
                document.getElementById('query_send_date_id').innerText = data.data['query_sent_date'];
                document.getElementById('query_send_deadline_id').innerText = data.data['query_deadline'];
                document.getElementById('query_status').innerText = data.data['status'];



                document.getElementById('query_sent_document_view').href = server_ip + document_path;

                if (data.data.query_received_date == null) {

                } else {
                    var document_path = data.received_document.path;
//for receiving part
                    document.getElementById('query_received_view_id').hidden = false;
                    document.getElementById('query_receive_from_id').innerText = data.data['applicant_first_name']+' '+data.data['applicant_last_name'];
                    document.getElementById('query_receive_to_id').innerText = data.data['assessor_first_name']+' '+data.data['assessor_last_name'];
                    document.getElementById('query_receive_date_id').innerText = data.data['query_received_date'];
                    document.getElementById('query_response_description_id').innerText = data.data['response_description'];



    if( data.received_document!=null)
    {
    var document_path = data.received_document.path;

    document.getElementById('received_view').href = server_ip + document_path;
    if (data.attachments!=null){
                        var document_path = data.attachments.path;
                        document.getElementById('query_attached_document_view').href = server_ip + document_path;


                }

    }

                }
            },
            error: function (data) {
                console.log(data)

            }
        });

    }



function deferment_details_query(o) {
        let id = o.value;

        var server_ip=document.getElementById('server_ip').value;


        $.ajax({

            type: 'GET',

            url: "{{ route('query_details') }}",

            data: {id: id},

            success: function (data) {

                //for sending part

                console.log(data)
                var document_path = data.sent_document.path;
                document.getElementById('superviosr_subject').innerText = data.data['sent_subject'];
                document.getElementById('supervisor_name').innerText =data.data['supervisor_first_name']+' '+data.data['supervisor_middle_name'];
                document.getElementById('applicant_name').innerText = data.data['applicant_first_name']+' '+data.data['applicant_middle_name'];
                document.getElementById('query_send_date').innerText = data.data['sent_date'];
                document.getElementById('query_received_date').innerText = data.data['received_date'];
                document.getElementById('status').innerText = data.data['status'];
                document.getElementById('status').AddClass = "badge badge-success" ;


                document.getElementById('query_sent_document_view').href = server_ip + document_path;

                if (data.received_document == null) {

                    document.getElementById('query_received_document_view').hidden = true

                }
                 else {
                    var document_path = data.received_document.path;
                    document.getElementById('query_received_document_view').hidden = false

                    document.getElementById('query_received_document_view').href = server_ip + document_path;


                }
            },
            error: function (data) {
                console.log(data)

            }
        });

    }

</script>
