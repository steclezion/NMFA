@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><h2> Show Rule</h2></h3>
                            <div class="card-tools">

                                <a href="{{ route('roles.edit',[$role->id]) }}" class="btn btn-primary"> Edit</a>

                            </div>
                        </div>


                        <div class="card-body">


                            <strong>Name:</strong>

                            {{ $role->name }}
<br>

                            <strong>Permissions:</strong>

                            @if(!empty($rolePermissions))

                                @foreach($rolePermissions as $v)

                                    <label class="label label-success">{{ $v->name }},</label>

                                @endforeach

                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection