@extends('layouts.app')


@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><h2>Show User</h2></h3>

                            <div class="card-tools">

                                <a class="btn btn-primary" href="{{ route('users.index') }}"><i class="fas fa-arrow-alt-circle-left"> </i> Back</a>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="form-group">

                                <strong>Name:</strong>

                                {{ $user->first_name }}
                                {{ $user->middle_name }}

                            </div>



                            <div class="form-group">

                                <strong>Email:</strong>

                                {{ $user->email }}

                            </div>


                            <div class="form-group">

                                <strong>Roles:</strong>

                                @if(!empty($user->getRoleNames()))

                                    @foreach($user->getRoleNames() as $v)

                                        <label class="badge badge-success">{{ $v }}</label>

                                    @endforeach

                                @endif

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection