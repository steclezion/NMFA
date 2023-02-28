@extends('layouts.app')


@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title card-block"><h2>Users Management</h2></h3>
                            <div class="card-tools ">
                                <a class="btn btn-success" href="{{ route('users.create') }}"><i class="fas fa-plus"> </i> Create New User</a>

                            </div>

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
                                        width="15%">Roles
                                    </th>

                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Actions: activate to sort column ascending" width="15%">Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $key => $user)

                                    <tr>

                                        <td>{{ ++$i }}</td>

                                        <td>{{ $user->first_name }} {{ $user->middle_name }}</td>

                                        <td>{{ $user->email }}</td>

                                        <td>

                                            @if(!empty($user->getRoleNames()))

                                                @foreach($user->getRoleNames() as $v)

                                                    <label class="badge badge-success btn-xs">{{ $v }}</label>

                                                @endforeach

                                            @endif

                                        </td>

                                        <td>

                                            <a class="btn btn-info btn-xs" href="{{ route('users.show',$user->id) }}">Show</a>

                                            <a class="btn btn-primary btn-xs" href="{{ route('users.edit',$user->id) }}">Edit</a>

                                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}

                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}

                                            {!! Form::close() !!}

                                        </td>

                                    </tr>


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
