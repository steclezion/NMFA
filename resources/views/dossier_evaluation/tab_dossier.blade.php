{{-----------  START DOSSIER TAB ----------------------}}

<?php
use App\Http\Controllers\UtilsController as Utils;
?>

<div class="tab-pane fade" id="custom-tabs-three-dossier" role="tabpanel"
     aria-labelledby="custom-tabs-three-dossier-tab">

    {{-- start list of dossier files--}}
    <div class="card card-blue collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Dossier Files</h3>
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

                <table id="example3" class="table table-sm table-hover dataTable dtr-inline"
                       role="grid" aria-describedby="example1_info">
                    <thead>
                    <tr role="row">
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                            rowspan="1" colspan="1"
                            aria-label="Supplier Name: activate to sort column descending"
                            aria-sort="ascending" width="5%">S.N
                        </th>
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                            rowspan="1" colspan="1"
                            aria-label="Supplier Name: activate to sort column descending"
                            aria-sort="ascending" width="30%">Filename
                        </th>
                        {{--<th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                            rowspan="1" colspan="1"
                            aria-label="Supplier Name: activate to sort column descending"
                            aria-sort="ascending" width="20%">Path
                        </th>--}}
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                            rowspan="1" colspan="1"
                            aria-label="Supplier Name: activate to sort column descending"
                            aria-sort="ascending" width="10%">File size
                        </th>
                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1" width="10%">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($paths as $path)
                        <tr role="row">

                            <td>{{$i++}}</td>
                            <td>{{basename($path)}}</td>

                            <td>{{Utils::human_readable_filesize(filesize(public_path(Config::get('site_vars.dossier_dir').$path)))}}</td>
                            <td>
                                <a href="{{asset(Config::get('site_vars.dossier_dir'). $path)}}"
                                   target="_blank"
                                   class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>


        </div>
        <!-- /.card-body -->
    </div>
    {{--end list of dossier files--}}


    {{-- start list of assigned dossier sections--}}
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><strong>Details of Assigned Dossier Sections</strong></h3>
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
                            aria-sort="ascending" width="10%">Section Status
                        </th>
                        <th class="sorting sorting_asc" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Reference Number: activate to sort column descending"
                            aria-sort="ascending" width="10%">Description
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="12%">Assigned To
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="15%">Assigned On
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="15%">Deadline
                        </th>

                        <th class="sorting" tabindex="0"
                            aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Title: activate to sort column ascending"
                            width="15%"> Report Received On
                        </th>
                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($dossier_section_assignment as $assignment)
                        <tr role="row" class="odd">
                            <td>{{$i++}}</td>
                            @if($assignment->status == 'Inprogress')
                                <td><span class="badge bg-primary">In-progress</span></td>
                            @elseif($assignment->status=='Completed')
                                <td><span class="badge bg-success">{{$assignment->status}}</span></td>
                            @elseif($assignment->status=='Locked')
                                <td><span class="badge bg-danger">{{$assignment->status}}</span></td>
                            @else
                                <td><span class="badge bg-secondary">{{$assignment->status}}</span></td>
                            @endif
                            <td>{{$assignment->assignment_description}}</td>
                            <td>{{$assignment->first_name}} {{$assignment->middle_name}}</td>
                            <td>{{$assignment->section_sent_date}}</td>
                            <td>{{$assignment->section_deadline}}</td>
                            <td>{{$assignment->section_received_date}}</td>

                            <td>
                                @if(! ($main_task->task_status=='Locked' | $main_task->task_status=='Decided'))

                                    @can('assessor_roles')
                                        @if($current_user_id==$dossier_evaluation_details->assessor_id)
                                            @if ($assignment->section_received_date==null)

                                                <button type="button" class="btn btn-primary btn-sm"
                                                        title="Deadline Extension "
                                                        data-toggle="modal"
                                                        data-target="#modalextend_section"
                                                        onclick="deadline_modal_section(this)"
                                                        value="{{ $assignment->id }}">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                        title=" Evaluated section has been uploaded. "
                                                        data-toggle="modal"
                                                        data-target="#modalextend_section"
                                                        onclick="deadline_modal_section(this)"
                                                        value="{{ $assignment->id }}"
                                                        disabled>
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            @endif
                                        @endif
                                    @endcan
                                @endif


                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#modal_section_details" onclick="details_section(this)"
                                        value='{{ $assignment->id }}'>
                                    <i class="fas fa-list "></i>
                                </button>
                                <!-- <button type="button"
                                        data-toggle="modal"
                                        data-target="#deleteRecordModal"
                                        onclick="delete_document(this)" value=""
                                        title="Delete the document" class="btn btn-danger btn-sm"><i
                                            class="fas fa-trash "></i>
                                </button> -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
            {{--  Modal for Section Assignment details  --}}
            <div class="modal fade" id="modal_section_details" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">


                    <form action="{{ route('update_deadline') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Assigned Dossier Section Details</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                {{------------------------Start Downloaded-Section Details-------------------}}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Assigned Section Details
                                            </strong></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <table class="table table-condensed">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">From</td>
                                            <td class="text-left"><span id="section_send_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="section_send_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Date</td>
                                            <td class="text-left"><span id="section_send_date_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Deadline</td>
                                            <td class="text-left"><span id="section_send_deadline_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td class="text-left"><span class="badge bg-success"
                                                                        id="section_status"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Document</td>
                                            <td class="text-left"><a id="section_sent_document_view" href=""
                                                                     target="_blank"
                                                                     data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm"
                                                                     data-placement="top" title="View the file">
                                                    <i class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div> {{--end card-info --}}
                                {{------------------------End Downloaded-Section  Details-------------------}}


                                {{--this is for the uploaded details--}}
                                {{--                                <div class="card card-info" id="section_received_view_id" hidden>
                                                                    <div class="card-header">
                                                                        <h3 class="card-title"><strong> Dossier Section Evaluation Assignment
                                                                                Response</strong>
                                                                        </h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <label>From: </label> <span id="section_receive_from_id"></span><br>
                                                                        <label>To:</label> <span id="section_receive_to_id"></span><br>
                                                                        <label>Received Date:</label> <span id="section_receive_date_id"></span><br>
                                                                        <label>View Received Document:</label>
                                                                        <a id="section_received_view" href="" target="_blank" data-toggle="tooltip"
                                                                           class="btn btn-info btn-sm"
                                                                           data-placement="top" title="View the file"><i
                                                                                class="fas fa-book-open"></i></a><br>

                                                                    </div>

                                                                </div>--}}


                                {{------------------------Start Downloaded-Section Details-------------------}}
                                <div class="card card-info" id="section_received_view_id" hidden>
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Assigned Section Evaluation Response</strong>
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <table class="table table-condensed">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">From</td>
                                            <td class="text-left"><span id="section_receive_from_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">To</td>
                                            <td class="text-left"><span id="section_receive_to_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Received Date</td>
                                            <td class="text-left"><span id="section_receive_date_id"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Received Document</td>
                                            <td class="text-left"><a id="section_received_view" href="" target="_blank"
                                                                     data-toggle="tooltip"
                                                                     class="btn btn-info btn-sm"
                                                                     data-placement="top" title="View the file"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div> {{--end card-info --}}
                                {{------------------------End Downloaded-Section  Details-------------------}}


                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{--  end of Modal for Section Assignment  --}}
            {{--  Modal for Extend Dossier Assesment deadline  --}}
            <div class="modal fade" id="modalextend_section" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form action="{{ route('update_deadline') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Extend Deadline</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name='section_deadline_id' id='section_deadline_id' hidden/>
                                    <input type="text" name='type' value='section' hidden/>
                                    <input type="text" name='hidden_dossier_asg_id'
                                           value='{{$dossier_evaluation_details->dossier_ass_id}}' hidden/>
                                </div>
                                <div class="form-group">
                                    <label>Extension Description</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="extend_reason">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>New Deadline</label>
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




            {{-- MODAL: start upload Dossier Assignment response  --}}
            <div class="modal fade" id="uploadDossierAssignmentResponseModal" data-backdrop="static" tabindex="-1"
                 role="dialog"
                 aria-labelledby="uploadResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Assigned Evaluation Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('upload_assigned_evaluation_response') }}"
                                  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="section_assigned_description">Description</label>
                                    <input name="section_assigned_description" type="text" class="form-control"
                                           id="description">
                                </div>

                                <div class="form-group">
                                    <label for="section_assigned_response_file">Assigned Dossier Section
                                        Evaluation</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="section_assigned_response_file"
                                                   id="section_assigned_response_file"
                                                   class="custom-file-input">
                                            <label class="custom-file-label"
                                                   for="section_assigned_response_file">Choose
                                                file</label>
                                        </div>

                                    </div>
                                </div>

                                <input type="hidden" name="dossier_assignment_id"
                                       value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                                <input type="hidden" name="hidden_section_id" id="hidden_section_id" value=""/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end upload Dossier Assignment response--}}
        </div>
    </div>
    {{-- end list of assigned dossier sections--}}


    {{--start send dossier section--}}
    @can('assessor_roles')


        @if($current_user_id==$dossier_evaluation_details->assessor_id)
            <div class="card card-outline card-success collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Assign Dossier Section</strong></h3>

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

                    <form method="post" action="{{ route('assign_dossier_section') }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">

                            <div class="form-group">
                                <label> Section Description</label>
                                <input type="text" class="form-control"
                                       name="description" required>
                            </div>

                            <div class="form-group">
                                <label for="nmfa_units">Assessor/PERC Members</label>
                                <select class="form-control select2 select2-hidden-accessible"
                                        id='role'  style="width: 100%;" aria-hidden="true"
                                        name="assigned_user" required >
                                    <option></option>
                                    @foreach($perc_users as $role)
                                        <option value="{{$role->id}}">{{$role->first_name}} {{$role->middle_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                          {{--  <div class="form-group" id='assigned_units_staff' hidden>
                                <label for="nmfa_units">Assign To</label>
                                <select class="form-control select2 select2-hidden-accessible"
                                        id='assigned_unit' name='assigned_user' style="width: 100%;" aria-hidden="true"
                                        required>

                                </select>
                            </div>--}}

                            {{-- <div class="form-group">
                                 <label>Evaluation Form:</label>

                                 <select  name="evaluation_form" id="evaluation_form_id" class="form-control" required>
                                 @foreach($evaluation_document as $document)
                                     <option value="{{$document->id}}">{{$document->name}}</option>
                                     @endforeach
                                 </select>
                             </div>--}}

                            <div class="form-group">
                                <label> Due Date</label>
                                <input type="date" class="form-control"
                                       name="date_due" required>
                            </div>

                            <label for="dossier_section">Dossier Section (Attach Zip if multiple)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="dossier_section_file"
                                           id="dossier_section_file"
                                           class="custom-file-input" required>
                                    <label class="custom-file-label"
                                           for="dossier_section_file">Choose file</label>
                                </div>

                            </div>

                        </div>

                        <input type="hidden" name="hidden_dossier_assignment_id"
                               value="{{$dossier_evaluation_details->dossier_ass_id}}"/>
                        <div class="card-footer" style="float:right">
                            @if($main_task->task_status=='Locked' || $main_task->task_status=='Decision')
                                <button class="btn btn-success" role="button" disabled>Assign
                                </button>
                            @else
                                <button class="btn btn-success" role="button">Assign
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        @endif
    @endcan
    {{--end send dossier section--}}
</div>

{{--------------------END DOSSIER TAB ------------------}}
<script>
    function deadline_modal_section(o) {

        document.getElementById('section_deadline_id').value = o.value;

    }

    function section(o) {


        document.getElementById('hidden_section_id').value = o.value;

    }

    function edit_section_response_details(o) {
//         console.log(o);
// alert(o);

        document.getElementById('section_description').value = o.response_description;
        document.getElementById('section_edit_id').value = o.id;

    }

    function details_section(o) {
        let id = o.value;

        var server_ip = document.getElementById('server_ip').value;
        document.getElementById('section_received_view_id').hidden = true;


        $.ajax({

            type: 'GET',

            url: "{{ route('retrieve_details') }}",

            data: {id: id, typ: 'section'},

            success: function (data) {
                //for sending part
                var document_path = data.sent_document.path;
                document.getElementById('section_send_from_id').innerText = data.data['section_from_user_first_name'] + ' ' + data.data['section_from_user_middle_name'];
                document.getElementById('section_send_to_id').innerText = data.data['section_to_user_first_name'] + ' ' + data.data['section_to_user_middle_name'];
                document.getElementById('section_send_date_id').innerText = data.data['section_sent_date'];
                document.getElementById('section_send_deadline_id').innerText = data.data['section_deadline'];
                document.getElementById('section_status').innerText = data.data['status'];
                document.getElementById('section_sent_document_view').href = server_ip + document_path;


                if (data.data.section_received_date == null) {

                } else {
                    var document_path = data.received_document.path;
                    // alert(document_path);
//for receiving part
                    document.getElementById('section_received_view_id').hidden = false;
                    document.getElementById('section_receive_from_id').innerText = data.data['section_to_user_first_name'] + ' ' + data.data['section_to_user_middle_name'];
                    document.getElementById('section_receive_to_id').innerText = data.data['section_from_user_first_name'] + ' ' + data.data['section_from_user_middle_name'];
                    document.getElementById('section_receive_date_id').innerText = data.data['section_received_date'];
                    // var test="\{\{asset\("+document_path +"\)\}\}";
                    document.getElementById('section_received_view').href = server_ip + document_path;
                    // alert( document.getElementById('received_view').href)
                    // alert(data.qc['qc_deadline']);
                    //  alert(data.qc.qc_deadline);
                }
            },
            error: function (data) {
                console.log(data)

            }
        });

    }

    function retrive_staff(o) {
        // alert(o.value)
        var staff_div = document.getElementById('assigned_units_staff')
        let assigned_unit = document.getElementById('assigned_unit');
        staff_div.hidden = false;
        let id = o.value;

        $.ajax({

            type: 'GET',

            url: "{{ route('retrieve_unit_staff') }}",

            data: {id: id},

            success: function (data) {
                //for sending part
                // console.log(data)
                assigned_unit.innerHTML = data.response;

            },
            error: function (data) {
                console.log(data)

            }
        });

    }
</script>
