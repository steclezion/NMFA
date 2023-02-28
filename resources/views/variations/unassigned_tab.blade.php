<div class="tab-pane fade" id="custom-tabs-three-unassigned" role="tabpanel"
     aria-labelledby="custom-tabs-three-unassigned-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">All Unassigned Variations</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


        <div id="example3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
        <table id="example3" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

<thead>
<tr role="row">
    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
        aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
        width="5%">S.N</th>
    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
        width="20%">Reg. Number</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Variation Ref. Num.</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Generic Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Brand Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Applicant Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Received On</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"aria-label="Actions: activate to sort column ascending" width="15%">Actions</th>
</tr>
    </thead>
    <tbody>
      @php($i=1)
    @foreach($unassigned_variations as $unassigned)
    <tr role="row" class="odd">
        <td>{{$i++}}</td>
        <td tabindex="0" class="dtr-control sorting_1">{{$unassigned->registration_number}}</td>
        <td>{{$unassigned->variation_reference_number}}</td>
        <td>{{$unassigned->product_name}}</td>
        <td>{{$unassigned->product_trade_name}}</td>
        <td>{{$unassigned->company_name}}</td>
        <td>{{$unassigned->created_at}}</td>
      <td>

          {{--<a href="#" class="btn btn-info"><i class="fas fa-eye"></i> Details</a>--}}
                 
                 <a href="{{ url('variation/assign/'.$unassigned->id)}}" class="btn btn-sm btn-warning" title="Assign Variation"><i class="fas fa-edit"></i></a>

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
