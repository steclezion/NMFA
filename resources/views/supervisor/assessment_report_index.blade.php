@extends('layouts.app')
@section('content')
    <?php
            use Illuminate\Support\Str;
    ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong> Assessment Reports</strong>
                            </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" >
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
                                            width="15%" id="received"> Product
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Dossier Ref. Num
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> From
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="21%"> Subject
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Assessment Status
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="13%"> Report Received On
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="13%" id="received"> Comment Sent On
                                        </th>



                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($assessment_reports as $assessment_report)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$assessment_report->product_name}}</td>
                                            <td>{{$assessment_report->dossier_ref_num}}</td>
                                            <td>{{$assessment_report->first_name}} {{$assessment_report->middle_name}}</td>
                                            <td>{{$assessment_report->name}}</td>

                                            @if(Str::of($assessment_report->status)->startsWith('Commented'))
                                            <td><span class="badge badge-success">{{$assessment_report->status}} </span> </td>
                                            @else
                                                <td><span class="badge badge-primary">{{$assessment_report->status}} </span> </td>
                                            @endif
                                            <td>{{$assessment_report->assessment_sent_date}}</td>
                                            <td>{{$assessment_report->assessment_received_date}}</td>
                                            <td>
                                                <a class="btn btn-info btn-sm"
                                                        title="Show details and Upload Response"
                                                        href=" {{ route('assessment_report_detail', $assessment_report->id )}}">
                                                    <i class="fas fa-list"></i>
                                                 </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div> {{-- end div: example1_wrapper--}}



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
            </div>
        </div>
        </div>
    </section>

@endsection
