@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            {{--<div class="col-md-10 col-lg-10 col-sm-10 offset-1">--}}
            <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Assessment Report Details</strong></h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            {{---------------------------- Submitted Assessment Reports----------------------------------}}
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="card card-outline card-blue">
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Submitted Assessment Report</strong>
                                        </h3>
                                    </div>

                                    <table class="table table-condensed table-borderless">
                                        <tbody>
                                        <tr>
                                            <td class="text-muted" width="30%">From</td>
                                            <td class="text-left">{{$assessment_report_detail->first_name}}
                                                {{$assessment_report_detail->middle_name}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Name</td>
                                            <td class="text-left">{{$assessment_report_detail->name}}</td>
                                        </tr>
                                        @if($assessment_report_detail->assessment_received_date == null)
                                            <tr>
                                                <td class="text-muted">Status</td>
                                                <td class="text-left">
                                                    <span class="badge bg-primary">{{$assessment_report_detail->status}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">Assessment Received Date</td>
                                            <td class="text-left">{{$assessment_report_detail->assessment_sent_date}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Description</td>
                                            <td class="text-left">{{$assessment_report_detail->request_subject}}</td>
                                        </tr>
                                        @foreach($uploaded_documents as $document)
                                            <tr>
                                                <td class="text-muted">{{ $document->name}}</td>
                                                <td class="text-left"><a href="{{asset($document->path)}}"
                                                                         target="_blank"
                                                                         data-toggle="tooltip"
                                                                         class="btn btn-primary btn-sm"
                                                                         data-placement="top"
                                                                         title="Download the MS-Word file">
                                                        <i class="fas fa-file-word"></i> Download</a></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div> {{--end-of-column1--}}

                            {{----------------------------Commented Assessment Reports----------------------------------}}
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="card card-outline card-green">
                                    <div class="card-header">
                                        <h3 class="card-title"><strong>Commented Assessment Reports</strong>
                                        </h3>
                                    </div>

                                    @if(isset($assessment_response_detail))
                                        <table class="table table-condensed table-borderless">
                                            <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Comment Sent Date</td>
                                                <td class="text-left">{{$assessment_response_detail->assessment_received_date}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Description</td>
                                                <td class="text-left">
                                                    {{$assessment_response_detail->response_description}}</td>
                                            </tr>
                                            @if($assessment_report_detail->assessment_received_date != null)
                                                <tr>
                                                    <td class="text-muted">Status</td>
                                                    <td class="text-left">
                                                        <span class="badge bg-success">{{$assessment_report_detail->status}}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                            @foreach($commented_documents as $document)
                                                <tr>
                                                    <td class="text-muted">{{ $document->name}}</td>
                                                    <td class="text-left">
                                                        <a href="{{asset($document->path)}}" target="_blank"
                                                           data-toggle="tooltip"
                                                           class="btn btn-success btn-sm"
                                                           data-placement="top"
                                                           title="Download the commented MS-word file">
                                                            <i class="fas fa-file-word"></i> Download</a>
                                                    </td>
                                                </tr>

                                            @endforeach


                                            </tbody>
                                        </table>
                                    @endif

                                </div> {{--end-of-column2--}}
                            </div>
                        </div> {{--card-body--}}
                    </div> {{--end-card--}}

                    <div class="modal-footer justify-content-between">

                        <a href="{{url('Assessment_reports/submitted')}}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-circle-left"></i>
                            Back</a>

                        {{----------------------------Upload comment button----------------------------------}}

                         {{-- The third time final_revised report is uploaded, not need to upload comment by supervisor
                         therefore, lock upload if assessment report has been submitted for 2 times
                          --}}

                        @if($response_sent == false && $can_comment == true)

                            @if (!isset($assessment_response_detail))
                               @if($assessment_report_detail->application_type==1)
                                   <button type="button" class="btn btn-success btn-sm" style="padding-right: 10px;"
                                           title="Upload the Commented Document"
                                           data-toggle="modal"
                                           data-target="#upload_comment"
                                           onclick="">
                                       <i class="fas fa-upload"></i>
                                       Upload Comment
                                   </button>
                               @elseif($assessment_report_detail->application_type==2)
                                   <button type="button" class="btn btn-success btn-sm" style="padding-right: 10px;"
                                           title="Upload the Commented Document"
                                           data-toggle="modal"
                                           data-target="#upload_fs_comment"
                                           onclick="">
                                       <i class="fas fa-upload"></i>
                                       Upload Comment
                                   </button>
                               @else
                                   Wrong application type
                               @endif
                           @endif

                       @else

                           <button type="button" class="btn btn-secondary btn-sm" style="padding-right: 10px;"
                                   title="Commented Reports already uploaded."
                                   onclick="" disabled>
                               <i class="fas fa-upload"></i>
                               Upload Comment
                           </button>


                       @endif

                   </div>
               </div>


           </div>
       </div>
       </div>
       {{--  Modal for uploading commented stn assessment_Report  --}}
       <div class="modal fade" id="upload_comment" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="upload_comment" aria-hidden="true">
           <div class="modal-dialog modal-md" role="document">

               <form action="{{route('upload_commented_document')}}" method="POST" enctype="multipart/form-data">
                   @csrf
                   <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title">Upload Commented Assessment Reports</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                       <div class="modal-body">
                           <div class="form-group">
                               <input type="text" name='assessment_report' id='assessment_report'
                                      value="{{$assessment_report_detail->id}}" hidden/>

                           </div>
                           <div class="form-group">
                               <label>Report Type</label>

                               <select class="form-control" name="report_type" required>
                                   @if($eval_progress_status->assessment_submitted < 3)
                                       <option value="commented_initial_report">Initial Assessment Report</option>
                                       <option value="commented_deferment_report" disabled>Deferment Assessment Report</option>
                                   @elseif($eval_progress_status->deferred_assessment_submitted < 3)
                                       <option value="commented_initial_report" disabled>Initial Assessment Report </option>
                                       <option value="commented_deferment_report">Deferment Assessment Report</option>
                                   @else
                                       <option value="commented_initial_report" disabled>Initial Assessment Report</option>
                                       <option value="commented_deferment_report" disabled>Deferment Assessment Report</option>
                                   @endif

                               </select>
                           </div>

                           <div class="form-group">
                               <label> Description</label>
                               <div class="input-group">
                                   <input type="text" class="form-control" name="description" required>

                               </div>
                           </div>
                           <div class="form-group">
                               <label for="assessment_report_file">Commented Assessment
                                   Report</label>
                               <div class="input-group">
                                   <div class="custom-file">
                                       <input type="file" name="assessment_report_file"
                                              id="assessment_report_file"
                                              class="custom-file-input"
                                              onchange="filevalidiator('assessment_report_span','assessment_report_file','submit_assessment_report_id',['doc', 'docx'])"
                                              required>
                                       <label class="custom-file-label"
                                              for="assessment_report_file">Choose
                                           file</label>
                                   </div>

                               </div>
                               <span class="text text-danger" id="assessment_report_span"></span>
                           </div>

                           <div class="form-group">
                               <label for="assessment_report_smpc_file">Commented Assessment
                                   Report SmPC</label>
                               <div class="input-group">
                                   <div class="custom-file">
                                       <input type="file"
                                              name="assessment_report_smpc_file"
                                              id="assessment_report_smpc_file"
                                              class="custom-file-input"
                                              onchange="filevalidiator('assessment_report_smpc_file_span','assessment_report_smpc_file','submit_assessment_report_id',['doc', 'docx'])"
                                              required>
                                       <label class="custom-file-label"
                                              for="assessment_report_smpc_file">Choose
                                           file</label>
                                   </div>

                               </div>
                               <span class="text text-danger" id="assessment_report_smpc_file_span"></span>

                           </div>
                           <div class="form-group">
                               <label for="assessment_report_pils_file">Commented Assessment
                                   Report PILs </label>
                               <div class="input-group">
                                   <div class="custom-file">
                                       <input type="file"
                                              name="assessment_report_pils_file"
                                              id="assessment_report_pils_file"
                                              class="custom-file-input"
                                              onchange="filevalidiator('assessment_report_pils_file_span','assessment_report_pils_file','submit_assessment_report_id',['doc', 'docx'])">
                                       <label class="custom-file-label"
                                              for="assessment_report_pils_file">Choose
                                           file</label>
                                   </div>

                               </div>
                               <span class="text text-danger" id="assessment_report_pils_file_span"></span>

                           </div>
                       </div>

                       <input type="hidden" name='dossier_assignment_id'
                              value="{{$assessment_report_detail->assessment_related_id}}"/>

                       <div class="modal-footer justify-content-between">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                           <button type="submit" id="submit_assessment_report_id" class="btn btn-success">Upload
                           </button>
                       </div>
                   </div>

               </form>
           </div>
       </div>
       {{--  end of uploading commented std assessment_Report  --}}

       {{--  Modal for uploading commented fs assessment_Report  --}}
       <div class="modal fade" id="upload_fs_comment" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="upload_fs_comment" aria-hidden="true">
           <div class="modal-dialog modal-md" role="document">

               <form action="{{route('upload_commented_document')}}" method="POST" enctype="multipart/form-data">
                   @csrf
                   <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title">Upload Commented Report</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                       <div class="modal-body">
                           <div class="form-group">
                               <input type="text" name='assessment_report' id='assessment_report'
                                      value="{{$assessment_report_detail->id}}" hidden/>

                           </div>

                           <div class="form-group">
                               <label>Report Type</label>
                               <select class="form-control" name="report_type" required>
                                   @if($eval_progress_status->assessment_submitted < 3)
                                       <option value="commented_initial_report">Initial Assessment Report</option>
                                       <option value="commented_deferment_report" disabled>Deferment Assessment Report</option>
                                       {{--<option value="commented_variation_report" disabled>Variation Assessment Report</option>--}}
                                   @elseif($eval_progress_status->deferred_assessment_submitted < 3)
                                       <option value="commented_initial_report" disabled>Initial Assessment Report </option>
                                       <option value="commented_deferment_report">Deferment Assessment Report</option>
                                   @else
                                       <option value="commented_initial_report" disabled>Initial Assessment Report</option>
                                       <option value="commented_deferment_report" disabled>Deferment Assessment Report</option>
                                       {{--<option value="commented_variation_report" disabled>Variation Assessment Report</option>--}}
                                   @endif

                               </select>
                           </div>

                           <div class="form-group">
                               <label> Description: </label>
                               <div class="input-group">
                                   <input type="text" class="form-control" name="description" required>

                               </div>
                           </div>
                           <div class="form-group">
                               <label for="assessment_report_file">Commented Assessment
                                   Report</label>
                               <div class="input-group">
                                   <div class="custom-file">
                                       <input type="file" name="assessment_report_file"
                                              id="assessment_report_file_fast"
                                              class="custom-file-input"
                                              onchange="filevalidiator('assessment_report_fs_span','assessment_report_file_fast','fs_submit_btn_id',['doc', 'docx'])">
                                              required>
                                       <label class="custom-file-label"
                                              for="assessment_report_file">Choose
                                           file</label>
                                   </div>

                               </div>
                               <span class="text text-danger" id="assessment_report_fs_span"></span>
                           </div>


                       </div>

                       <input type="hidden" name='dossier_assignment_id'
                              value="{{$assessment_report_detail->assessment_related_id}}"/>

                       <div class="modal-footer justify-content-between">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                           <button type="submit" id="fs_submit_btn_id" class="btn btn-success">Upload</button>
                       </div>
                   </div>

               </form>
           </div>
       </div>
       {{--  end of Modal for uploading commented fs assessment_Report  --}}


   </section>
@endsection
@section('scripts')

   <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
   <script>
       $(function () {
           bsCustomFileInput.init();
       });



   </script>
@endsection
