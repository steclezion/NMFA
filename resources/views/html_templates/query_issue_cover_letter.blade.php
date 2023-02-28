@extends('layouts.app')
@section('stylesheets')

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')


<div class="row">

    <!-- /.col -->
    <div class="col-md-8 offset-2">

        <form method="POST" action="{{ route('save_to_draft') }}">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Query Cover Letter</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-body">


                    <input type="hidden" value="{{ $template->id }}" name="hidden_template_id" />
                    <input type="hidden" value="{{ $dossier_evaluation_details->dossier_ass_id }}" name="hidden_dossier_asg_id" />
                    <input type="hidden" value="query_cover_letter" name="type" />
                    <div class="form-group" >
                        <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                        

                                        <label>Date:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                        @if (isset($date))
                                            {{ $date }}

                                        @else
                                        [Date/Month/Year]
                                        @endif
                                            </span>
                                        <br>
                                        <label>Ref:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                                @if (isset($reference_letter))
                                                {{ $reference_letter }}

                                            @else
                                            [NMFA/XX/YEAR/Sequential Number]
                                            @endif
                                            </span>
                                        <br>
                                        <label>To:</label>

                                                @if (isset($dossier_evaluation_details))

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">  {{ $dossier_evaluation_details->company_name }} </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> {{ $dossier_evaluation_details->address_line_one }} </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> {{ $dossier_evaluation_details->state }}</span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">{{ $dossier_evaluation_details->country_name }}</span>

                                            @else
                                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                            [Name of the applicant (marketing authorization holder)]<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[street/plot number]<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[region/state]<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[country]
                                         </span>
                                            @endif




                                        <br>

                                        <label>Subject: Queries to </label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                             @if (isset($dossier_evaluation_details))

                                                {{ $dossier_evaluation_details->company_name }}

                                            @else
                                                [name of the applicant/manufacturer]
                                            @endif
                                                  </span>
                                        <b>for the Registration Application of </b>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                              @if (isset($dossier_evaluation_details->dossier_ref_num))

                                                {{ $dossier_evaluation_details->product_trade_name }},{{ $dossier_evaluation_details->dosage_form_name }} ({{ $dossier_evaluation_details->brand_name }})

                                            @else
                                                [name of the product, dosage form] [Brand name]
                                            @endif
                                                  </span>
                                        <br>
                                        &nbsp;Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> @if (isset($dossier_evaluation_details))

                                        {{ $dossier_evaluation_details->con_first_name }} {{ $dossier_evaluation_details->con_last_name }}

                                    @else
                                        [Name of the contact person] :
                                    @endif </span>
                                        <br>
                                       Reference is made to the application for registration dated
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> 
                                        @if (isset($dossier_evaluation_details))

{{ $application_created_at }}

@else
[date/month/year]
@endif</span>
                                         of the following product:
                                <br>
                                         <table border="1"  width="100%">
                                            
                                              
                                             <tbody>
                                             <tr>
                                                <th>S.No</th>
                                                 <th>Product Name</th>
                                                 <th>Application Number</th>
                                             </tr>
                                                 <tr>
                                                 <td>1</td>
                                                 <td><span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                                          @if (isset($dossier_evaluation_details->dossier_ref_num))

                                                             {{ $dossier_evaluation_details->product_trade_name }},{{ $dossier_evaluation_details->dosage_form_name }} ({{ $dossier_evaluation_details->brand_name }})

                                                         @else
                                                             [name of the product, dosage form] [Brand name]
                                                         @endif
                                                              </span></td>
                                                 <td><span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> @if (isset($dossier_evaluation_details->dossier_ref_num))

                                                             {{ $dossier_evaluation_details->application_number }}

                                                         @else
                                                             [Application number]
                                                         @endif
                                                         </span></td>
                                                        </tr>
                                             </tbody>
                                         </table>
                                         <br>
                                         This is to kindly inform you that evaluation of
                                         <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> @if (isset($dossier_evaluation_details->dossier_ref_num))

                                                 {{ $dossier_evaluation_details->product_trade_name }},{{ $dossier_evaluation_details->dosage_form_name }} ({{ $dossier_evaluation_details->brand_name }})

                                             @else
                                                 [name of the product, dosage form] [Brand name]
                                             @endif</span>
                                         has been completed. In this regard, we are attaching herewith the queries for your reference and to respond not later than
                                         <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> [Month(text) date, year]. </span>
                                         Failure to respond by this date, the authority, at its discretion, will proceed to make a decision on the product for re-application of marketing authorization.
                                         <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Iyassu Bahta<br>
                                         Director, National Medicines and Food Administration<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea

<br>

                            </textarea>

                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="modal-footer justify-content-between">
                        <a class="btn btn-secondary" href="/dossier_evaluation/edit/{{$dossier_evaluation_details->dossier_ass_id}}" >Back</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Download </button>
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </form>

    </div>


    <!-- /.col -->
</div>
@endsection
@section('scripts')
<script src="{{asset('plugins/word_converter/printThis.js') }}"></script>
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>

<script>
    function test() {
            $('#name').printThis({
                header: "<img src='images/nmfa_header.png' alt='tst'/>",
                footer: "<img src='images/nmfa_footer.png' alt='tst'/>"

            });
        }

    $(function () {


        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })

    $('#reservationdate').datetimepicker({
        format: 'L'

    });


</script>
@endsection
