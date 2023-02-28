@extends('layouts.app_app')


@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><h2>Assign/Unassign</h2></h3>
                         

                        </div>

                        <div class="card-body">
                            <table id="example1"
                                   class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                   role="grid"
                                   aria-describedby="example1_info">

                                <thead>
                                <tr role="row">
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Supplier Name: activate to sort column descending"
                                        aria-sort="ascending"
                                        width="5%">No
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Supplier Name: activate to sort column descending"
                                        aria-sort="ascending"
                                        width="20%">Name
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Supplier Name: activate to sort column descending"
                                        aria-sort="ascending"
                                        width="20%">Email
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Supplier Name: activate to sort column descending"
                                        aria-sort="ascending"
                                        width="30%">Roles
                                    </th>

                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Actions: activate to sort column ascending" width="25%">Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $key => $user)
                                @if(!empty($user->getRoleNames()))
                                       
                                       @foreach($user->getRoleNames() as $v)
                                       @if($v == 'Assessor') 
                                    <tr>

                                        <td>{{ ++$i }}</td>

                                        <td>{{ $user->first_name }} {{ $user->middle_name }}</td>

                                        <td>{{ $user->email }}</td>

                                        <td>

                                            @if(!empty($user->getRoleNames()))
                                       
                                                @foreach($user->getRoleNames() as $v)
                                               
                                                    <label class="badge badge-success">{{ $v }}</label>
                                           
                                                @endforeach

                                            @endif

                                        </td>

                                        <td>

                                            <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>

                                            <a class="btn btn-primary"
                                               href="{{ route('users.edit',$user->id) }}">Assign</a>

                                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}

                                            {!! Form::submit('UnAssign', ['class' => 'btn btn-danger']) !!}

                                            {!! Form::close() !!}

                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach

@endif

                                @endforeach
                                </tbody>

                            </table>


                            {{--    {!! $data->render() !!}--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection