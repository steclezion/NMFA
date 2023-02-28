
{{--    Decision Reject modal--}}

<div class="modal fade" id="modal_reject" tabindex="-1" role="dialog"
     aria-labelledby="modal_reject" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejection Letter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form name="upload_response" method="POST"
                      action="{{route('download_variation_decision_letter') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                    <textarea id="summernote1" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                           

                                        <label>Date:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_date">
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

                                               

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_company_name"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_plot_number">  </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_region"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_country"></span>





                                        <br>

                                        <label>Subject: Decision on Variation of </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_full_name">
                                             
                                                  </span>
                                        <br>
                                        &nbsp;Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_applicant_name">  </span>
                                        <br>
                                        <br>
                                        Reference is made to your letter dated  <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_dated"> [date/month/year]</span>
                                       (ref.  <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_variation_ref_id"> </span> )
                                        regarding the product variation of <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_applicaion_details">  </span>.
                                         Upon the complete evaluation of the submitted variation, the National Medicines
                                                   and Food Administration (NMFA), at its discretion, has decided to <b>reject</b> the implementation of 
                                                   the variation for the following reasons:
                                         <br>
                                         1.
                                         <br>
                                         <br>
                                         <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Iyassu Bahta<br>
                                         Director, National Medicines and Food Administration<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea



                            </textarea>
<input type=hidden id='reject_decision_id' name='variation_decision_id' value="{{$decision->id}}" />
</div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div> {{--modal-body--}}
        </div>
    </div>
</div>

{{--  End of  Decision Reject Modal--}}

{{-- MODAL: start Send Rejection Letter --}}
            <div class="modal fade" id="SendRejectionLetterModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="SendRejectionLetterModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Variation Decision Letter </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_variation_decision') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter"> Decision Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="decision_letter"
                                                   id="decision_letter"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('uploaded_decision_document_error','decision_letter','send_decision_btn',['pdf'])" required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose
                                                file</label>
                                        </div>

                                    </div>
                                    <p class="text text-danger" id="uploaded_decision_document_error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="attachment">Attachments (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="attachment"
                                                   id="attachment"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('uploaded_decision_attach_error','attachment','send_decision_btn',['zip', 'rar'])">
                                            <label class="custom-file-label"
                                                   for="attachment">Choose File(zip, rar)
                                                file</label>
                                        </div>

                                    </div>
                                    <p class="text text-danger" id="uploaded_decision_attach_error"></p>
                                </div>

                                <input type="hidden" name="variation_decision_id"
                                       value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="send_decision_btn" class="btn btn-success">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Rejection Letter --}}



            {{--    Decision Accept modal--}}

<div class="modal fade" id="modal_accept" tabindex="-1" role="dialog"
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
                      action="{{route('download_variation_decision_letter') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                    <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                           

                                        <label>Date:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_date">
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

                                               

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_company_name"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_plot_number">  </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_region"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_country"></span>





                                        <br>

                                        <label>Subject: Decision on Variation of </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_full_name">
                                             
                                                  </span>
                                        <br>
                                        &nbsp;Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicant_name">  </span>
                                        <br>
                                        <br>
                                        Reference is made to your letter dated  <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_dated"> [date/month/year]</span>
                                       (ref.  <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_variation_ref_id"> </span> ) 
                                        regarding the product variation of <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="accept_applicaion_details">  </span>.
                                         Accordingly, the National Medicines and Food Administration (NMFA), 
                                         Ministry of Health (MOH) confirms that the variation has been considered and is well <b>accepted</b>.
                                         <br>
                                         <br>
                                         The continuous updates in regards to your product is well appreciated. 

                                         <br>
                                         <br>
                                         <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Iyassu Bahta<br>
                                         Director, National Medicines and Food Administration<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea



                            </textarea>
<input type=hidden id='accept_decision_id' name='variation_decision_id' value="{{$decision->id}}" />
</div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div> {{--modal-body--}}
        </div>
    </div>
</div>

{{--  End of  Decision accept Modal--}}
