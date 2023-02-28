<div class="tab-pane fade" id="custom-tabs-three-reassign" role="tabpanel"
     aria-labelledby="custom-tabs-three-reassign-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">Reassign Dossiers</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


        <div id="example3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
        <table id="example3" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

<thead>
    <tr role="row">
    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>
       <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="10%"> Dossier Ref. Num.</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" width="20%">Applicant Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Product Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Route of Registration</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="20%">Actions</th></tr>
    </thead>
    <tbody>
    @php($i=1)
    @foreach($reassign_dossiers as $reassign)
    <tr role="row" class="odd">
      <td>{{$i++}}</td>

      <td tabindex="0" class="dtr-control sorting_1">{{$reassign->dossier_ref_num}}</td>
      <td tabindex="0" class="dtr-control sorting_1">{{$reassign->company_name}}</td>
      <td tabindex="0" class="dtr-control sorting_1">{{$reassign->product_trade_name}}</td>
      @if($reassign->application_type==1)
          <td>SR</td>
        @endif
        @if($reassign->application_type==2)
            <td>FR</td>
        @endif


      <td>

          <a href="{{ url('dossier_assignment/reassign/'.$reassign->id)}}" class="btn btn-warning" title="Reassign Dossier"><i class="fas fa-edit"></i></a>

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
