
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
                      action="{{route('download_decision_letter') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                    <textarea id="summernote1" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">
                            <div  id="name">

                                        <label>Date:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_date">
                                        @if (isset($date))
                                            {{ $date }}

                                        @else
                                        [Date/Month/Year]
                                        @endif
                                            </span>
                                        <br>
                                        <label>Ref:</label>
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_reference_number">
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

                                        <label>Subject: Decision on registration application for </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_full_name">
                                             
                                                  </span>
                                        <br>
                                        &nbsp;Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_applicant_name">  </span>
                                        <br>
                                        As per your application for (re-)registration of 
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_applicaion_details">
                                            
                                                  </span> dated
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="reject_dated"> [date/month/year]</span>
                                       , the National Medicines and Food Administration(NMFA), Ministry of Health has reached a decision regarding this product.
                                       <br>
                                       Upon the complete evaluation of your registration application, the NMFA, at its discretion, has decided
                                        to reject the application for the following reasons:
                                         <br>
                                         1.
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
                                         <br>
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
<input type=hidden id='reject_decision_id' name='decision_id' value="{{$decision->id}}" />
</div>

                            </textarea>

</div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" >Generate</button>
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
                            <h5 class="modal-title">Send Rejection Letter </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_application_rejection') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter"> Rejection Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="rejection_letter"
                                                   id="rejection_letter"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('uploaded_document_id','rejection_letter','send_rejection_letter',['pdf'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose  .pdf  file
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="uploaded_document_id"></span>
                                </div>

                                <div class="form-group">
                                    <label for="attachment">Attachments (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="attachment"
                                                   id="attachment"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('uploaded_zip_document_id','attachment','send_rejection_letter',['zip','rar'])"
                                            >
                                            <label class="custom-file-label"
                                                   for="attachment">Choose ( .zip .rar) file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="uploaded_zip_document_id"></span>
                                </div>

                                <input type="hidden" name="decision_id"
                                       value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="send_rejection_letter">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Rejection Letter --}}