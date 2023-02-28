<div class="tab-pane fade" id="custom-tabs-three-assigned" role="tabpanel"
     aria-labelledby="custom-tabs-three-assigned-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">All Assigned Variations</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


        <div id="example5_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
        <table id="example5" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

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
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Assigned To</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Assigned Date</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="15%">Status</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"aria-label="Actions: activate to sort column ascending" width="15%">Actions</th>
</tr>
</thead>
    <tbody>
      @php($i=1)
    @foreach($assigned_variations as $assigned)
    <tr role="row" class="odd">
      <td>{{$i++}}</td>
      <td tabindex="0" class="dtr-control sorting_1">{{$assigned->registration_number}}</td>
      <td>{{$assigned->variation_reference_number}}</td>
      <td>{{$assigned->product_name}}</td>
      <td>{{$assigned->product_trade_name}}</td>
      <td>{{$assigned->company_name}}</td>
        <td>{{$assigned->created_at}}</td>
        <td>{{$assigned->first_name}} {{$assigned->last_name}}</td>
        <td>{{$assigned->assigned_datetime}}</td>
        @if($assigned->task_status == 'Inprogress')
        <td><span class="badge badge-primary">In-progress</span></td>
            @elseif($assigned->task_status == 'pause')
                <td><span class="badge badge-warning">Paused</span></td>
        @else
            <td><span class="badge badge-secondary">{{$assigned->task_status}}</span></td>
        @endif

      <td>

          <a href="{{ route('variation_evaluation_edit',[$assigned->id])  }}"
             class="btn btn-info" title="Show Details"><i class="fas fa-list"></i></a>

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
