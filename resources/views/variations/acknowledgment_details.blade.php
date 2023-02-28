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
                            <h3 class="card-title"><strong>Variation Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <input type="hidden" value="{{$variation->id}}" name="decision_id">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                <div class="form-group">
                                        <label> Subject:</label>
                                        {{$variation->applicant_subject}}
                                    </div>
                                    <div class="form-group">
                                        <label> Received On: </label>

                                        {{$variation->created_at}}

                                    </div>
                                    
                                    <div class="form-group">
                                        <label> Cover letter:</label>
                                         <a href="{{asset($variation->cover_letter_path)}}" type="button" target="_blank"
                                           title="View the document" class="btn btn-info btn-sm"><i
                                                    class="fas fa-eye "></i></a>
                                    </div>
                                    <div class="form-group">
                                        <label> Variation Document:</label>
                                          <a href="{{asset($attachment->path)}}" type="button" target="_blank"
                                           title="View the document" class="btn btn-info btn-sm"><i
                                                    class="fas fa-link "></i></a>
                                    </div>
                                  
                                   
                                   


                                </div>


                                <div class="col-12 col-sm-6">

                                    <table class="table table-condensed table-borderless">
                                        <tbody>
                                        {{------------------------------------start Acceptance Letter-------------------}}
                                        <tr>
                                            <td class="text-muted" width="35%">Acknowledgment Letter</td>
                                            <td class="text-left">
                                                @if(!isset($variation->sealed_acknowledgment_document_id))
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            title="Generate Acknowledgment Letter Template" data-toggle="modal"
                                                            data-target="#modal_acknowledgment"
                                                            onclick="information_retriver_ajax(this,'accept')"
                                                            value="{{$variation->id}}"> <i class="fa fa-file"></i> Generate
                                                    </button>
                                                @else

                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Sealed Documents Already Sent." disabled>
                                                        <i class="fa fa-file"></i>
                                                        Generate
                                                    </button>
                                                @endif
                                            </td>

                                        <tr>
                                            <td class="text-muted">Generated Acknowledgment Letter</td>
                                            <td class="text-left">
                                                @if(!isset($variation->acknowledgment_document))
                                                    <button title="Pending Generation of Letter"
                                                            class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i>
                                                        View and Download
                                                    </button>

                                                @else
                                                    <a href="{{asset($variation->acknowledgment_document)}}" type="button"
                                                       target="_blank" title="View and Download Acknowledgment Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download </a>

                                                @endif
                                            </td>
                                        </tr>
                                        

                                        {{------------------------------------// End Acceptance Letter-------------------}}
                                        <tr>
                                            <td colspan="2">
                                                <hr/>
                                            </td>
                                        </tr>
                                       
                                       

                                       
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> Acknowledgment Letter</td>
                                            <td class="text-left">
                                                @if(isset($variation->acknowledgment_document_id) and !isset($variation->sealed_acknowledgment_document_id))

                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                title="Send Sealed Letter"
                                                                data-toggle="modal"
                                                                data-target="#SendAcknowledgmentLetterModal"
                                                                value="{{$variation->id}}"><i class="fas fa-upload"></i>
                                                            Send Sealed
                                                        </button>

                                                    @else
                                                        <button class="btn btn-secondary btn-sm"
                                                                title="Sealed Letters Already Sent." disabled>
                                                            <i class="fas fa-upload"></i>
                                                            Send Sealed
                                                        </button>
                                                    @endif
                                               
                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td class="text-muted"><strong>Sealed</strong> Acknowledgment Letter</td>
                                            <td class="text-left">
                                                @if(isset($variation->sealed_acknowledgment_document_id))
                                                    <a href="{{asset($variation->sealed_acknowledgment_document_path)}}"
                                                       type="button" target="_blank"
                                                       title="View and Download Sealed Letter"
                                                       class="btn btn-success btn-sm"><i class="fas fa-download"></i>
                                                        View and Download</a>
                                                @else
                                                    <button title="Sealed Letter NOT Sent" class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-download"></i> View and Download
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                       
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{route('variation_index')}}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-circle-left"></i> Back </a>

                                    </div>
                                </div>


                            </div>


                        </div>
                        <!-- /.card -->


                        {{----------- Accept Modal ----------------------}}
                        @include('variations.acknowledgment_letter')
                        {{--------------------END Modal  ------------------}}

                        {{----------- Market AH Modal ----------------------}}
                        {{--  @include('decisions.market_modal')  --}}
                        {{--------------------END Modal  ------------------}}


                    </div>
                </div>
            </div>
        </div>
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
                    // alert(id)
                    // document.getElementById(type+'_decision_id').value=id;
                    $.ajax({

                        type: 'GET',

                        url: "{{ route('information_retriver_ajax') }}",

                        data: {
                            id: id,decision_type:'variation'
                        },

                        success: function (data) {

                            console.log(data);

                            document.getElementById(type + '_company_name').innerHTML = data.data.company_name
                            document.getElementById(type + '_plot_number').innerHTML = data.data.address_line_one
                            document.getElementById(type + '_region').innerHTML = data.data.state
                            document.getElementById(type + '_country').innerHTML = data.data.country_name
                            document.getElementById('acknowledgement_date').innerHTML = data.date
                            document.getElementById('reference_letter').innerHTML = data.reference_letter
                            document.getElementById(type + '_applicant_name').innerHTML = data.data.contact_first_name + ' ' + data.data.contact_last_name

                            document.getElementById(type + '_full_name').innerHTML = data.data.product_name + ', ' + data.data.dosage_form_name + ' (' + data.data.product_trade_name + ')'
                            document.getElementById(type + '_applicaion_details').innerHTML = data.data.product_name + ', ' + data.data.dosage_form_name + ' (' + data.data.product_trade_name + ')'

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

                            console.log(data)

                            document.getElementById('certificate_number').innerHTML = data.certificate.certificate_number
                            document.getElementById('proprietary_name').innerHTML = data.data.product_name
                            document.getElementById('active_ingredient').innerHTML = data.data.address_line_one
                            document.getElementById('strength').innerHTML = data.data.state
                            document.getElementById('dosage_form').innerHTML = data.data.country_name
                            document.getElementById('route').innerHTML = data.data.route_administration_name
                            document.getElementById('approved_shelf_life').innerHTML = data.data.applicant_first_name + ' ' + data.data.applicant_middle_name
                            document.getElementById('accept_applicant_name').innerHTML = data.data.applicant_first_name + ' ' + data.data.applicant_middle_name
                            document.getElementById('presentation').innerHTML = data.data.product_trade_name + ', ' + data.data.dosage_form_name + ', ' + data.data.route_administration_name
                            document.getElementById('marketing').innerHTML = data.data.company_name;
                            document.getElementById('manufacturer_name').innerHTML = data.data.company;
                            document.getElementById('agent').innerHTML = data.created_at;
                            if (data.data.application_type == 2) {
                                application = "Fast Track"
                            } else {
                                application = "Standard Mode"
                            }
                            document.getElementById('application_type').innerHTML = application;
                            document.getElementById('registration_number').innerHTML = data.certificate.registration_number;
                            document.getElementById('date_registered').innerHTML = data.created_at;
                            document.getElementById('date_issued').innerHTML = data.created_at;

                            document.getElementById('accept_full_name').innerHTML = data.data.product_name + ', ' + data.data.dosage_form_name + ' (' + data.data.product_trade_name + ')'
                            document.getElementById('accept_applicaion_details').innerHTML = data.data.product_name + ', ' + data.data.dosage_form_name + ' (' + data.data.product_trade_name + ')'


                            var d = new Date(data.created_at);
                            var expire_date = new Date()
                            d.setFullYear(d.getFullYear() + 5, d.getMonth(), d.getDate())
                            // expire_date.setFullYear(d.getFullYear()+5;

                            document.getElementById('expire_date').innerHTML = d;

                        }

                    });
                }


                function submitter(o) {

                    var dat = document.getElementById('data_content').innerHTML
                    var data = document.getElementById('data')
                    var decision_id = document.getElementById('decision_id')
                    data.value = dat;
                    decision_id.value = o.value;

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
                            id: id
                            , dat: dat
                        },

                        success: function (data) {

                            console.log(data['data']);

                            location.reload();
                        }


                    });
                }

                

            </script>
@endsection
