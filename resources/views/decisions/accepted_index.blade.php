<div class="tab-pane fade active show " id="custom-tabs-three-accepted" role="tabpanel" aria-labelledby="custom-tabs-three-accepted-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Accepted Products</h3>

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
                                        {{--<th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Temp Appl. Num
                                        </th>  <th class="sorting" tabindex="0"
                                                   aria-controls="example1" rowspan="1" colspan="1"
                                                   aria-label="Title: activate to sort column ascending"
                                                   width="15%" id="received"> Dos assign
                                        </th>--}}
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
                                            width="20%" id="subject"> Registration No. 
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Certificate No. 
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Decision Status 
                                        </th>
                                       


                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($accepted_decisions as $decision)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                         {{--   <td>{{$decision->application_number}} </td>
                                            <td>{{$decision->dossier_assignment_id}} </td>--}}
                                            <td>{{$decision->product_trade_name}} </td>


                                            <td>{{$decision->company_name}}</td>
                                            <td>{{$decision->meeting_date}}</td>
                                            <td>{{$decision->registration_number}}</td>
                                            <td>{{$decision->certificate_number}}</td>
                                            <td><span class="badge badge-success"> {{$decision->decision_status}}</span>
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
          