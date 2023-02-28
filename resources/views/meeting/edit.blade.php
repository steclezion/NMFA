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
                            <h3 class="card-title"><strong>Meeting Details</strong>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="POST" action="{{ route('update_meeting') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6">

                                        <input type="hidden" name="meeting_id" value="{{$meeting->id}}"/>
                                        <div class="card card-outline card-blue">
                                            <div class="card-header">
                                                <h3 class="card-title"><strong>Meeting Details</strong>
                                                </h3>
                                            </div>


                                            <table class="table table-condensed table-borderless">
                                                <tbody>
                                                @if($meeting->postponed==1)

                                                    <tr>
                                                        <td class="text-muted" width="40%">Initial Meeting Date</td>
                                                        <td class="text-left">{{$meeting->meeting_date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Initial Meeting Time</td>
                                                        <td class="text-left">{{$meeting->time}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-muted">Postponed Date</td>
                                                        <td class="text-left"><span
                                                                    class="badge badge-success"> {{$meeting->postponed_date}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Postponed Time</td>
                                                        <td class="text-left"><span
                                                                    class="badge badge-success"> {{$meeting->postponed_time}} </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Postponement Reason</td>
                                                        <td class="text-left">{{$meeting->postponed_reason}}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-muted">Meeting Date</td>
                                                        <td class="text-left">{{$meeting->meeting_date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Time</td>
                                                        <td class="text-left">{{$meeting->time}}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="text-muted">Venue</td>
                                                    <td class="text-left">   {{$meeting->venue}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Description</td>
                                                    <td class="text-left">{{$meeting->description}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Meeting Type</td>
                                                    <td class="text-left"><span
                                                                class="badge badge-success">{{$meeting->type}}</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>


                                        </div>
                                    </div>


                                    <div class="col-12 col-sm-6">

                                        <div class="card card-outline card-blue">
                                            <div class="card-header">
                                                <h3 class="card-title"><strong>Meeting Minutes</strong>
                                                </h3>
                                            </div>

                                            <div class="mr-4 ml-4 mt-2 mb-2">

                                                @if(!isset($meeting->minutes_id))

                                                    <div class="form-group">
                                                        <label class="text-muted" for="query_response_cover_letter">Upload
                                                            Minutes</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="minutes"
                                                                       id="minutes"
                                                                       class="custom-file-input"
                                                                       onchange="CheckPDFFile(this,'send_query','send_document_id')"
                                                                       required>
                                                                <label class="custom-file-label"
                                                                       for="query_response_cover_letter">Choose
                                                                    file</label>
                                                            </div>

                                                        </div>

                                                        <span class="text text-danger" id="send_document_id"></span>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="text-muted">Participants</label>
                                                        <div class="select2-red">
                                                            <select class="select2" name='participants[]'
                                                                    multiple="multiple"
                                                                    data-placeholder="Select a participants"
                                                                    data-dropdown-css-class="select2-red"
                                                                    style="width: 100%;"
                                                                    required>

                                                                @foreach($percs as $perc)
                                                                    <option value="{{$perc->id}}">{{$perc->first_name}} {{$perc->middle_name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                @else

                                                    <div class="form-group">
                                                        <label for="query_response_cover_letter" class="text-muted">Minutes</label>
                                                        <a href="{{asset($meeting->path)}}" type="button"
                                                           target="_blank"
                                                           title="View the document" class="btn btn-info btn-sm"><i
                                                                    class="fas fa-eye">
                                                                <span style="font-family: Arial, Helvetica, sans-serif">View</span></i></a>

                                                        <span class="text text-danger" id="send_document_id"></span>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="text-muted">Participants</label>
                                                        <div class="select2-red">
                                                            <select class="select2" name='participants[]'
                                                                    multiple="multiple"
                                                                    data-placeholder="Select a participants"
                                                                    data-dropdown-css-class="select2-red"
                                                                    style="width: 100%;"
                                                                    disabled>
                                                                <option>All</option>
                                                                @foreach($percs as $perc)
                                                                    <option selected
                                                                            value="{{$perc->id}}">{{$perc->first_name}} {{$perc->middle_name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                @endif
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </div> {{--end right card--}}
                                </div> {{--row2 --}}
                                <div class="form-group">
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{route('meeting_index')}}" class="btn btn-secondary"><i
                                                    class="fas fa-arrow-circle-left"></i> Back </a>

                                        @if(!isset($meeting->minutes_id))
                                            @if($meeting->postponed!=1)
                                                <button type="button" class="btn btn-warning" title="Postpone meeting"
                                                        data-toggle="modal" data-target="#postpone_meeting" onclick=""
                                                        value="">
                                                    <i class="fas fa-clock"></i> Postpone meeting
                                                </button>
                                            @endif


                                            <button type="submit" class="btn btn-primary"><i
                                                        class="fas fa-upload"></i> Upload
                                            </button>
                                        @endif
                                    </div>
                                </div>

                            </form>
                        </div>


                        @if(isset($meeting->minutes_id) and $meeting->type=='Decision_Meeting')
                            <div id="example1_wrapper"
                                 class="dataTables_wrapper dt-bootstrap4
                                no-footer">
                                <table
                                        class="table table-bordered table-striped
                                    dataTable no-footer dtr-inline"
                                        role="grid"
                                        aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received">Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Reference Number:activate to sort column descending"
                                            aria-sort="ascending" width="20%"> Applicant Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Assessor Name
                                        </th>
                                        <th rowspan="1" colspan="1" width="10%">Decision</th>
                                        <th rowspan="1" colspan="1"
                                            width="15%" data-toggle="tooltip" data-placement="top"   data-html="true"
                                            title="You may modify the decision within 3 days, provided Sealed Documents are NOT sent to Applicant.
                                            Otherwise the decision buttons will be disabled permanently and a <b>Decision Finalized</b> status is displayed.">

                                            Time Elapsed After Decision <i class="fa fa-info-circle"></i>
                                        </th>

                                        <th rowspan="1" colspan="1" width="25%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($decisions as $evaluation)
                                        <tr role="row" class="odd" id='{{$evaluation->id}}'>
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->product_trade_name}} </td>
                                            <td>{{$evaluation->trade_name}}</td>
                                            <td>{{$evaluation->first_name}}
                                                {{$evaluation->middle_name}}</td>
                                            <td>
                                                @if($evaluation->decision_status=='Accepted')
                                                    <span class="badge badge-success">{{$evaluation->decision_status}}</span>
                                                @elseif($evaluation->decision_status=='Deferred')
                                                    <span class="badge badge-warning">{{$evaluation->decision_status}}</span>
                                                @elseif($evaluation->decision_status=='Rejected')
                                                    <span class="badge badge-danger">{{$evaluation->decision_status}}</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Yet Decided</span>
                                                @endif

                                            </td>


                                            <?php
                                            /** @var TYPE_NAME $query */
                                            /** @var TYPE_NAME $evaluation */
                                            $now = \Carbon\Carbon::now();
                                            if ($evaluation->decision_date != null) {
                                                $decision_date = \Carbon\Carbon::create($evaluation->decision_date);
                                                $diffInHours = $decision_date->diffInHours($now, false);
                                                $diff = $decision_date->diff($now)->format('%dd:%Hhr:%I:%S');
                                            } else {
                                                $diffInHours = -1;  // decision not taken
                                            }
                                            ?>


                                            <td id='days_counter_id'>
                                                @if($evaluation->decision_status==null)

                                                    <span class="badge badge-secondary">Not Yet Decided</span>
                                                @elseif($evaluation->sealed_document_id != null)
                                                    <span class="badge badge-danger">Decision Finalized</span>
                                                @elseif($diffInHours >= 0 and $diffInHours < 72)
                                                    <span class="badge badge-info">{{$diff}}</span>
                                                @else
                                                    <span class="badge badge-danger">Decision Finalized</span>
                                                @endif
                                            </td>
                                            <td>


                                                @if($evaluation->decision_status==null or  ($diffInHours >= 0 and $diffInHours < 72))
                                                    @if($evaluation->sealed_document_id == null)

                                                        <button type="button" class="btn btn-success btn-sm"
                                                                title="Update Decision as Accepted" data-toggle="modal"
                                                                data-target="#modal_accept"
                                                                onclick="decision(this,'accept','Dossier Evaluation')"
                                                                value="{{$evaluation->id}}"> Accept
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                                title="Update Decision as Deferred" data-toggle="modal"
                                                                data-target="#modal_defer"
                                                                onclick="decision(this,'defer','Dossier Evaluation')"
                                                                value="{{$evaluation->id}}"> Defer
                                                        </button>

                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                title="Update Decision as Rejected" data-toggle="modal"
                                                                data-target="#modal_reject"
                                                                onclick="decision(this,'reject','Dossier Evaluation')"
                                                                value="{{$evaluation->id}}"> Reject
                                                        </button>
                                                    @else {{--since sealed documents are already sent to applicant
                                                    disable decision buttons--}}

                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                          data-placement="top"
                                                          title="Sealed Documents Already Sent.">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            style="pointer-events: none;" disabled> Accept
                                                    </button></span>
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                          data-placement="bottom"
                                                          title="Sealed Documents Already Sent.">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            style="pointer-events: none;" disabled> Defer
                                                    </button></span>
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                          data-placement="top"
                                                          title="Sealed Documents Already Sent.">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            style="pointer-events: none;" disabled> Reject
                                                    </button></span>
                                                    @endif

                                                @else
                                                    <span class="d-inline-block" tabindex="0"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Decision Already Taken and Modification of Decision Expired.">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            style="pointer-events: none;" disabled> Accept
                                                    </button></span>
                                                    <span class="d-inline-block" tabindex="0"
                                                          data-toggle="tooltip" data-placement="bottom"
                                                          title="Decision Already Taken and Modification of Decision Expired.">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            style="pointer-events: none;" disabled> Defer
                                                    </button></span>
                                                    <span class="d-inline-block" tabindex="0"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Decision Already Taken and Modification of Decision Expired.">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            style="pointer-events: none;" disabled> Reject
                                                    </button></span>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        @elseif(isset($meeting->minutes_id) and $meeting->type=='Other_Meeting')

                            <div id="example1_wrapper"
                                 class="dataTables_wrapper dt-bootstrap4
                                no-footer">
                                <table
                                        class="table table-bordered table-striped
                                    dataTable no-footer dtr-inline"
                                        role="grid"
                                        aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Title: activate to sort column ascending"
                                            width="20%" id="received">Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Reference Number:activate to sort column descending"
                                            aria-sort="ascending" width="20%"> Company
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Reference Number:activate to sort column descending"
                                            aria-sort="ascending" width="10%"> Type
                                        </th>
                                        <th rowspan="1" colspan="1" width="10%">Decision</th>
                                        <th rowspan="1" colspan="1"
                                            width="15%" data-toggle="tooltip" data-placement="top" data-html="true"
                                            title="You may modify the decision within 3 days, provided Sealed Documents are NOT sent to Applicant.
                                            Otherwise the decision buttons will be disabled permanently and a <b>Decision Finalized</b> status is displayed.">
                                            Time Elapsed After Decision <i class="fa fa-info-circle"></i>
                                        </th>
                                        <th rowspan="1" colspan="1" width="25%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($variation_decisions as $evaluation)
                                        <tr role="row" class="odd" id='{{$evaluation->id}}'>
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->product_trade_name}} </td>
                                            <td>{{$evaluation->trade_name}}</td>
                                            <td><span class="badge badge-danger">Variation</span></td>
                                            <td>
                                                @if($evaluation->decision_status=='Accepted')
                                                    <span class="badge badge-success">{{$evaluation->decision_status}}</span>

                                                @elseif($evaluation->decision_status=='Rejected')
                                                    <span class="badge badge-danger">{{$evaluation->decision_status}}</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Yet Decided</span>
                                                @endif

                                            </td>


                                            <?php
                                            /** @var TYPE_NAME $query */
                                            /** @var TYPE_NAME $evaluation */
                                            $now = \Carbon\Carbon::now();
                                            if ($evaluation->decision_date != null) {
                                                $decision_date = \Carbon\Carbon::create($evaluation->decision_date);
                                                $diffInHours = $decision_date->diffInHours($now, false);
                                                $diff = $decision_date->diff($now)->format('%dd:%Hhr:%I:%S');
                                            } else {
                                                $diffInHours = -1;  // decision not taken
                                            }
                                            ?>


                                            <td id='days_counter_id'>
                                                @if($evaluation->decision_status==null)

                                                    <span class="badge badge-secondary">Not Yet Decided</span>
                                                @elseif($diffInHours >= 0 and $diffInHours < 72)
                                                    <span class="badge badge-info">{{$diff}}</span>
                                                @else
                                                    <span class="badge badge-warning">Decision Finalized</span>
                                                @endif
                                            </td>
                                            <td>


                                                @if($evaluation->decision_status==null or  ($diffInHours >= 0 and $diffInHours < 72))
                                                    @if($evaluation->sealed_document_id == null)
                                                        <button type="button" class="btn btn-success btn-sm"
                                                                title="Update Decision as Accepted" data-toggle="modal"
                                                                data-target="#modal_accept"
                                                                onclick="decision(this,'accept','variation')"
                                                                value="{{$evaluation->id}}"> Accept
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                title="Update Decision as Rejected" data-toggle="modal"
                                                                data-target="#modal_reject"
                                                                onclick="decision(this,'reject','variation')"
                                                                value="{{$evaluation->id}}"> Reject
                                                        </button>
                                                    @else

                                                        <button type="button" class="btn btn-success btn-sm"
                                                                title="Sealed Documents Already Sent."
                                                                disabled> Accept
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                title="Sealed Documents Already Sent."
                                                                disabled> Reject
                                                        </button>
                                                    @endif

                                                @else
                                                    <span class="d-inline-block" tabindex="0"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Decision Already Taken and Modification of Decision Expired.">
                                        <button type="button" class="btn btn-success btn-sm"
                                                style="pointer-events: none;" disabled> Accept
                                        </button></span>

                                                    <span class="d-inline-block" tabindex="0"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Decision Already Taken and Modification of Decision Expired.">
                                        <button type="button" class="btn btn-danger btn-sm"
                                                style="pointer-events: none;" disabled> Reject
                                        </button></span>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>

                        @endif

                    </div>
                    <!-- /.card -->
                    {{-- MODAL for postpone meeting--}}

                    <div class="modal fade" id="postpone_meeting" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="deleteRecordModal" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">

                            <form action="{{ route('postpone_meeting')}}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"> Postpone Meeting</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Reason For postponement</label><textarea type="text" class="form-control"
                                                                                        name='postpone_reason' required>
                                                                      </textarea>
                                        <label>Postponed date</label><input type="date" class="form-control"
                                                                            name='postpone_date' required><br>
                                        <label>Postponed time</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                   data-target="#timepicker" name="postpone_time" required>
                                            <div class="input-group-append" data-target="#timepicker"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{$meeting->id}}" name="meeting_id"/>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn bg-secondary" data-dismiss="modal">Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success">Update Meeting Schedule

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- End of modal postpone meeting--}}

                </div>
            </div>
        </div>
        </div>

    </section>
@endsection
@section('scripts')

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        function decision(o, decision, type) {

            var type = type;
            var decision = decision;
            var id = o.value;

            let type_real = '';

            if (decision == 'accept')
                type_real = 'Acceptance';
            else if (decision == 'defer')
                type_real = 'Deferral';
            else if (decision == 'reject')
                type_real = 'Rejection';

            var response = confirm("Confirm " + type_real + " of the product " + document.getElementById(o.value).cells[1].innerHTML);

            if (response == true) {

                $.ajax({

                    type: 'GET',

                    url: "{{ route('product_decision') }}",

                    data: {id: id, decision: decision, type: type},

                    success: function (data) {
                        console.log(data)
                        if (decision == 'accept') {
                            var badg = 'badge-success';
                        } else if (decision == 'defer') {
                            var badg = 'badge-warning';
                        } else {
                            var badg = 'badge-danger';
                        }

                        if (data.edit_decision_hrs_counter >= 0 && data.edit_decision_hrs_counter <= 72) {
                            var counter = '<span class="badge badge-info"> ' + data.diff_displayed+'</span>';
                        } else {
                            var counter = '<span class="badge badge-warning">Decision Finalized</span>';

                        }

                        document.getElementById(o.value).cells[4].innerHTML = ' <span class="badge ' + badg + '">' + data.decision.decision_status + '</span>'
                        document.getElementById(o.value).cells[5].innerHTML = counter;

                    }

                });

            } else {
                return false;
            }
        }


        $(function () {
            bsCustomFileInput.init();
        });

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
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        $('#summernote').summernote()
        $('#summernote1').summernote()
        $('#summernote2').summernote()

        function date_count(obj) {
            alert(obj.value);
        }
    </script>
@endsection