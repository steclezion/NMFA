@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong> Locked Application Tasks List</strong>
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
                                            width="15%" id="received"> Assessor
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="21%"> Product Name
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Applicant Name
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="13%"> Task Start Date
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="13%" id="received"> End Date
                                        </th>


                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($locked_application_tasks as $locked_application_task)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            
                                            <td>{{$locked_application_task->first_name}} {{$locked_application_task->middle_name}}</td>


                                            <td>{{$locked_application_task->product_name}}</td>
                                            <td>{{$locked_application_task->trade_name}}</td>

                                            <td>{{$locked_application_task->start_time}}</td>
                                            <td>{{$locked_application_task->end_time}}</td>


                                            <td>


                                            @if($locked_application_task->deadline_extended)
                                        <button type="button" class="btn btn-warning btn-sm"
                                                title="Extend Application Task Deadline For Already Extended"
                                                data-toggle="modal"
                                                data-target="#modalextend_query"  
                                                onclick="deadline_modal_query(this)" value="{{ $locked_application_task->id }}" >
                                            <i class="fas fa-clock"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-success btn-sm"
                                                title="Extend Application Task Deadline"
                                                data-toggle="modal"
                                                data-target="#modalextend_query"
                                                onclick="deadline_modal_query(this)" value="{{ $locked_application_task->id }}" >
                                            <i class="fas fa-clock"></i>
                                        </button>
                                        @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        {{--  Modal for Extend deadline  --}}
            <div class="modal fade" id="modalextend_query" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="modalextend" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <form action="{{route('app_update_deadline') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Extend Deadline</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name='type' value='task' hidden/>
                                    <input type="text" name='task_id' id='task_id'
                                           value='' hidden/>

                                </div>
                                <div class="form-group">
                                    <label> Reason for Extension</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="extend_reason">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>New Deadline</label>
                                    <div class="input-group " id="reservationdate" data-target-input="nearest">
                                        <input type="number" class="form-control" name="new_deadline">

                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Extend</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{--  end of Modal for extend deadline  --}}

    </section>


@endsection
@section('scripts')
    <script>
      function deadline_modal_query(o) {

document.getElementById('task_id').value = o.value;
}
     
    </script>

@endsection
