@extends('layouts.app')
@section('content')

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Unassigned Dossiers List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                              <table id="example1" class="table table-bordered table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

                                  <thead>
                                      <tr role="row">
                                      <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>
                                          <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="20%">Application ID</th>
                                          <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="10%"> Mode</th>
                                      <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" width="20%">Dossier Path</th>
                                      <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="20%">Product Name</th>
                                      <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="20%">Actions</th></tr>
                                      </thead>
                                      <tbody>
                                        @php($i=1)
                                      @foreach($unassigned_dossiers as $unassigned)
                                      <tr role="row" class="odd">
                                        <td>{{$i++}}</td>
                                        <td tabindex="0" class="dtr-control sorting_1">{{$unassigned->application_id}}</td>
                                        @if($unassigned->application_type==1)
                                            <td>SR</td>
                                          @endif
                                          @if($unassigned->application_type==2)
                                              <td>FR</td>
                                          @endif


                                          <td>{{$unassigned->path}}</td>
                                        <td>{{$unassigned->description}}</td>
                                        <td>

                                            <a href="#" class="btn btn-info"><i class="fas fa-list"></i> Details</a>
                                            <a href="{{ url('dossier_assignment/assign/'.$unassigned->id)}}" class="btn btn-success"><i class="fas fa-edit"></i> Assign</a>

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
                 </div>
           </div>
      </div>
  </section>

  @endsection
