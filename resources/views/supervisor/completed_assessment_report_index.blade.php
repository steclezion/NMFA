@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Completed Assessment Reports List</strong>
                            </h3>

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
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Dossier Ref. Num.
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Assessor
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="10%"> Generic Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="10%"> Brand Name
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="10%" id="subject"> Applicant Name
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            data-toggle="tooltip"
                                            width="13%" title="Date the evaluation task was assigned to the Assessor">
                                            Eval. Start Date <i class="fa fa-info-circle"></i>
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            data-toggle="tooltip"
                                            width="13%" id="received"
                                            title="Date the completed evaluation was submitted to supervisor">
                                            Eval. End Date <i class="fa fa-info-circle"></i>
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="25%" id="received"
                                            data-toggle="tooltip" data-placement="top" data-html="true"
                                            title="Completed Evaluation are either in 'Completed', 'Queued', or 'Decision' status.  <br/>
                                            To view the decision taken, go to Certifications>Decision. <br/>
                                            To view the market status of accepted products go to post-market">
                                            Status <i class="fa fa-info-circle"></i>
                                        </th>

                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($completed_assessment_assignments as $completed_assessment_assignment)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$completed_assessment_assignment->dossier_ref_num}}</td>
                                            <td>{{$completed_assessment_assignment->first_name}} {{$completed_assessment_assignment->middle_name}}</td>
                                            <td>{{$completed_assessment_assignment->product_name}}</td>
                                            <td>{{$completed_assessment_assignment->product_trade_name}}</td>
                                            <td>{{$completed_assessment_assignment->company_name}}</td>
                                            <td>{{$completed_assessment_assignment->start_time}}</td>
                                            <td>{{$completed_assessment_assignment->actual_end_time}}</td>

                                            @if(strtolower($completed_assessment_assignment->task_status) == 'completed')
                                                <td>
                                                    <span class='badge badge-success'>Completed</span>
                                                </td>
                                            @elseif(strtolower($completed_assessment_assignment->task_status) == 'decision')
                                                <td><span class='badge badge-danger'>Completed > Decision</span></td>
                                            @else
                                                <td>
                                                    <span class='badge badge-secondary'>{{$completed_assessment_assignment->task_status}}</span>
                                                </td>
                                            @endif

                                            <td>
                                                <a href="{{ route('dossier_evaluation_edit',[$completed_assessment_assignment->doss_assignment_id])  }}"
                                                   class="btn btn-info btn-sm"><i class="fas fa-list"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div> {{-- end div: example1_wrapper--}}


                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


@endsection
@section('scripts')

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

    </script>

@endsection

