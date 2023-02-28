@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Market Authorized Products</h3>

                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                        <table id="example"
                                               class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                               role="grid" aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="6%">S.N
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="18%">Registration Num.
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="18%">Applicant
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="20%">Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="20%">Generic Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="15%">Market Authorization<br/> Status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="20%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($applications as $application)
                                        <tr role="row">
                                            <td>{{$i++}}</td>
                                            <td tabindex="0">{{$application->registration_number}}</td>
                                            <td>{{$application->trade_name}}</td>
                                            <td>{{$application->product_trade_name}}</td>
                                            <td>{{$application->product_name}}</td>
                                            <td style="font-weight:bold;">{{$application->market_status}}</td>
                                            <td>
                                            <a href="{{ route('supervisor_track_application_status.application', [$application->application_id]) }}" class="btn btn-sm btn-info" title="View Application"><i class="fas fa-eye"></i></a>											
                                                <a href="{{ route('suspensions.show_app', [$application->id]) }}" class="btn btn-sm btn-warning" title="Edit Application"><i class="fas fa-edit"></i></a>
											
											                                                <a href="{{ route('variation_applicant_index', $application->certification_id) }}"
                                          class="btn btn-primary btn-sm" title="Variations List"><i class="fa fa-list"></i> </a>
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
    </section>
@endsection




