

            {{-- MODAL: start Send Query Letter --}}
            <div class="modal fade" id="queryResponseModal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="queryResponseModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Query Response </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form name="upload_response" method="POST"
                                  action="{{route('query_response') }}"  enctype="multipart/form-data">
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
                                    <label for="attachment">Document </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="document" id="document"
                                                   class="custom-file-input" onchange="filevalidiator('uploaded_document_id','document','send_response_btn_id',['pdf','zip',
                                                   'rar'])"
                                                   required >
                                            <label class="custom-file-label"
                                                   for="attachment"> Choose (.pdf, .zip, .rar) file.
                                                </label>
                                        </div>

                                    </div>

                                    <span class="text text-danger" id="uploaded_document_id"></span>
                                </div>

                                <input type="hidden" name="deferment_id" id="deferment_id"  />
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="send_response_btn_id">Send</button>
                                </div>
                            </form>
                        </div> {{--modal-body--}}
                    </div>
                </div>
            </div>
            {{-- MODAL: end Send Deferral Letter --}}