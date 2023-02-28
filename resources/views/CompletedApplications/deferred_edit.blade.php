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
                            <h3 class="card-title"><strong>Product Decision Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <form method="POST" action="{{ route('return_deferment_to_assessor') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 col-sm-6">

                                        {{------------------------------------start Decision Details-------------------}}
                                        <table class="table table-condensed table-borderless">
                                            <tbody>
                                            <tr>
                                                <td class="text-muted" width="30%">Application Number</td>
                                                <td class="text-left">
                                                    {{$application_details->application_number}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted" width="30%">Product Name</td>
                                                <td class="text-left">
                                                    {{$application_details->product_trade_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Applicant Name</td>
                                                <td class="text-left">
                                                    {{$application_details->company_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Route Administration</td>
                                                <td class="text-left">
                                                    {{$application_details->route_administration_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Application Start</td>
                                                <td class="text-left">
                                                    <span class="badge badge-success">{{$application_details->app_created_at}}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-muted">Decision Date </td>
                                                <td class="text-left">
                                                    {{$decision->decision_date}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Decision Status:</td>
                                                <td class="text-left">
                                                    @if($decision->decision_status=='Accepted')
                                                        <span class="badge badge-success"> {{$decision->decision_status}}</span>
                                                    @elseif($decision->decision_status=='Rejected')
                                                        <span class="badge badge-danger"> {{$decision->decision_status}}</span>
                                                    @elseif($decision->decision_status=='Deferred')
                                                        <span class="badge badge-warning"> {{$decision->decision_status}}</span>
                                                    @else
                                                        <span class="badge badge-secondary"> Decision Not Given</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        {{------------------------------------ // End Decision Details-------------------}}

                                    </div>


                                    <div class="col-12 col-sm-6">

                                        <table class="table table-condensed table-borderless">
                                            <tbody>



                                            <tr>
                                                <td class="text-muted"><strong>Sealed</strong> Decision Letter</td>

                                                @if(isset($decision->sealed_document_id))
                                                <td class="text-left">
                                                        <a href="{{asset($decision->downloaded_document_path)}}" type="button"
                                                           target="_blank" title="View and Download Deferral Letter"
                                                           class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                            View and Download</a>

                                                </td>
                                                @endif
                                            </tr>


                                            @if($decision->attachments==1)
                                                <tr>
                                                    <td class="text-muted">View Attachments</td>
                                                    <td class="text-left">
                                                        <a href="{{asset($attachment->path)}}" data-toggle="tooltip"
                                                           class="btn btn-success btn-sm" data-placement="top"
                                                           title="Download the Attachment"><i class="fas fa-paperclip"></i>
                                                            Download
                                                        </a>
                                                    </td>
                                                </tr>


                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-footer justify-content-between">
                                            <a href="{{route('applicant_decision_index')}}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-circle-left"></i> Back </a>


                                        </div>
                                    </div>


                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

        {{-----------  Accept Modal ----------------------}}
        @include('decisions.query_response_modal')
        {{--------------------END Modal  ------------------}}

        @if($decision->sealed_document_id!=null and $decision->decision_status=="Deferred")
            <div class="card card-outline card-success ">
                <div class="card-header">
                    <h3 class="card-title"><strong>Deferral Queries</strong>
                    </h3>

                    <div class="card-tools">
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
                                    aria-sort="ascending" width="5%"> No.
                                </th>
                                <th class="sorting sorting_asc" tabindex="0"
                                    aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Serial Number: activate to sort column descending"
                                    aria-sort="ascending" width="20%"> Subject
                                </th>
                                <th class="sorting sorting_asc" tabindex="0"
                                    aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Reference Number: activate to sort column descending"
                                    aria-sort="ascending" width="20%">Date of Reception
                                </th>
                                <th class="sorting" tabindex="0"
                                    aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Title: activate to sort column ascending"
                                    width="20%"> Response Deadline
                                </th>
                                <th class="sorting" tabindex="0"
                                    aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Title: activate to sort column ascending"
                                    width="20%">Response Sent On
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

                                        @if($deferment_query->received_date==null)
                                        <button type="button" class="btn btn-warning btn-sm"
                                                title="Send Response Letter" onclick="decision_id_assignment(this)"
                                                value="{{$deferment_query->id}}" data-toggle="modal"
                                                data-target="#queryResponseModal"><i class="fas fa-edit"></i>
                                        </button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    title="Request for Deadline Extension"
                                                    data-toggle="modal" data-target="#dedline_extension"
                                                    onclick="extend_deadline({{$deferment_query->id}})"
                                                    value="">
                                                <i class='fas fa-clock'></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                    title="Query Response Already Sent." disabled>
                                                <i class='fas fa-clock'></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                    title="Query Response Already Sent." disabled
                                            ><i class="fas fa-edit"></i>
                                            </button>
                                        @endif

                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#modal_query_details" onclick="details_query(this)"
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

            {{-- Modal for deadline extension request --}}

            <div class="modal fade" id="dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="deleteRecordModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form action="{{ route('query_deferment_deadline_extension')}}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Request For Deadline
                                    Extension</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label>Reason For Extension</label><input type="text" class="form-control"
                                                                          name='extension_reason' required><br>
                                <label>Required Deadline</label><input type="date" class="form-control"
                                                                       name='extended_deadline' required><br>


                            </div>

                            <input type="hidden" id="query_deferment_id" name="query_deferment_id"/>
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


            {{-- End of modeal extension request--}}

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
                                <input type="hidden" value="{{$decision->id}}" name="decision_id">
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
                                            <td class="text-muted" width="23%">Query Status</td>
                                            <td class="text-left"><span class="badge bg-success" id="status"></span>
                                            </td>
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
                                            <td class="text-muted">Sent Date</td>
                                            <td class="text-left"><span id="query_send_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Recieved Date</td>
                                            <td class="text-left"><span id="query_received_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Query</td>
                                            <td class="text-left"><span id="sent_query"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Document</td>
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
        @endif


        @endsection
        @section('scripts')

            <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
            <script>
                function extend_deadline(query_deferment_id) {
                    document.getElementById('query_deferment_id').value = query_deferment_id;
                }

                $(function () {
                    bsCustomFileInput.init();
                });


                function decision_id_assignment(o) {
                    var id = o.value;

                    document.getElementById('deferment_id').value = id;

                }




                function details_query(o) {
                    let id = o.value;

                    var server_ip=document.getElementById('server_ip').value;


                    $.ajax({

                        type: 'GET',

                        url: "{{ route('query_details') }}",

                        data: {id: id},

                        success: function (data) {

                            //for sending part

                            console.log(data)

                            document.getElementById('superviosr_subject').innerText = data.data['sent_subject'];
                            document.getElementById('supervisor_name').innerText =data.data['supervisor_first_name']+' '+data.data['supervisor_middle_name'];
                            document.getElementById('applicant_name').innerText = data.data['company_name'];
                            document.getElementById('query_send_date').innerText = data.data['sent_date'];
                            document.getElementById('query_received_date').innerText = data.data['received_date'];
                            document.getElementById('sent_query').innerText = data.data['sent_query'];
                            document.getElementById('status').innerText = data.data['status'];
                            document.getElementById('status').AddClass = "badge badge-success" ;
                            document.getElementById('received_query').innerText = data.data['received_subject'];

                           if(data.sent_document.path!=null) {
                               var document_path = data.sent_document.path;


                               document.getElementById('query_sent_document_view').href = server_ip + document_path;
                           }
                            if (data.received_document == null) {

                                document.getElementById('query_received_document_view').hidden = true

                            } else {

                                document.getElementById('query_received_document_view').hidden = false;


                                if( data.received_document.path!=null) {


                                    var document_path = data.received_document.path;

                                    document.getElementById('query_received_document_view').href = server_ip + document_path;
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