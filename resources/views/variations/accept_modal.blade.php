
{{--    Decision accept modal--}}

<div class="modal fade" id="modal_accept" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="modal_accept" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Acceptance Letter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form name="upload_response" method="POST"
                      action="{{route('download_decision_letter') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Certificate Type :</label>
                         <select class="form-control" name="certificate_type"  required>
                                <option></option>
                                <option value="GM">Generic Medicine</option>
                                <option value="BGM">Branded Generic Medicine</option>
                                <option value="SM">Specialty Medicine</option>
                                <option value="HM">Herbal Medicine</option>
                                <option value="NS">Nutritional Supplement</option>
                                <option value="BP">Biological Products</option>
                                <option value="MD">Medical Devices</option>
                            

                         </select>
                          
                      </div>

                    <div class="form-group">
                    <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                            <div  id="name">

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
                                                @if (isset($dossier_evaluation_details->dossier_ref_num))
                                                {{ $dossier_evaluation_details->dossier_ref_num }}

                                            @else
                                            [NMFA/XX/YEAR/Sequential Number]
                                            @endif
                                            </span>
                                        <br>
                                        <label>To:</label>

                                               

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_company_name"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;street/plot number: <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_plot_number">  </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;region/state: <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_region"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;country: <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_country"></span>





                                        <br>

                                        <label>Subject: Decision on registration application for </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_full_name">
                                             
                                                  </span>
                                        <br>
                                        Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicant_name">  </span>,
                                        <br>
                                        As per your application for (re-)registration of 
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicaion_details">
                                            
                                                  </span> dated
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_dated"> [date/month/year]</span>
                                         , has been completed through the <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_procedure"> [registration procedure/route]</span>.               
                                       The National Medicines and Food Administration (NMFA), at its discretion, has granted <b>approval</b> of the registration application subject to the conditions of this letter. 
                                       This letter and the attached Certificate of Registration constitute the approval. 
                                       Due to the below mentioned reasons, the NMFA, at its discretion, has decided to defer the registration application.
                                       <br>
                                       <p class="MsoNormal" style="margin-top:6.0pt;text-align:justify"><span style="font-size:12.0pt;line-height:115%;font-family:'Times New Roman'">The
conditions that apply are as follows:<o:p></o:p></span></p>

<ul>
<li >
The medical product should conform to all the details provided in your applicationand dossier and as modified in subsequent correspondences.
</li>


<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
The medical product should be dispensed as <i><span style="color:#0070C0">[Schedule of the medical product]</span>.</li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">No changes should be made to the quality specification, composition, packaging
material, manufacturing process and site of manufacture without prior approval from the NMFA.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You are obliged to monitor the quality of the product on the market and report quality defects to the NMFA for the appropriate regulatory action to be taken.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You are obliged to monitor the safety of the product granted marketing approval and report all adverse reactions or events to the NMFA.</li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You are requested to promptly communicate any changes in the safety information on the Finished Pharmaceutical Product (FPP) to the NMFA.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
The manufacture and control of medicines should be in accordance with the current Good Manufacturing Practices (cGMP).</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
In order to assess compliance with GMP requirements, inspections and investigations may be carried out regularly by authorized inspectors, as deemed necessary.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
The medical product should be imported in compliance to the provision of the Proclamation no. 36/1993.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You should ensure that the Marketing Authorization (MA) is not transferred without written approval of the NMFA.</span></li>

<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You are obliged to notify the NMFA of any changes or amendments (variations) that may affect the quality, safety and efficacy of the FPP.</span></li>


<li >
<span style="font-size:12.0pt;line-height:115%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">
You are obliged to renew the registration application in due time (not later than three months prior to the expiry date of the registration).</span></li>
                                    
</ul>
                                    <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Iyassu Bahta<br>
                                         Director, National Medicines and Food Administration<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea
<input type=hidden id='accept_decision_id' name='decision_id' value="{{$decision->id}}" />
</div>

                            </textarea>

</div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Generate</button>
                    </div>
                </form>
            </div> {{--modal-body--}}
        </div>
    </div>
</div>

{{--  End of  Decision Reject Modal--}}

{{-- MODAL: start Send Acceptance Letter --}}
            <div class="modal fade" id="SendAccpetanceLetterModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="SendAccpetanceLetterModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Sealed Documents</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_application_accept') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter"> Acceptance Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="acceptance_letter"
                                                   id="rejection_letter"
                                                   class="custom-file-input" required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger"></span>
                                </div>

                                <div class="form-group">
                                    <label for="rejection_letter"> MAH Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="mah_letter"
                                                   id="rejection_letter"
                                                   class="custom-file-input" required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" ></span>
                                </div>

                                <div class="form-group">
                                    <label for="attachment">Attachments (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="attachment"
                                                   id="attachment"
                                                   class="custom-file-input"  >
                                            <label class="custom-file-label"
                                                   for="attachment">Choose .zip
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="uploaded_zip_document_id"></span>
                                </div>

                                <input type="hidden" name="decision_id"
                                       value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Rejection Letter --}}