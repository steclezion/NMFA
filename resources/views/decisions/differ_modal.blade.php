
{{--    Decision Reject modal--}}

<div class="modal fade" id="modal_defer" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="modal_defer" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deferral Letter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form name="upload_response" method="POST"
                      action="{{route('download_decision_letter') }}">
                    @csrf

                    <div class="form-group">
                        <label>Deadline :</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest" >
                              <input type="date" class="form-control" name="deadline" onchange="date_count(this)"  required>

                          </div>
                      </div>
                    <textarea id="summernote" class="form-control" name="data" style="height: 300px; display: none;font-size: xx-large">
                            

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
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_reference_number">
                                                 @if (isset($reference_letter))
                                                {{ $reference_letter }}

                                            @else
                                            [NMFA/XX/YEAR/Sequential Number]
                                            @endif
                                            </span>
                                        <br>
                                        <label>To:</label>

                                               

                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_company_name"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_plot_number">  </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_region"> </span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_country"></span>





                                        <br>

                                        <label>Subject: Decision on registration application for </label>
                                   
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_full_name">
                                             
                                                  </span>
                                        <br>
                                        Dear <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_applicant_name">  </span>,
                                        <br>
                                        As per your application for (re-)registration of 
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_applicaion_details">
                                            
                                                  </span> dated
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191" id="defer_dated"> [date/month/year]</span>
                                       , the National Medicines and Food Administration(NMFA), Ministry of Health has reached a decision regarding this product.
                                       <br>
                                       Due to the below mentioned reasons, the NMFA, at its discretion, has decided to defer the registration application.
                                        
                                         <br>
                                        1.
                                         <br>
                                         <br>
                                         <br>
                                        In this regard, you are kindly requested to address the above issue(s) within <span id="datys_count">90</span> days of receipt of this letter. 
                                        Failure to respond by the stated time will result in the rejection of the (re-)registration application. 

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
                            <input type=hidden id="defer_decision_id" name="decision_id" value="{{$decision->id}}" />
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
{{-- MODAL: start Send Deferral Letter --}}
            <div class="modal fade" id="SendDefermentLetterModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="SendDefermentLetterModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Deferral Letter </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_application_deferral') }}"  enctype="multipart/form-data">
                                @csrf

                                {{--todo add deferred date--}}

                                <div class="form-group">
                                    <label for="rejection_letter"> Deferral Letter</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="deferment_letter"
                                                   id="deferment_letter"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('uploaded_document_id','deferment_letter','send_deferment_letter',['pdf'])"
                                                   required>
                                            <label class="custom-file-label"
                                                   for="rejection_letter">Choose
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
                                                   onchange="filevalidiator('uploaded_zip_document_id','attachment','send_deferment_letter',['zip','rar'])">
                                            <label class="custom-file-label"
                                                   for="attachment">Choose (.zip .rar)
                                                file</label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="uploaded_zip_document_id"></span>
                                </div>

                                <input type="hidden" name="decision_id"
                                       value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="send_deferment_letter">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Deferral Letter --}}










            {{-- MODAL: start Send Query Letter --}}
            <div class="modal fade" id="queryModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="queryModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Query Letter </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('send_deferral_query') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter">  Subject</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="text" name="subject"   id="rejection_letter" class="form-control" required>
                                           
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label> Deadline :</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="date" class="form-control" name="deadline">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="attachment">Document (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="document" id="document_upload_deferral_query"
                                                   class="custom-file-input"
                                                   onchange="filevalidiator('query_pdf_zip_document_id','document_upload_deferral_query','send_query',['pdf','zip','rar'])">
                                            <label class="custom-file-label"
                                                   for="attachment">
                                                Choose (.pdf .zip .rar) file
                                                </label>
                                        </div>

                                    </div>
                                    <span class="text text-danger" id="query_pdf_zip_document_id"></span>
                                </div>

                                <input type="hidden" name="decision_id"
                                       value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="send_query">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Deferral Letter --}}



            {{-- MODAL: start Send Query Letter --}}
            <div class="modal fade" id="AssessorReturnModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="AssessorReturnModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Return to Assessor </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('return_deferment_to_assessor') }}"  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label for="rejection_letter"> Evaluation Deadline</label>
                                    <div class="input-group">
                                    <div class="input-group date" id="evaluationDeadline" data-target-input="nearest" >
                              <input type="date" class="form-control" name="evaluationDeadline" required>



                          </div>

                                    </div>
                                </div>

                                <input type="hidden" name="assigned_assessor" value="{{ $assessor->first_name}} {{$assessor->middle_name}}"/>
                                <input type="hidden" name="decision_id" value="{{$decision->id}}"/>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Return </button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Deferral Letter --}}