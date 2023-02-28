<div class="tab-pane fade" id="custom-tabs-three-completed-variations" role="tabpanel"
     aria-labelledby="custom-tabs-three-completed-variations-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Completed Variations</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                <table id="example2"
                       class="table table-bordered dataTable no-footer dtr-inline"
                       role="grid"
                       aria-describedby="example2_info">

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
                            aria-sort="ascending" width="40%">Generic Name
                        </th>
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                            rowspan="1" colspan="1"
                            aria-label="Supplier Name: activate to sort column descending"
                            aria-sort="ascending" width="40%">Brand Name
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
                            aria-sort="ascending" width="10%">Decision
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1" aria-label="Actions: activate to sort column ascending"
                            width="8%">Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($completed_variations as $evaluation)
                        <tr role="row" class="odd">
                            <td>{{$i++}}</td>
                            <td>{{$evaluation->variation_reference_number}}</td>
                            <td>{{$evaluation->product_name}}</td>
                            <td>{{$evaluation->product_trade_name}}</td>
                            <td>{{$evaluation->company_name}}</td>
                            <td tabindex="0">{{$evaluation->assigned_datetime}}</td>
                            <td tabindex="0">
                                @if(isset($evaluation->decision_status))
                                    @if($evaluation->decision_status=='Accepted')
                                        <span class="badge badge-success">{{$evaluation->decision_status}}</span>
                                    @else

                                        <span class="badge badge-danger">{{$evaluation->decision_status}}</span>
                                    @endif

                                @else

                                    <span class="badge badge-secondary"> Not Decided</span>

                                @endif

                            </td>

                            <td>
                                <div>
                                    <a href="{{ route('variation_evaluation_edit',[$evaluation->id])  }}"
                                       class="btn btn-info btn-md"
                                       title="Show Evaluation Tasks"><i class="fas fa-list"></i></a>
                                    @if($evaluation->task_status=='Locked')
                                        @if(!$evaluation->evaluation_deadline_extended)
                                            <button type="button" class="btn btn-primary btn-md"
                                                    title="Request for Deadline Extension"
                                                    data-toggle="modal" data-target="#dedline_extension"
                                                    onclick="extend_deadline({{$evaluation->id}})"
                                                    value="">
                                                <i class='fas fa-clock'></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm"
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

