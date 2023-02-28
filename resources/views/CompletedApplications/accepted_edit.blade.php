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
                                            <td class="text-muted">Route of Administration</td>
                                            <td class="text-left">
                                                {{$application_details->route_administration_name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Application Started</td>
                                            <td class="text-left">
                                                {{$application_details->app_created_at}}
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
                                        {{------------------------------------start Acceptance Letter-------------------}}
                                   
                                   
                                    <tr>
                                            <td class="text-muted">Registration Number</td>
                                        <td class="text-left">@if(isset($decision->downloaded_document_path))
                                                <span class="badge badge-primary">{{$certificate->registration_number}}</span> @endif
                                        </td>
                                    </tr>
                                    <tr>
                                            <td class="text-muted">Certificate Number</td>
                                        <td class="text-left">
                                            @if(isset($decision->downloaded_document_path))
                                                <span class="badge badge-primary"> {{$certificate->certificate_number}} </span> @endif
                                        </td>
                                    </tr>

                                   
                                    {{------------------------------------start Sealed Documents-------------------}}
                                   
                                    <tr>
                                            <td class="text-muted">Decision Letter</td>
                                        <td class="text-left">
                                            @if(isset($decision->sealed_document_id))
                                                <a href="{{asset($decision->downloaded_document_path)}}" type="button"
                                                   target="_blank" title="View and Download Acceptance Letter"
                                                   class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                    View and Download</a> @else
                                                <button title="Please Send the Sealed Documents"
                                                        class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-download"></i> View and Download
                                                </button> @endif
                                        </td>
                                    </tr>
                                    <tr>
                                            <td class="text-muted">MAH Certificate</td>
                                        <td class="text-left">
                                            @if(isset($decision->sealed_document_id))
                                                <a href="{{asset($certificate->sealed_MAH_document_path)}}"
                                                   type="button" target="_blank" title="View and Download MAH Letter"
                                                   class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                    View and Download</a> @else
                                                <button title="Please Send the Sealed Documents"
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
                                        <a href="{{route('applicant_decision_index')}}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-circle-left"></i> Back </a>


                                    </div>
                                </div>


                            </div>


                        </div>
                        <!-- /.card -->
    @if($certificate!=null)
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> Variations</h3>
                                <div class="card-tools">
                                    {{--<button class="btn btn-warning btn-sm" title="Send Query to Applicant"
                                            data-toggle="modal" data-target="#modalsend_variation">
                                            
                                        <i class="fas fa-plus"> <span
                                                    style="font-family: sans-serif; font-weight: normal ;"> New Variation </span></i>
                                    </button>--}}
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
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1"
                                                aria-label="Supplier Name: activate to sort column descending"
                                                aria-sort="ascending" width="17%">Application Num.
                                            </th>
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
                                                <td>{{$certificate->application_number}}</td>
                                                <td tabindex="0">{{$variation->variation_reference_number}}</td>
                                                <td tabindex="0">{{$variation->created_at}} </td>
                                                <td>
                                                @if(isset($variation->sealed_document_id))
                                                    @if($variation->status == 'Unassigned' or $variation->status == 'Assigned')
                                                        <span class="badge badge-primary"> In-progress</span>
                                                    @else
                                                        <span class="badge badge-primary"> {{$variation->status}}</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-primary">In-progress</span>
                                                    @endif

                                                    </td>

                                                    @if(isset($variation->sealed_document_id))

                                                        @if($variation->decision_status=='Rejected')
                                                            <td tabindex="0">
                                                                <span class="badge bg-danger">Rejected</span>
                                                            </td>
                                                        @elseif($variation->decision_status=='Accepted')
                                                            <td tabindex="0">
                                                                <span class="badge bg-success">Accepted</span>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td tabindex="0">
                                                            <span class="badge bg-secondary">Not-decided</span>
                                                        </td>

                                                    @endif
                                                    <td>
                                                        <div>


                                                <a href="{{ route('variation_applicant_details',[$variation->id])  }}"
                                                               class="btn btn-info"><i class="fas fa-list"
                                                                                       title="View Details"></i></a>


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
                                {{----------- Marjet AH Modal ----------------------}}
                                 @include('decisions.market_modal') 
                                 {{--------------------END Modal ------------------}}


                            </div>
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
