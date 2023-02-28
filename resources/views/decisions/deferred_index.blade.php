<div class="tab-pane fade" id="custom-tabs-three-deferred" role="tabpanel" aria-labelledby="custom-tabs-three-deferred-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Deferred Products</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


                            <div id="example1_wrapper"
                                 class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1"
                                       class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                       role="grid" aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="20%"> Applicant Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="20%"> Decision Date
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Decision
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Deferred Until
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Status
                                        </th>
                                       


                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($deferred_decisions as $decision)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$decision->product_trade_name}} </td>


                                            <td>{{$decision->company_name}}</td>
                                            <td>{{$decision->meeting_date}}</td>
                                            <td> 
                                            <span class="badge badge-warning">{{$decision->decision_status}}</span>

                                    </td>
                                    
                                    <td> {{$decision->deferred_date}} </td>
                                    <td>
                                    @if($decision->locked==1)
                                    <span class="badge badge-primary">Returned to Assessor</span>
                                    @else
                                    <span class="badge badge-warning">{{$decision->decision_status}}</span>
@endif
                                    </td>
                                            <td>


                                                <a href="{{ route('decision_details',[$decision->id])  }}" class="btn btn-info btn-sm"><i class="fas fa-list"></i> </a>
                                                <a href="{{ route('dossier_evaluation_edit',[$decision->dossier_assignment_id])  }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Show Evaluation Details"><i class="fas fa-eye"></i></a>


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div> {{-- end div: example1_wrapper--}}


                        </div>
                    </div>
                </div>
     