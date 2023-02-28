<div class="tab-pane fade active show " id="custom-tabs-three-acknowledgment" role="tabpanel" aria-labelledby="custom-tabs-three-all-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Unacknowledged Variations</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                <table id="example1" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid"
                    aria-describedby="example1_info">

                    <thead>
                        <tr role="row">
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
                                width="5%">S.N</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
                                width="20%">Reg. Number</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Variation Ref. Num</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Generic Name</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Brand Name</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Applicant Name</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Received On</th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"aria-label="Actions: activate to sort column ascending" width="15%">Actions</th>
                        </tr>

                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach($acknowledgments as $acknowledgment)
                        <tr role="row" class="odd">
                            <td>{{$i++}}</td>

                            <td>{{$acknowledgment->registration_number}}</td>
                            <td>{{$acknowledgment->variation_reference_number}}</td>
                            <td>{{$acknowledgment->product_name}}</td>
                            <td>{{$acknowledgment->product_trade_name}}</td>
                            <td>{{$acknowledgment->company_name}}</td>
                            <td>{{$acknowledgment->created_at}}</td>

                         
                            <td>
                              
                                <a href="{{ route('variation_acknowledgment',[$acknowledgment->id])  }}"
                                    class="btn btn-info"><i class="fas fa-list" title="Show Details"></i></a>
                               

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
    <!-- /.card-body -->
</div>
