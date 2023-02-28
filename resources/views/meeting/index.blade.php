@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Meetings</strong>
                            </h3>
                            <div class="card-tools">
                                <a href="{{route('create_meeting')}}" class="btn btn-primary">
                                    <i fas class="fa fa-plus"></i> New Decision Meeting</a>
                                <a href="{{route('create_other_meeting')}}" class="btn btn-primary">
                                    <i fas class="fa fa-users"></i> New Other Meeting</a>
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
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Type
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="20%"> Description
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Meeting Date
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="11%" id="subject"> Meeting Done
                                        </th>


                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($meetings as $meeting)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            @if($meeting->type == 'Decision_Meeting')
                                            <td>Decision Meeting </td>
                                                @elseif($meeting->type == 'Other_Meeting')
                                                    <td>Other Meeting </td>
                                            @endif


                                            <td>{{$meeting->description}}</td>
                                            @if($meeting->postponed==1)
                                                <td>{{$meeting->postponed_date}} <span class="badge badge-danger">Postponed</span>
                                                </td>
                                            @else
                                                <td>{{$meeting->meeting_date}}</td>
                                            @endif
                                            <td align="center">

                                                @if($meeting->done==1)
                                                    <span class="badge badge-success badge-btn">Yes</span>
                                                @else
                                                    <span class="badge badge-warning badge-btn">No</span>
                                                @endif
                                            </td>


                                            <td>
                                                @if($meeting->done==1)
                                                    <a href="{{ route('uploade_meeting_details',[$meeting->id])  }}"
                                                       class="btn btn-info btn-sm"><i class="fas fa-list"></i> </a>
                                                @else

                                                    <a href="{{ route('uploade_meeting_details',[$meeting->id])  }}"
                                                       class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> </a>



                                                @endif
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

    </section>



@endsection
@section('scripts')
    <script>

        function decision_que(o, command) {
            const id = o.value;
            const command_type = command;


            $.ajax({

                type: 'GET',

                url: "{{ route('decision_que') }}",

                data: {id: id, command_type: command_type},

                success: function (data) {
                    if (data.queued == 'added') {
                        toastr.success('Product Added to Registration Queue')
                        document.getElementById('add_to_que_btn_' + data.id).hidden = true;
                        document.getElementById('remove_from_que_btn_' + data.id).hidden = false;
                    } else if (data.queued == 'removed') {
                        toastr.warning('Product Removed From Registration Queue')

                        document.getElementById('add_to_que_btn_' + data.id).hidden = false;
                        document.getElementById('remove_from_que_btn_' + data.id).hidden = true;
                    } else {
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
