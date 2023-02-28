@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-queries-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-three-screening-tab" data-toggle="pill"
                                       href="#custom-tabs-three-screening" role="tab"
                                       aria-controls="custom-tabs-three-screening"
                                       aria-selected="true">Screening Queries</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-evaluation-tab" data-toggle="pill"
                                       href="#custom-tabs-three-evaluation" role="tab"
                                       aria-controls="custom-tabs-three-evaluation"
                                       aria-selected="false">Evaluation Queries</a>
                                </li>
                                 <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-variation-tab" data-toggle="pill"
                                       href="#custom-tabs-three-variation" role="tab"
                                       aria-controls="custom-tabs-three-variation"
                                       aria-selected="false">Variation Queries</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">


                                <div class="tab-pane fade show active" id="custom-tabs-three-screening"
                                     role="tabpanel" aria-labelledby="custom-tabs-three-screening-tab">

                                    @include('applicant.tab_screening_queries')
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-three-evaluation"
                                     role="tabpanel" aria-labelledby="custom-tabs-three-evaluation-tab">

                                    @include('applicant.tab_evaluation_queries')
                                </div>
                                  <div class="tab-pane fade" id="custom-tabs-three-variation"
                                     role="tabpanel" aria-labelledby="custom-tabs-three-variation-tab">

                                    @include('applicant.tab_variation_queries')
                                </div>

                            </div>
                        </div>
 <!-- Yemane Extension   -->

                        {{-- MODAL for deadline extension Request--}}

<div class="modal fade" id="dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="deleteRecordModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">

        <form action="{{ route('query_deadline_extension')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request For Evaluation Deadline Extension</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Reason For Extension</label><input type="text" class="form-control"
                                                              name='extension_reason'><br>
                    <label>Required Deadline</label><input type="date" class="form-control"
                                                           name='extended_deadline'><br>


                </div>

                <input type="hidden" id="extension_query_id" name="extension_query_id"/>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send
                        Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- End of modal deadline extension Request--}}


{{-- MODAL for Variation deadline extension Request--}}

<div class="modal fade" id="variation_dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="deleteRecordModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">

        <form action="{{ route('variation_query_deadline_extension')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request For Variation Deadline Extension</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Reason For Extension</label><input type="text" class="form-control"
                                                              name='extension_reason'><br>
                    <label>Required Deadline</label><input type="date" class="form-control"
                                                           name='extended_deadline'><br>
                    <input type="text" name='type' value='qc' hidden/>


                </div>

                <input type="hidden" id="extension_variation_query_id" name="extension_variation_query_id"/>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send
                        Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- End of modal deadline extension Request--}}
 <!-- End Yemane Extension   -->


                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')

  {{--  Release 1 scripts-----------------------------------------------}}

  @include('layouts.modal_upload_issued_query_from_applicant')

  @include('layouts.modal_upload_issued_query_from_asessor')



  {{--  Release 2 scripts-----------------------------------------------}}

  <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>


        $(function () {
            bsCustomFileInput.init();
        });

        function deadline_modal_query(o,type) {
            if(type=='variation')
            {
                
                 document.getElementById('query_variation_id').value = o.value;
            }

            document.getElementById('query_id').value = o.value;
        }

        function get_query_name(o, query,type) {

 if(type=='variation')
            {
                 document.getElementById('hidden_variation_query_id').value = query.id;
            document.getElementById("hidden_variation_id").value = query.query_related_id;
            }
else{
            document.getElementById('hidden_query_id').value = query.id;
            document.getElementById('dossier_assignment_id').value = query.query_related_id;


                var words = o.value.split(" ");
                var query_name = words[0] + ' Exchange ' + words[1];
                document.getElementById('query_name_placeholder').value = query_name;


}
        }

        function edit_response_details(o, query,type) {

 if(type=='variation')
            {
              document.getElementById('hidden_variation_query_id1').value = query.id;
            document.getElementById('variation_id').value = query.query_related_id;
            document.getElementById('description_variation').value = query.response_description;
            }
            else{
            document.getElementById('hidden_query_id1').value = query.id;
            document.getElementById('query_name1').value = query.name;
            document.getElementById('dossier_assignment_id_edit').value = query.query_related_id;
            document.getElementById('description1').value = query.response_description;
            }
        }


        function details_query(o,type) {
            let id = o.value;
            if(type=='variation')
            {
                typo='variation'
            }
            else{
                typo='query'
            }

            var server_ip = document.getElementById('server_ip').value;

           

            $.ajax({

                type: 'GET',

                url: "{{ route('retrieve_details') }}",

                data: {id: id, typ: typo},

                success: function (data) {
                    //for sending
                if(typo=='query'){
                    document.getElementById('query_received_view_id').hidden = true;
                                    var document_path = data.sent_document.path;
                                
                        if (data.data['name'] == 'Query 1') {
                            document.getElementById('query_name').innerText = "Query Exchange 1"
                        } else if (data.data['name'] == 'Query 2') {
                            document.getElementById('query_name').innerText = "Query Exchange 2"
                        }
                                    
                                

                        //document.getElementById('query_send_from_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_last_name'];
                        document.getElementById('query_send_to_id').innerText = data.data['applicant_first_name'] + ' ' + data.data['applicant_last_name'];
                                    document.getElementById('query_send_date_id').innerText = data.data['query_sent_date'];
                                    document.getElementById('query_send_deadline_id').innerText = data.data['query_deadline'];
                                    document.getElementById('query_status').innerText = data.data['status'];
                                    document.getElementById('query_sent_document_view').href = server_ip + document_path;


                                    if (data.data.query_received_date == null) {

                                    } else {
                                        var document_path = data.received_document.path;
                //for receiving part
                                        document.getElementById('query_received_view_id').hidden = false;
                            document.getElementById('query_receive_from_id').innerText = data.data['applicant_first_name'] + ' ' + data.data['applicant_last_name'];
                            //document.getElementById('query_receive_to_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_last_name'];
                                        document.getElementById('query_receive_date_id').innerText = data.data['query_received_date'];
                                        document.getElementById('query_response_description_id').innerText = data.data['response_description'];

                                        // var test="\{\{asset\("+document_path +"\)\}\}";

                                        if (data.received_document != null) {
                                            var document_path = data.received_document.path;

                                            document.getElementById('received_view').href = server_ip + document_path;
                                            if (data.attachments != null) {
                                                console.log(data.attachments);
                                                var document_path = data.attachments.path;
                                                document.getElementById('query_attached_document_view').href = server_ip + document_path;


                                            }

                                        }
                                    }
                }
                
                                    else{
                                        document.getElementById('query_variation_received_view_id').hidden = true;
                                        var document_path = data.sent_document.path;
                                
                                
                        //document.getElementById('query_variation_send_from_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_middle_name'];
                        document.getElementById('query_variation_send_to_id').innerText = data.data['applicant_first_name'] + ' ' + data.data['applicant_last_name'];
                                    document.getElementById('query_variation_send_date_id').innerText = data.data['query_sent_date'];
                                    document.getElementById('query_variation_send_deadline_id').innerText = data.data['query_deadline'];
                                    document.getElementById('query_variation_status').innerText = data.data['status'];
                                    document.getElementById('query_variation_sent_document_view').href = server_ip + document_path;


                                    if (data.data.query_received_date == null) {

                                    } else {
                                        var document_path = data.received_document.path;
                //for receiving part
                                        document.getElementById('query_variation_received_view_id').hidden = false;
                            document.getElementById('query_variation_receive_from_id').innerText = data.data['applicant_first_name'] + ' ' + data.data['applicant_last_name'];
                            //document.getElementById('query_variation_receive_to_id').innerText = data.data['assessor_first_name'] + ' ' + data.data['assessor_middle_name'];
                                        document.getElementById('query_variation_receive_date_id').innerText = data.data['query_received_date'];
                                        document.getElementById('query_variation_response_description_id').innerText = data.data['response_description'];

                                        // var test="\{\{asset\("+document_path +"\)\}\}";

                                        if (data.received_document != null) {
                                            var document_path = data.received_document.path;

                                            document.getElementById('variation_received_view').href = server_ip + document_path;
                                            if (data.attachments != null) {
                                                console.log(data.attachments);
                                                var document_path = data.attachments.path;
                                                document.getElementById('query_variation_attached_document_view').href = server_ip + document_path;


                                            }

                                        }
                                    }
                    }
                },
                error: function (data) {
                    console.log(data)

                }
            });

        }



        // Yemane Extension

        function extend_deadline(query_id) {
        document.getElementById('extension_query_id').value = query_id;
    }

    function variation_extend_deadline(query_id) {
        document.getElementById('extension_variation_query_id').value = query_id;
    }

    //  End Yemane Extension


    </script>









@endsection