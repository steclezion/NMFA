@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}"> @section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Accepted Application Details</strong>
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
                                            <td class="text-muted" width="30%">Variation Reference Number</td>
                                            <td class="text-left">
                                                {{$variation->variation_reference_number}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted" width="30%">Product Name</td>
                                            <td class="text-left">
                                                {{$variation->product_trade_name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Subject</td>
                                            <td class="text-left">
                                                {{$variation->applicant_subject}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">Variation Requested Date</td>
                                            <td class="text-left">
                                              {{$variation->created_at}}
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class="text-muted">Acknowledgment Letter </td>
                                            <td class="text-left">
                                                @if(isset($variation->sealed_acknowledgment_document_id))
                                                <a href="{{asset($variation->sealed_acknowledgment_document_path)}}"
                                                   type="button" target="_blank" title="View and Download MAH Letter"
                                                   class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                    View and Download</a>
                                                    @else
                                                    <span class="badge badge-secondary"> Acknowledgment Letter Not Sent</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td class="text-left">

                                                @if(isset($variation->decision_letter_path))
                                                @if($variation->status == 'Unassigned' or $variation->status == 'Assigned')
                                                    <span class="badge badge-primary"> In-progress</span>
                                                @else
                                                    <span class="badge badge-primary"> {{$variation->status}}</span>
                                                @endif
                                                @else
                                                    <span class="badge badge-primary">In-progress</span>
                                                @endif

                                            </td>
                                        </tr>
                                       
                                        </tbody>
                                    </table>
                                    {{------------------------------------ // End Decision Details-------------------}}
                                    
                            </div>


                            <div class="col-12 col-sm-6">
                                @if(isset($variation->decision_letter_path))
                                <table class="table table-condensed table-borderless">
                                    <tbody>
                                        {{------------------------------------start Acceptance Letter-------------------}}


                                        <tr>
                                            <td class="text-muted">Variation Decision Status:</td>
                                            <td class="text-left">
                                                @if($variation->decision_status=='Accepted')
                                                    <span class="badge badge-success"> {{$variation->decision_status}}</span>
                                                @elseif($variation->decision_status=='Rejected')
                                                    <span class="badge badge-danger"> {{$variation->decision_status}}</span>

                                                @else

                                                    <span class="badge badge-secondary">Variation Decision Not Given</span>
                                                @endif

                                            </td>
                                        </tr>

                                   
                                    {{------------------------------------start Sealed Documents-------------------}}
                                   
                                    <tr>
                                        <td class="text-muted"><strong>Sealed</strong> Decision Letter</td>
                                        <td class="text-left">

                                                <a href="{{asset($variation->decision_letter_path)}}" type="button"
                                                   target="_blank" title="View and Download Acceptance Letter"
                                                   class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                    View and Download</a>
                                        </td>
                                    </tr>


                                    @if($variation->variation_decision_attachment_available==1)
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


                                        @if($variation->appeal_letter_id != null)
                                            <table class="table table-condensed table-borderless">
                                                <tbody>
                                                <tr>
                                                    <td class="text-muted" width="50%" value="Yes">
                                                        Appeal
                                                    </td>
                                                    <td class="text-left">
                                                        <input type="checkbox" checked disabled/>
                                                </tr>

                                                <tr>
                                                    <td class="text-muted"> Appeal Decision</td>
                                                    <td class="text-left">
                                                        @if($variation->appeal_status=='Accepted')
                                                            <span class="badge badge-success">{{ $variation->appeal_status}}</span>
                                                        @else
                                                            <span class="badge badge-danger"> {{$variation->appeal_status}}</span>

                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted"> View Appeal Decision Letter:</td>
                                                    <td class="text-left">
                                                        <a href="{{asset($variation->appeal_letter_path)}}"
                                                           type="button" target="_blank"
                                                           title="View the Appeal Letter"
                                                           class="btn btn-info btn-sm"><i
                                                                    class="fas fa-eye "></i> View</a></td>
                                                </tr>
                                    </tbody>
                                </table>

                                        @else
                                        </tbody>
                                    </table>
                                            <div class="alert alert-default-danger">

                                                <h5><i class="icon fas fa-exclamation-circle"></i>Issuing Appeal Letter.
                                                </h5>
                                                The application for the Variation of
                                                <b> {{$variation->product_trade_name}} </b> has been <b>Rejected</b>.
                                                If you are not satisfied with the Decision, Please Contact the Eritrean
                                                Ministry of Health (MOH) for an Appeal.
                                                <ul>

                                                    {{--todo: uncomment below when variations is included--}}
                                                    {{--@if($evaluation_document_progress->variation_assessment_submitted > 2)
                                                        <li>Variations Evaluation</li>
                                                    @endif--}}
                                                </ul>
                                            </div>
                                        @endif
@endif


                                </div>
                                <div class="form-group">
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{ route('applicant_decision_details',[$variation->decision_id])  }}"
                                           class="btn btn-secondary btn-sm"><i class="fas fa-arrow-alt-circle-left" title="Send Acknowledgment "></i>Back</a>



                                    </div>
                                </div>


                            </div>


                        </div>

                            </div>
                        </div>
                    </div
                </div>
            </div>
        </div>
    </section>
                @endsection
                @section('scripts')

                    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
                    <script src="{{asset('plugins/word_converter/printThis.js') }}"></script>
                    <script>
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


                        $('#summernote').summernote()
                        $('#summernote1').summernote()


                        function information_retriver_ajax(o, type) {
                            var id = o.value;
                            var decision_type = 'Dossier Evaluation';
                            // alert(id)
                            // document.getElementById(type+'_decision_id').value=id;
                            $.ajax({

                                type: 'GET',

                                url: "{{ route('information_retriver_ajax') }}",

                                data: {
                                    id: id,
                                    decision_type: type,
                                },

                                success: function (data) {

                                    document.getElementById(type + '_company_name').innerHTML = data.data.company_name
                                    document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one
                                    document.getElementById(type + '_region').innerHTML = data.data.state
                                    document.getElementById(type + '_country').innerHTML = data.data.country_name


                                    document.getElementById(type + '_full_name').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name


                                    document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name

                                    document.getElementById(type + '_applicaion_details').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name


                                    document.getElementById(type + '_dated').innerHTML = data.created_at;


                                }

                            });
                        }


                        function retrive_all_information(o) {
                            var id = o.value;


                            $.ajax({

                                type: 'GET',

                                url: "{{ route('retrive_all_information') }}",

                                data: {
                                    id: id
                                },

                                success: function (data) {

                                    document.getElementById('certificate_number').innerHTML = data.certificate.certificate_number
                                    document.getElementById('proprietary_name').innerHTML = data.data.product_name
                                    document.getElementById('active_ingredient').innerHTML = data.data.address_line_one
                                    document.getElementById('strength').innerHTML = data.data.state
                                    document.getElementById('dosage_form').innerHTML = data.data.country_name
                                    document.getElementById('route').innerHTML = data.data.route_administration_name
                                    document.getElementById('approved_shelf_life').innerHTML = data.data.applicant_first_name + ' ' + data.data.applicant_middle_name
                                    document.getElementById('presentation').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name
                                    document.getElementById('marketing').innerHTML = data.data.company_name;
                                    document.getElementById('manufacturer_name').innerHTML = data.data.company;
                                    document.getElementById('agent').innerHTML = data.created_at;
                                    if (data.data.application_type == 2) {
                                        application = "Fast Track"
                                    } else {
                                        application = "Standard Mode"
                                    }
                                    var d = new Date(data.created_at);
                                    var certified = d.getFullYear().toString() + "-" + d.getMonth().toString() + "-" + d.getDate().toString();

                                    var year = d.getFullYear() + 5;
                                    var expiry = year.toString() + "-" + d.getMonth().toString() + "-" + d.getDate().toString();
                                    // expire_date.setFullYear(d.getFullYear()+5;

                                    //alert(d.toISOString());
                                    document.getElementById('application_type').innerHTML = application;
                                    document.getElementById('registration_number').innerHTML = data.certificate.registration_number;
                                    document.getElementById('date_registered').innerHTML = certified;
                                    document.getElementById('date_issued').innerHTML = certified;

                                    // var expire_date = new Date("YYYY-MM-DD");
                                    // alert(d);

                                    d.setFullYear(d.getFullYear() + 5, d.getMonth(), d.getDate())
                                    // expire_date.setFullYear(d.getFullYear()+5;

                                    document.getElementById('expire_date').innerHTML = expiry;

                                }

                            });
                        }


                        function submitter(o) {

                            var dat = document.getElementById('data_content').innerHTML
                            var data = document.getElementById('data')
                            var decision_id = document.getElementById('decision_id');
                            data.value = dat;
                            decision_id.value = o.value;
                            document.getElementById('certified_date').value = document.getElementById('date_registered').innerHTML;
                            document.getElementById('expiry_date').value = document.getElementById('expire_date').innerHTML;

                        }


                        function test(o) {

                            var id = o.value;
                            var dat = document.getElementById('whole_content').innerHTML
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });


                            $.ajax({

                                type: 'POST',

                                url: "{{ route('download_market_authorization_letter') }}",

                                data: {
                                    id: id,
                                    dat: dat
                                },

                                success: function (data) {

                                    console.log(data['data']);

                                    location.reload();
                                }


                            });
                        }
                    </script>
@endsection