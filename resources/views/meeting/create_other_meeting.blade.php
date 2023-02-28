@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>New Meeting</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="POST" action="{{ route('other_store_meeting') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 offset-1">
                                        <div class="form-group">
                                            <label>Meeting Date:</label>
                                            <div class="input-group date" id="reservationdate"
                                                 data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                       data-target="#reservationdate" name="meeting_date" required>
                                                <div class="input-group-append" data-target="#reservationdate"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Meeting Time:</label>

                                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                       data-target="#timepicker" name="time" required>
                                                <div class="input-group-append" data-target="#timepicker"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-1">

                                        <div class="form-group">
                                            <label> Venue:</label>
                                            <input class="form-control" id="venu_address"
                                                   name='venue' required>
                                        </div>

                                        <div class="form-group">
                                            <label> Description:</label>
                                            <input type="texta" class="form-control"
                                                   name='description'>
                                        </div>

                                    </div>
                                </div> {{--row--}}
                                <div class="form-group">
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{route('meeting_index')}}" class="btn btn-secondary"><i
                                                    class="fas fa-arrow-circle-left"></i> Back </a>


                                        <button type="submit" class="btn btn-primary"><i
                                                    class="fas fa-eye"></i> Preview and Send Letter
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><strong>Completed Variations</strong>
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                            <div id="example1_wrapper"
                                 class="dataTables_wrapper dt-bootstrap4
                                no-footer">
                                <table id="example1"
                                       class="table table-bordered table-striped
                                    dataTable no-footer dtr-inline"
                                       role="grid"
                                       aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc"
                                            tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Serial Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending"
                                            width="3%">S.N
                                        </th>

                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject"> Registration Number
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject"> Variation Ref. Num.
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject">
                                            Assessor
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="15%" id="received">
                                            Product Name
                                        </th>
                                        <th class="sorting sorting_asc"
                                            tabindex="0"
                                            aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending"
                                            width="20%"> Applicant Name
                                        </th>

                                        <th rowspan="1" colspan="1"
                                            width="20%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($variations   as $evaluation)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$evaluation->registration_number}}</td>
                                            <td>{{$evaluation->variation_reference_number}}</td>
                                            <td>{{$evaluation->first_name}}
                                                {{$evaluation->middle_name}}</td>
                                            <td>{{$evaluation->product_trade_name}} </td>
                                            <td>{{$evaluation->trade_name}}</td>
                                            <td>


                                                <a href="{{
                                                    route('dossier_evaluation_edit',[$evaluation->id])
                                                    }}" class="btn btn-info
                                                    btn-sm"><i class="fas
                                                        fa-eye"></i> </a>
                                                @if($evaluation->task_status=='completed')
                                                    <button
                                                            id="add_to_que_btn_{{$evaluation->id}}"
                                                            class="btn btn-success
                                                    btn-sm"
                                                            title="Register for Certification"
                                                            onclick="decision_que(this,'add','variation')"
                                                            value="{{$evaluation->id}}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <button hidden
                                                            id="remove_from_que_btn_{{$evaluation->id}}"
                                                            class="btn btn-danger
                                                    btn-sm"
                                                            title="Remove from Certification"
                                                            onclick="decision_que(this,'remove','variation')"
                                                            value="{{$evaluation->id}}">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                @else
                                                    <button hidden
                                                            id="add_to_que_btn_{{$evaluation->id}}"
                                                            class="btn btn-success
                                                    btn-sm"
                                                            title="Register for Certification"
                                                            onclick="decision_que(this,'add','variation')"
                                                            value="{{$evaluation->id}}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <button
                                                            id="remove_from_que_btn_{{$evaluation->id}}"
                                                            class="btn btn-danger
                                                    btn-sm"
                                                            title="Remove from Certification"
                                                            onclick="decision_que(this,'remove','variation')"
                                                            value="{{$evaluation->id}}">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
</table>

                            </div> {{-- end div: example1_wrapper--}}
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        //Date and time picker
        $('#reservationdatetime').datetimepicker({icons: {time: 'far fa-clock'}});
        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });
        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        });

        function decision_que(o, command,type) {
            var id = o.value;
            var command_type = command;
            var type = type;


            $.ajax({

                type: 'GET',

                url: "{{ route('decision_que') }}",

                data: {id: id, command_type: command_type,type:type},

                success: function (data) {
                    console.log(data)
                    if (data.queued == 'added')
                     {
                        toastr.success('Product Added to Registration Queue')
                        document.getElementById('add_to_que_btn_' + data.id).hidden = true;
                        document.getElementById('remove_from_que_btn_' + data.id).hidden = false;
                    } else if (data.queued == 'removed')
                     {
                        toastr.warning('Product Removed From Registration Queue')

                        document.getElementById('add_to_que_btn_' + data.id).hidden = false;
                        document.getElementById('remove_from_que_btn_' + data.id).hidden = true;
                    } else 
                    {
                        alert('you have error in supervisor controller')
                        console.log(data)
                    }

                },
                error: function (data) {

                    console.log(data);
                }
            });

        }
    </script>
@endsection