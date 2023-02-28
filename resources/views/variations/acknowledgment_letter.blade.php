

<div class="modal fade" id="modal_acknowledgment" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="modal_acknowledgment" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Acknowledgment Letter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form name="upload_response" method="POST"
                      action="{{route('download_acknowledgment_letter') }}"
                      enctype="multipart/form-data">
                    @csrf

                  

                    <div class="form-group">
                    <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                            <div  id="name">

                                        <label>Date: </label>

                                        <span id="acknowledgement_date" style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">

                                            </span>
                                        <br>
                                        <label>Ref:</label>
                                        <span id="reference_letter"  style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">

                                            </span>
                                        <br>
                                        <label>To:</label>

                                               

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_company_name"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_plot_number">  </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_region"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_country"></span>





                                        <br>

                                        <label>Subject: Acknowledgement letter for receipt of variation/s of  </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_full_name">
                                             
                                                  </span>
                                        <br>
                                        Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicant_name">  </span>,
                                        <br>
                                        Reference is made to your letter dated <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_dated"> [date/month/year]</span>
                                         regarding the variation of <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicaion_details">
                                            
                                                  </span>
                                                  . Accordingly, the National Medicines and Food Administration (NMFA), Ministry of Health (MOH) acknowledges the receipt of the variation submitted.  <br>
                                       <p class="MsoNormal" style="margin-top:6.0pt;text-align:justify">

                                       Please be informed the NMFA may contact you for further details and may request additional information when deemed necessary.
<br>
<br>
The continuous updates in regards to your product is well appreciated.

<o:p></o:p></p>

                                    <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Iyassu Bahta<br>
                                         Director, National Medicines and Food Administration<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea
<input type=hidden id='variation_id' name='variation_id' value="{{$variation->id}}" />
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


{{-- MODAL: start Send Acceptance Letter --}}
            <div class="modal fade" id="SendAcknowledgmentLetterModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="SendAcknowledgmentLetterModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Sealed Document</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_variation_acknowledgment') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter"> Subject</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="text" class="form-control" name="supervisor_subject" required>
                                            
                                        </div>

                                    </div>
                                    <span class="text text-danger"></span>
                                </div>

                                <div class="form-group">
                                    <label for="rejection_letter"> Acknowledgement Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="sealed_acknowledgment_letter"
                                                   id="rejection_letter"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('ack_letter_span_error','rejection_letter','submit_ack_btn_id',['pdf'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <p class="text text-danger" id="ack_letter_span_error"></p>
                                </div>

                               

                                <input type="hidden" name="variation_id"
                                       value="{{$variation->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit_ack_btn_id" class="btn btn-success">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Rejection Letter --}}