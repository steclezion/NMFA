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
                            <input type="hidden" value="{{$decision->id}}" name="decision_id">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label> Product Name :</label>

                                            {{$decision->product_name}}

                                        </div>
                                        <div class="form-group">
                                            <label> Application Number :</label>

                                            {{$decision->application_number}}

                                        </div>
                                        <div class="form-group">
                                            <label> Application Type:</label>
                                            @if($decision->application_type==2)
                                                Fast Track
                                            @else
                                                Standard Mode
                                            @endif

                                        </div>
                                        <div class="form-group">
                                            <label> Decision Date :</label>

                                            {{$decision->meeting_date}}

                                        </div>

                                        <div class="form-group">
                                            <label> Decision:</label>
                                            @if($decision->decision_status=='Accepted')
                                                <span class="badge badge-success">{{$decision->decision_status}}</span>
                                            @elseif($decision->decision_status=='Deferred')
                                                <span class="badge badge-warning">{{$decision->decision_status}}</span>
                                            @else
                                                <span class="badge badge-danger">{{$decision->decision_status}}</span>
                                            @endif
                                        </div>


                                    </div>


                                    <div class="col-12 col-sm-6">


                                        @if($certificate!=null)


                                            <div class="form-group">
                                                <label> MAH Certificate</label>
                                                <a id="received_view"
                                                   href="{{asset($certificate->sealed_MAH_document_path)}}"
                                                   target="_blank" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm"
                                                   data-placement="top" title="View the file"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            </div>

                                        @endif
                                        <div class="form-group">
                                            <label>Decision Letter</label>
                                            <a id="received_view" href="{{asset($decision->sealed_document_path)}}"
                                               target="_blank" data-toggle="tooltip"
                                               class="btn btn-info btn-sm"
                                               data-placement="top" title="View the file"><i
                                                        class="fas fa-book-open"></i> View</a>
                                        </div>
                                        @if($decision->attachments==1)

                                            <div class="form-group">
                                                <label>Attachments</label>
                                                <a href="{{asset($attachment->path)}}" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm" data-placement="top"
                                                   title="Download the Attachment"><i
                                                            class="fas fa-paperclip"></i> </a>
                                            </div>
                                        @endif
                                        @if($decision->appeal_letter_id!=null)
                                            <div class="form-group">
                                                <label> Appeal Status :</label>

                                                {{$decision->appeal_status}}

                                            </div>



                                            <div class="form-group">
                                                <label>Appeal Response</label>
                                                <a href="{{asset($decision->appeal_letter_path)}}" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm" data-placement="top" target="_blank"
                                                   title="Download the Attachment"><i
                                                            class="fas fa-book-open"></i> </a>
                                            </div>


                                        @endif


                                    </div>
                                </div>


                                <!-- /.col -->

                                <div class="form-group">
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{route('applicant_decision_index')}}" class="btn btn-secondary"><i
                                                    class="fas fa-arrow-circle-left"></i> Back </a>

                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>

                        </form>
                    </div>
                    </div>
                </div>
            </div>

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
                                        aria-sort="ascending" width="10%">Sent Date
                                    </th>
                                    <th class="sorting" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Title: activate to sort column ascending"
                                        width="30%"> Query Body
                                    </th>
                                    <th class="sorting" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Title: activate to sort column ascending"
                                        width="10%">Received Date
                                    </th>
                                    <th class="sorting" tabindex="0"
                                        aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Title: activate to sort column ascending"
                                        width="30%">Received Body
                                    </th>
                                    <th rowspan="1" colspan="1" width="20%">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($deferment_queries as $deferment_query)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$deferment_query->sent_subject}}</td>
                                        <td>{{$deferment_query->sent_date}}</td>
                                        <td>{{$deferment_query->sent_query}}</td>
                                        <td>{{$deferment_query->received_date}}</td>
                                        <td>{{$deferment_query->received_response}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    title="Send Response Letter" onclick="decision_id_assignment(this)"
                                                    value="{{$deferment_query->id}}" data-toggle="modal"
                                                    data-target="#queryResponseModal"><i class="fas fa-edit"></i>
                                            </button>

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

                                    <table id="example2" class="table table-condensed responsive-table">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="23%">Query Status</td>
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
                                            <td class="text-muted">Sent Date</td>
                                            <td class="text-left"><span id="query_send_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Received Date</td>
                                            <td class="text-left"><span id="query_received_date"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sent Query</td>
                                            <td class="text-left"><span  id="sent_query"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Document</td>
                                            <td class="text-left"> <a id="query_sent_document_view" href="" target="_blank" data-toggle="tooltip"
                                                                      class="btn btn-info btn-sm"
                                                                      data-placement="top" title="View the file"><i
                                                        class="fas fa-book-open"></i> View </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Query Response</td>
                                            <td class="text-left"><span  class="badge bg-success" id="received_query"></span></td>
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


            @endsection
            @section('scripts')

                <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
                <script>


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
                var document_path = data.sent_document.path;
                document.getElementById('superviosr_subject').innerText = data.data['sent_subject'];
                document.getElementById('supervisor_name').innerText =data.data['supervisor_first_name']+' '+data.data['supervisor_middle_name'];
                document.getElementById('applicant_name').innerText = data.data['company_name'];
                document.getElementById('query_send_date').innerText = data.data['sent_date'];
                document.getElementById('query_received_date').innerText = data.data['received_date'];
                document.getElementById('sent_query').innerText = data.data['sent_query'];
                document.getElementById('status').innerText = data.data['status'];
                document.getElementById('status').AddClass = "badge badge-success" ;
                document.getElementById('received_query').innerText = data.data['received_response'];



                document.getElementById('query_sent_document_view').href = server_ip + document_path;

                if (data.received_document == null) {

                    document.getElementById('query_received_document_view').hidden = true

                } else {
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
@endsection