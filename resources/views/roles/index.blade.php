@extends('layouts.app')


@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><h2>Role Management</h2></h3>
                        @can('role-create')
                            <div class="card-tools ">
                                <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                            </div>
                        @endcan
                    </div>
                    <!-- /.card-header -->


                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                               role="grid"
                               aria-describedby="example1_info">

                            <thead>
                            <tr role="row">
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1"
                                    aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
                                    width="20%">No
                                </th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1"
                                    aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending"
                                    width="40%">Name
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Actions: activate to sort column ascending" width="40%">Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach ($roles as $key => $role)

                                <tr>

                                    <td>{{ ++$i }}</td>

                                    <td>{{ $role->name }}</td>

                                    <td>

                                        <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>

                                        @can('role-edit')


                                            <a class="btn btn-primary"
                                               href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                        @endcan


                                        @can('role-delete')


                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}

                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                                            {!! Form::close() !!}


                                        @endcan
                                    </td>

                                </tr>

                            @endforeach
                            </tbody>
                        </table>


                        {{--            {!! $roles->render() !!}--}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection