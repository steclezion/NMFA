<div class="tab-pane fade active show" id="custom-tabs-three-unassigned" role="tabpanel"
     aria-labelledby="custom-tabs-three-unassigned-tab">
    <div class="card card-blue">
        <div class="card-header">
            <h3 class="card-title">All Unassigned Dossiers</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">


        <div id="example3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
        <table id="example3" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

<thead>
    <tr role="row">
    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="20%">Application Num.</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="10%">Registration Type</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="10%"> Route of Registration</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" width="20%">Applicant Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Generic Name</th>
    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="20%">Actions</th></tr>
    </thead>
    <tbody>
      @php($i=1)
    @foreach($unassigned_dossiers as $unassigned)
    <tr role="row" class="odd">
      <td>{{$i++}}</td>
      <td tabindex="0" class="dtr-control sorting_1">{{$unassigned->application_id}}</td>
      <td tabindex="0" class="dtr-control sorting_1">{{$unassigned->registration_type}}</td>
      @if($unassigned->application_type==1)
          <td>SR</td>
        @endif
        @if($unassigned->application_type==2)
            <td>FR</td>
        @endif


        <td>{{$unassigned->company_name}}</td>
      <td>{{$unassigned->product_name}}</td>
      <td>

          {{--<a href="#" class="btn btn-info"><i class="fas fa-eye"></i> Details</a>--}}
          <a  class="btn btn-sm btn-info" href="{{  route('supervisor_check_progress_of_assessor.checklist_progress',$unassigned->app_id) }}" title="Show Details">
                                        <i class='fas fa-list'> </i> </a>         
                 <a href="{{ url('dossier_assignment/assign/'.$unassigned->id)}}" class="btn btn-sm btn-warning" title="Assign Dossier"><i class="fas fa-edit"></i></a>

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
