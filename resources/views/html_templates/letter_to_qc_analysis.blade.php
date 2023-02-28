@extends('layouts.app')
@section('stylesheets')

    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')

    <div class="row">

        <!-- /.col -->
        <div class="col-md-8 offset-2">

            <form method="POST" action="{{ route('download_qualitycontrol_pdf') }}">
                @csrf
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Letter of Request to Inspection Unit for Sample Testing</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="form-group">
                            <label>To:</label>
                            <select class="form-control" name='to_user'
                                    onchange='insert_name_to_textarea(this,{{ $users }} )' required>
                                <option></option>
                                @foreach ($users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->first_name }} {{ $user->middle_name }}</option>

                                @endforeach
                            </select>
                        </div>


                        <input type="hidden" value="{{ $template->id }}" name="hidden_template_id"/>
                        <input type="hidden" value="{{ $dossier_evaluation_details->dossier_ass_id }}"
                               name="hidden_dossier_asg_id"/>

                        <div class="form-group">
                                                                                <textarea id="summernote"
                                                                                          class="form-control"
                                                                                          name='data'
                                                                                          style="height: 300px; display: none;">
                                                                                <div class="page">
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
                                                                                            [NMFA/XX/YEAR/Sequential
                                                                                            Number]
                                                                                        @endif
                                                                                       </span>
                                                                                    <br>
                                                                                    <label>To:</label>
                                                                                    <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"
                                                                                          id='inpsection_unit_user'>


                                                                                        [Head of the Inspection Unit]


                                                                                        </span>
                                                                                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inspection Unit
                                                                                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;National Medicines and Food Administration
                                                                                    <br><p class="MsoNormal"
                                                                                           style="line-height:200%"><b
                                                                                                style="mso-bidi-font-weight:nrmal">
                                                                                        <span style="font-size:12.0pt;line-height:200%;font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                              lang="EN-US">
                                                                                            Subject:Request for Quality Control Analysis of Registration Samples
                                                                                            </span></b>
                                                                                        </p>
                                                                                        <p class="MsoNormal"
                                                                                           style="text-align:justify">
                                                                                            <span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                  lang="EN-US">
                                                                                            Dear Sir/Madam,
                                                                                            </span>
                                                                                        </p>
                                                                                        <p class="MsoNormal"
                                                                                           style="text-align:justify">
                                                                                            <span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                  lang="EN-US">
                                                                                                This is to inform you that the registration samples of
                                                                                                <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                                                                                 @if (isset($dossier_evaluation_details))
                                                                                                        {{ $dossier_evaluation_details->product_trade_name }}

                                                                                                    @else
                                                                                                        product nameand
                                                                                                    @endif

                                                                                                    @if (isset($dossier_evaluation_details))
                                                                                                        {{ $dossier_evaluation_details->strength_amount }}{{ $dossier_evaluation_details->strength_unit }}

                                                                                                    @else
                                                                                                        &lt;strength&gt;
                                                                                                    @endif
                                                                                             </span>
                                                                                                 from
                                                                                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                                                                                    @if (isset($dossier_evaluation_details))
                                                                                                    {{ $dossier_evaluation_details->company_name }}

                                                                                                @else
                                                                                                    &lt;name of the
                                                                                                    applicant&gt;
                                                                                                @endif

                                                                                                </span>

                                                                                                <?php
                                                                                                use Carbon\Carbon;
                                                                                                /** @var TYPE_NAME $dossier_evaluation_details */
                                                                                                $sample_received_date = Carbon::createFromFormat('Y-m-d', $dossier_evaluation_details->sample_received_date);
                                                                                                $sample_received_date = $sample_received_date->format('M d, Y');
                                                                                                ?>
                                                                                                 have been received on  <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">{{ $sample_received_date }}</span>.
                                                                                                Therefore, you are kindly requested to send the product samples to the National Drug Quality Control Laboratory to undergo the necessary quality control tests.</span>
                                                                                        </p>
                                                                                    <table style="border:5;border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt"
                                                                                           cellspacing="0"
                                                                                           cellpadding="0">
                                                                                            <tbody>
                                                                                                <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes">
                                                                                                        <td colspan="2"
                                                                                                            style="width:467.5pt;border:solid windowtext 1.0pt; mso-border-alt:solid windowtext .5pt;background:#F2F2F2;mso-background-themecolor: background1;mso-background-themeshade:242;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                            width="623"
                                                                                                            valign="top">
                                                                                                            <p class="MsoNormal"
                                                                                                               style="margin-bottom:0cm;margin-bottom:.0001pt;line-height: 115%"><b
                                                                                                                        style="mso-bidi-font-weight:normal"><span
                                                                                                                            style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font: minor-latin"
                                                                                                                            lang="EN-US">Sample details </span></b>
                                                                                                            </p>
                                                                                                        </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Name of the product</span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Manufacturer</span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Batch number</span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Manufacturing date</span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Expiry date </span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes">
                                                                                                    <td style="width:162.8pt;border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="217"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><span
                                                                                                                style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                lang="EN-US">Number of samples </span>
                                                                                                    </p>
                                                                                                    </td>
                                                                                                    <td style="width:304.7pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt; mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt"
                                                                                                        width="406"
                                                                                                        valign="top">
                                                                                                    <p class="MsoNormal"
                                                                                                       style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:115%"><b
                                                                                                                style="mso-bidi-font-weight:normal"><span
                                                                                                                    style="font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                                    lang="EN-US">&nbsp;</span></b>
                                                                                                    </p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            </table>
                                                                                            <br>
                                                                                            <p class="MsoNormal"
                                                                                               style="margin-bottom:12.0pt;tab-stops:204.65pt">
                                                                                            <span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;;
                                                                                                mso-bidi-font-family:Calibri;mso-bidi-theme-font:minor-latin"
                                                                                                  lang="EN-US">Best regards, </span>

                                                            <span style="mso-tab-count:1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                                                                                                <span style="font-size:14.0pt;mso-bidi-font-size:12.0pt;line-height:107%;
                                                            font-family:&quot;Cambria&quot;,&quot;serif&quot;;mso-bidi-font-family:Calibri;mso-bidi-theme-font:
                                                            minor-latin" lang="EN-US"></span></p>

                                                                                            <p class="MsoNormal"
                                                                                               style="margin-bottom:0cm;margin-bottom:.0001pt"><span
                                                                                                        style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"
                                                                                                        lang="EN-US">Iyassu
                                                            Bahta</span></p>

                                                                                            <p class="MsoNormal"
                                                                                               style="margin-bottom:0cm;margin-bottom:.0001pt"><span
                                                                                                        style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"
                                                                                                        lang="EN-US">Director, National Medicines and Food Administration</span></p>

                                                                                            <p class="MsoNormal"
                                                                                               style="margin-bottom:0cm;margin-bottom:.0001pt"><span
                                                                                                        style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"
                                                                                                        lang="EN-US">Ministry of Health</span></p>

                                                                                            <p class="MsoNormal"
                                                                                               style="margin-bottom:0cm;margin-bottom:.0001pt"><span
                                                                                                        style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"
                                                                                                        lang="EN-US">Asmara, Eritrea</span></p>

                                                                                    <br/>
                                                                                            <p class="MsoNormal"
                                                                                               style="tab-stops:204.65pt"><span
                                                                                                        style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;"
                                                                                                        lang="EN-US">CC: Product Evaluation and Registration Unit, NMFA </span></p>

                                                                                </div>
                                                                            </textarea>


                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="modal-footer justify-content-between">

                            <a class="btn btn-primary"
                               href="/dossier_evaluation/edit/{{$dossier_evaluation_details->dossier_ass_id}}">Back</a>
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Download
                            </button>
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
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>

    <script>
        $(function () {


            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'

        });

        function insert_name_to_textarea(o, users) {

            for (i = 0; i < users.length; i++) {
                if (users[i].id == o.value) {
                    document.getElementById('inpsection_unit_user').innerText = users[i].first_name + ' ' + users[i].middle_name + ' ' + users[i].last_name
                }

            }


        }
    </script>
@endsection

