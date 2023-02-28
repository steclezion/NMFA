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
                            <h3 class="card-title"><strong>Rejected Application Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <form method="POST" action="{{ route('update_reject_decision') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$decision->id}}" name="decision_id">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-6 col-sm-6">

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
                                                    <span class="badge badge-danger">{{$decision->decision_status}}</span>
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


                                    </div>


                                    <div class="col-6 col-sm-6">

                                        <table class="table table-condensed table-borderless">
                                            <tbody>
                                            {{------------------------------------start Rejection Letter-------------------}}
                                            <tr>
                                                <td class="text-muted" width="35%">Rejection Letter</td>
                                                <td class="text-left">
                                                    @if(!isset($decision->sealed_document_id))
                                                        <button type="button" class="btn btn-success btn-sm"
                                                                title="Generate Rejection Letter"
                                                                data-toggle="modal"
                                                                data-target="#modal_reject"
                                                                onclick="information_retriver_ajax(this,'reject')"
                                                                value="{{$decision->id}}"><i class="fa fa-file"></i>
                                                            Generate
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                                title="Documents Already Sent" disabled><i
                                                                    class="fa fa-file"></i>
                                                            Generate
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Generated Rejection Letter</td>
                                                <td class="text-left">
                                                    @if(isset($decision->downloaded_document_id))
                                                        <a href="{{asset($decision->downloaded_document_path)}}"
                                                           type="button" target="_blank"
                                                           title="View and Download Rejection Letter"
                                                           class="btn btn-success btn-sm"><i
                                                                    class="fas fa-download"></i>
                                                            View and Download
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                                title="Please Generate the Letter First" disabled>
                                                            View and Download
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            {{------------------------------------//end Rejected Letter-------------------}}

                                            <tr>
                                                <td colspan="2">
                                                    <hr/>
                                                </td>
                                            </tr>

                                            {{------------------------------------start  Sealed Documents-------------------}}
                                            <tr>
                                                <td class="text-muted"><strong>Sealed</strong> Rejection Letter</td>
                                                <td class="text-left">
                                                    @if(!isset($decision->sealed_document_id) & isset($decision->downloaded_document_id))
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                title="Send Rejection Letter" data-toggle="modal"
                                                                data-target="#SendRejectionLetterModal"
                                                                value="{{$decision->id}}"><i class="fa fa-upload"></i>
                                                            Send Sealed
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                                title="Document Already Sent" disabled><i
                                                                    class="fa fa-upload"></i>
                                                            Send Sealed
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-muted">Sent Rejection Letter</td>
                                                <td class="text-left">
                                                    @if(isset($decision->sealed_document_id))
                                                        <a id="received_view"
                                                           href="{{asset($decision->sealed_document_path)}}"
                                                           target="_blank" data-toggle="tooltip"
                                                           class="btn btn-success btn-sm"
                                                           data-placement="top"
                                                           title="View and Download Sealed Rejection Letter">
                                                            <i class="fas fa-download"></i> View and Download</a>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                                title="Document Not Yet Sent" disabled><i
                                                                    class="fas fa-download"></i>
                                                            View and Download
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
                                            <tr>
                                                <td colspan="2">
                                                    <hr/>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>


                                        {{------------------------------------Start Appeal issues-------------------}}

                                        @if(isset($decision->sealed_document_id ) && $decision->appeal==1 )
                                            @if($decision->appeal_letter_id != null)
                                                <table width="100%">
                                                    <tr>
                                                        <td>
                                                            <label>Appeal </label>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" checked disabled/>
                                                    </tr>

                                                    <tr>
                                                        <td width="30%">
                                                            <label for="query_response_cover_letter"> Appeal
                                                                Decision </label></td>
                                                        <td>
                                                            @if($decision->appeal_status=='Accepted')
                                                                <span class="badge badge-success">{{ $decision->appeal_status}}</span>
                                                            @else
                                                                <span class="badge badge-danger"> {{$decision->appeal_status}}</span>

                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%">
                                                            <label for="query_response_cover_letter">Appeal
                                                                Decision Letter </label></td>
                                                        <td width="35%">
                                                            <a href="{{asset($decision->appeal_letter_path)}}"
                                                               type="button" target="_blank"
                                                               title="View the Appeal Letter"
                                                               class="btn btn-info btn-sm"><i
                                                                        class="fas fa-eye "></i> View</a></td>
                                                    </tr>

                                                </table>

                                            @else
                                                <div class="form-group">
                                                    <label>Appeal </label>
                                                    <input type="checkbox" onclick="appeal_status(this)"/>
                                                </div>
                                                <div class="form-group" id="show_appeal_div" hidden>
                                                    <table width="100%">
                                                        <tr height="50px">
                                                            <td width="35%">
                                                                <b> Accepted</b>:<input type="radio" name="appeal"
                                                                                        onclick="radio_checker(this)"
                                                                                        value="1"/>
                                                            </td>
                                                            <td width="65%">
                                                                <div class="custom-file" id="accepted_document_div"
                                                                     hidden>
                                                                    <input type="file" name="accepted_document"
                                                                           id="accepted_document_id"
                                                                           class="custom-file-input"
                                                                           onchange="filevalidiator('uploaded_accept_appeal_decision','accepted_document_id','upload_id',['pdf'])"
                                                                           required>
                                                                    <label class="custom-file-label"
                                                                           for="query_response_cover_letter">Choose
                                                                        file</label>
                                                                    <p class="text text-danger" id="uploaded_accept_appeal_decision"></p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr height="50px">
                                                            <td width="35%">
                                                                <b>Rejected</b>: <input type="radio" name="appeal"
                                                                                        onclick="radio_checker(this)"
                                                                                        value="0"/>
                                                            </td>
                                                            <td width="65%">
                                                                <div class="custom-file" id="rejected_document_div"
                                                                     hidden>
                                                                    <input type="file" name="rejected_document"
                                                                           id="rejected_document_id"
                                                                           class="custom-file-input"
                                                                           onchange="filevalidiator('uploaded_reject_appeal_decision','rejected_document_id','upload_id',['pdf'])"
                                                                           required>
                                                                    <label class="custom-file-label"
                                                                           for="query_response_cover_letter">Choose
                                                                        file</label>
                                                                    <p class="text text-danger" id="uploaded_reject_appeal_decision"></p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                    </div>
                                    @endif
                                    @endif
                                </div>

                                <!-- /.col -->

                            </div>
                            <div class="form-group">
                                <div class="modal-footer justify-content-between">
                                    <a href="{{route('decision_index')}}" class="btn btn-secondary"><i
                                                class="fas fa-arrow-circle-left"></i> Back </a>
                                    @if(isset($decision->sealed_document_id ) && !($decision->appeal==0) )
                                        <button type="submit" class="btn btn-primary" id="upload_id" hidden><i
                                                    class="fas fa-upload"></i> Upload
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- /.card -->

                        </form>


                        {{-----------  Reject Modal ----------------------}}
                        @include('decisions.reject_modal')
                        {{--------------------END Modal  ------------------}}


                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    </div>
@endsection
@section('scripts')

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>


        $(function () {
            bsCustomFileInput.init();
        });

        function appeal_status(o) {
            if (o.checked == true) {
                document.getElementById('show_appeal_div').hidden = false;
                document.getElementById('upload_id').hidden = false;


            } else {
                document.getElementById('upload_id').hidden = true;
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

                    document.getElementById(type + '_company_name').innerHTML = data.data.company_name
                    document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one
                    document.getElementById(type + '_region').innerHTML = data.data.state
                    document.getElementById(type + '_country').innerHTML = data.data.country_name
                    document.getElementById(type + '_reference_number').innerHTML = data.reference_letter;

                    document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name

                    document.getElementById(type + '_full_name').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'

                    document.getElementById(type + '_applicaion_details').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'


                    document.getElementById(type + '_dated').innerHTML = data.created_at;

                    document.getElementById(type + '_date').innerHTML = data.date;


                }

            });
        }

        function refresh(o) {
            $('#modal_reject').modal('hide');
            var id = setInterval(download_page, 3000);

            function download_page() {
                location.reload();
                clearInterval(id);
            }

        }


    </script>
@endsection