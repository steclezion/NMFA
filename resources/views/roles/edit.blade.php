@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><h2>Edit Role</h2></h3>
                            <div class="card-tools">

                                <a href="{{ route('roles.index') }}" class="btn btn-primary"> Back</a>

                            </div>
                        </div>


                        @if (count($errors) > 0)

                            <div class="alert alert-danger">

                                <strong>Whoops!</strong> There were some problems with your input.<br><br>

                                <ul>

                                    @foreach ($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                        {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
                        <div class="card-body">


                            <strong>Name:</strong>

                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}


                            <div class="form-group">

                                <strong>Permission:</strong>

                                <br/>

                                @foreach($permission as $value)

                                    <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}

                                        {{ $value->name }}</label>

                                    <br/>

                                @endforeach

                            </div>


                            <div class="card-footer">

                                <button type="submit" class="btn btn-primary">Submit</button>

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>






@endsection



