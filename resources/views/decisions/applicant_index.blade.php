



@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Product Decisions</h3>
                            <div class="card-tools">
                            </div>
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
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="5%">S.N
                                        </th>
                                        
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="20%">Application Number
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="20%">Application Type
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Product Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Decision Status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="20%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($products as $product)
                                        <tr role="row">
                                            <td>{{$i++}}</td>
                                            <td>{{$product->application_number}}</td>
                                            @if($product->application_type==1)
                                            <td tabindex="0">Standard Mode</td>
                                            @else
                                            <td tabindex="0">Fast Track</td>
                                            @endif

                                            <td tabindex="0">{{$product->product_name}}</td>
                                            <td tabindex="0">
                                                @if($product->decision_status=='Accepted')

                                                <span class="badge badge-success">{{$product->decision_status}}</span>
                                                @elseif($product->decision_status=='Deferred')
                                                @if($product->locked==1)
                                                <span class="badge badge-primary">Returned for Evaluation</span>
                                                @else
                                                <span class="badge badge-warning">{{$product->decision_status}}</span>
                                                @endif
                                                @else

                                                    <span class="badge badge-danger">{{$product->decision_status}}</span>
                                                @endif
</td>
                                            <td>
                                                <a href="{{ route('decision_applicant_details', [$product->decision_id]) }}" class="btn btn-info btn-sm"><i class="fas fa-list"></i></a>
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




