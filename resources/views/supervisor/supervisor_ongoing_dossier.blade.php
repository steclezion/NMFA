@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Ongoing Dossier Evaluations</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1"
                                       class="table  dataTable no-footer dtr-inline"
                                       role="grid"
                                       aria-describedby="example1_info">

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
                                            aria-sort="ascending" width="13%">Assessor Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="14%">Dossier Ref. Num.
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="13%">Assigned Date
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="13%">Days Remaining
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="15%">Progress
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="10%">Status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="10%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($assessor_assignment_details as $evaluation)
                                        @if($evaluation->task_status!='completed')
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->assessor_first_name}} {{$evaluation->assessor_middle_name}}</td>
                                            <td>{{$evaluation->dossier_ref_num}}</td>
                                            <td tabindex="0">{{$evaluation->assigned_datetime}}</td>
                                            <td tabindex="0">{{$evaluation->task_duration_days_plan -$evaluation->day_count}}
                                                days
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    @if($evaluation->task_status=='pause')
                                                        <div
                                                                class="progress-bar bg-gradient-warning progress-bar-striped"
                                                                role="progressbar"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: {{  $evaluation->progress_percentage }}%">
                                                            <span>{{ $evaluation->progress_percentage }}% Complete </span>
                                                        </div>
                                                    @elseif($evaluation->task_status=='Inprogress')
                                                        <div
                                                                class="progress-bar bg-gradient-primary progress-bar-striped"
                                                                role="progressbar"
                                                                 aria-valuemin="0" aria-valuemax="100"
                                                                style="width: {{  $evaluation->progress_percentage }}%">
                                                            <span>{{ $evaluation->progress_percentage }}% Complete </span>
                                                        </div>
                                                    @elseif($evaluation->task_status=='Locked')
                                                        <div
                                                                class="progress-bar bg-gradient-danger progress-bar-striped"
                                                                role="progressbar"
                                                                 aria-valuemin="0" aria-valuemax="100"
                                                                style="width: {{  $evaluation->progress_percentage }}%">
                                                            <span>{{ $evaluation->progress_percentage }}% Complete </span>
                                                        </div>

                                                    @endif
                                                </div>
                                            </td>
                                            @if($evaluation->task_status=='pause')
                                                <td tabindex="0">
                                                    <span class="badge bg-warning">Pause</span>
                                                </td>
                                            @elseif($evaluation->task_status=='Inprogress')
                                                <td tabindex="0">
                                                    <span class="badge bg-primary">In-progress</span>
                                                </td>
                                            @elseif($evaluation->task_status=='Locked')
                                                <td tabindex="0">
                                                    <span class="badge bg-danger">{{$evaluation->task_status}}</span>
                                                </td>
                                            @endif
                                            <td>
                                                <div>
                                                    <a href="{{ route('dossier_evaluation_edit',[$evaluation->id])  }}"
                                                       class="btn btn-info btn-sm"
                                                       title="Show Evaluation Details"><i class="fas fa-list"></i></a>


                                                </div>
                                            </td>
                                        </tr>
                                        @endif
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
