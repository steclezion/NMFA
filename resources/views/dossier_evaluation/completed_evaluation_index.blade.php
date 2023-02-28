@extends('layouts.app')
@section('content')
<?php
use App\Http\Controllers\DossierEvaluationController;
?>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Completed Dossier Evaluations</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
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
                                            aria-sort="ascending" width="15%">Dossier Ref Num
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="10%">Applicant Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Generic Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Brand Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Assigned Date
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Evaluation Completed Date
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Elapsed Days
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="30%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($completed_dossiers as $evaluation)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->dossier_ref_num}}</td>
                                            <td>{{$evaluation->product_name}}</td>
                                            <td>{{$evaluation->product_trade_name}}</td>
                                            <td>{{$evaluation->company_name}}</td>
                                            <td>{{$evaluation->start_time}}</td>
                                            <td>{{$evaluation->actual_end_time}}</td>

                                            <?php
                                            /** @var TYPE_NAME $evaluation */
                                            $completed_in_days = DossierEvaluationController::get_evaluation_days_count($day_count_type='completed_in_days', $evaluation->id);
                                            ?>
                                            <td>{{$completed_in_days}}


                                            @if($evaluation->task_status == 'Decision')
                                                <td><span class="badge badge-primary">Completed > Decision</span></td>
                                            @elseif(strtolower($evaluation->task_status) == 'completed')
                                                <td><span class="badge badge-success">Completed</span></td>
                                            @elseif(strtolower($evaluation->task_status) == 'queued')
                                                <td><span class="badge badge-secondary">Completed > Queued</span></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('dossier_evaluation_edit',[$evaluation->id])  }}"
                                                   class="btn btn-info"><i class="fas fa-list"></i> </a>

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



