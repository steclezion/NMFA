@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dossier Sections You have Evaluated</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1" class="table table-striped dataTable no-footer dtr-inline"
                                       role="grid" aria-describedby="example1_info">

                                    <thead>

                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending">S.N
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1">Assigned by
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1">Applicant Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1">Generic Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1">Brand Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Response Description
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" title="Date of submission of the completed evaluation">Evaluation Submission Date
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Actions
                                        </th>
                                    </tr>

                                    </thead>

                                    <tbody>
                                    @php($i=1)
                                    @foreach($section_assigns as $section_assign)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$section_assign->first_name}} {{$section_assign->last_name}}</td>
                                            <td>{{$section_assign->company_name}} </td>
                                            <td>{{$section_assign->product_name}} </td>
                                            <td>{{$section_assign->product_trade_name}} </td>
                                            <td>{{$section_assign->response_description}} </td>
                                            <td>{{$section_assign->section_received_date}}</td>
                                            <td align="center">
                                                <a href="{{ asset($section_assign->path)}}" target="_blank"
                                                   data-toggle="tooltip" class="btn btn-success btn-sm"
                                                   data-placement="top" title="View/Download the Dossier Section Evaluation you have Submitted"><i
                                                            class="fas fa-download"></i></a>

                                                <a href="{{ route('dossier_section_assign_show',[$section_assign->id])}}"
                                                   class="btn btn-sm btn-info"><i class="fas fa-list"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                   {{-- @php($i=1)
                                    @foreach($section_assigns as $section_assign)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td tabindex="0">{{$section_assign->first_name}}</td>
                                            <td>{{$section_assign->assignment_description}} </td>
                                            <td>{{$section_assign->section_sent_date}}</td>
                                            <td>{{$section_assign->section_deadline}}</td>
                                            <td align="center"><a href="{{ asset($section_assign->path)}}"
                                                                  target="_blank" data-toggle="tooltip"
                                                                  class="btn btn-info btn-sm"
                                                                  data-placement="top" title="View the file"><i
                                                            class="fas fa-book-open"></i></a>
                                                --}}{{-- <a  href="{{ asset($section_assign->path)}}" target="_blank" data-toggle="tooltip" class="btn btn-success btn-sm"
                                                     data-placement="top" title="Download the file"><i class="fas fa-download"></i></a><br>--}}{{--

                                            </td>
                                            <td>
                                                <a href="{{ route('dossier_section_assign_show',[$section_assign->id])}}"
                                                   class="btn btn-sm btn-info"><i class="fas fa-list"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>--}}

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

