<div class="tab-pane fade active show " id="custom-tabs-three-ongoing" role="tabpanel"
     aria-labelledby="custom-tabs-three-ongoing-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Ongoing Variations</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">

        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1"
                                       class="table table-bordered dataTable no-footer dtr-inline"
                                       role="grid"
                                       aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="5%">S.N
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="17%">Variation Ref. Num.
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Generic Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Brand Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="10%">Applicant Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Assigned Date
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="10%">Deadline
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="10%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($evaluations as $evaluation)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->variation_reference_number}}</td>
                                            <td>{{$evaluation->product_name}}</td>
                                            <td>{{$evaluation->product_trade_name}}</td>
                                            <td>{{$evaluation->company_name}}</td>
                                            <td tabindex="0">{{$evaluation->assigned_datetime}}</td>
                                            <td tabindex="0">{{$evaluation->deadline}} </td>
                                         
                                            <td>
                                                <div>
                                                    <a href="{{ route('variation_evaluation_edit',[$evaluation->id])  }}"
                                                       class="btn btn-info btn-md"
                                                       title="Show Evaluation Tasks"><i class="fas fa-list"></i></a>
                                                    @if($evaluation->task_status!='Locked')
                                                        <button type="button" class="btn btn-secondary btn-md"
                                                                title="Variation Evaluation has not expired yet or is Completed."
                                                                disabled>
                                                            <i class='fas fa-clock'></i>
                                                        </button>

                                                    @elseif($evaluation->task_status=='Locked')
                                                        @if(!$evaluation->evaluation_deadline_extended)
                                                            <button type="button" class="btn btn-primary btn-md"
                                                                    title="Request for Deadline Extension"
                                                                    data-toggle="modal" data-target="#dedline_extension"
                                                                    onclick="extend_deadline({{$evaluation->id}})"
                                                                    value="">
                                                                <i class='fas fa-clock'></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-secondary btn-md"
                                                                    title="Deadline extension request already sent."
                                                                    data-toggle="modal" data-target="#dedline_extension"
                                                                    disabled>
                                                                <i class='fas fa-clock'></i>
                                                            </button>
                                                        @endif

                                                  @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
        </div>
        </div>

    </div>
    <!-- /.card-body -->
</div>

{{-- MODAL for deadline extension Request--}}

                    <div class="modal fade" id="dedline_extension" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="deleteRecordModal" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">

                            <form action="{{ route('dossier_evaluation_deadline_extension')}}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request For Deadline
                                            Extension</h5>
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

                                    <input type="hidden" id="dossier_assign_id" name="dossier_assign_id"/>
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
                <!-- /.card-body -->

<script>
    function extend_deadline(dossier_ass_id) {
        document.getElementById('dossier_assign_id').value = dossier_ass_id;
    }
</script>
