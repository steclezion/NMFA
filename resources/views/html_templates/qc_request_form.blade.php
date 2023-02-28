@extends('layouts.app')
@section('stylesheets')

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')

<div class="row">

    <!-- /.col -->
    <div class="col-md-8 offset-2">

        <form method="POST" action="{{ route('send_to_qc') }}">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $breadcrumb_title }}</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <div class="form-group">
                        <input class="form-control" placeholder="To: {{ $dossier_evaluation_details->application_id }}">
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Subject: {{ $breadcrumb_title }}">
                    </div>

                    <input type="hidden" value="{{ $template->id }}" name="hidden_template_id" />
                    <input type="hidden" value="{{ $dossier_evaluation_details->id }}" name="hidden_dossier_asg_id" />
                    <div class="form-group" >
                                <div class="page" >
                                        <p class="MsoNormal" style="text-align:center" align="center">
                                            <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family: &quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
                                                  National Medicines and Food Administration
                                            </span>
                                        </p>

                                        <p class="MsoNormal" style="text-align:center" align="center">
                                            <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
                                                 Sample Request Form for Analysis]
                                            </span>
                                        </p>
                                        <p class="MsoNormal" style="margin-bottom:6.0pt"><b style="mso-bidi-font-weight:normal">
                                            <span style="font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;background:darkgray;mso-highlight:darkgray;mso-ansi-language:EN-US" lang="EN-US">
                                               Sample Information
                                            </span></b>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                    Reference number:__________________________<br>
                                                    Generic name:__________________________<br>
                                                    Dosage form:__________________________________<br>
                                                    Batch number:_________________________________<br>
                                                    Manufacturing date:____________________<br>
                                                    Country of Origin:__________________________<br>
                                                    Source of the sample:____________________<br>
                                                    Date of sampling:_______________________<br>

                                            </div>
                                            <div class="col-md-6">

                                                    Manufacturer:________________________<br>
                                                    Product name (brand):________________________<br>
                                                    Strength:________________________<br>
                                                    Batch size:________________________<br>
                                                    Expiry date:________________________<br>
                                                    Supplier:________________________<br>
                                                    Sample size:________________________<br>
                                                    Date of submission to QC:________________<br>
                                            </div>
                                            &nbsp;&nbsp;Specification to be used for testing:_________________________________________<br>
                                        </div>
                                       <span  style="width: 100%; font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:Times New Roman;;background:darkgray; mso-highlight:darkgray;mso-ansi-language:EN-US" lang="EN-US"><b>Urgency of Test</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br>
                                        <input type="checkbox" onclick='test(this)' checked>Normal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox">Urgent<br>

                                        <span  style="width: 100%; font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:Times New Roman;;background:darkgray; mso-highlight:darkgray;mso-ansi-language:EN-US" lang="EN-US"><b>Storage Condition</b>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br>
                                            <input type="checkbox">Room Temperature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" >Refrigerator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox">Frozen<br>

                                            <span  style="width: 100%; font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:Times New Roman;;background:darkgray; mso-highlight:darkgray;mso-ansi-language:EN-US" lang="EN-US"><b>Reason for Test</b>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br>
                                                <input type="checkbox">Registration &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" >Pre-Marketing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox">Post-Marketing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox">Complaint<br>


                                            <span  style="width: 100%; font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:Times New Roman;;background:darkgray; mso-highlight:darkgray;mso-ansi-language:EN-US" lang="EN-US"><b>Type of Tests(test parameters)</b>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br>


                                                <table class="MsoTableGrid" style="border-collapse:collapse;border:none;mso-yfti-tbllook:1184;mso-padding-alt:
                                                0cm 5.4pt 0cm 5.4pt;mso-border-insideh:none;mso-border-insidev:none" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes">
                                                 <td colspan="2" style="width:226.1pt;padding:0cm 5.4pt 0cm 5.4pt" width="301" valign="top">
                                                 <p class="MsoNormal" style="margin-bottom:10.0pt;line-height:normal"><b style="mso-bidi-font-weight:normal"><u><span style="font-size:
                                                 12.0pt;mso-bidi-font-size:11.0pt;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                 mso-ansi-language:EN-US" lang="EN-US">Physico-Chemical</span></u></b></p>
                                                 </td>
                                                 <td colspan="2" style="width:224.7pt;padding:0cm 5.4pt 0cm 5.4pt" width="300" valign="top">
                                                 <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                 normal">
                                                 <b style="mso-bidi-font-weight:normal"><u><span style="font-size:12.0pt;mso-bidi-font-size:11.0pt;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                 mso-ansi-language:EN-US" lang="EN-US">Microbiology</span></u></b></p>
                                                 </td>
                                                </tr>
                                                <tr style="mso-yfti-irow:1">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Identification</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Bacterial Endotoxin Test</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:2">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Disintegration</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Sterility test</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:3">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Dissolution</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Microbial Enumeration Test</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:4">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Friability</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Test for Specified Micro-organisms</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:5">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Uniformity of dosage form</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Antimicrobial Effectiveness Test</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:6">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">pH</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Antimicrobial Assay</span></p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:7">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Viscosity</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:8">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Impurity and related substance</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:9">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Assay</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:10">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Test for particular matter</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                       </tr>
                                                       <tr style="mso-yfti-irow:11;mso-yfti-lastrow:yes">
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal"><input type="checkbox" /><span style="mso-no-proof:yes"></span></p>
                                                        </td>
                                                        <td style="width:203.3pt;padding:0cm 5.4pt 0cm 5.4pt" width="271" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        115%"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;
                                                        mso-ansi-language:EN-US" lang="EN-US">Others</span></p>
                                                        </td>
                                                        <td style="width:22.8pt;padding:0cm 5.4pt 0cm 5.4pt" width="30" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                        <td style="width:201.9pt;padding:0cm 5.4pt 0cm 5.4pt" width="269" valign="top">
                                                        <p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
                                                        normal">&nbsp;</p>
                                                        </td>
                                                       </tr>
                                                      </tbody></table><br>
Comments:________________________________________________________________________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
________________________________________________________________________________________

Delivered by:________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sig:_________________________<br>
Received by:________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sig:_________________________<br>

                                </div>

                    </div>
                    <div class="form-group">
                        <div class="btn btn-default btn-file">
                            <i class="fas fa-paperclip"></i> Attachment
                            <input type="file" name="attachment">
                        </div>
                        <p class="help-block">Max. 32MB</p>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button>
                        <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                    </div>
                    <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
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

    $(function () {


        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })

    function test() {
        alert('thisi si')
    }
</script>
@endsection
