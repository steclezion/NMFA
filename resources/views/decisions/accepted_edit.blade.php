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
                            <h3 class="card-title"><strong>Accepted Application Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <input type="hidden" value="{{$decision->id}}" name="decision_id">
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
                                                <span class="badge badge-success">{{$decision->decision_status}}</span>
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
                                                <a href="{{asset($decision->minute_path)}}" type="button"
                                                   target="_blank" title="View the document"
                                                   class="btn btn-info btn-sm"><i
                                                            class="fas fa-eye "></i> View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Meeting Attendees</td>
                                            <td class="text-left">
                                                <div class="select2-green">
                                                    <select class="select2" name='participants' multiple="multiple"
                                                            disabled data-dropdown-css-class="select2-purple"
                                                            style="width: 90%;" onchange="test(this)">
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


                                <div class="col-12 col-sm-6">

                                    <table class="table table-condensed table-borderless">
                                        <tbody>
                                        {{------------------------------------start Acceptance Letter-------------------}}
                                        <tr>
                                            <td class="text-muted" width="35%">Acceptance Letter</td>
                                            <td class="text-left">
                                                @if(!isset($certificate->sealed_MA_document))
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            title="Generate Acceptance Letter" data-toggle="modal"
                                                            data-target="#modal_accept"
                                                            onclick="information_retriver_ajax(this,'accept')"
                                                            value="{{$decision->id}}"><i class="fa fa-file"></i>
                                                        Generate
                                                    </button> @else

                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Sealed Letters Already Sent." disabled>
                                                        <i class="fa fa-file"></i>
                                                        Generate
                                                    </button> @endif
                                            </td>

                                        <tr>
                                            <td class="text-muted">Generated Acceptance Letter</td>
                                            <td class="text-left">
                                                @if(!isset($decision->downloaded_document_id))
                                                    <button title="Pending Generation of Acceptance Letter"
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i>
                                                        View and Download
                                                    </button>
                                                @else
                                                    <a href="{{asset($decision->downloaded_document_path)}}"
                                                       type="button"
                                                       target="_blank" title="View and Download Acceptance Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Generated Registration Number</td>
                                            <td class="text-left">@if(isset($decision->downloaded_document_path))    {{--$decision->downloaded_document_path--}}
                                                <span class="badge badge-primary">{{$certificate->registration_number}}</span> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Generated Certificate Number</td>
                                            <td class="text-left">
                                                @if(isset($decision->downloaded_document_path))
                                                    <span class="badge badge-primary"> {{$certificate->certificate_number}} </span> @endif
                                            </td>
                                        </tr>

                                        {{------------------------------------// End Acceptance Letter-------------------}}
                                        <tr>
                                            <td colspan="2">
                                                <hr/>
                                            </td>
                                        </tr>
                                        {{------------------------------------start MAH Letter-------------------}}
                                        <tr>
                                            <td class="text-muted">Marketing Authorization Certificate</td>
                                            <td class="text-left">
                                                @if(isset($certificate->sealed_MA_document))

                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Sealed Letters Already Sent." disabled>
                                                        <i class="fas fa-certificate"></i>
                                                        Generate
                                                    </button>
                                                @elseif(isset($decision->downloaded_document_path) & !isset($certificate->sealed_MA_document))
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            title="Generate Market Authorization Certificate"
                                                            data-toggle="modal" data-target="#modal_market"
                                                            value="{{$decision->id}}"
                                                            onclick="retrive_all_information(this)">
                                                        <i class="fa fa-certificate"></i>
                                                        Generate
                                                    </button> @else

                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Please generate acceptance letter first." disabled>

                                                        <i class="fas fa-certificate"></i>
                                                        Generate
                                                    </button> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Generated (Unsealed) MA Certificate</td>
                                            <td class="text-left">
                                                @if(isset($certificate->downloaded_MAH_document_path))
                                                    <a href="{{asset($certificate->downloaded_MAH_document_path)}}"
                                                       type="button" target="_blank"
                                                       title="View and Download MA Certificate"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download</a> @else

                                                    <button title="Please generate MA certificate first."
                                                            class="btn btn-secondary btn-sm" disabled><i
                                                                class="fas fa-download"></i> View and Download
                                                    </button> @endif
                                            </td>
                                        </tr>
                                        {{------------------------------------//end MAH Letter-------------------}}

                                        <tr>
                                            <td colspan="2">
                                                <hr/>
                                            </td>
                                        </tr>

                                        {{------------------------------------start Sealed Documents-------------------}}
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> Acceptance Letter and MA
                                                Certificate
                                            </td>
                                            <td class="text-left">
                                                @if(isset($certificate->MA_document_downloaded)) @if(!isset($certificate->sealed_MA_document))

                                                    <button type="button" class="btn btn-primary btn-sm"
                                                            title="Send Acceptance and MA Letter" data-toggle="modal"
                                                            data-target="#SendAccpetanceLetterModal"
                                                            value="{{$decision->id}}"><i class="fas fa-upload"></i>
                                                        Send Sealed
                                                    </button> @else
                                                    <button class="btn btn-secondary btn-sm"
                                                            title="Sealed Letters Already Sent." disabled>
                                                        <i class="fas fa-upload"></i>
                                                        Send Sealed
                                                    </button> @endif @else
                                                    <button class="btn btn-secondary btn-sm"
                                                            title="Required Letters not Generated Yet." disabled>
                                                        <i class="fas fa-upload"></i> Send Sealed
                                                    </button> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> Acceptance Letter</td>
                                            <td class="text-left">
                                                @if(isset($decision->sealed_document_id))
                                                    <a href="{{asset($decision->downloaded_document_path)}}"
                                                       type="button"
                                                       target="_blank" title="View and Download Acceptance Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download</a> @else
                                                    <button title="Please Send the Sealed Documents first"
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i> View and Download
                                                    </button> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> MA Certificate</td>
                                            <td class="text-left">
                                                @if(isset($decision->sealed_document_id))
                                                    <a href="{{asset($certificate->sealed_MAH_document_path)}}"
                                                       type="button" target="_blank"
                                                       title="View and Download MA Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download</a> @else
                                                    <button title="Please Send the Sealed Documents first"
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i> View and Download
                                                    </button> @endif
                                            </td>
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
                                        <a href="{{route('decision_index')}}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-circle-left"></i> Back </a>


                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>


                <!-- /.card -->
                @if(isset($decision->sealed_document_id))
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> Variations</h3>
                                <div class="card-tools">
                                    <button class="btn btn-warning btn-sm" title="Send Query to Applicant"
                                            data-toggle="modal" data-target="#modalsend_variation">

                                        <i class="fas fa-plus"> <span
                                                    style="font-family: sans-serif; font-weight: normal ;"> New Variation </span></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                    <table id="example1" class="table  dataTable no-footer dtr-inline" role="grid"
                                           aria-describedby="example1_info">

                                        <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Supplier Name: activate to sort column descending"
                                                aria-sort="ascending" width="5%">S.N
                                            </th>
                                            {{-- <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                 rowspan="1" colspan="1"
                                                 aria-label="Supplier Name: activate to sort column descending"
                                                 aria-sort="ascending" width="17%">Application Num.
                                             </th>--}}
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Supplier Name: activate to sort column descending"
                                                aria-sort="ascending" width="15%">Variation Reference Number
                                            </th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Supplier Name: activate to sort column descending"
                                                aria-sort="ascending" width="16%">Date
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Country: activate to sort column ascending"
                                                width="20%">Status
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Country: activate to sort column ascending"
                                                width="10%">Decision
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Actions: activate to sort column ascending"
                                                width="20%">Actions
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($i=1)
                                        @foreach($variations as $variation) @if($variation->status!='completed')
                                            <tr role="row" class="odd">
                                                <td>{{$i++}}</td>
                                                {{-- <td>{{$certificate->application_number}}</td>--}}
                                                <td tabindex="0">{{$variation->variation_reference_number}}</td>
                                                <td tabindex="0">{{$variation->created_at}} </td>
                                                <td tabindex="0">{{$variation->status}} </td>

                                                @if($variation->decision_status=='Rejected')
                                                    <td tabindex="0">
                                                        <span class="badge bg-danger">Rejected</span>
                                                    </td>
                                                @elseif($variation->decision_status=='Accepted')
                                                    <td tabindex="0">
                                                        <span class="badge bg-success">Accepted</span>
                                                    </td>
                                                @else
                                                    <td tabindex="0">
                                                        <span class="badge bg-primary"></span>
                                                    </td>

                                                @endif
                                                <td>
                                                    <div>

                                                        @if($variation->acknowledgment_document_id==null)
                                                            <a href="{{ route('variation_acknowledgment',[$variation->id])  }}"
                                                               class="btn btn-info"><i class="fas fa-list"
                                                                                       title="Send Acknowledgment "></i></a>

                                                        @elseif($variation->assessor_id==null)
                                                            <a href="{{ url('variation/assign/'.$variation->id)}}"
                                                               class="btn btn-sm btn-warning"
                                                               title="Assign Variation"><i class="fas fa-edit"></i></a>

                                                        @else
                                                            <a href="{{ route('variation_evaluation_edit',[$variation->id])  }}"
                                                               class="btn btn-info" title="Show Variation Details"><i
                                                                        class="fas fa-list"></i></a>
                                                        @endif
                                                        @if($variation->decision_status!=null)
                                                            <a href="{{ route('variation_decision_details',[$variation->id])  }}"
                                                               class="btn btn-info" title="Show Decision Details"><i
                                                                        class="fas fa-edit"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                @endif
                                {{----------- Accept Modal ----------------------}}
                                @include('decisions.accept_modal')
                                {{--------------------END Modal ------------------}}
                                {{----------- Market AH Modal ----------------------}}
                                @include('decisions.market_modal')
                                {{--------------------END Modal ------------------}}


                            </div>
                        </div>
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
        $('#summernote2').summernote()


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

                    let route_of_registration = '';

                    if (data.data.application_type == 1) {

                        route_of_registration = 'Standard Mode';
                    } else {
                        route_of_registration = 'Fast track - ' + data.data.fast_track_details;

                        document.getElementById(type + '_applicaion_details').innerHTML =
                            data.data.generic_name + ', ' + data.data.dosage_form_name + ', ' + route_of_registration

                    }


                    document.getElementById(type + '_company_name').innerHTML = data.data.company_name;
                    document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one;
                    document.getElementById(type + '_region').innerHTML = data.data.state;
                    document.getElementById(type + '_country').innerHTML = data.data.country_name;
                    document.getElementById(type + '_procedure').innerHTML = route_of_registration;
                    document.getElementById(type + '_reference_number').innerHTML = data.reference_letter;
                    document.getElementById('cat_of_use').innerHTML = data.data.category_use;


                    /*  document.getElementById(type + '_full_name').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name */

                    document.getElementById(type + '_full_name').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'

                    document.getElementById(type + '_applicaion_details').innerHTML = data.data.generic_name + ', ' + data.data.dosage_form_name + '(' + data.data.product_trade_name + ')'


                    document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name


                    document.getElementById(type + '_dated').innerHTML = data.created_at;
                    document.getElementById(type + '_date').innerHTML = data.date;


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

                    document.getElementById('certificate_number').innerHTML = data.certificate.certificate_number;
                    document.getElementById('proprietary_name').innerHTML = data.data.product_trade_name;
                    document.getElementById('active_ingredient').innerHTML = data.api;
                    document.getElementById('strength').innerHTML = data.strength;
                    document.getElementById('dosage_form').innerHTML = data.data.dosage_form_name;
                    document.getElementById('route').innerHTML = data.data.route_administration_name;
                    document.getElementById('approved_shelf_life').innerHTML = data.approved_self_life;
                    document.getElementById('presentation').innerHTML = data.presentation_packaging;
                    document.getElementById('marketing').innerHTML = data.data.company_name;
                    //document.getElementById('manufacturer_name').innerHTML = data.data.company_name + '<br> ' + data.data.address_line_one + ', ' + data.data.state + ', ' + data.data.country_name;
                    document.getElementById('manufacturer_name').innerHTML = data.data.company_name + '<br> ' + data.data.manufacturer_address ;
                    document.getElementById('agent').innerHTML = data.agent_company;
                    if (data.data.application_type == 2) {
                        application = "Fast Track"
                    } else {
                        application = "Standard Mode"
                    }


                    //alert(d.toISOString());
                    document.getElementById('application_type').innerHTML = application;
                    document.getElementById('registration_number').innerHTML = data.certificate.registration_number;
                    document.getElementById('date_registered').innerHTML = data.certified_date;

                    document.getElementById('date_issued').innerHTML = data.date_now;

                    document.getElementById('expire_date').innerHTML = data.expiry_date;


                }

            });
        }


        function submitter(o) {

            var dat = document.getElementById('data_1').innerHTML
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