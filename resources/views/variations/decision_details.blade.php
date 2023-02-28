@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Variation Application Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <form method="POST" action="{{ route('appeal_reject') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$decision->id}}" name="variation_decision_id">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <div class="form-group">
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
                                            <label> Minutes:</label>
                                            {{$decision->description}}
                                        </div>
                                        <div class="form-group">
                                            <label> Decision:</label>
                                            @if($decision->decision_status=='Rejected')

                                                <span class="badge badge-danger">{{$decision->decision_status}}</span>
                                            @elseif($decision->decision_status=='Accepted')

                                                <span class="badge badge-success">{{$decision->decision_status}}</span>
                                            @endif

                                        </div>

                                        <div class="form-group">
                                            <label for="query_response_cover_letter">Meeting Minutes: </label>
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
                                    </div>


                                    <div class="col-6 col-sm-6">


                                        <div class="form-group">
                                            <label for="query_response_cover_letter"> Decision Letter: </label>
                                            @if(!isset($decision->sealed_document_id))
                                                @if($decision->decision_status=='Accepted')
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            title="Generate and Save Acceptance Letter"
                                                            data-toggle="modal"
                                                            data-target="#modal_accept"
                                                            onclick="information_retriver_ajax(this,'accept','variation')"
                                                            value="{{$decision->variation_id}}">
                                                        Generate
                                                    </button>
                                                @elseif($decision->decision_status=='Rejected')
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            title="Generate and Save Rejection Letter"
                                                            data-toggle="modal"
                                                            data-target="#modal_reject"
                                                            onclick="information_retriver_ajax(this,'reject','variation')"
                                                            value="{{$decision->variation_id}}">
                                                        Generate
                                                    </button>
                                                @endif
                                            @else

                                                <button type="button" class="btn btn-secondary btn-sm" title="---"
                                                        disabled>
                                                    Generate
                                                </button>
                                            @endif
                                        </div>


                                        <div class="form-group">
                                            <label for="query_response_cover_letter">Generated Letter</label>

                                            {{--@if(!isset($decision->sealed_document_id))--}}

                                            @if(isset($decision->downloaded_document_id))
                                                <a href="{{asset($decision->downloaded_document_path)}}" type="button"
                                                   target="_blank"
                                                   title="View and Download Letter"
                                                   class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                    View and Download
                                                </a>
                                            @else

                                                <button type="button" class="btn btn-secondary btn-sm" title="---"
                                                        disabled>
                                                    View and Download
                                                </button>

                                            @endif


                                        </div>


                                        <div class="form-group">


                                            <label> Sealed Letter</label>

                                            @if(!isset($decision->sealed_document_id) & isset($decision->downloaded_document_id))

                                                <button type="button" class="btn btn-success btn-sm"
                                                        title="Send Letter" data-toggle="modal"
                                                        data-target="#SendRejectionLetterModal"
                                                        value="{{$decision->id}}">
                                                    Send Sealed
                                                </button>
                                            @else

                                                <button type="button" class="btn btn-secondary btn-sm" title="---"
                                                        disabled>
                                                    Send Sealed
                                                </button>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Sent Letter</label>
                                            @if(isset($decision->sealed_document_id))
                                                <a id="received_view" href="{{asset($decision->sealed_document_path)}}"
                                                   target="_blank" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm"
                                                   data-placement="top" title="View the files"><i
                                                            class="fas fa-book-open"></i> View</a>
                                            @else

                                                <button type="button" class="btn btn-secondary btn-sm" title="---"
                                                        disabled>
                                                    View
                                                </button>

                                            @endif

                                        </div>
                                        @if($decision->attachment_available)

                                            <div class="form-group">
                                                <label>Attachments</label>
                                                <a href="{{asset($attachment->path)}}" data-toggle="tooltip"
                                                   class="btn btn-info btn-sm" data-placement="top"
                                                   title="Download the Attachment"><i
                                                            class="fas fa-paperclip"></i> </a>
                                            </div>
                                        @endif



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
                                                            <label for="query_response_cover_letter"> View Appeal
                                                                Decision Letter: </label></td>
                                                        <td width="35%">
                                                            <a href="{{asset($decision->appeal_letter_path)}}"
                                                               type="button" target="_blank"
                                                               title="View the Appeal Letter"
                                                               class="btn btn-info btn-sm"><i
                                                                        class="fas fa-eye "></i></a></td>
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
                                                                    <p class="text text-danger"
                                                                       id="uploaded_accept_appeal_decision"></p>
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
                                                                    <p class="text text-danger"
                                                                       id="uploaded_reject_appeal_decision"></p>
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
                                    <a href="{{route('variation_index')}}" class="btn btn-secondary"><i
                                                class="fas fa-arrow-circle-left"></i> Back </a>
                                    @if(isset($decision->sealed_document_id ) && !($decision->appeal==0) )
                                        <button type="submit" class="btn btn-primary" id="upload_id" hidden><i
                                                    class="fas fa-upload"></i> Upload
                                        </button>
                                    @endif
                                </div>
                            </div>


                    </div>


                </div>
                <!-- /.card -->

                </form>


                {{-----------  Accept Modal ----------------------}}
                @include('variations.decision_modal')
                {{--------------------END Modal  ------------------}}


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


        function information_retriver_ajax(o, type, decision_type) {
            var id = o.value;
            var type = type;
            var decision_type = decision_type
            document.getElementById(type + '_decision_id').value = id;
            $.ajax({

                type: 'GET',

                url: "{{ route('information_retriver_ajax') }}",

                data: {id: id, decision_type: decision_type},

                success: function (data) {


                    document.getElementById(type + '_company_name').innerHTML = data.data.company_name
                    document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one
                    document.getElementById(type + '_region').innerHTML = data.data.state
                    document.getElementById(type + '_date').innerHTML = data.date
                    document.getElementById(type + '_country').innerHTML = data.data.country_name
                    document.getElementById(type + '_variation_ref_id').innerHTML = data.data.variation_reference_number


                    document.getElementById(type + '_full_name').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name


                    document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name

                    document.getElementById(type + '_applicaion_details').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name


                    document.getElementById(type + '_dated').innerHTML = data.created_at;


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
