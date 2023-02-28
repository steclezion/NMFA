
@extends('layouts.app')
@section('content')

<div class="row">

    <!-- /.col -->
    <div class="col-md-8 offset-2">

        <form method="POST" class="form-horizontal" action="{{ route('send_to_qc_from_inspection') }}">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Sample Test Request Form </h3>
                </div>
                <!-- /.card-header -->


                <div class="card-body" >
                    <div class="form-group">
                        <label>To:</label>
                        <select class="form-control"  name="to_user" required >
                            <option></option>
                            @foreach ($users as $user)
                                <option value='{{ $user->id }}'>{{ $user->first_name }} {{ $user->middle_name }}</option>

                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" value="{{$qc_id}}" name="qc_id"/>
                    <div class="form-group">
                            <label>Subject:</label>
                        <input class="form-control" placeholder="Enter Subject Here" name='subject' required>
                      </div>
                      <div class="form-group">
                        <label>Deadline :</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="date" class="form-control" name="deadline" required>

                          </div>
                      </div>
                    <input type="hidden" value="" name="hidden_template_id" />
                    <input type="hidden" value="" name="hidden_dossier_asg_id" />
                    <div class="card card-primary" >
                            <div class="card card-header">
                            </div>
                    <div class="card card-body"  id="data_div" >
                    <div class="row" >

        <p class="MsoNormal" style="text-align:center" align="center">
            <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family: &quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
                  National Medicines and Food Administration
            </span>
        </p>

        <p class="MsoNormal" style="text-align:center" align="center">
            <span style="font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;mso-ansi-language:EN-US" lang="EN-US">
                 Sample Request Form for Analysis
            </span>
        </p>


        <div class="row">
            <div class=" col-md-5 offset-1">

            <div class="form-group">
            Reference number:<input type="text" name="reference_number" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"  />
                </div>

                                <input type="text" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>

                    <div class="form-group inline">
                                    <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
                                 </div>
                 <div class="form-group inline">
                                        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
                    </div>
                <div class="form-group inline">
                                            <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
                    </div>
                    <div class="form-group inline">
                            <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
    </div>
    <div class="form-group inline">
            <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>




            </div>
            <div class="col-md-5 offset-1">
                    <div class="form-group inline">
                            <input type="text" class="form-control" value=" " style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
    </div>
    <div class="form-group inline">
            <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
 <div class="form-group inline">
        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
 <div class="form-group inline">
        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
 <div class="form-group inline">
        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
<div class="form-group inline">
        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
<div class="form-group inline">
        <input type="text" class="form-control" value="" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
            </div>
            <div class="col-12">
                     <input type="text" class="form-control" value=" Specification to be used for testing" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
            </div>


        </div>
    </div>
    <div class="row " style="margin-top: 10px">
                    <div class="card card-gray col-md-4">

                        <div class="card card-header">
                                Urgency of Test
                        </div>
                                <div class="card card-body">
                                    <div class="form-group">
                                    <input type="radio" name="urgency" /> Normal
                                    <br>
                                    <input type="radio" name="urgency" /> Urgent
                                </div>
                        </div>
                    </div>

                    <div class="card card-gray col-md-4">

                            <div class="card card-header">
                                    Storage Condition
                            </div>
                                    <div class="card card-body">
                                        <div class="form-group">
                                        <input type="radio" name="storage_condition" /> Room Temperature
                                        <br>
                                        <input type="radio" name="storage_condition" />Refrigerator
                                        <br>
                                        <input type="radio" name="storage_condition" /> Frozen
                                    </div>
                            </div>
                        </div>


                        <div class="card card-gray col-md-4">

                                <div class="card card-header">
                                        Reason for Test
                                </div>
                                        <div class="card card-body">
                                            <div class="form-group">
                                            <input type="radio" name="testing_reason" /> Registration
                                            <br>
                                            <input type="radio" name="testing_reason" /> Pre-Marketing
                                            <br>
                                            <input type="radio" name="testing_reason" /> Post-Marketing
                                            <br>
                                            <input type="radio" name="testing_reason" /> Complaint
                                        </div>
                                </div>
                            </div>

                        </div>




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

<div class="col-12">
        <input type="textarea" class="form-control" value=" Comments" style="border-width:0px;border:none;outline:none;border-bottom: solid thin"/>
</div>
<div class="row" styl="margin-top:10px">
        <input type="text" class="form-control" value=" Delivered by" style="width:50%;border:none;outline:none;border-bottom: solid thin"/>
        <input type="text" class="form-control" value=" Sig" style="width:50%;border:none;outline:none;border-bottom: solid thin"/>
        <br>
        <input type="text" class="form-control" value=" Received by" style="width:50%;border:none;outline:none;border-bottom: solid thin"/>
        <input type="text" class="form-control" value=" Sig" style="width:50%;border:none;outline:none;border-bottom: solid thin"/>
</div>

</div>
</div>
</div>
                <input type="hidden" name="hidden_data_holder" id="hidden_data_holder">
<!-- /.card-body -->
<div class="card-footer">
<div class="float-right">
    <button type="submit" class="btn btn-primary" onclick="data()"><i class="far fa-envelope"></i> Send to QC</button>
</div>
<button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Cancel</button>
</div>
<!-- /.card-footer -->
</div>
<!-- /.card -->
</form>
</div>
<!-- /.col -->
</div>
<script>
    $('#reservationdate').datetimepicker({
        format: 'L'

    });

  
@endsection
