@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Deferred Application Details</strong>
                            </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-sm-6">

                                    {{------------------------------------start Decision Details-------------------}}
                                    <table class="table table-condensed table-borderless">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="30%">Application Number</td>
                                            <td class="text-left">
                                                {{$decision->application_number}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted" width="30%">Decision Date</td>
                                            <td class="text-left">
                                                {{$decision->meeting_date}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Decision Time</td>
                                            <td class="text-left">
                                                {{$decision->time}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Venue</td>
                                            <td class="text-left">
                                                {{$decision->venue}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Decision</td>
                                            <td class="text-left">
                                                <span class="badge badge-warning">{{$decision->decision_status}}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">Minutes Desc.</td>
                                            <td class="text-left">
                                                {{$decision->description}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Meeting Minutes</td>
                                            <td class="text-left">
                                                <a href="{{asset($decision->minute_path)}}" type="button" target="_blank"
                                                   title="View the document" class="btn btn-info btn-sm"><i
                                                            class="fas fa-eye "></i> View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Meeting Attendees</td>
                                            <td class="text-left">
                                                <div class="select2-green">
                                                    <select class="select2" name='participants' multiple="multiple" disabled
                                                            data-dropdown-css-class="select2-purple" style="width: 90%;"
                                                            onchange="test(this)">
                                                        @foreach($participants as $perc)
                                                            <option value="{{$perc->id}}"
                                                                    selected>{{$perc->first_name}} {{$perc->middle_name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    {{------------------------------------ // End Decision Details-------------------}}

                                    {{--<div class="form-group">
                                        <label> Decision Date :</label>

                                        {{$decision->meeting_date}}

                                    </div>
                                    <div class="form-group">
                                        <label> Venue:</label>
                                        {{$decision->venue}}
                                    </div>
                                    <div class="form-group">
                                        <label> Time:</label>
                                        {{$decision->time}}
                                    </div>
                                    <div class="form-group">
                                        <label> Minute Details:</label>
                                        {{$decision->description}}
                                    </div>
                                    <div class="form-group">
                                        <label> Decision:</label>

                                        <span class="badge badge-warning">{{$decision->decision_status}}</span>

                                    </div>
                                    <div class="form-group">
                                        <label for="query_response_cover_letter"> View Minutes: </label>
                                        <a href="{{asset($decision->minute_path)}}" type="button" target="_blank"
                                           title="View the document" class="btn btn-info btn-sm"><i
                                                    class="fas fa-eye "></i></a>

                                    </div>

                                    <div class="form-group">
                                        <label>Meeting Attendees</label>
                                        <div class="select2-purple">
                                            <select class="select2" name='participants' multiple="multiple" disabled
                                                    data-placeholder="Select a State"
                                                    data-dropdown-css-class="select2-purple" style="width: 100%;"
                                                    onchange="test(this)">
                                                @foreach($participants as $perc)
                                                    <option value="{{$perc->id}}"
                                                            selected>{{$perc->first_name}} {{$perc->middle_name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    --}}

                                </div>


                                <div class="col-12 col-sm-6">


                                    <table class="table table-condensed table-borderless">
                                        <tbody>
                                        {{------------------------------------start Acceptance Letter-------------------}}
                                        <tr>
                                            <td class="text-muted" width="35%">Deferral Letter</td>
                                            <td class="text-left">
                                                @if(!isset($decision->sealed_document_id))
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            title="Generate Deferral Letter"
                                                            data-toggle="modal"
                                                            data-target="#modal_defer"
                                                            onclick="information_retriver_ajax(this,'defer')"
                                                            value="{{$decision->id}}"><i class="fa fa-file"></i>
                                                        Generate
                                                    </button>
                                                @else
                                                    <button title="Documents Already Sent."
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fa fa-file"></i> Generate
                                                    </button>
                                                @endif

                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">Generated Deferral Letter</td>
                                            <td class="text-left">
                                                @if(isset($decision->downloaded_document_id))
                                                    <a href="{{asset($decision->downloaded_document_path)}}"
                                                       type="button" target="_blank"
                                                       title="View and Download Deferral Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download</a>
                                                @else
                                                    <button title="Please Generate the Letter First"
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i> View and Download
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        {{------------------------------------//end Defferal Letter-------------------}}

                                        <tr>
                                            <td colspan="2">
                                                <hr/>
                                            </td>
                                        </tr>

                                        {{------------------------------------start  Sealed Documents-------------------}}
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> Deferral Letter</td>
                                            <td class="text-left">
                                                @if(!isset($decision->sealed_document_id) & isset($decision->downloaded_document_id))
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            title="Send Deferral Letter" data-toggle="modal"
                                                            data-target="#SendDefermentLetterModal"
                                                            value="{{$decision->id}}"><i class="fas fa-upload"></i>
                                                        Send Sealed
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Document Already Sent" disabled><i
                                                                class="fas fa-upload"></i>
                                                        Send Sealed
                                                    </button>
                                                @endif

                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Deferral Letter</td>
                                            <td class="text-left">
                                                @if(isset($decision->sealed_document_id))
                                                    <a id="received_view"
                                                       href="{{asset($decision->sealed_document_path)}}" target="_blank"
                                                       class="btn btn-success btn-sm"
                                                       data-placement="top"
                                                       title="View and Download Sealed Deferral Letter"><i
                                                                class="fas fa-download"></i> View and Download</a>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Document Not Yet Sent" disabled>
                                                        View
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($decision->attachments==1)
                                            <tr>
                                                <td class="text-muted">Attachments</td>
                                                <td class="text-left">
                                                    <a href="{{asset($attachment->path)}}" data-toggle="tooltip"
                                                       class="btn btn-success btn-sm" data-placement="top"
                                                       title="Download the Attachment"><i
                                                                class="fas fa-paperclip"></i> Download</a>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <!-- /.col -->

                        <div class="form-group">
                            <div class="modal-footer justify-content-between">
                                <a href="{{route('decision_index')}}" class="btn btn-secondary"><i
                                            class="fas fa-arrow-circle-left"></i> Back </a>
                                @if($decision->sealed_document_id!=null and $all_queries_reponsed and $decision->locked==0)

                                    <button type="button" class="btn btn-primary" title="Return Product Dossier Back to Assessor"
                                            data-toggle="modal"
                                            data-target="#AssessorReturnModal">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        Return to Assessor: {{ $assessor->first_name}} {{$assessor->middle_name}}
                                    </button>
                                @elseif (!$all_queries_reponsed)
                                    <button type="button" class="btn btn-secondary"
                                            title="Response of issued query pending" data-toggle="modal"
                                            data-target="#AssessorReturnModal" disabled>
                                        <i class="fas fa-arrow-circle-left"></i>
                                        Return to Assessor
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary"
                                            title="Response of issued query pending" data-toggle="modal"
                                            data-target="#AssessorReturnModal" disabled>
                                        <i class="fas fa-arrow-circle-left"></i>
                                        Return to Assessor
                                    </button>
                                @endif

                            </div>
                        </div>
                        <!-- /.card -->


                    </div>
                </div>
            </div>

            {{-----------  Deffer Modal ----------------------}}
            @include('decisions.differ_modal')
            {{--------------------END Modal  ------------------}}

            @if($decision->sealed_document_id!=null)
                <div class="card card-outline card-success ">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Deferral Decision</strong>
                        </h3>

                        <div class="card-tools">
                            @if($all_queries_reponsed and $decision->locked==0)
                                <button type="button" class="btn btn-warning btn-sm" title="Send Deferral Letter"
                                        data-toggle="modal"
                                        data-target="#queryModal"><i class="fas fa-plus"></i>
                                    New Query
                                </button>
                            @elseif($all_queries_reponsed)
                                <button type="button" class="btn btn-secondary btn-sm"
                                        title="Response Already Sent." data-toggle="modal"
                                        data-target="#queryModal" disabled><i class="fas fa-plus"></i>
                                    New Query
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm"
                                        title="Response of issued query pending." data-toggle="modal"
                                        data-target="#queryModal" disabled><i class="fas fa-plus"></i>
                                    New Query
                                </button>
                        @endif
                        <!-- <button type="button" class="btn btn-tool"
                        data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button> -->
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                            <table id="example1"
                                   class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                   role="grid" aria-describedby="example1_info">

                                <thead>
                                <tr role="row">
                                    <th class="sorting sorting_asc" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Serial Number: activate to sort column descending"
                                        aria-sort="ascending" width="5%"> S.N
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Serial Number: activate to sort column descending"
                                        aria-sort="ascending" width="25%"> Subject
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Reference Number: activate to sort column descending"
                                        aria-sort="ascending" width="25%">Deferral Sent Date
                                    </th>
                                    <th class="sorting" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Title: activate to sort column ascending"
                                        width="20%"> Response Deadline
                                    </th>
                                    <th class="sorting" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Title: activate to sort column ascending"
                                        width="20%">Response Received On
                                    </th>

                                    <th rowspan="1" colspan="1" width="15%">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($deferment_queries as $deferment_query)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$deferment_query->sent_subject}}</td>
                                        <td>{{$deferment_query->sent_date}}</td>
                                        <td>{{$deferment_query->deadline}}</td>
                                        <td>{{$deferment_query->received_date}}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#modal_query_details" onclick="details_query(this)"
                                                    value='{{ $deferment_query->id }}'>
                                                <i class="fas fa-list"></i>
                                            </button>
                                            @if($deferment_query->deadline_requested)
                                            <button type="button" class="btn btn-primary btn-sm" title="Extend Deadline"
                                                    data-toggle="modal"
                                                    data-target="#modalextend_query"
                                                    onclick="deadline_modal_query(this)" value="{{ $deferment_query->id }}">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        @else

                                                <button type="button" class="btn btn-secondary btn-sm" title="Not Applicable"
                                                      disabled>
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            @endif

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>



                {{--  Modal modal_query_details  --}}
                <div class="modal fade" id="modal_query_details" data-backdrop="static" tabindex="-1" role="dialog"
                     aria-labelledby="modalextend" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">


                        <form action="{{ route('update_deadline') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Decision Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{------------------------Start Issued Query Details-------------------}}
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title"><strong>Details</strong></h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <!-- form start -->

                                        <table id="example2" class="table table-condensed responsive-table">
                                            <tbody>
                                            <tr>
                                                <td class="text-muted" width="23%">Decision Status</td>
                                                <td class="text-left"><span class="badge bg-success" id="status"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted" width="23%">Subject</td>
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
                                                <td class="text-muted">Sent Date</td>
                                                <td class="text-left"><span id="query_send_date"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Received Date</td>
                                                <td class="text-left"><span id="query_received_date"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Decision details</td>
                                                <td class="text-left"><span id="sent_query"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Decision Letter</td>
                                                <td class="text-left"><a id="query_sent_document_view" href=""
                                                                         target="_blank" data-toggle="tooltip"
                                                                         class="btn btn-info btn-sm"
                                                                         data-placement="top" title="View the file"><i
                                                                class="fas fa-book-open"></i> View </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Response Subject</td>
                                                <td class="text-left"><span id="received_query"></span></td>

                                            </tr>
                                            <tr>
                                                <td class="text-muted">Response Document</td>
                                                <td class="text-left"><a id="query_received_document_view" href=""
                                                                         target="_blank" data-toggle="tooltip"
                                                                         class="btn btn-info btn-sm"
                                                                         data-placement="top" title="View the file"><i
                                                                class="fas fa-book-open"></i> View </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div> {{--end card-info --}}
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
                                        <input type="text" name='deferment_query_id' id='deferment_query_id' hidden/>
                                        <input type="text" name='type' value='deferment_query' hidden/>
                                        <input type="text" name='hidden_dossier_asg_id'
                                               value='{{$decision->dossier_assignment_id}}' hidden/>

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



            @endif


            @endsection
            @section('scripts')

                <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
                <script>

                    function deadline_modal_query(o) {

                        document.getElementById('deferment_query_id').value = o.value;
                    }



                    $(function () {
                        bsCustomFileInput.init();
                    });

                    function appeal_status(o) {
                        if (o.checked == true) {
                            document.getElementById('show_appeal_div').hidden = false;

                        } else {
                            document.getElementById('show_appeal_div').hidden = true;
                        }
                    }

                    function radio_checker(val) {
                        if (val.value == 1) {
                            document.getElementById('accepted_document_div').hidden = false;
                            document.getElementById('accepted_document_id').required = true;
                            document.getElementById('rejected_document_div').hidden = true;
                            document.getElementById('rejected_document_id').required = false;
                        } else {
                            document.getElementById('accepted_document_div').hidden = true;
                            document.getElementById('accepted_document_id').required = false;
                            document.getElementById('rejected_document_div').hidden = false;
                            document.getElementById('rejected_document_id').required = true;
                        }
                    }


                    function test(o) {
                        console.log(o);
                    }

                    $(function () {
                        //Initialize Select2 Elements
                        $('.select2').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })


                    })


                    $('#summernote').summernote()
                    $('#summernote1').summernote()

                    function date_count(obj) {
                        var current_date = new Date();
                        var deadline_date = new Date(obj.value);
                        var total_time_in_days = Math.round((deadline_date.getTime() - current_date.getTime()) / (1000 * 3600 * 24))
                        document.getElementById('datys_count').innerHTML = total_time_in_days;
                    }


                    function information_retriver_ajax(o, type) {
                        var id = o.value;
                        var decision_type = 'Dossier Evaluation';

                        document.getElementById(type + '_decision_id').value = id;
                        $.ajax({

                            type: 'GET',

                            url: "{{ route('information_retriver_ajax') }}",

                            data: {
                                id: id,
                                decision_type:decision_type
                            },

                            success: function (data) {
                                console.log(data)

                                if(data.data.application_type == 1){

                                    document.getElementById(type + '_applicaion_details').innerHTML =
                                        data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', Standard Mode'
                                }else{

                                    document.getElementById(type + '_applicaion_details').innerHTML =
                                        data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', Fast track - ' + data.data.fast_track_details

                                }

                                document.getElementById(type + '_company_name').innerHTML = data.data.company_name
                                document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one
                                document.getElementById(type + '_region').innerHTML = data.data.state
                                document.getElementById(type + '_country').innerHTML = data.data.country_name;
                                document.getElementById(type + '_reference_number').innerHTML = data.reference_letter;

                                document.getElementById(type + '_full_name').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'


                                document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name

                                document.getElementById(type + '_applicaion_details').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'


                                document.getElementById(type + '_dated').innerHTML = data.created_at;
                                document.getElementById(type + '_date').innerHTML = data.date;


                            }

                        });
                    }


                    function details_query(o) {
                        let id = o.value;

                        var server_ip = document.getElementById('server_ip').value;


                        $.ajax({

                            type: 'GET',

                            url: "{{ route('query_details') }}",

                            data: {id: id},

                            success: function (data) {

                                //for sending part

                                document.getElementById('superviosr_subject').innerText = data.data['sent_subject'];
                                document.getElementById('supervisor_name').innerText = data.data['supervisor_first_name'] + ' ' + data.data['supervisor_middle_name'];
                                document.getElementById('applicant_name').innerText = data.data['company_name'];
                                document.getElementById('query_send_date').innerText = data.data['sent_date'];
                                document.getElementById('query_received_date').innerText = data.data['received_date'];
                                document.getElementById('sent_query').innerText = data.data['sent_query'];
                                document.getElementById('status').innerText = data.data['status'];
                                document.getElementById('status').AddClass = "badge badge-success";
                                document.getElementById('received_query').innerText = data.data['received_subject'];


                                if(data.sent_document.path) {
                                    var document_path = data.sent_document.path;

                                    document.getElementById('query_sent_document_view').hidden = false;
                                    document.getElementById('query_sent_document_view').href = server_ip + document_path;
                                }
                                else{

                                    document.getElementById('query_sent_document_view').hidden = true;
                                }



                                if (data.received_document == null) {

                                    document.getElementById('query_received_document_view').hidden = true;

                                } else {

                                    document.getElementById('query_received_document_view').hidden = false

                                    if( data.received_document.path) {


                                        var document_path = data.received_document.path;

                                        document.getElementById('query_received_document_view').hidden = false;
                                        document.getElementById('query_received_document_view').href = server_ip + document_path;
                                    }
                                    else{
                                        document.getElementById('query_received_document_view').disabled = true;

                                    }
                                }
                            },
                            error: function (data) {
                                console.log(data)

                            }
                        });

                    }


                </script>
@endsection
